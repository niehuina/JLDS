<?php
if (!defined('ROOT_PATH')) {
    if (is_file('../../../shop/configs/config.ini.php')) {
        require_once '../../../shop/configs/config.ini.php';
    } else {
        die('请先运行index.php,生成应用程序框架结构！');
    }

    //不会重复包含, 否则会死循环: web调用不到此处, 通过crontab调用
    $Base_CronModel = new Base_CronModel();
    $rows = $Base_CronModel->checkTask(); //并非指执行自己, 将所有需要执行的都执行掉, 如果自己达到执行条件,也不执行.

    //终止执行下面内容, 否则会执行两次
    return;
}
$cur_dir = dirname(__FILE__);
chdir($cur_dir);

Yf_Log::log(__FILE__, Yf_Log::INFO, 'crontab');

$file_name_row = pathinfo(__FILE__);
$crontab_file = $file_name_row['basename'];

/**
 * 订单提成返利：合伙人和高级合伙人
 */
$Order_BaseModel = new Order_BaseModel();
$Order_GoodsModel = new Order_GoodsModel();
$User_InfoModel = new User_InfoModel();
$User_GradeModel = new User_GradeModel();

//查找出所有确认收货的未结算订单
//$time = time()-7*24*60*60;
//$N = date('Y-m-d H:i:s',$time);
$N = get_date_time();

$cond_row = array();
$cond_row['order_status'] = Order_StateModel::ORDER_FINISH;
$cond_row['order_is_virtual'] = Order_BaseModel::ORDER_IS_REAL;
$cond_row['order_finished_time:<='] = $N;
$order_row['order_finished_time'] = 'asc';

$flag = true;

//开启事物
$Order_BaseModel->sql->startTransactionDb();

//获取所有的合伙人
$partner_list = $User_InfoModel->getByWhere(['user_grade' => 3, 'user_name:!='=>'admin', 'user_statu'=>0]);
$user_grade = $User_GradeModel->getOne(3);

