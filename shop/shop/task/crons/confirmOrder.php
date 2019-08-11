<?php
if (!defined('ROOT_PATH'))
{
	if (is_file('../../../shop/configs/config.ini.php'))
	{
		require_once '../../../shop/configs/config.ini.php';
	}
	else
	{
		die('请先运行index.php,生成应用程序框架结构！');
	}

	//不会重复包含, 否则会死循环: web调用不到此处, 通过crontab调用
	$Base_CronModel = new Base_CronModel();
	$rows = $Base_CronModel->checkTask(); //并非指执行自己, 将所有需要执行的都执行掉, 如果自己达到执行条件,也不执行.

	//终止执行下面内容, 否则会执行两次
	return ;
}


Yf_Log::log(__FILE__, Yf_Log::INFO, 'crontab');

$file_name_row = pathinfo(__FILE__);
$crontab_file = $file_name_row['basename'];

//自动确认收货(实物订单)
$Order_BaseModel = new Order_BaseModel();
$Order_GoodsModel = new Order_GoodsModel();

//开启事物
$Order_BaseModel->sql->startTransactionDb();

//查找出所有待收货状态的商品
$cond_row = array();
$cond_row['order_status'] = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
$cond_row['order_is_virtual'] = Order_BaseModel::ORDER_IS_REAL;
$cond_row['order_receiver_date:<='] = get_date_time();
$order_list = $Order_BaseModel->getKeyByWhere($cond_row);
fb($order_list);

$flag= true;
$order_row = array();
if($order_list)
{
	foreach ($order_list as $key => $val)
	{
		$order_row[] = $val;
        $order_id = $val;
        $Order_BaseModel->confirmOrder($order_id);

//		$order_base           = $Order_BaseModel->getOne($order_id);
//		$order_payment_amount = $order_base['order_payment_amount'];
//
//		$condition['order_status'] = Order_StateModel::ORDER_FINISH;
//
//		$condition['order_finished_time'] = get_date_time();
//
//		$flag = $Order_BaseModel->editBase($order_id, $condition);
//
//		//修改订单商品表中的订单状态
//		$edit_row['order_goods_status'] = Order_StateModel::ORDER_FINISH;
//
//		$order_goods_id = $Order_GoodsModel->getKeyByWhere(array('order_id' => $order_id));
//
//		$Order_GoodsModel->editGoods($order_goods_id, $edit_row);
//
//        $order_goods_data = $Order_GoodsModel->getByWhere(array('order_id' => $order_id));
//        if (Web_ConfigModel::value('Plugin_Directseller')) {
//            //确认收货以后将总佣金写入商品订单表
//            $order_directseller_commission = array_sum(array_column($order_goods_data, 'directseller_commission_0')) + array_sum(array_column($order_goods_data, 'directseller_commission_1')) + array_sum(array_column($order_goods_data, 'directseller_commission_2'));
//            $condition['order_directseller_commission'] = $order_directseller_commission;
//        }
//
//        //添加到个人仓库
//        $User_Stock_Model = new User_StockModel();
//        $User_Stock_Model->editStockFromOrder($order_goods_data, $order_base['buyer_user_id'], $order_base['buyer_user_name']);
//
//        $Order_BaseModel->confirmOrder_user_log($order_payment_amount, $order_base['buyer_user_id'], $order_base['buyer_user_name']);
	}

	//将需要确认的订单号远程发送给Paycenter修改订单状态
	//远程修改paycenter中的订单状态
//	$key      = Yf_Registry::get('shop_api_key');
//	$url         = Yf_Registry::get('paycenter_api_url');
//	$shop_app_id = Yf_Registry::get('shop_app_id');
//	$formvars = array();
//
//	$formvars['order_id']    = $order_row;
//	$formvars['app_id']        = $shop_app_id;
//	$formvars['type']		= 'row';
//
//	fb($formvars);
//
//	$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=confirmOrder&typ=json', $url), $formvars);
}
else
{
	$flag = true;
}


if ($flag && $Order_BaseModel->sql->commitDb())
{
	$status = 200;
	$msg    = __('success');
}
else
{
	$Order_BaseModel->sql->rollBackDb();
	$m      = $Order_BaseModel->msg->getMessages();
	$msg    = $m ? $m[0] : __('failure');
	$status = 250;
}


return $flag;
?>