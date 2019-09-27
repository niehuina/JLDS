<?php


class User_SettleProfitModel extends Yf_Model
{
    public function __construct(&$db_id = 'shop', &$user = null)
    {
        $this->_cacheFlag = CHE;
        parent::__construct($db_id, $user);
    }

    /**
     * 订单差价返利
     * @param $user_id
     * @param $user_info
     * @return bool
     * @throws Exception
     */
    public function directOrder($user_id, $user_info)
    {
        $User_InfoModel = new User_InfoModel();
        $Order_BaseModel = new Order_BaseModel();
        $Order_GoodsModel = new Order_GoodsModel();

        $cond_row = array();
        $cond_row['directseller_id'] = $user_id;
        $cond_row['order_status'] = Order_StateModel::ORDER_FINISH;
        $cond_row['order_is_virtual'] = Order_BaseModel::ORDER_IS_REAL;
        $cond_row['order_finished_time:<='] = get_date_time();
        $cond_row['(order_payment_amount-order_refund_amount):>'] = 0;
        $cond_row['directseller_is_settlement'] = Order_BaseModel::IS_NOT_SETTLEMENT; //未结算
        $order_row['order_finished_time'] = 'asc';

        $order_list = $Order_BaseModel->getByWhere($cond_row, $order_row);

        //开启事物
        $rs_row = array();
        foreach ($order_list as $key=>$order) {
            $directseller_member[0] = $order['directseller_id'];     //直属一级

            $cond_goods_row['order_id'] = $order['order_id'];
            $order_goods_list = $Order_GoodsModel->getByWhere($cond_goods_row);

            $order_field['directseller_is_settlement'] = Order_BaseModel::IS_SETTLEMENT;
            $order_flag = $Order_BaseModel->editBase($order['order_id'], $order_field);
            check_rs($order_flag, $rs_row);

            $directseller_commission = array(0);
            foreach ($order_goods_list as $k => $v) {
                if ($v['goods_return_status'] == 0 && ($v['goods_refund_status'] == 0 || $v['goods_refund_status'] == 3)
                    || ($v['goods_return_status'] == 3 && ($v['goods_refund_status'] == 0 || $v['goods_refund_status'] == 3))
                    && $v['directseller_flag']) {
                    $directseller_commission[0] += $v['directseller_commission_0'];  //一级分佣
//                $directseller_commission[1] += $v['directseller_commission_1'];  //二级级分佣
//                $directseller_commission[2] += $v['directseller_commission_2'];  //三级分佣
                }else if(($v['goods_return_status'] == 2 || $v['goods_refund_status'] == 2) && $v['directseller_flag']){
                    $order_goods_num = $v['order_goods_num']*1;
                    $order_goods_returnnum = $v['order_goods_returnnum']*1;
                    $order_goods_real = $order_goods_num - $order_goods_returnnum;
                    $directseller_commission[0] += $v['directseller_commission_0'] / $order_goods_num * $order_goods_real;  //一级分佣
                }

                $goods_field['directseller_is_settlement'] = Order_BaseModel::IS_SETTLEMENT;
                $goods_flag = $Order_GoodsModel->editGoods($v['order_goods_id'], $goods_field);
                check_rs($goods_flag, $rs_row);
            }

            foreach ($directseller_member as $ks => $user_id) {
                if ($directseller_commission[$ks] == 0) continue;
                $edit_row['user_directseller_commission'] = $user_info['user_directseller_commission'] + $directseller_commission[$ks];
                $User_InfoModel->editInfo($user_id, $edit_row);

                if ($user_id) {
                    //将需要确认的订单号远程发送给Paycenter修改订单状态
                    //远程修改paycenter中的订单状态
                    $key = Yf_Registry::get('paycenter_api_key');
                    $url = Yf_Registry::get('paycenter_api_url');
                    $paycenter_app_id = Yf_Registry::get('paycenter_app_id');

                    $formvars = array();
                    $formvars['order_id'] = $order['order_id'];
                    $formvars['user_id'] = $user_id;
                    $formvars['user_money'] = $directseller_commission[$ks];
                    $formvars['reason'] = '订单差价返还';
                    $formvars['trade_type'] = 14;
                    $formvars['app_id'] = $paycenter_app_id;
                    $formvars['type'] = 'row';

                    $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=directsellerOrder&typ=json', $url), $formvars);
                }
            }
        }

        return is_ok($rs_row);
    }