foreach ($partner_list as $key => $user_info) {
    $user_id = $user_info['user_id'];
    //合伙人只计算其直属下级用户的所有订单
    $user_children_ids = $User_InfoModel->getUserDirectChildrenNoPartner($user_id);
    if(count($user_children_ids) == 0) continue;
    $cond_row['buyer_user_id:in'] = $user_children_ids;
    $cond_row['order_status'] = Order_StateModel::ORDER_FINISH; //已完成
    $cond_row['rebate_is_settlement'] = Order_BaseModel::IS_NOT_SETTLEMENT; //未结算
    $cond_row['order_create_time:>='] = $user_info['user_grade_update_date'];
    $user_orders = $Order_BaseModel->getByWhere($cond_row, $order_row);

    $order_rebate_value = 0;
    if ($user_orders) {
        //计算其直属下级用户的未结算的订单总额
        $user_order_pay_amount = array_sum(array_column($user_orders, 'order_payment_amount'));
        $user_order_refund_amount = array_sum(array_column($user_orders, 'order_refund_amount'));
        $user_order_amount = $user_order_pay_amount - $user_order_refund_amount;

        $user_order_ids = array_column($user_orders, 'order_id');
        $order_edit_row['rebate_is_settlement'] = Order_BaseModel::IS_SETTLEMENT;
        $flag = $Order_BaseModel->editBase($user_order_ids, $order_edit_row);
        if ($user_order_amount == 0) continue;

        //获取合伙人的返利比例
        $grade_order_amount = $user_grade['order_amount'] * 1;
        $grade_order_rebate1 = $user_grade['order_rebate1'] * 1/100;
        $grade_order_rebate2 = $user_grade['order_rebate2'] * 1/100;

        $before_order_total_amount = $user_info['children_order_total_amount'] * 1;
        foreach ($user_orders as $k=>$order){
            $order_amount = $order['order_payment_amount'] * 1 - $order['order_refund_amount'] * 1;
            $total_children_order_total_amount = $before_order_total_amount + $order_amount;

            //不管超指标还是未超指标，都需要：当前金额*rebate1
            $rebate_value1 = $order_amount * $grade_order_rebate1;

            //如果上次累计订单总金额已超指标，则超过指标部分提成为:该次金额*rebate2
            if($before_order_total_amount * 1 >= $grade_order_amount){
                $rebate_value2 = $order_amount * $grade_order_rebate2;
            }else if ($total_children_order_total_amount > $grade_order_amount) {
                //如果该次累计总金额超过指标，则超过指标部分提成为:（累计总金额-指标部分）*rebate2
                $order_amount2 = $total_children_order_total_amount - $grade_order_amount;
                if ($order_amount2 > 0) {
                    $rebate_value2 = $order_amount2 * $grade_order_rebate2;
                } else {
                    $rebate_value2 = 0;
                }
            }else if ($total_children_order_total_amount <= $grade_order_amount) {
                //如果该次累计总金额未超过指标，则超过指标部分提成为:0
                $rebate_value2 = 0;
            }
            $order_rebate_value_temp = $rebate_value1 + $rebate_value2;

            if($order_rebate_value == 0) continue;
            $order_rebate_value_temp = round($order_rebate_value_temp,2);

            //将需要确认的订单号远程发送给Paycenter修改订单状态
            //远程修改paycenter中的订单状态
            $key = Yf_Registry::get('paycenter_api_key');
            $url = Yf_Registry::get('paycenter_api_url');
            $paycenter_app_id = Yf_Registry::get('paycenter_app_id');
            $formvars = array();

            $formvars['user_id'] = $user_id;
            $formvars['order_id'] = $order['order_id'];
            $formvars['user_money'] = $order_rebate_value_temp;
            $formvars['reason'] = '合伙人订单提成结算';
            $current_year = date('Y');
            $formvars['desc'] = "{$current_year}年度累计订单金额{$total_children_order_total_amount}";
            $formvars['app_id'] = $paycenter_app_id;
            $formvars['trade_type'] = 15;
            $formvars['type'] = 'row';

            $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=directsellerOrder&typ=json', $url), $formvars);

            $before_order_total_amount = $before_order_total_amount + $order_amount;//累计金额
            $order_rebate_value = $order_rebate_value + $order_rebate_value_temp;
        }

//        $total_children_order_total_amount = $user_info['children_order_total_amount'] * 1 + $user_order_amount;
//        $rebate_value1 = $user_order_amount * $grade_order_rebate1;
//        //如果上次累计订单总金额已超指标，则超过指标部分提成为:该次金额*rebate2
//        if($user_info['children_order_total_amount'] * 1 >= $grade_order_amount){
//            $rebate_value2 = $user_order_amount * $grade_order_rebate2;
//        }else if ($total_children_order_total_amount > $grade_order_amount) {
//            //如果该次累计总金额超过指标，则超过指标部分提成为:（累计总金额-指标部分）*rebate2
//            $order_amount2 = $total_children_order_total_amount - $grade_order_amount;
//            if ($order_amount2 > 0) {
//                $rebate_value2 = $order_amount2 * $grade_order_rebate2;
//            } else {
//                $rebate_value2 = 0;
//            }
//        }else if ($total_children_order_total_amount <= $grade_order_amount) {
//            //如果该次累计总金额未超过指标，则超过指标部分提成为:0
//            $rebate_value2 = 0;
//        }
//        $order_rebate_value = $rebate_value1 + $rebate_value2;

        //更新合伙人的当前度累计订单金额
        $edit_row['children_order_total_amount'] = $total_children_order_total_amount;
        $User_InfoModel->editInfo($user_id, $edit_row);
    }
}

if ($flag !== false && $Order_BaseModel->sql->commitDb()) {
    $status = 200;
    $msg = __('success');
} else {
    $Order_BaseModel->sql->rollBackDb();
    $m = $Order_BaseModel->msg->getMessages();
    $msg = $m ? $m[0] : __('failure');
    $status = 250;
}

//开启事物
$Order_BaseModel->sql->startTransactionDb();

