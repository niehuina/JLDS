<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}


class Stock_OrderModel extends Stock_Order
{
    /**
     * 读取订单列表
     *
     * @param array $cond_row 查询条件
     * @param array $order_row 排序信息
     * @return array $rows 返回的查询内容
     * @access public
     */
    public function getOrderList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
    {
        $data = $this->listByWhere($cond_row, $order_row, $page, $rows);

        $url = Yf_Registry::get('url');
        $Order_StateModel = new Order_StateModel();
        foreach ($data['items'] as $key => $val) {
            $order_id = $val['stock_order_id'];

            //订单详情URL
            $data['items'][$key]['info_url'] = $url . '?ctl=Seller_Stock_Order&met=physicalInfo&o&typ=e&order_id=' . $order_id;
            //发货单URL
            $data['items'][$key]['delivery_url'] = $url . '?ctl=Seller_Stock_Order&met=getOrderPrint&typ=e&order_id=' . $order_id;
            //设置发货URL
            $data['items'][$key]['send_url'] = $url . '?ctl=Seller_Stock_Order&met=send&typ=e&order_id=' . $order_id;

            if ($val['order_status'] == Order_StateModel::ORDER_PAYED) {
                $set_html = "<a href=\"javascript:void(0)\" data-order_id=$order_id dialog_id=\"seller_order_cancel_order\" class=\"ncbtn ncbtn-grapefruit mt5 bbc_seller_btns\"><i class=\"icon-remove-circle\"></i>取消订单</a>";

                $send_url = $data['items'][$key]['send_url'];
                $set_html .= "<a class=\"ncbtn ncbtn-mint mt10 bbc_seller_btns\" target='_blank' ' href=\"$send_url\"><i class=\"icon-truck\"></i>设置发货</a>";

                $data['items'][$key]['set_html'] = $set_html;
            } elseif ($val['order_shipping_status'] == Order_StateModel::ORDER_SHIPPING_PART) {
                $send_url = $data['items'][$key]['send_url'];
                $set_html = "<a class=\"ncbtn ncbtn-mint mt10 bbc_seller_btns\" target='_blank' ' href=\"$send_url\"><i class=\"icon-truck\"></i>设置发货</a>";

                $data['items'][$key]['set_html'] = $set_html;
            } else {
                $data['items'][$key]['set_html'] = null;
                if($val['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS){
                    $Stock_OrderShippingModel = new Stock_OrderShippingModel();
                    $order_shipping = $Stock_OrderShippingModel->getByWhere(['stock_order_id' => $order_id]);
                    $data['items'][$key]['order_shipping'] = array_values($order_shipping);
                }
            }

            $data['items'][$key]['order_status_html'] = $Order_StateModel->orderState[$val['order_status']];
        }

        return $data;
    }

    public function getOrderUserList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
    {
        $cond_row['shop_user_id'] = Perm::$userId;
        $data = $this->listByWhere($cond_row, $order_row, $page, $rows);

        $url = Yf_Registry::get('url');
        $Order_StateModel = new Order_StateModel();
        foreach ($data['items'] as $key => $val) {
            $order_id = $val['stock_order_id'];

            //订单详情URL
            $data['items'][$key]['info_url'] = $url . '?ctl=Seller_Stock_Order&met=physicalInfo&o&typ=e&order_id=' . $order_id;
            //发货单URL
            $data['items'][$key]['delivery_url'] = $url . '?ctl=Seller_Stock_Order&met=getOrderPrint&typ=e&order_id=' . $order_id;
            //设置收货URL
            $data['items'][$key]['send_url'] = $url . '?ctl=Seller_Stock_Order&met=send&typ=e&order_id=' . $order_id;

            if ($val['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS && $val['order_shipping_status'] == Order_StateModel::ORDER_SHIPPING_ALL) {
                $set_html = "<a class=\"ncbtn ncbtn-mint mt10 bbc_seller_btns\" onclick=\"confirmOrder('{$order_id}')\"><i class=\"icon-truck\"></i>确认收货</a>";
                $data['items'][$key]['set_html'] = $set_html;
            } else {
                $data['items'][$key]['set_html'] = null;
                if($val['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS){
                    $Stock_OrderShippingModel = new Stock_OrderShippingModel();
                    $order_shipping = $Stock_OrderShippingModel->getByWhere(['stock_order_id' => $order_id]);
                    $data['items'][$key]['order_shipping'] = array_values($order_shipping);
                }
            }

            $data['items'][$key]['order_status_html'] = $Order_StateModel->orderState[$val['order_status']];
        }

        return $data;
    }


    public function getOrderInfo($cond_row = array())
    {
        $Order_StateModel = new Order_StateModel();
        $order_info = $this->getOneByWhere($cond_row);
        $order_info['receiver_info'] = $order_info['order_receiver_name'] . '&nbsp;' .  $order_info['order_receiver_address']. '&nbsp;' .$order_info['order_receiver_phone'];
        $order_info['order_stauts_const'] = $Order_StateModel->orderState[$order_info['order_status']];;

        $Stock_OrderGoodsModel = new Stock_OrderGoodsModel();
        $goods_row['stock_order_id'] = $order_info['stock_order_id'];
        $order_row['CONVERT(goods_name USING gbk)'] = 'asc';
        $goods_list = $Stock_OrderGoodsModel->getByWhere($goods_row, $order_row);

        $order_info['goods_list'] = $goods_list;

        //发货人信息
        if (empty($order_info['order_seller_name'])) {
            $order_info['shipper'] = 0;
            $order_info['shipper_info'] = '还未设置发货地址，请进入发货设置 &gt 地址库中添加';
        } else {
            $order_info['shipper'] = 1;
            $order_info['shipper_info'] = $order_info['order_seller_name'] . "&nbsp" . $order_info['order_seller_address'] . "&nbsp" . $order_info['order_seller_contact'];
        }

        //运费信息
        if ($order_info['order_shipping_fee'] == 0) {
            $order_info['shipping_info'] = "(免运费)";
        } else {
            $shipping_fee = @format_money($order_info['order_shipping_fee']);
            $order_info['shipping_info'] = "(含运费$shipping_fee)";
        }

        return $order_info;
    }

    /*
     *  ly
     *
     * 获取实物交易订单信息
     * @param $condition 筛选条件
     * @return array $data 订单信息
     * */
    public function getPhysicalInfoData($condi = array())
    {
        $data = $this->getOrderInfo($condi);
        $data['goods_count'] = count($data['goods_list']);
        $data['goods_list'] = array_values($data['goods_list']);

        $data['payment_name'] = '备货金';
        switch ($data['order_status']) {
            case Order_StateModel::ORDER_PAYED:
                $payment_name = $data['payment_name'];
                if (empty($payment_name)) {
                    $payment_name = 'XXX';
                }

                $data['order_status_text'] = '已经付款';
                $data['order_status_html'] = "<li>1. 该订单已用买家的“" . $payment_name . "”成功支付，支付单号 “" .$data['payment_number']. "”。</li><li>2. 订单已提交，等待商家进行备货发货。</li>";

                //页面的订单状态
                $data['order_payed'] = "current";
                $data['order_wait_confirm_goods'] = "";
                $data['order_received'] = "";
                $data['order_evaluate'] = "";
                break;

            case Order_StateModel::ORDER_WAIT_PREPARE_GOODS :
                $payment_name = $data['payment_name'];
                if (empty($payment_name)) {
                    $payment_name = 'XXX';
                }
                $data['order_status_text'] = '等待发货';
                $data['order_status_html'] = "<li>1. 该订单已用买家的“" . $payment_name . "”成功支付，支付单号 “" .$data['payment_number']. "”。</li><li>2. 订单已提交，等待商家进行备货发货。</li>";

                //页面的订单状态
                $data['order_payed'] = "current";
                $data['order_wait_confirm_goods'] = "";
                $data['order_received'] = "";
                $data['order_evaluate'] = "";
                break;


            case Order_StateModel::ORDER_WAIT_CONFIRM_GOODS :
                $data['order_status_text'] = '已经发货';
                $order_shipping_time = $data['order_receiver_date'];
                if (empty($data['order_receiver_date'])) {
                    $order_shipping_time = strtotime($data['order_shipping_time']);

                    $confirm_order_time = Yf_Registry::get('confirm_order_time');
                    $order_shipping_time = date('Y-m-d', $order_shipping_time + $confirm_order_time);
                    $data['order_receiver_date'] = date('Y-m-d H:i:s', $order_shipping_time);
                }

                if(!empty($data['order_shipping_express_id']) && !empty($data['order_shipping_code']))
                {
                    //查找物流公司
                    $expressModel = new ExpressModel();
                    $express_base = $expressModel->getExpress($data['order_shipping_express_id']);
                    $express_base = pos($express_base);
                    $express_name = $express_base['express_name'];
                    $order_shipping_code = $data['order_shipping_code'];

                    $data['order_status_html'] = "<li>1. 商品已发出；$express_name : $order_shipping_code 。</li><li>2. 如果买家没有及时进行收货，系统将于<time>$order_shipping_time</time>自动完成“确认收货”，完成交易。</li>";

                }
                else {
                    $Stock_OrderShippingModel = new Stock_OrderShippingModel();
                    $order_shipping = $Stock_OrderShippingModel->getByWhere(['stock_order_id' => $condi['stock_order_id']], ['shipping_time'=>'asc']);
                    $data['order_shipping'] = array_values($order_shipping);
                    if (count($order_shipping) > 0) {
                        if($data['order_shipping_status'] == Order_StateModel::ORDER_SHIPPING_PART){
                            $data['order_status_text'] = '部分发货';
                            $data['order_stauts_const'] = '部分发货';
                            $order_shipping_html1 = '<li>1. 商品已发出部分；';
                            $order_shipping_html2 = "</li><li>2. 因您购买的商品未全部发货，请耐心等待。</li>";
                        }else {
                            $order_shipping_html1 = '<li>1. 商品已发出；';
                            $order_shipping_html2 = "</li><li>2. 系统将于<time>$order_shipping_time</time>自动完成“确认收货”，完成交易。</li>";
                        }
                        $expressModel = new ExpressModel();
                        foreach ($order_shipping as $key => $shipping) {
                            //查找物流公司
                            $express_base = $expressModel->getExpress($shipping['shipping_express_id']);
                            $express_base = pos($express_base);
                            $express_name = $express_base['express_name'];
                            $order_shipping_code = $shipping['shipping_code'];
                            $order_shipping_html1 .= "$express_name : $order_shipping_code;  ";
                        }
                        $order_shipping_html = $order_shipping_html1 . $order_shipping_html2;
                        $data['order_status_html'] = $order_shipping_html;
                    } else {
                        $data['order_status_html'] = "<li>1. 商品已发出；无需物流。</li><li>2. 系统将于<time>$order_shipping_time</time>自动完成“确认收货”，完成交易。</li>";
                    }
                }

                //页面的订单状态
                $data['order_payed'] = "current";
                $data['order_wait_confirm_goods'] = "current";
                $data['order_received'] = "";
                $data['order_evaluate'] = "";
                break;

            case Order_StateModel::ORDER_RECEIVED:
            case Order_StateModel::ORDER_FINISH:
                $data['order_status_text'] = '已经收货';
                $data['order_status_html'] = '<li>1. 交易已完成，买家可以对购买的商品及服务进行评价。</li><li>2. 评价后的情况会在商品详细页面中显示，以供其它会员在购买时参考。</li>';

                //页面的订单状态
                $data['order_payed'] = "current";
                $data['order_wait_confirm_goods'] = "current";
                $data['order_received'] = "current";

                break;

            case Order_StateModel::ORDER_CANCEL:
                $data['order_status_text'] = '交易关闭';
                $order_cancel_date = $data['order_cancel_date'];
                $order_cancel_reason = $data['order_cancel_reason'];

                //判断关闭者身份 1=>买家 2=>卖家 3=>系统
                if ($data['order_cancel_identity'] == Order_BaseModel::CANCEL_USER_BUYER) {
                    $identity = '买家';
                } else if ($data['order_cancel_identity'] == Order_BaseModel::CANCEL_USER_SELLER) {
                    $identity = '商家';
                } else if ($data['order_cancel_identity'] == Order_BaseModel::CANCEL_USER_SYSTEM) {
                    $identity = '系统';
                }

                $data['order_status_html'] = "<li> $identity 于 $order_cancel_date 取消了订单 ( $order_cancel_reason ) </li>";
                break;
        }

        //取出物流公司名称
//        if (!empty($data['order_shipping_express_id'])) {
//            $expressModel = new ExpressModel();
//            $express_base = $expressModel->getExpress($data['order_shipping_express_id']);
//            $express_base = pos($express_base);
//            $data['express_name'] = $express_base['express_name'];
//        } else {
//            $data['express_name'] = '';
//        }

        //店主名称
        $shopBaseModel = new Shop_BaseModel();
        $shop_base = $shopBaseModel->getOne($data['shop_id']);
        $data['shop_user_name'] = $shop_base['user_name'];
        $data['shop_tel'] = $shop_base['shop_tel'];

        return $data;
    }
}