    /**
     * 合伙人订单提成返利
     * @param $user_id
     * @param $user_info
     * @return bool
     * @throws Exception
     */
    public function rebateOrderForPartner($user_id, $user_info)
    {
        $User_GradeModel = new User_GradeModel();
        $User_InfoModel = new User_InfoModel();
        $Order_BaseModel = new Order_BaseModel();
        $user_grade = $User_GradeModel->getOne(3);

        //合伙人只计算其直属下级用户的所有订单
        $cond_row['directseller_p_id'] = $user_id;
        $cond_row['order_status'] = Order_StateModel::ORDER_FINISH; //已完成
        $cond_row['rebate_is_settlement'] = Order_BaseModel::IS_NOT_SETTLEMENT; //未结算
        $cond_row['order_create_time:>='] = $user_info['user_grade_update_date'];
        $order_row['order_finished_time'] = 'asc';
        $user_orders = $Order_BaseModel->getByWhere($cond_row, $order_row);
        Yf_Log::log("partner order:".count($user_orders), Yf_Log::LOG, 'user_settle');

        $rs_row = array();
        if (count($user_orders) > 0) {
            //计算其直属下级用户的未结算的订单总额
            $user_order_pay_amount = array_sum(array_column($user_orders, 'order_payment_amount'));
            $user_order_refund_amount = array_sum(array_column($user_orders, 'order_refund_amount'));
            $user_order_amount = $user_order_pay_amount - $user_order_refund_amount;
            Yf_Log::log("partner user_order_amount:".$user_order_amount, Yf_Log::LOG, 'user_settle');

            //更新订单提成结算状态
            $user_order_ids = array_column($user_orders, 'order_id');
            $order_edit_row['rebate_is_settlement'] = Order_BaseModel::IS_SETTLEMENT;
            $flag = $Order_BaseModel->editBase($user_order_ids, $order_edit_row);
            check_rs($flag, $rs_row);
            if ($user_order_amount == 0) return true;

            //获取合伙人的返利比例
            $grade_order_amount = $user_grade['order_amount'] * 1;
            $grade_order_rebate1 = $user_grade['order_rebate1'] * 1 / 100;
            $grade_order_rebate2 = $user_grade['order_rebate2'] * 1 / 100;

            $total_children_order_total_amount = $user_info['children_order_total_amount'] * 1;
            $before_order_total_amount = $total_children_order_total_amount;
            foreach ($user_orders as $k => $order) {
                $order_amount = $order['order_payment_amount'] * 1 - $order['order_refund_amount'] * 1;
                $total_children_order_total_amount = $before_order_total_amount + $order_amount;

//                //不管超指标还是未超指标，都需要：当前金额*rebate1
//                $rebate_value1 = $order_amount * $grade_order_rebate1;
//
//                $rebate_value2 = 0;
//                //如果上次累计订单总金额已超指标，则超过指标部分提成为:该次金额*rebate2
//                if ($before_order_total_amount * 1 >= $grade_order_amount) {
//                    $rebate_value2 = $order_amount * $grade_order_rebate2;
//                } else if ($total_children_order_total_amount > $grade_order_amount) {
//                    //如果该次累计总金额超过指标，则超过指标部分提成为:（累计总金额-指标部分）*rebate2
//                    $order_amount2 = $total_children_order_total_amount - $grade_order_amount;
//                    if ($order_amount2 > 0) {
//                        $rebate_value2 = $order_amount2 * $grade_order_rebate2;
//                    } else {
//                        $rebate_value2 = 0;
//                    }
//                } else if ($total_children_order_total_amount <= $grade_order_amount) {
//                    //如果该次累计总金额未超过指标，则超过指标部分提成为:0
//                    $rebate_value2 = 0;
//                }
//                $order_rebate_value_temp = $rebate_value1 + $rebate_value2;
                $before_order_total_amount = $before_order_total_amount + $order_amount;

                $order_rebate_value_temp = $order['order_directseller_commission2'];
                if ($order_rebate_value_temp == 0) continue;
                $order_rebate_value_temp = round($order_rebate_value_temp, 2);

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
                $formvars['desc'] = "订单金额{$order_amount},{$current_year}年度累计订单金额{$total_children_order_total_amount}";
                $formvars['app_id'] = $paycenter_app_id;
                $formvars['trade_type'] = 15;
                $formvars['type'] = 'row';

                $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=directsellerOrder&typ=json', $url), $formvars);
            }

            //更新合伙人的当前度累计订单金额
            $edit_row['children_order_total_amount'] = $user_order_amount;
            $flag1 = $User_InfoModel->editInfo($user_id, $edit_row);
            check_rs($flag1, $rs_row);
        }

        return is_ok($rs_row);
    }

