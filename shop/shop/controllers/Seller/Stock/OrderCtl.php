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
        $self_shop_id = Web_ConfigModel::value('self_shop_id');
        if($self_shop_id == Perm::$shopId) {
            $data = $Stock_OrderModel->getOrderList($condition, $order, $page, $rows);
        }else{
            $data = $Stock_OrderModel->getOrderUserList($condition, $order, $page, $rows);
        }

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

        $self_shop_id = Web_ConfigModel::value('self_shop_id');
        if($self_shop_id == Perm::$shopId) {
            $data = $Stock_OrderModel->getOrderList($condition, $order, $page, $rows);
        }else{
            $data = $Stock_OrderModel->getOrderUserList($condition, $order, $page, $rows);
        }

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
            $self_shop_id = Web_ConfigModel::value('self_shop_id');
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
                if(!$goods_num || $goods_num > $goods_base['goods_stock']) continue;

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
        $page    = request_int('page', 0);
        $rows    = request_int('rows', 20);

        $self_shop_id = Web_ConfigModel::value('self_shop_id');
        $cond_row['shop_id'] = $self_shop_id;
        $cond_row['common_state'] = Goods_CommonModel::GOODS_STATE_NORMAL;
        $cond_row['common_verify'] = Goods_CommonModel::GOODS_VERIFY_ALLOW;
        if (!empty($goods_key) && isset($goods_key))
        {
            $cond_row['common_name:like'] = '%' . $goods_key . '%';
        }

        $order_row['common_id'] = 'DESC';
        $Goods_CommonModel = new Goods_CommonModel();
        $common_rows = $Goods_CommonModel->getByWhere($cond_row);
        $common_id_rows = array_column($common_rows, 'common_id');
        $goods_list = array();
        if(!empty($common_id_rows))
        {
            $Goods_BaseModel = new Goods_BaseModel();
            $cond_goods_row = array();
            $cond_goods_row['common_id:in'] = $common_id_rows;
            $goods_order_row['CONVERT(goods_name USING gbk)'] = 'asc';
            $goods_list = $Goods_BaseModel->listByWhere($cond_goods_row, $goods_order_row, $page, $rows);
        }

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

    public function confirmOrder()
    {
        $typ = request_string('typ');

        if ($typ == 'e')
        {
            include $this->view->getView();
        }
        else
        {
            $Order_BaseModel = new Stock_OrderModel();
            $Order_GoodsModel = new Stock_OrderGoodsModel();

            $rs_row = array();
            //开启事物
            $Order_BaseModel->sql->startTransactionDb();

            $order_id = request_string('order_id');

            $order_base           = $Order_BaseModel->getOne($order_id);
            //判断下单者是否是当前用户
            if($order_base['shop_user_id'] == Perm::$userId && $order_base['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS)
            {
                $order_payment_amount = $order_base['order_payment_amount'];

                $condition['order_status'] = Order_StateModel::ORDER_FINISH;

                $condition['order_finished_time'] = get_date_time();

//                if(Web_ConfigModel::value('Plugin_Directseller'))
//                {
//                    //确认收货以后将总佣金写入商品订单表
//                    $order_goods_data = $Order_GoodsModel->getByWhere(array('order_id'=>$order_id));
//
//                    $order_directseller_commission = array_sum(array_column($order_goods_data,'directseller_commission_0')) + array_sum(array_column($order_goods_data,'directseller_commission_1')) + array_sum(array_column($order_goods_data,'directseller_commission_2'));
//                    $condition['order_directseller_commission'] = $order_directseller_commission;
//                }

                $edit_flag = $Order_BaseModel->editOrder($order_id, $condition);
                check_rs($edit_flag,$rs_row);

                //修改订单商品表中的订单状态
                $edit_row['order_goods_status'] = Order_StateModel::ORDER_FINISH;
                $order_goods_id                 = $Order_GoodsModel->getKeyByWhere(array('stock_order_id' => $order_id));

                $edit_flag1 = $Order_GoodsModel->editOrderGoods($order_goods_id, $edit_row);
                check_rs($edit_flag1,$rs_row);

                $User_Stock_Model = new User_StockModel();
                $user_stock_list = $User_Stock_Model->getByWhere(['user_id'=>$order_base['shop_user_id']]);
//                $goods_id_array = array($user_stock_list, 'goods_id');

                $goods_id_array = array_reduce($user_stock_list, function($carry,$item){
                    $carry[$item['goods_id']] = $item['stock_id'];
                    return $carry;
                });

                $Stock_OrderGoodsModel = new Stock_OrderGoodsModel();
                $orderGoods_list = $Stock_OrderGoodsModel->getByWhere(array('stock_order_id' => $order_id));
                foreach ($orderGoods_list as $order_goods){
                    $goods_id = $order_goods['goods_id'];
                    if(array_key_exists($goods_id, $goods_id_array)){
                        $stock_row = array();
                        $stock_row['goods_stock'] = $order_goods['goods_num'];

                        //修改用户仓储商品数量
                        $stock_id = $goods_id_array[$goods_id];
                        $s_flag = $User_Stock_Model->editUserStock($stock_id,$stock_row, true);
                        check_rs($s_flag,$rs_row);
                    }else{
                        $stock_row = array();
                        $stock_row['user_id'] = $order_base['shop_user_id'];
                        $stock_row['user_name'] = $order_base['shop_user_name'];
                        $stock_row['goods_id'] = $order_goods['goods_id'];
                        $stock_row['common_id'] = $order_goods['common_id'];
                        $stock_row['goods_name'] = $order_goods['goods_name'];
                        $stock_row['goods_stock'] = $order_goods['goods_num'];
                        $stock_row['alarm_stock'] = 0;
                        $stock_row['stock_date_time'] = get_date_time();

                        //添加到用户仓储
                        $s_flag = $User_Stock_Model->addUserStock($stock_row);
                        check_rs($s_flag,$rs_row);
                    }
                }

                //将需要确认的订单号远程发送给Paycenter修改订单状态
                //远程修改paycenter中的订单状态
                //判断修改用户的备货金
                $formvars = array();
                $formvars['order_id']    = $order_id;
                $formvars['from_app_id'] = Yf_Registry::get('shop_app_id');

                $rs = $this->getPayCenterUrl('Api_Pay_Pay', 'confirmOrder', $formvars);
                if($rs['status'] == 250)
                {
                    $rs_flag = false;
                    check_rs($rs_flag,$rs_row);
                }

//                /*
//                *  经验与成长值
//                */
//                $user_points        = Web_ConfigModel::value("points_recharge");//订单每多少获取多少积分
//                $user_points_amount = Web_ConfigModel::value("points_order");//订单每多少获取多少积分
//
//                if ($order_payment_amount / $user_points < $user_points_amount)
//                {
//                    $user_points = floor($order_payment_amount / $user_points);
//                }
//                else
//                {
//                    $user_points = $user_points_amount;
//                }
//
//                $user_grade        = Web_ConfigModel::value("grade_recharge");//订单每多少获取多少积分
//                $user_grade_amount = Web_ConfigModel::value("grade_order");//订单每多少获取多少成长值
//
//                if ($order_payment_amount / $user_grade > $user_grade_amount)
//                {
//                    $user_grade = floor($order_payment_amount / $user_grade);
//                }
//                else
//                {
//                    $user_grade = $user_grade_amount;
//                }
//
//                $User_ResourceModel = new User_ResourceModel();
//                //获取积分经验值
//                $ce = $User_ResourceModel->getResource(Perm::$userId);
//
//                $resource_row['user_points'] = $ce[Perm::$userId]['user_points'] * 1 + $user_points * 1;
//                $resource_row['user_growth'] = $ce[Perm::$userId]['user_growth'] * 1 + $user_grade * 1;
//
//                $res_flag = $User_ResourceModel->editResource(Perm::$userId, $resource_row);
//                check_rs($res_flag,$rs_row);
            }
            else
            {
                $flag = false;

                check_rs($flag,$rs_row);
            }

            $flag = is_ok($rs_row);

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

            $this->data->addBody(-140, array(), $msg, $status);
        }
    }

    public function user_stock()
    {
        $Yf_Page = new Yf_Page();
        $row     = $Yf_Page->listRows;
        $offset  = request_int('firstRow', 0);
        $page    = ceil_r($offset / $row);

        $goods_key  = request_string('goods_key', '');
        $User_Stock_Model = new User_StockModel();
        $cond_row['user_id'] = Perm::$userId;
        if(!empty($goods_key)){
            $cond_row['goods_name:like'] = '%' . $goods_key . '%';
        }
        $order_row['CONVERT(goods_name USING gbk)'] = 'asc';
        $goods = $User_Stock_Model->getUserStockList($cond_row, $order_row, $page, $row);

        $Yf_Page->totalRows = $goods['totalsize'];
        $page_nav = $Yf_Page->prompt();

        if ('json' == $this->typ)
        {
            $this->data->addBody(-140, $goods);
        }else{
            include $this->view->getView();
        }
    }

    public function setAlarm()
    {
        $typ = request_string('typ');
        $goods_id  = request_string('goods_id', '0');

        $User_Stock_Model = new User_StockModel();
        $cond_row['user_id'] = Perm::$userId;
        if($goods_id){
            $cond_row['goods_id'] = $goods_id;
        }
        $goods_stock = $User_Stock_Model->getOneByWhere($cond_row);

        if ($typ == 'e')
        {
            include $this->view->getView();
        }else{
            $alarm_stock  = request_int('alarm_stock', '0');
            $edit_row['alarm_stock'] = $alarm_stock;
            $flag =$User_Stock_Model->editUserStock($goods_stock['stock_id'], $edit_row);

            if ($flag)
            {
                $status = 200;
                $msg    = __('success');
            }
            else
            {
                $msg    = __('failure');
                $status = 250;
            }

            $this->data->addBody(-140, array(), $msg, $status);
        }
    }

    public function stock_check()
    {
        $typ = request_string('typ');
        if($typ == 'e'){
            include $this->view->getView();
        }else{
            $real_stock_list = request_string('real_stock_list');
            $real_stock_list = decode_json($real_stock_list);

            $User_Stock_Model = new User_StockModel();
            $Stock_CheckModel = new Stock_CheckModel();
            $Stock_CheckGoodsModel = new Stock_CheckGoodsModel();

            $cond_row['user_id'] = Perm::$userId;
            $goods_stock_list = $User_Stock_Model->getByWhere($cond_row);

            $User_Stock_Model->sql->startTransactionDb();
            $rs_row = array();
            //添加盘点记录表
            $add_row = array();
            $add_row['user_id'] = Perm::$userId;
            $add_row['user_name'] = '';
            $add_row['check_date'] = date('Y-m-d');
            $add_row['check_date_time'] = get_date_time();
            $check_id = $Stock_CheckModel->addCheck($add_row, true);
            check_rs($check_id, $rs_row);

            try {
                foreach ($goods_stock_list as $stock_id => $goods_stock) {
                    $real_num = isset($real_stock_list[$stock_id]) ? $real_stock_list[$stock_id] : $goods_stock['goods_stock'];
                    //添加盘点明细表
                    $add_goods_row = array();
                    $add_goods_row['check_id'] = $check_id;
                    $add_goods_row['user_id'] = Perm::$userId;
                    $add_goods_row['goods_id'] = $goods_stock['goods_id'];
                    $add_goods_row['common_id'] = $goods_stock['common_id'];
                    $add_goods_row['goods_name'] = $goods_stock['goods_name'];
                    $add_goods_row['goods_stock'] = $goods_stock['goods_stock'];
                    $add_goods_row['real_goods_stock'] = $real_num;
                    //根据实际数据与库存数量相比，得出盘盈/盘亏/账实相符
                    if($real_num > $goods_stock['goods_stock']){
                        $add_goods_row['check_status'] = Stock_CheckModel::STOCK_SURPLUS;
                    }else if($real_num < $goods_stock['goods_stock']){
                        $add_goods_row['check_status'] = Stock_CheckModel::STOCK_LOSSES;
                    }else{
                        $add_goods_row['check_status'] = Stock_CheckModel::STOCK_NORMAL;
                    }
                    $add_goods_row['check_date_time'] = get_date_time();
                    $add_flag = $Stock_CheckGoodsModel->addCheckGoods($add_goods_row);
                    check_rs($add_flag, $rs_row);

                    //修改商品库存
                    if($real_num != $goods_stock['goods_stock']) {
                        $edit_row = array();
                        $edit_row['goods_stock'] = $real_num;
                        $edit_flag = $User_Stock_Model->editUserStock($stock_id, $edit_row);
                        check_rs($edit_flag, $rs_row);
                    }
                }
            }catch (Exception $e){
                $User_Stock_Model->sql->rollBackDb();
                $msg =  __('failure');
                $status = 250;
            }

            if(is_ok($rs_row) && $User_Stock_Model->sql->commitDb()){
                $msg =  __('success');
                $status = 200;

                $redirect = "index.php?ctl=Seller_Stock_Order&met=user_stock&typ=e";
                location_to(urldecode($redirect));
            }else{
                $User_Stock_Model->sql->rollBackDb();
                $m = $User_Stock_Model->msg->getMessages();
                $msg = $m ? $m[0] : __('failure');
                $status = 250;
            }
        }
    }

    public function stock_goods()
    {
        $page    = request_int('page', 0);
        $rows    = request_int('rows', 20);
        $goods_key = request_string('goods_key', '');

        $User_Stock_Model = new User_StockModel();
        $cond_row['user_id'] = Perm::$userId;
        if (!empty($goods_key)) {
            $cond_row['goods_name:like'] = '%' . $goods_key . '%';
        }
        $order_row['CONVERT(goods_name USING gbk)'] = 'asc';
        $goods = $User_Stock_Model->getUserStockList($cond_row, $order_row, $page, $rows);

        $this->data->addBody(-140, $goods);
    }

    public function stock_self_use()
    {
        $typ = request_string('typ');
        if($typ == 'e'){
            include $this->view->getView();
        }else{
            $out_num_list = request_string('out_num_list');
            $out_num_list = decode_json($out_num_list);

            $User_Stock_Model = new User_StockModel();
            $User_StockOutModel = new User_StockOutModel();

            $cond_row = array();
            $cond_row['user_id'] = Perm::$userId;
            $goods_stock_list = $User_Stock_Model->getByWhere($cond_row);

            $user_id = Perm::$userId;
            $prefix = sprintf('%s-%s-%s', Yf_Registry::get('shop_app_id'), date('Ymd'), $user_id);
            $Number_SeqModel = new Number_SeqModel();
            $order_number = $Number_SeqModel->createSeq($prefix);
            $order_id = sprintf('%s-%s', 'BH', $order_number);

            $User_StockOutModel->sql->startTransactionDb();
            $rs_row = array();
            foreach ($out_num_list as $stock_id=>$out_num)
            {
                $goods_stock = $goods_stock_list[$stock_id];
                if($out_num > 0 && $out_num <= $goods_stock['goods_stock']) {
                    $add_row = array();
                    $add_row['out_order_id'] = $order_id;
                    $add_row['user_id'] = $user_id;
                    $add_row['user_name'] = $user_id;
                    $add_row['goods_id'] = $goods_stock['goods_id'];
                    $add_row['common_id'] = $goods_stock['common_id'];
                    $add_row['goods_name'] = $goods_stock['goods_name'];
                    $add_row['out_num'] = $out_num;
                    $add_row['out_type'] = User_StockOutModel::OUT_SELF;
                    $add_row['out_time'] = get_date_time();

                    $add_flag = $User_StockOutModel->addStockOut($add_row);
                    check_rs($add_flag, $rs_row);

                    //修改商品库存
                    if($out_num) {
                        $edit_row = array();
                        $edit_row['goods_stock'] = $out_num * -1;
                        $edit_flag = $User_Stock_Model->editUserStock($stock_id, $edit_row, true);
                        check_rs($edit_flag, $rs_row);
                    }
                }

            }

            if(is_ok($rs_row) && $User_StockOutModel->sql->commitDb()){
                $msg =  __('success');
                $status = 200;
            }else{
                $User_StockOutModel->sql->rollBackDb();
                $m = $User_StockOutModel->msg->getMessages();
                $msg = $m ? $m[0] : __('failure');
                $status = 250;
            }

            $this->data->addBody(-140, array(), $msg, $status);
        }
    }
}