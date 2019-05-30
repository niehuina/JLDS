<?php


class Seller_Stock_OrderCtl extends Seller_Controller
{
    /**
     * Constructor
     *
     * @param string $ctl 控制器目录
     * @param string $met 控制器方法
     * @param string $typ 返回数据类型
     * @access public
     */
    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);
    }

    public function physical()
    {
        $condition = array();
        self::createSearchCondi($condition);

        //分页
        $Yf_Page = new Yf_Page();
        $rows = $Yf_Page->listRows;
        $offset = request_int('firstRow', 0);
        $page = ceil_r($offset / $rows);

        $order['order_create_time'] = 'desc';
        $Stock_OrderModel = new Stock_OrderModel();
        $data = $Stock_OrderModel->getOrderList($condition, $order, $page, $rows);

        $Yf_Page->totalRows = $data['totalsize'];
        $page_nav = $Yf_Page->prompt();

        include $this->view->getView();
    }

    public function delivering()
    {
        $condition = array();
        self::createSearchCondi($condition);
        $condition['order_status'] = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;

        //分页
        $Yf_Page = new Yf_Page();
        $rows = $Yf_Page->listRows;
        $offset = request_int('firstRow', 0);
        $page = ceil_r($offset / $rows);

        $order['order_create_time'] = 'desc';
        $Stock_OrderModel = new Stock_OrderModel();
        $data = $Stock_OrderModel->getOrderList($condition, $order, $page, $rows);

        $Yf_Page->totalRows = $data['totalsize'];
        $page_nav = $Yf_Page->prompt();

        $this->view->setMet('physical');
        include $this->view->getView();
    }

    public function delivered()
    {
        $condition = array();
        self::createSearchCondi($condition);
        $condition['order_status'] = Order_StateModel::ORDER_FINISH;

        //分页
        $Yf_Page = new Yf_Page();
        $rows = $Yf_Page->listRows;
        $offset = request_int('firstRow', 0);
        $page = ceil_r($offset / $rows);

        $order['order_create_time'] = 'desc';
        $Stock_OrderModel = new Stock_OrderModel();
        $data = $Stock_OrderModel->getOrderList($condition, $order, $page, $rows);

        $Yf_Page->totalRows = $data['totalsize'];
        $page_nav = $Yf_Page->prompt();

        $this->view->setMet('physical');
        include $this->view->getView();
    }

    private function createSearchCondi(&$condition)
    {
        $query_start_date = request_string('query_start_date');
        $query_end_date = request_string('query_end_date');
        $query_buyer_name = request_string('query_buyer_name');
        $query_order_sn = request_string('query_order_sn');

        if (!empty($query_start_date)) {
            $condition['order_create_time:>='] = $query_start_date;
        }

        if (!empty($query_end_date)) {
            $condition['order_create_time:<='] = $query_end_date;
        }

        if (!empty($query_buyer_name)) {
            $condition['buyer_user_name:LIKE'] = "%$query_buyer_name%";
        }

        if (!empty($query_order_id)) {
            $condition['stock_order_id'] = $query_order_sn;
        }

        return $condition;
    }

    public function add()
    {
        $action = request_string('action');
        $order_id = request_int('order_id');
        $user_id = request_int('user_id');
        $shop_id = request_int('shop_id');

        if ($user_id) {
            $Shop_BaseModel = new Shop_BaseModel();
            $shop_info = $Shop_BaseModel->getOne($shop_id);

            $User_InfoModel = new User_InfoModel();
            $user_info = $User_InfoModel->getOne($user_id);

            $User_AddressModel = new User_AddressModel();
            $user_address = $User_AddressModel->getDefaultAddress($user_id);
            if (!$user_address) {
                $user_address = $User_AddressModel->getAddressList(['user_id' => $user_id]);
                $user_address = current($user_address);
            }

            //获取一级地址
            $district_parent_id = request_int('pid', 0);
            $baseDistrictModel = new Base_DistrictModel();
            $district = $baseDistrictModel->getDistrictTree($district_parent_id);

            //收货人信息
            if (empty($user_address)) {
                $user_address['shipper'] = 0;
                $user_address['shipper_info'] = '还未设置发货地址，请进入发货设置 &gt 地址库中添加';
            } else {
                $user_address['shipper'] = 1;
                $user_address['shipper_info'] = $user_address['user_address_contact'] . "&nbsp" . $user_address['user_address_area'] . "&nbsp" . $user_address['user_address_address'] . "&nbsp" . $user_address['user_address_phone'];
            }

            //获取用户的资金信息
            $user_resouce = null;
            $formvars = array();
            $formvars['user_id'] = $user_id;
            $rs = $this->getPayCenterUrl('Api_User_Info', 'getUserResourceInfo', $formvars);
            if ($rs['status'] == '200') {
                $user_resouce = $rs['data'];
            }

            $this->view->setMet('selectGoods');
        } else {

        }
        include $this->view->getView();
    }

    public function addSendOrder()
    {
        $action = request_string('action');
        $order_id = request_string('order_id');
        $user_id = request_int('user_id');
        $shop_id = request_int('shop_id');
        $select_goods_list = request_string('select_goods_list');
        $select_goods_list = decode_json($select_goods_list);
        $order_address_name = request_string('order_address_name');
        $user_address_area = request_string('user_address_area');
        $order_address_address = request_string('order_address_address');
        $order_address_phone = request_string('order_address_phone');
//        $order_total_amount  = request_string('total_amount');

        if (isset($action) && $action == 'edit') {

        } else {
            $Shop_BaseModel = new Shop_BaseModel();
            $shop_info = $Shop_BaseModel->getOne($shop_id);

            $User_InfoModel = new User_InfoModel();
            $user_info = $User_InfoModel->getOne($user_id);

            $prefix = sprintf('%s-%s-', Yf_Registry::get('shop_app_id'), date('YmdHis'));
            $Number_SeqModel = new Number_SeqModel();
            $order_number = $Number_SeqModel->createSeq($prefix);
            $order_id = sprintf('%s-%s-%s-%s', 'BH', $user_id, $shop_id, $order_number);

            $order_row = array();
            $order_row['stock_order_id'] = $order_id;
            $order_row['order_date'] = date('Y-m-d');
            $order_row['order_create_time'] = get_date_time();
            $order_row['shop_id'] = $shop_id;
            $order_row['shop_name'] = $shop_info['shop_name'];
            $order_row['shop_user_id'] = $user_id;
            $order_row['shop_user_name'] = $user_info['user_name'];
            $order_row['order_receiver_name'] = $order_address_name;
            $order_row['order_receiver_address'] = $user_address_area . ' ' . $order_address_address;
            $order_row['order_receiver_phone'] = $order_address_phone;

            //备货订单物流信息
//            $order_row['order_shipping_fee'] = 0;
//            $order_row['order_shipping_time'] = $order_address_phone;
//            $order_row['order_shipping_method'] = $order_address_phone;
//            $order_row['order_shipping_express_id'] = $order_address_phone;
//            $order_row['order_shipping_code'] = $order_address_phone;
//            $order_row['order_shipping_message'] = $order_address_phone;

            //计算订单总金额
            $order_total_amount = 0;
            $order_total_amount_vip = 0;
            $order_total_amount_partner = 0;

            $goods_ids = array_keys($select_goods_list);
            $cond_row = array();
            $self_shop_id = Perm::$shopId;
            $cond_row['shop_id'] = $self_shop_id;
            $cond_row['goods_id:in'] = $goods_ids;
            $order['CONVERT(goods_name USING gbk)'] = 'asc';
            $Goods_BaseModel = new Goods_BaseModel();
            $goods_list = $Goods_BaseModel->getByWhere($cond_row, $order);

            //如果卖家设置了默认地址，则将默认地址信息加入order表
            $Shop_ShippingAddressModel = new Shop_ShippingAddressModel();
            $address_list              = $Shop_ShippingAddressModel->getByWhere(array('shop_id' => $self_shop_id, 'shipping_address_default'=>1));
            if($address_list)
            {
                $address_list = current($address_list);
                $order_row['order_seller_address'] = $address_list['shipping_address_area'] . " " . $address_list['shipping_address_address'];
                $order_row['order_seller_contact'] = $address_list['shipping_address_phone'];
                $order_row['order_seller_name']    = $address_list['shipping_address_contact'];
            }

            $rs = array();
            $Stock_OrderModel = new Stock_OrderModel();
            $Stock_OrderGoodsModel = new Stock_OrderGoodsModel();
            $Goods_BaseModel = new Goods_BaseModel();
            $Goods_CommonModel = new Goods_CommonModel();
            $Stock_OrderModel->sql->startTransactionDb();
            foreach ($select_goods_list as $key => $goods_num) {
                $goods_base = $goods_list[$key];

                //计算单个商品金额
                $order_goods_amount = $goods_base['goods_price'] * 1 * $goods_num;
                $order_goods_amount_vip = $goods_base['goods_price_vip'] * 1 * $goods_num;
                $order_goods_amount_partner = $goods_base['goods_price_partner'] * 1 * $goods_num;

                //计算订单总金额
                $order_total_amount = $order_total_amount + $order_goods_amount;
                $order_total_amount_vip = $order_total_amount_vip + $order_goods_amount_vip;
                $order_total_amount_partner = $order_total_amount_partner + $order_goods_amount_partner;

                $order_goods_row = array();
                $order_goods_row['stock_order_id'] = $order_id;
                $order_goods_row['shop_id'] = $shop_id;
                $order_goods_row['shop_user_id'] = $user_id;
                $order_goods_row['goods_id'] = $goods_base['goods_id'];
                $order_goods_row['common_id'] = $goods_base['common_id'];
                $order_goods_row['goods_name'] = $goods_base['goods_name'];
                $order_goods_row['goods_class_id'] = $goods_base['cat_id'];
                $order_goods_row['goods_price'] = $goods_base['goods_price']; //商品单价
                $order_goods_row['goods_price_vip'] = $goods_base['goods_price_vip']; //商品VIP价
                $order_goods_row['goods_price_partner'] = $goods_base['goods_price_partner']; //商品股东价
                $order_goods_row['goods_num'] = $goods_num;
                $order_goods_row['goods_image'] = $goods_base['goods_image'];
                $order_goods_row['order_goods_amount'] = $order_goods_amount;  //商品总金额
                $order_goods_row['order_goods_amount_vip'] = $order_goods_amount_vip;  //商品VIP价总金额
                $order_goods_row['order_goods_amount_partner'] = $order_goods_amount_partner;  //商品股东价总金额
                $order_goods_row['order_goods_commission'] = $order_goods_amount_vip-$order_goods_amount_partner;  //商品股东价总金额
                $order_goods_row['order_goods_time'] = get_date_time();

                $flag2 = $Stock_OrderGoodsModel->addOrderGoods($order_goods_row);
                check_rs($flag2, $rs);

                //减少商品库存
                $flags = $Goods_BaseModel->reduceGoodsStock($goods_base['goods_id'], $goods_num);
                check_rs($flag2, $rs);
            }

            $order_row['order_payment_amount'] = $order_total_amount;
            $order_row['order_payment_amount_vip'] = $order_total_amount_vip;
            $order_row['order_payment_amount_partner'] = $order_total_amount_partner;
            $order_row['order_commission_fee'] = $order_total_amount_vip - $order_total_amount_partner;
            $order_row['order_status'] = Order_StateModel::ORDER_PAYED;
            $flag = $Stock_OrderModel->addOrder($order_row);
            check_rs($flag, $rs);

            //支付中心生成订单
            $formvars = array();
            $formvars['from_app_id'] = Yf_Registry::get('shop_app_id');
            $formvars['consume_trade_id'] = $order_id;
            $formvars['order_id'] = $order_id;
            $formvars['buy_id'] = $user_id;
            $formvars['buyer_name'] = $user_info['user_name'];
            $formvars['seller_id'] = $shop_info['user_id'];
            $formvars['seller_name'] = $shop_info['user_name'];
            $formvars['order_state_id'] = $order_row['order_status'];
            $formvars['order_payment_amount'] = $order_row['order_goods_amount_vip'];
            $formvars['order_commission_fee'] = 0;
            $formvars['trade_remark'] = $order_row['order_message'];
            $formvars['trade_create_time'] = $order_row['order_create_time'];
            $formvars['trade_title'] = $user_info['user_name'] . '的备货订单';        //订单标题

            $rs1 = $this->getPayCenterUrl('Api_Pay_Pay', 'addConsumeTradeForStock', $formvars);

            //将合并支付单号插入数据库
            if ($rs1['status'] == 200) {
                $flag = $Stock_OrderModel->editOrder($order_id, array('payment_number' => $rs1['data']['union_order']));
                check_rs($flag, $rs);
            } else {
                check_rs(false, $rs);
            }

            $data['order_id'] = $order_id;
            if (is_ok($rs) && $Stock_OrderModel->sql->commitDb()) {
                $status = 200;
                $msg = __('success');
                location_to(Yf_Registry::get('url').'?ctl=Seller_Stock_Order&met=physical');
            } else {
                $Stock_OrderModel->sql->rollBackDb();
                $m = $Stock_OrderModel->msg->getMessages();
                $msg = $m ? $m[0] : __('failure');
                $status = 250;
            }

            $this->data->addBody(-140, $data, $msg, $status);
        }
    }

    public function listShop()
    {
        $Shop_BaseModel = new Shop_BaseModel();

        $page = request_int('page', 1);
        $rows = request_int('rows', 20);

        $cond_row = array();
        $cond_row['shop_status'] = 3;
        $cond_row['shop_self_support'] = Shop_BaseModel::SELF_SUPPORT_FALSE;
        $order_row['shop_create_time'] = 'asc';
        $user_name = request_string('user_name');
        if ($user_name) {
            $cond_row['user_name'] = ['like', "%{$user_name}%"];
        }

        $order_row['shop_create_time'] = 'asc';
        $shop_list = $Shop_BaseModel->getShopUserList($cond_row, $order_row, $page, $rows);

        $this->data->addBody(-140, $shop_list);
    }

    public function listGoods()
    {
        $page = request_int('page', 1);
        $rows = request_int('rows', 20);

        $self_shop_id = Web_ConfigModel::value('self_shop_id');

        $order_row['CONVERT(goods_name USING gbk)'] = 'asc';
        $Goods_BaseModel = new Goods_BaseModel();
        $goods_list = $Goods_BaseModel->getGoodsListByShopId($self_shop_id, $order_row, $page, $rows);

        $this->data->addBody(-140, $goods_list);
    }

    public function chooseSendAddress()
    {
        $typ = request_string('typ');

        if ($typ == 'e')
        {
            $shop_id = Perm::$shopId;
            $Shop_ShippingAddressModel = new Shop_ShippingAddressModel();
            $address_list              = $Shop_ShippingAddressModel->getByWhere(array('shop_id' => $shop_id));
            $address_list              = array_values($address_list);
            foreach ($address_list as $key => $val)
            {
                $address_list[$key]['address_info']  = $val['shipping_address_area'] . " " . $val['shipping_address_address'];
                $address_list[$key]['address_value'] = $val['shipping_address_contact'] . "&nbsp" . $val['shipping_address_phone'] . "&nbsp" . $val['shipping_address_area'] . "&nbsp" . $val['shipping_address_address'];
            }

            include $this->view->getView();
        }
        else
        {
            $order_id     = request_string('order_id');
            $send_address = request_row('send_address');

            $Stock_OrderModel = new Stock_OrderModel();
            $update_data['order_seller_name']    = $send_address['order_seller_name'];
            $update_data['order_seller_address'] = $send_address['order_seller_address'];
            $update_data['order_seller_contact'] = $send_address['order_seller_contact'];
            $flag                                = $Stock_OrderModel->editOrder($order_id, $update_data);

            if ($flag || $flag === 0)
            {
                $msg    = __('设置成功');
                $status = 200;
            }
            else
            {
                $msg    = __('设置失败');
                $status = 250;
            }

            $this->data->addBody(-140, array(), $msg, $status);
        }
    }

    /**
     * 实物交易订单 ==> 设置发货
     *
     * @access public
     */
    public function send()
    {
        $typ = request_string('typ');
        $order_id = request_string('order_id');

        $Stock_OrderModel = new Stock_OrderModel();

        if ($typ == 'e') {
            $condi['stock_order_id'] = $order_id;
            $data = $Stock_OrderModel->getOrderInfo($condi);

            //默认物流公司 url
            $default_express_url = Yf_Registry::get('url') . '?ctl=Seller_Trade_Deliver&met=express&typ=e';
            //打印运单URL
            $print_tpl_url = Yf_Registry::get('url') . '?ctl=Seller_Trade_Waybill&met=printTpl&typ=e&order_id=' . $order_id;

            //默认物流公司
            $Shop_ExpressModel = new Shop_ExpressModel();
            $express_list = $Shop_ExpressModel->getDefaultShopExpress();
            if (is_array($express_list) && $express_list) {
                $express_list = array_values($express_list);
            }
            include $this->view->getView();
        } else {
            //判断该笔订单是否是自己的单子
            $order_base = $Stock_OrderModel->getOne($order_id);

            $rs_row = array();

            //开启事物
            $Stock_OrderModel->sql->startTransactionDb();

            if ($order_base['order_status'] < Order_StateModel::ORDER_RECEIVED) {
                //设置发货
                $update_data['order_status'] = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
                $update_data['order_shipping_express_id'] = request_int('order_shipping_express_id');
                $update_data['order_shipping_code'] = request_int('order_shipping_code');
                $update_data['order_shipping_message'] = request_string('order_shipping_message');
//                $update_data['order_shipping_method']     = $order_address_phone;
//                $update_data['order_seller_message']      = request_string('order_seller_message');

                //配送时间 收货时间
                $current_time = time();
                $confirm_order_time = Yf_Registry::get('confirm_order_time');
                $update_data['order_shipping_time'] = date('Y-m-d H:i:s', $current_time);
                $update_data['order_receiver_date'] = date('Y-m-d H:i:s', $current_time + $confirm_order_time);

                $edit_flag = $Stock_OrderModel->editOrder($order_id, $update_data);
                check_rs($edit_flag, $rs_row);

                $flag = is_ok($rs_row);
                if($flag) {
                    //远程修改paycenter中的订单信息
                    $formvars = array();
                    $formvars['order_id'] = $order_id;
                    $rs = $this->getPayCenterUrl('Api_Pay_Pay', 'sendOrderGoods', $formvars);
                    if ($rs['status'] == 200) {
                        $rs_flag = true;
                        check_rs($rs_flag, $rs_row);
                    } else {
                        $rs_flag = false;
                        check_rs($rs_flag, $rs_row);
                    }
                }
            } else {
                $flag = false;
                check_rs($flag, $rs_row);
            }
            $flag = is_ok($rs_row);

            if ($flag && $Stock_OrderModel->sql->commitDb()) {
                //发送站内信
                $message = new MessageModel();
                $message->sendMessage('ordor_complete_shipping', $order_base['shop_user_id'], $order_base['shop_user_name'], $order_id, $order_base['shop_name'], 0, MessageModel::ORDER_MESSAGE);

                $msg = __('success');
                $status = 200;
            } else {
                $Stock_OrderModel->sql->rollBackDb();
                $msg = __('failure');
                $status = 250;
            }

            $this->data->addBody(-140, array(), $msg, $status);
        }
    }


    /**
     * 实物交易订单 ==> 选择发货地址
     *
     * @access public
     */
    public function editBuyerAddress()
    {
        $typ = request_string('typ');

        if ($typ == 'e')
        {
            include $this->view->getView();
        }
        else
        {
            $Stock_OrderModel = new Stock_OrderModel();

            $order_id = request_string('order_id');

            $update_data['order_receiver_name']    = request_string('order_receiver_name');
            $update_data['order_receiver_address'] = request_string('order_receiver_address');
            $update_data['order_receiver_phone'] = request_string('order_receiver_phone');

            $flag = $Stock_OrderModel->editOrder($order_id, $update_data);

            if ($flag)
            {
                $update_data['receiver_info'] = $update_data['order_receiver_name'] . "&nbsp;" . $update_data['order_receiver_address'] . "&nbsp;" . $update_data['order_receiver_phone'];
                $msg                          = __('success');
                $status                       = 200;
            }
            else
            {
                $msg    = __('failure');
                $status = 250;
            }

            $this->data->addBody(-140, $update_data, $msg, $status);
        }

    }

    public function physicalInfo()
    {
        $order_id        = request_string('order_id');
        $Order_BaseModel = new Stock_OrderModel();
        $data            = $Order_BaseModel->getPhysicalInfoData(array('stock_order_id' => $order_id));
        include $this->view->getView();
    }

    public function cancelOrder()
    {
        $typ = request_string('typ');
        $rs_row = array();

        if ($typ == 'e') {
            $cancel_row['cancel_identity'] = Order_CancelReasonModel::CANCEL_SELLER;

            //获取取消原因
            $Order_CancelReasonModel = new Order_CancelReasonModel;
            $reason = array_values($Order_CancelReasonModel->getByWhere($cancel_row));

            include $this->view->getView();
        } else {
            $Stock_OrderModel = new Stock_OrderModel();

            //开启事物
            $Stock_OrderModel->sql->startTransactionDb();

            $order_id = request_string('order_id');
            $state_info = request_string('state_info');

            //获取订单详情，判断订单的当前状态与下单这是否为当前用户
            $order_base = $Stock_OrderModel->getOne($order_id);

            if ($order_base['order_status'] == Order_StateModel::ORDER_PAYED) {
                if (empty($state_info)) {
                    $state_info = request_string('state_info1');
                }
                //加入取消时间
                $condition['order_status'] = Order_StateModel::ORDER_CANCEL;
                $condition['order_cancel_reason'] = addslashes($state_info);
                $condition['order_cancel_identity'] = Order_BaseModel::IS_ADMIN_CANCEL;
                $condition['order_cancel_date'] = get_date_time();

                $edit_flag = $Stock_OrderModel->editOrder($order_id, $condition);
                check_rs($edit_flag, $rs_row);

                //修改订单商品表中的订单状态
                $edit_row['order_goods_status'] = Order_StateModel::ORDER_CANCEL;
                $Stock_OrderGoodsModel = new Stock_OrderGoodsModel();
                $order_goods_id = $Stock_OrderGoodsModel->getKeyByWhere(array('stock_order_id' => $order_id));

                $edit_flag1 = $Stock_OrderGoodsModel->editOrderGoods($order_goods_id, $edit_row);
                check_rs($edit_flag1, $rs_row);

                //退还订单商品的库存
                $Goods_BaseModel = new Goods_BaseModel();
                $edit_flag2 = $Goods_BaseModel->addGoodsStock($order_goods_id);
                check_rs($edit_flag2, $rs_row);
            }

            $flag = is_ok($rs_row);

            if($flag){
                //将需要取消的订单号远程发送给Paycenter修改订单状态
                //远程修改paycenter中的订单状态
                $formvars = array();
                $formvars['order_id'] = $order_id;
                $rs = $this->getPayCenterUrl('Api_Pay_Pay', 'cancelOrder', $formvars);
                if ($rs['status'] == 200) {
                    $edit_flag3 = true;
                    check_rs($edit_flag3, $rs_row);
                } else {
                    $edit_flag3 = false;
                    check_rs($edit_flag3, $rs_row);
                }
            }

            $flag = is_ok($rs_row);
            if ($flag && $Stock_OrderModel->sql->commitDb()) {
                $status = 200;
                $msg = __('success');
            } else {
                $Stock_OrderModel->sql->rollBackDb();
                $m = $Stock_OrderModel->msg->getMessages();
                $msg = $m ? $m[0] : __('failure');
                $status = 250;
            }
            $this->data->addBody(-140, array(), $msg, $status);
        }
    }
}