    /**
     * 一般高级合伙人订单提成返利
     * @param $user_id
     * @param $user_info
     * @return bool
     * @throws Exception
     */
    public function rebateOrderForGPartner($user_id, $user_info)
    {
        $User_GradeModel = new User_GradeModel();
        $User_InfoModel = new User_InfoModel();
        $Order_BaseModel = new Order_BaseModel();
        $user_g_grade = $User_GradeModel->getOne(4);

        //获取高级合伙人所有线下的未结算订单
        $cond_row['directseller_gp_id'] = $user_id;
        $cond_row['order_status'] = Order_StateModel::ORDER_FINISH; //已完成
        $cond_row['g_rebate_is_settlement'] = Order_BaseModel::IS_NOT_SETTLEMENT; //未结算
        $cond_row['order_create_time:>='] = $user_info['user_grade_update_date'];//订单是升级成为高级合伙人后的订单
        $order_row['order_finished_time'] = 'asc';
        $user_orders = $Order_BaseModel->getByWhere($cond_row, $order_row);
        Yf_Log::log(count($user_orders), Yf_Log::LOG, 'user_settle');

        $flag = true;
        if (count($user_orders) > 0) {
            $user_order_pay_amount = array_sum(array_column($user_orders, 'order_payment_amount'));
            $user_order_refund_amount = array_sum(array_column($user_orders, 'order_refund_amount'));
            $user_order_amount = $user_order_pay_amount - $user_order_refund_amount;
            Yf_Log::log("g_partner user_order_amount:".$user_order_amount, Yf_Log::LOG, 'user_settle');

            //更新订单提成结算状态
            $user_order_ids = array_column($user_orders, 'order_id');
            $order_edit_row['g_rebate_is_settlement'] = Order_BaseModel::IS_SETTLEMENT;
            $flag = $Order_BaseModel->editBase($user_order_ids, $order_edit_row);

            //计算高级合伙人的提成比例
//            $grade_order_rebate1 = $user_g_grade['order_rebate1'] * 1/100;
//            $grade_order_rebate2 = $user_g_grade['order_rebate2'] * 1/100;
//            $grade_order_rebate_top = $user_g_grade['order_rebate_top'] * 1/100;
//            $partner_count = $user_info['current_year_partner_count'];
//            $order_rebate = $grade_order_rebate1 * 1 + $grade_order_rebate2 * $partner_count;
//            if ($order_rebate > $grade_order_rebate_top) {
//                $order_rebate = $grade_order_rebate_top;
//            }
//            if ($order_rebate == 0) return true;

            foreach ($user_orders as $k=>$order){
                $order_amount = $order['order_payment_amount'] * 1 - $order['order_refund_amount'] * 1;
//                $order_rebate_value_temp = $order_amount * $order_rebate;
//                $order_rebate_temp = $order_rebate*100;
                $order_rebate_value_temp = $order['order_directseller_commission3'];
                $order_rebate_temp = $order_amount/$order_rebate_value_temp*100;
                Yf_Log::log($order_rebate_value_temp, Yf_Log::LOG, 'user_settle');

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
                $formvars['desc'] = "订单金额{$order_amount},订单结算时提成比例为{$order_rebate_temp}%";
                $formvars['app_id'] = $paycenter_app_id;
                $formvars['trade_type'] = 15;
                $formvars['type'] = 'row';

                $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=directsellerOrder&typ=json', $url), $formvars);
            }
        }

        return $flag;
    }

    /**
     * 高级合伙人备货单差额返还
     * @param $user_id
     * @return bool
     * @throws Exception
     */
    public function stockOrderSettle($user_id)
    {
        $Stock_OrderModel = new Stock_OrderModel();

        //查询未结算的备货单
        $cond_row = array();
        $cond_row['shop_user_id'] = $user_id;
        $cond_row['order_status'] = Order_StateModel::ORDER_FINISH;
        $cond_row['order_finished_time:<='] = get_date_time();
        $cond_row['order_is_settlement'] = Order_BaseModel::IS_NOT_SETTLEMENT; //未结算

        $data = $Stock_OrderModel->getByWhere($cond_row);
        if(count($data) > 0) {
            foreach ($data as $key => $order) {
                $stock_order_id = $order['stock_order_id'];
                $edit['order_is_settlement'] = Order_BaseModel::IS_SETTLEMENT;
                $flag = $Stock_OrderModel->editOrder($stock_order_id, $edit);

                if($order['order_commission_fee'] == 0) continue;

                //将需要确认的订单号远程发送给Paycenter修改订单状态
                //远程修改paycenter中的订单状态
                $key = Yf_Registry::get('paycenter_api_key');
                $url = Yf_Registry::get('paycenter_api_url');
                $paycenter_app_id = Yf_Registry::get('paycenter_app_id');
                $formvars = array();

                $formvars['order_id'] = $stock_order_id;
                $formvars['user_id'] = $order['shop_user_id'];
                $formvars['user_money'] = $order['order_commission_fee'];
                $formvars['reason'] = '备货订单差额返还';
                $formvars['trade_type'] = 13;
                $formvars['app_id'] = $paycenter_app_id;
                $formvars['type'] = 'row';

                $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=directsellerOrder&typ=json', $url), $formvars);
            }
        }else{
            $flag = true;
        }

        return $flag;
    }
}