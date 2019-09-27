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
Yf_Log::log("partner:".count($partner_list), Yf_Log::LOG, 'user_settle');

$User_SettleProfitModel = new User_SettleProfitModel();
foreach ($partner_list as $key => $user_info) {
    $user_id = $user_info['user_id'];
    $flag = $User_SettleProfitModel->rebateOrderForPartner($user_id, $user_info);
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

$flag = true;
//开启事物
$Order_BaseModel->sql->startTransactionDb();

//获取所有的高级合伙人
$g_partner_list = $User_InfoModel->getByWhere(['user_grade' => 4, 'user_name:!='=>'admin', 'user_statu'=>0]);
$user_g_grade = $User_GradeModel->getOne(4);
Yf_Log::log("g_partner:".count($g_partner_list), Yf_Log::LOG, 'user_settle');
foreach ($g_partner_list as $key => $user_info) {
    $user_id = $user_info['user_id'];
    $flag = $User_SettleProfitModel->rebateOrderForPartner($user_id, $user_info);
    $flag1 = $User_SettleProfitModel->rebateOrderForGPartner($user_id, $user_info);
}

if ($flag !== false && $flag1 !== false && $Order_BaseModel->sql->commitDb()) {
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