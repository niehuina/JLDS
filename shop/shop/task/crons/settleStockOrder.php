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
 * 结算备货差价
 */
$Stock_OrderModel = new Stock_OrderModel();
$Order_GoodsModel = new Stock_OrderGoodsModel();
$User_InfoModel = new User_InfoModel();

//开启事物
$Stock_OrderModel->sql->startTransactionDb();

//查找出所有确认收货的未结算订单
//$time = time()-7*24*60*60;
//$N = date('Y-m-d H:i:s',$time);
$N = get_date_time();

$cond_row = array();
$cond_row['order_status'] = Order_StateModel::ORDER_FINISH;
$cond_row['order_finished_time:<='] = $N;
$cond_row['order_is_settlement'] = Order_BaseModel::IS_NOT_SETTLEMENT; //未结算

$data = $Stock_OrderModel->getByWhere($cond_row);

if ($data) {
    foreach ($data as $key => $order) {
        $stock_order_id = $order['stock_order_id'];
        $edit['order_is_settlement'] = Order_BaseModel::IS_SETTLEMENT;
        $flag = $Stock_OrderModel->editOrder($stock_order_id, $edit);

        //将需要确认的订单号远程发送给Paycenter修改订单状态
        //远程修改paycenter中的订单状态
        $key = Yf_Registry::get('paycenter_api_key');
        $url = Yf_Registry::get('paycenter_api_url');
        $paycenter_app_id = Yf_Registry::get('paycenter_app_id');
        $formvars = array();

        $formvars['order_id'] = $stock_order_id;
        $formvars['user_id'] = $order['shop_user_id'];
        $formvars['user_money'] = $order['order_commission_fee'];
        $formvars['reason'] = '备货订单' . $val['order_id'] . '差额返还';
        $formvars['app_id'] = $paycenter_app_id;
        $formvars['type'] = 'row';

        $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=directsellerOrder&typ=json', $url), $formvars);
    }
} else {
    $flag = true;
}


if ($flag && $Stock_OrderModel->sql->commitDb()) {
    $status = 200;
    $msg = __('success');
} else {
    $Stock_OrderModel->sql->rollBackDb();
    $m = $Stock_OrderModel->msg->getMessages();
    $msg = $m ? $m[0] : __('failure');
    $status = 250;
}

return $flag;
?>