//获取所有的高级合伙人
$g_partner_list = $User_InfoModel->getByWhere(['user_grade' => 4, 'user_name:!='=>'admin', 'user_statu'=>0]);
$user_g_grade = $User_GradeModel->getOne(4);
Yf_Log::log(count($g_partner_list), Yf_Log::LOG, 'debug');
foreach ($g_partner_list as $key => $user_info) {
    $user_id = $user_info['user_id'];

    //计算高级合伙人的提成比例
    $grade_order_rebate1 = $user_g_grade['order_rebate1'] * 1/100;
    $grade_order_rebate2 = $user_g_grade['order_rebate2'] * 1/100;
    $grade_order_rebate_top = $user_g_grade['order_rebate_top'] * 1;
    $partner_count = $user_info['current_year_partner_count'];
    $order_rebate = $grade_order_rebate1 * 1 + $grade_order_rebate2 * $partner_count;
    if ($order_rebate > $grade_order_rebate_top) {
        $order_rebate = $grade_order_rebate_top;
    }
    if ($order_rebate == 0) continue;

    //获取高级合伙人所有线下的未结算订单
    $user_children_ids = $User_InfoModel->getUserChildren($user_id, 0);
    if(!$user_children_ids) continue;
    $cond_row['buyer_user_id:in'] = explode(',', $user_children_ids);
    $cond_row['order_status'] = Order_StateModel::ORDER_FINISH; //已完成
    $cond_row['g_rebate_is_settlement'] = Order_BaseModel::IS_NOT_SETTLEMENT; //未结算
    $cond_row['order_create_time:>='] = $user_info['user_grade_update_date'];//订单是升级成为高级合伙人后的订单
    $user_orders = $Order_BaseModel->getByWhere($cond_row, $order_row);
    Yf_Log::log(count($user_orders), Yf_Log::LOG, 'debug');

    $order_rebate_value = 0;
    if (count($user_orders) > 0) {
        $user_order_pay_amount = array_sum(array_column($user_orders, 'order_payment_amount'));
        $user_order_refund_amount = array_sum(array_column($user_orders, 'order_refund_amount'));
        $user_order_amount = $user_order_pay_amount - $user_order_refund_amount;
        Yf_Log::log($user_order_amount, Yf_Log::LOG, 'debug');

        foreach ($user_orders as $k=>$order){
            $order_amount = $order['order_payment_amount'] * 1 - $order['order_refund_amount'] * 1;
            $order_rebate_value_temp = $order_amount * $order_rebate;
            Yf_Log::log($order_rebate_value_temp, Yf_Log::LOG, 'debug');

            if($order_rebate_value_temp == 0) continue;
            $order_rebate_value_temp = round($order_rebate_value_temp,2);

            //将需要确认的订单号远程发送给Paycenter修改订单状态
            //远程修改paycenter中的订单状态
            $key = Yf_Registry::get('paycenter_api_key');
            $url = Yf_Registry::get('paycenter_api_url');
            $paycenter_app_id = Yf_Registry::get('paycenter_app_id');

            $formvars = array();
            $formvars['user_id'] = $user_id;
            $formvars['order_id'] = $order['order_id'];
            $formvars['user_money'] = $order_rebate_value_temp;
            $formvars['reason'] = '高级合伙人订单提成结算';
            $formvars['desc'] = "订单结算时提成比例为{$order_rebate}";
            $formvars['app_id'] = $paycenter_app_id;
            $formvars['trade_type'] = 15;
            $formvars['type'] = 'row';

            $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=directsellerOrder&typ=json', $url), $formvars);
        }

        //计算出未结算的订单总额
//        $order_rebate_value = $user_order_amount * $order_rebate;

        $user_order_ids = array_column($user_orders, 'order_id');
        $order_edit_row['g_rebate_is_settlement'] = Order_BaseModel::IS_SETTLEMENT;
        $flag = $Order_BaseModel->editBase($user_order_ids, $order_edit_row);
    }
}

if ($flag !== false && $Order_BaseModel->sql->commitDb()) {
    $status = 200;
    $msg = __('success');
} else {
    $Order_BaseModel->sql->rollBackDb();
    $m = $Order_BaseModel->msg->getMessages();
    $msg = $m ? $m[0] : __('failure');
    $status = 250;
}

return $flag;
?>