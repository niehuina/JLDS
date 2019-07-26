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

        $is_partner = self::$is_partner;
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
        if(self::$self_shop_id == Perm::$shopId) {
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

        if(self::$self_shop_id == Perm::$shopId) {
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
        if(self::$self_shop_id == Perm::$shopId) {
            $data = $Stock_OrderModel->getOrderList($condition, $order, $page, $rows);
        }else{
            $data = $Stock_OrderModel->getOrderUserList($condition, $order, $page, $rows);
        }

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
        $skip_off = request_int('skip_off');            //是否显示已取消订单

        if (!empty($query_start_date)) {
            $condition['order_create_time:>='] = $query_start_date;
        }

        if (!empty($query_end_date)) {
            $condition['order_create_time:<='] = date('Y-m-d 23:59:59',strtotime($query_end_date));
        }

        if (!empty($query_buyer_name)) {
            $condition['shop_user_name:LIKE'] = "%$query_buyer_name%";
        }

        if (!empty($query_order_sn)) {
            $condition['stock_order_id'] = $query_order_sn;
        }

        if ($skip_off) {
            $condition['order_status:<>'] = Order_StateModel::ORDER_CANCEL;
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
            $g_partner_info = $User_InfoModel->getOne($user_id);

            $Shop_ShippingAddressModel = new Shop_ShippingAddressModel();
            $user_address = $Shop_ShippingAddressModel->getOneByWhere(array('shop_id' => $shop_id, 'shipping_address_default'=>1));
            if(!$user_address)
            {
                $user_address = $Shop_ShippingAddressModel->getByWhere(array('shop_id' => $shop_id));
                $user_address = current($user_address);
            }

            //收货人信息
//            if ($user_address) {
//                $user_address['shipper'] = 1;
//                $user_address['shipper_info'] = $user_address['shipping_address_contact'] . "&nbsp" . $user_address['shipping_address_area'] . "&nbsp" . $user_address['shipping_address_address'] . "&nbsp" . $user_address['shipping_address_phone'];
//            }

            $district_parent_id = request_int('pid', 0);
            $baseDistrictModel = new Base_DistrictModel();
            $district = $baseDistrictModel->getDistrictTree($district_parent_id);

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

    public function getTransport()
    {
        $city_id  = request_int('city_id');
        $order_price  = request_string('order_price');
        $select_goods_list = request_string('select_goods_list');
        $select_goods_list = decode_json($select_goods_list);

        $goods_ids = array_keys($select_goods_list);
        $cond_row = array();
        $cond_row['shop_id'] = self::$self_shop_id;;
        $cond_row['goods_id:in'] = $goods_ids;
        $order['CONVERT(goods_name USING gbk)'] = 'asc';
        $Goods_BaseModel = new Goods_BaseModel();
        $Goods_CommonModel = new Goods_CommonModel();
        $goods_list = $Goods_BaseModel->getByWhere($cond_row, $order);
        $order = array('weight'=>0,'count'=>0,'shop_id'=>self::$self_shop_id,'price'=>$order_price);
        foreach ($select_goods_list as $key => $goods_num) {
            $goods_base = $goods_list[$key];
            $goods_common = $Goods_CommonModel->getOne($goods_base['common_id']);

            $order['weight'] += $goods_common['common_cubage'] * $goods_num;
            $order['count'] += $goods_num;
        }

        $Transport_TemplateModel = new Transport_TemplateModel();
        $transport_cost= $Transport_TemplateModel->shopTransportCost($city_id, $order);

        $this->data->addBody(-140, $transport_cost);
    }

    public function addOrder()
    {
        $action = request_string('action');
        $order_id = request_string('order_id');
        $user_id = request_int('user_id');
        $shop_id = request_int('shop_id');
        $select_goods_list = request_string('select_goods_list');
        $select_goods_list = decode_json($select_goods_list);
        $user_address_name = request_string('order_address_name');
        $user_address_area = request_string('order_address_area');
        $user_address_address = request_string('order_address_address');
        $user_address_phone = request_string('order_address_phone');
//        $order_total_amount  = request_string('total_amount');
        $shipping_fee  = request_string('shipping_fee');

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
            $order_row['shop_user_id'] = $user_id;
            $order_row['shop_user_name'] = $user_info['user_name'];
            $order_row['order_receiver_name'] = $user_address_name;
            $order_row['order_receiver_address'] = $user_address_area . ' ' . $user_address_address;
            $order_row['order_receiver_phone'] = $user_address_phone;

            $User_AddressModel = new User_AddressModel();
            $user_address['user_id'] = $user_id;
            $user_address['user_address_contact'] = $user_address_phone;

            //备货订单物流信息
            $order_row['order_shipping_fee'] = $shipping_fee;
//            $order_row['order_shipping_time'] = $order_address_phone;
//            $order_row['order_shipping_method'] = $order_address_phone;
//            $order_row['order_shipping_express_id'] = $order_address_phone;
//            $order_row['order_shipping_code'] = $order_address_phone;
//            $order_row['order_shipping_message'] = $order_address_phone;

            //计算订单总金额
            $order_total_amount = 0;
            $order_total_amount_vip = 0;
            $order_total_amount_partner = 0;
            $self_shop_id = self::$self_shop_id;

            $goods_ids = array_keys($select_goods_list);
            $cond_row = array();
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
            }

            $order_row['order_payment_amount'] = $order_total_amount + $shipping_fee;
            $order_row['order_payment_amount_vip'] = $order_total_amount_vip + $shipping_fee;
            $order_row['order_payment_amount_partner'] = $order_total_amount_partner + $shipping_fee;
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
            $formvars['seller_id'] = Perm::$userId;
            $formvars['seller_name'] = Perm::$row['user_name'];
            $formvars['order_state_id'] = $order_row['order_status'];
            $formvars['order_payment_amount'] = $order_row['order_payment_amount_vip'];
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
                //location_to(Yf_Registry::get('url').'?ctl=Seller_Stock_Order&met=physical');
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

        $cond_row['shop_id'] = self::$self_shop_id;
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

    public function chooseBuyerAddress()
    {
        $typ = request_string('typ');
        $user_id = request_int('user_id');
        $shop_id = request_int('shop_id');

        if ($typ == 'e')
        {
            $Shop_ShippingAddressModel = new Shop_ShippingAddressModel();
            $address_list = $Shop_ShippingAddressModel->getByWhere(array('shop_id' => $shop_id));
            $address_list              = array_values($address_list);
            foreach ($address_list as $key => $val)
            {
                $address_list[$key]['address_info']  = $val['shipping_address_area'] . " " . $val['shipping_address_address'];
                $address_list[$key]['address_value'] = $val['shipping_address_contact'] . "&nbsp" . $val['shipping_address_phone'] . "&nbsp" . $val['shipping_address_area'] . "&nbsp" . $val['shipping_address_address'];
            }

            include $this->view->getView();
        }
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

            if ($flag !== false)
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
            $Stock_OrderGoodsModel = new Stock_OrderGoodsModel();
            $Stock_OrderShippingModel = new Stock_OrderShippingModel();

            //判断该笔订单是否是自己的单子
            $order_base = $Stock_OrderModel->getOne($order_id);

            $rs_row = array();

            //开启事物
            $Stock_OrderModel->sql->startTransactionDb();

            if ($order_base['order_status'] < Order_StateModel::ORDER_RECEIVED) {
                //查询订单的总商品数量，及已发货的商品数量
                $goods_cond['stock_order_id'] = $order_id;
                $order_goods_count = $Stock_OrderGoodsModel->get_count($goods_cond);
                $goods_cond['goods_shipping_status'] = 1;
                $already_shipping_goods_count = $Stock_OrderGoodsModel->get_count($goods_cond);

                //本次发货的商品数量
                $order_send_goods = request_string('order_send_goods');
                $order_send_goods = decode_json($order_send_goods);
                $current_ship_goods_count = count($order_send_goods);

                $current_time = time();
                //判断订单的总商品数量 是否等于 已发货的商品数量+本次发货的商品数量
                if($order_goods_count == ($already_shipping_goods_count + $current_ship_goods_count)) {
                    //设置发货
                    $update_data['order_shipping_status'] = Order_StateModel::ORDER_SHIPPING_ALL;
//                $update_data['order_shipping_express_id'] = request_int('order_shipping_express_id');
//                $update_data['order_shipping_code'] = request_string('order_shipping_code');
//                $update_data['order_shipping_message'] = request_string('order_shipping_message');
//                $update_data['order_seller_message']      = request_string('order_seller_message');
//                $update_data['order_shipping_method']     = $order_address_phone;
                    //配送时间 默认收货时间
                    $confirm_order_time = Yf_Registry::get('confirm_order_time');
//                    $update_data['order_shipping_time'] = date('Y-m-d H:i:s', $current_time);
                    $update_data['order_receiver_date'] = date('Y-m-d H:i:s', $current_time + $confirm_order_time);
                }else{
                    $update_data['order_shipping_status'] = Order_StateModel::ORDER_SHIPPING_PART;
                }
                $update_data['order_status'] = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
                $update_data['order_shipping_time'] = date('Y-m-d H:i:s', $current_time);
                $update_flag = $Stock_OrderModel->editOrder($order_id, $update_data);
                check_rs($update_flag, $rs_row);

                //更新订单商品的发货状态
                $order_goods_id = array_values($order_send_goods);
                $goods_edit['goods_shipping_status'] = 1;
                $goods_edit['goods_shipping_code'] = request_string('order_shipping_code');
                $edit_flag = $Stock_OrderGoodsModel->editOrderGoods($order_goods_id,$goods_edit);
                check_rs($edit_flag, $rs_row);

                //添加订单物流记录
                $add_shipping['stock_order_id'] = $order_id;
                $add_shipping['shipping_express_id'] = request_int('order_shipping_express_id');
                $add_shipping['shipping_code'] = request_string('order_shipping_code');
                $add_shipping['shipping_message'] = request_string('order_shipping_message');
                $add_shipping['seller_message']      = request_string('order_seller_message');
                $add_shipping['shipping_stock_goods_id'] = implode(',', $order_goods_id);
                $add_shipping['shipping_time'] = date('Y-m-d H:i:s', $current_time);
                $add_flag = $Stock_OrderShippingModel->addOrderShipping($add_shipping);
                check_rs($add_flag, $rs_row);

                //减少商品库存
                $Goods_BaseModel = new Goods_BaseModel();
                foreach ($order_goods_id as $key=>$order_goods_id){
                    $order_goods_data = $Stock_OrderGoodsModel->getOne($order_goods_id);
                    $flags = $Goods_BaseModel->reduceGoodsStock($order_goods_data['goods_id'], $order_goods_data['goods_num']);
                    check_rs($flags, $rs_row);
                }

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
                $message->sendMessage('ordor_complete_shipping', $order_base['shop_user_id'], $order_base['shop_user_name'], $order_id, '', 0, MessageModel::ORDER_MESSAGE);

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
     * 选择物流商品
     * @throws Exception
     */
    public function stock_orderGoods()
    {
        $stock_id = request_string('order_id');
        $typ = request_string('typ');

        if ($typ == 'e')
        {
            include $this->view->getView();
        }else{
            if($_SERVER['REQUEST_METHOD']=="POST"){
                $this->data->addBody(-140, array(), '', 250);
            }else {
                $Yf_Page = new Yf_Page();
                $row = $Yf_Page->listRows;
                $offset = request_int('firstRow', 0);
                $page = ceil_r($offset / $row);

                $goods_key = request_string('goods_key');
                if (!empty($goods_key)) {
                    $cond_row['goods_name:like'] = '%' . $goods_key . '%';
                }
                $cond_row['stock_order_id'] = $stock_id;
                $cond_row['goods_shipping_status'] = 0;
                $order_row['order_goods_time'] = 'desc';
                $Stock_OrderGoodsModel = new Stock_OrderGoodsModel();
                $data = $Stock_OrderGoodsModel->listByWhere($cond_row, $order_row, $page, $row);

                $Yf_Page->totalRows = $data['totalsize'];
                $page_nav = $Yf_Page->prompt();

                $this->data->addBody(-140, $data);
            }
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

            if ($flag !== false)
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

        $order_id = request_string('order_id');
        //获取订单详情，判断订单的当前状态与下单这是否为当前用户
        $Stock_OrderModel = new Stock_OrderModel();
        $order_base = $Stock_OrderModel->getOne($order_id);

        if(Perm::$userId == $order_base['seller_user_id']){
            $cancel_member = Order_CancelReasonModel::CANCEL_BUYER;
        }else{
            $cancel_member = Order_CancelReasonModel::CANCEL_SELLER;
        }

        if ($typ == 'e') {
            //获取取消原因
            $Order_CancelReasonModel = new Order_CancelReasonModel;
            $reason = array_values($Order_CancelReasonModel->getByWhere($cancel_member));

            include $this->view->getView();
        } else {
            //开启事物
            $Stock_OrderModel->sql->startTransactionDb();

            $state_info = request_string('state_info');

            if ($order_base['order_status'] == Order_StateModel::ORDER_PAYED) {
                if (empty($state_info)) {
                    $state_info = request_string('state_info1');
                }
                //加入取消时间
                $condition['order_status'] = Order_StateModel::ORDER_CANCEL;
                $condition['order_cancel_reason'] = addslashes($state_info);
                $condition['order_cancel_identity'] = $cancel_member;
                $condition['order_cancel_date'] = get_date_time();

                $edit_flag = $Stock_OrderModel->editOrder($order_id, $condition);
                check_rs($edit_flag, $rs_row);

                //修改订单商品表中的订单状态
                $edit_row['order_goods_status'] = Order_StateModel::ORDER_CANCEL;
                $Stock_OrderGoodsModel = new Stock_OrderGoodsModel();
                $order_goods_id = $Stock_OrderGoodsModel->getKeyByWhere(array('stock_order_id' => $order_id));
                if($order_goods_id) {
                    $edit_flag1 = $Stock_OrderGoodsModel->editOrderGoods($order_goods_id, $edit_row);
                    check_rs($edit_flag1, $rs_row);
                }

//                //退还订单商品的库存
//                $Goods_BaseModel = new Goods_BaseModel();
//                $edit_flag2 = $Goods_BaseModel->addGoodsStock($order_goods_id);
//                check_rs($edit_flag2, $rs_row);
            }

            $flag = is_ok($rs_row);

            if($flag){
                //将需要取消的订单号远程发送给Paycenter修改订单状态
                //远程修改paycenter中的订单状态
                $formvars = array();
                $formvars['order_id'] = $order_id;
                $formvars['buy_id'] = $order_base['shop_user_id'];
                $formvars['payment_amount'] = $order_base['order_payment_amount_vip'];
                $rs = $this->getPayCenterUrl('Api_Pay_Pay', 'cancelOrderForStock', $formvars);
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

    public function confirmShipping()
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
            $Stock_OrderShippingModel = new Stock_OrderShippingModel();

            $rs_row = array();
            //开启事物
            $Order_BaseModel->sql->startTransactionDb();

            $order_id = request_string('order_id');
            $shipping_code = request_string('shipping_code');

            $order_base           = $Order_BaseModel->getOne($order_id);
            //判断下单者是否是当前用户
            if($order_base['shop_user_id'] == Perm::$userId && $order_base['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS)
            {
                //将当前物流确认收货
                $shipping = $Stock_OrderShippingModel->getOneByWhere(['stock_order_id'=>$order_id, 'shipping_code'=>$shipping_code]);
                $edit_shipping_row['shipping_status'] = Order_StateModel::SHIPPING_CONFIRM_YES;
                $edit_shipping_flag = $Stock_OrderShippingModel->editOrderShipping($shipping_code, $edit_shipping_row);
                check_rs($edit_shipping_flag,$rs_row);

                //修改该物流中的订单商品表中的订单状态
                $order_goods_id = explode(',',$shipping['shipping_stock_goods_id']);
                $edit_row['order_goods_status'] = Order_StateModel::ORDER_FINISH;
                $edit_flag1 = $Order_GoodsModel->editOrderGoods($order_goods_id, $edit_row);
                check_rs($edit_flag1,$rs_row);

                $User_Stock_Model = new User_StockModel();
                $user_stock_list = $User_Stock_Model->getByWhere(['user_id'=>$order_base['shop_user_id']]);
                $goods_id_array = array_reduce($user_stock_list, function($carry,$item){
                    $carry[$item['goods_id']] = $item['stock_id'];
                    return $carry;
                });

                $Stock_OrderGoodsModel = new Stock_OrderGoodsModel();
                $orderGoods_list = $Stock_OrderGoodsModel->getByWhere(array('stock_order_id' => $order_id, 'stock_order_goods_id:in'=>$order_goods_id));
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

                //如果该订单全部发货
                if($order_base['order_shipping_status'] == Order_StateModel::ORDER_SHIPPING_ALL) {
                    $shipping_list = $Stock_OrderShippingModel->getByWhere(['stock_order_id'=>$order_id]);
                    $shipping_list_status = array_column($shipping_list, 'shipping_status');
                    $flag = true;
                    foreach ($shipping_list_status as $status){
                        if ($status == 1)
                        {
                            continue;
                        }
                        else
                        {
                            $flag = false;
                            break;
                        }
                    }

                    if($flag) {
                        //如果全部收货，修改订单状态，然后修改用户备货金
                        $condition['order_status'] = Order_StateModel::ORDER_FINISH;
                        $condition['order_finished_time'] = get_date_time();
                        $edit_flag = $Order_BaseModel->editOrder($order_id, $condition);
                        check_rs($edit_flag,$rs_row);

                        //将需要确认的订单号远程发送给Paycenter修改订单状态
                        //远程修改paycenter中的订单状态
                        //判断修改用户的备货金
                        $formvars = array();
                        $formvars['order_id'] = $order_id;
                        $formvars['payment'] = 1;
                        $formvars['from_app_id'] = Yf_Registry::get('shop_app_id');
                        $rs = $this->getPayCenterUrl('Api_Pay_Pay', 'confirmOrder', $formvars);
                        if ($rs['status'] == 250) {
                            $rs_flag = false;
                            check_rs($rs_flag, $rs_row);
                        }
                    }
                }
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
                //修改订单状态
                $condition['order_status'] = Order_StateModel::ORDER_FINISH;
                $condition['order_finished_time'] = get_date_time();

//                $order_payment_amount = $order_base['order_payment_amount'];
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

                //修改物流表状态
                $Stock_OrderShippingModel = new Stock_OrderShippingModel();
                $shipping_ids = $Stock_OrderShippingModel->getKeyByWhere(['stock_order_id'=>$order_id, 'shipping_status:!='=>Order_StateModel::SHIPPING_CONFIRM_YES]);
                $edit_shipping_row['shipping_status'] = Order_StateModel::SHIPPING_CONFIRM_YES;
                $edit_shipping_flag = $Stock_OrderShippingModel->editOrderShipping($shipping_ids, $edit_shipping_row);
                check_rs($edit_shipping_flag,$rs_row);

                //修改订单商品表中的订单状态
                $orderGoods_list                 = $Order_GoodsModel->getByWhere(array('stock_order_id' => $order_id, 'order_goods_status:!='=>Order_StateModel::ORDER_FINISH));
                $order_goods_id = array_column($orderGoods_list, 'stock_order_goods_id');
                $edit_row['order_goods_status'] = Order_StateModel::ORDER_FINISH;
                $edit_flag1 = $Order_GoodsModel->editOrderGoods($order_goods_id, $edit_row);
                check_rs($edit_flag1,$rs_row);

                //将收货的商品加入库存中
                $User_Stock_Model = new User_StockModel();
                $user_stock_list = $User_Stock_Model->getByWhere(['user_id'=>$order_base['shop_user_id']]);
                $goods_id_array = array_reduce($user_stock_list, function($carry,$item){
                    $carry[$item['goods_id']] = $item['stock_id'];
                    return $carry;
                });

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
                $formvars['payment'] = 1;
                $formvars['from_app_id'] = Yf_Registry::get('shop_app_id');
                $rs = $this->getPayCenterUrl('Api_Pay_Pay', 'confirmOrder', $formvars);
                if($rs['status'] == 250)
                {
                    $rs_flag = false;
                    check_rs($rs_flag,$rs_row);
                }
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

    /**
     * 删除订单
     *
     * @author     Str
     */
    public function hideOrder()
    {
        $order_id = request_string('order_id');
        $user     = request_string('user');
        $op       = request_string('op');

        $edit_row = array();
        $flag = false;
        $Order_BaseModel = new Stock_OrderModel();
        $order_base = $Order_BaseModel->getOne($order_id);

        //买家删除订单
        if ($user == 'seller')
        {
            //判断订单状态是否是已完成（6）或者已取消（7）状态
            if($order_base['order_status'] >= Order_StateModel::ORDER_FINISH)
            {
                //判断当前用户是否是自营店铺
                if(self::$self_shop_id == Perm::$shopId)
                {
                    if ($op == 'del')
                    {
                        $edit_row['order_shop_hidden'] = Order_BaseModel::IS_SELLER_REMOVE;
                    }
                    else
                    {
                        $edit_row['order_shop_hidden'] = Order_BaseModel::IS_SELLER_HIDDEN;
                    }
                }
            }

            $flag = $Order_BaseModel->editOrder($order_id, $edit_row);
        }

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

    /**
     * 还原回收站中的订单
     *
     * @author     Str
     */
    public function restoreOrder()
    {
        $order_id = request_string('order_id');
        $user     = request_string('user');

        $edit_row = array();
        $flag = false;
        $Order_BaseModel = new Stock_OrderModel();

        if ($user == 'seller')
        {
            $edit_row['order_shop_hidden'] = Order_BaseModel::NO_SELLER_HIDDEN;
            $flag = $Order_BaseModel->editOrder($order_id, $edit_row);
        }

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

    public function stock_check_list()
    {
        return include $this->view->getView();
    }

    public function stock_check_log()
    {
        $Yf_Page = new Yf_Page();
        $row     = $Yf_Page->listRows;
        $offset  = request_int('firstRow', 0);
        $page    = ceil_r($offset / $row);

        $query_start_date = request_string('query_start_date');
        $query_end_date = request_string('query_end_date');
        if (!empty($query_start_date)) {
            $cond_row['check_date_time:>='] = $query_start_date;
        }

        if (!empty($query_end_date)) {
            $cond_row['check_date_time:<='] = date('Y-m-d 23:59:59',strtotime($query_end_date));
        }
        $Stock_CheckModel = new Stock_CheckModel();
        $cond_row['user_id'] = Perm::$userId;
        $order_row['check_date_time'] = 'desc';
        $data = $Stock_CheckModel->listByWhere($cond_row, $order_row, $page, $row);

        $Stock_CheckGoodsModel = new Stock_CheckGoodsModel();
        foreach($data['items'] as $key=>$check){
            $goods_cond_row = array();
            $goods_cond_row['check_id'] = $check['check_id'];
            $goods_count = $Stock_CheckGoodsModel->get_count($goods_cond_row);
            $data['items'][$key]['good_count'] = $goods_count;
        }

        $Yf_Page->totalRows = $data['totalsize'];
        $page_nav = $Yf_Page->prompt();

        $this->data->addBody(-140, $data);
    }

    public function stock_check_detail()
    {
        $check_id = request_int('check_id');
        $Stock_CheckModel = new Stock_CheckModel();
        $Stock_CheckGoodsModel = new Stock_CheckGoodsModel();

        $stock_check = $Stock_CheckModel->getOne($check_id);
        $goods_cond_row['check_id'] = $check_id;
        $goods_count = $Stock_CheckGoodsModel->get_count($goods_cond_row);

        include $this->view->getView();
    }

    public function check_goods()
    {
        $check_id = request_int('check_id');

        $Yf_Page = new Yf_Page();
        $row     = $Yf_Page->listRows;
        $offset  = request_int('firstRow', 0);
        $page    = ceil_r($offset / $row);

        $goods_key = request_string('goods_key');
        if(!empty($goods_key)){
            $cond_row['goods_name:like'] = '%' . $goods_key . '%';
        }
        $cond_row['check_id'] = $check_id;
        $order_row['check_date_time'] = 'desc';
        $Stock_CheckGoodsModel = new Stock_CheckGoodsModel();
        $data = $Stock_CheckGoodsModel->listByWhere($cond_row, $order_row, $page, $row);

        $Yf_Page->totalRows = $data['totalsize'];
        $page_nav = $Yf_Page->prompt();

        $this->data->addBody(-140, $data);
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
            }else{
                $User_Stock_Model->sql->rollBackDb();
                $m = $User_Stock_Model->msg->getMessages();
                $msg = $m ? $m[0] : __('failure');
                $status = 250;
            }

            $this->data->addBody(-140, array(), $msg, $status);
        }
    }

    public function stock_goods()
    {
        $page    = request_int('page', 1);
        $rows    = request_int('rows', 20);
        $goods_key = request_string('goods_key', '');
        Yf_Log::log(1, Yf_Log::LOG, 'debug1');

        $User_Stock_Model = new User_StockModel();
        $cond_row['user_id'] = Perm::$userId;
        if (!empty($goods_key)) {
            $cond_row['goods_name:like'] = '%' . $goods_key . '%';
        }
        $order_row['CONVERT(goods_name USING gbk)'] = 'asc';
        $goods = $User_Stock_Model->getUserStockList($cond_row, $order_row, $page, $rows);
        Yf_Log::log($goods, Yf_Log::LOG, 'debug1');
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

    public function get_order_profit()
    {
        $Stock_OrderModel = new Stock_OrderModel();

        $page    = request_int('curpage', 1);
        $rows    = request_int('page', 20);
        $status    = request_int('status', 0);
        $cond_row['order_is_settlement'] = 0;
        $cond_row['order_status'] = Order_StateModel::ORDER_FINISH;
        $order_row['order_create_time'] = 'desc';
        $order_list = $Stock_OrderModel->listByWhere($cond_row, $order_row, $page, $rows);
        foreach ($order_list['items'] as $key=>$order){
            $order_list['items'][$key]['order_create_text'] = '创建日'.date('m-d H:i', strtotime($order['order_create_time']));
            if($order['order_settlement_time']){
                $order_list['items'][$key]['order_settlement_text'] = '结算日'.date('m-d H:i', strtotime($order['order_settlement_time']));
            }else{
                $order_list['items'][$key]['order_settlement_text'] = '';
            }
            $order_list['items'][$key]['order_commission'] = $order['order_payment_amount_vip'] - $order['order_payment_amount_partner'];
        }

        $formvars = array();
        $formvars['user_id'] = Perm::$userId;
        $formvars['trade_type_id'] = request_int('type', 13);//备货差价返利
        $formvars['user_type'] = 1;
        $formvars['status'] = 2;
        $rs = $this->getPayCenterUrl('Api_Paycen_PayRecord', 'getRecordAmountByUserId',$formvars);
        if($rs['data']){
            $order_list['amount'] = $rs['data']['amount'];
        }

        $this->data->addBody(-140, $order_list);
    }
}