<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Order_BaseModel extends Order_Base
{

    const ORDER_IS_VIRTUAL = 1;            //服务订单
    const VIRTUAL_USED = 1;                //服务订单已使用
    const VIRTUAL_UNUSE = 0;                    //服务订单未使用
    const ORDER_IS_REAL = 0;                    //实物订单
    const IS_BUYER_CANCEL = 1;                //买家取消订单
    const IS_SELLER_CANCEL = 2;                //卖家取消订单
    const IS_ADMIN_CANCEL = 3;                //平台取消
	const IS_NOT_SETTLEMENT = 0;                //未结算
	const IS_SETTLEMENT = 1;                    //已结算

    const NO_BUYER_HIDDEN = 0;                //买家不隐藏订单
    const NO_SELLER_HIDDEN = 0;                //卖家不隐藏订单
    const NO_SUBUSER_HIDDEN = 0;                //主管账号不隐藏订单

    const IS_BUYER_HIDDEN = 1;                //买家隐藏订单
    const IS_SELLER_HIDDEN = 1;                //卖家隐藏订单
    const IS_SUBUSER_HIDDEN = 1;                //主管账号隐藏订单

    const IS_BUYER_REMOVE = 2;                //买家删除订单
    const IS_SELLER_REMOVE = 2;                //卖家删除订单
    const IS_SUBUSER_REMOVE = 2;               //主账号删除订单

    const RETURN_ALL = 2;
    const RETURN_SOME = 1;

    const REFUND_NO = 0;
    const REFUND_IN = 1;
    const REFUND_COM = 2;

    //订单取消身份
    const CANCEL_USER_BUYER = 1;
    const CANCEL_USER_SELLER = 2;
    const CANCEL_USER_SYSTEM = 3;

    //买家是否评价
    const BUYER_EVALUATE_NO = 0;
    const BUYER_EVALUATE_YES = 1;
    const BUYER_EVALUATE_AGAIN = 2; //已追加评价


    //买家是否评价
    const SELLER_EVALUATE_NO = 0;
    const SELLER_EVALUATE_YES = 1;

    //订单来源
    const FROM_PC 		= 1;  	//来源于pc端
    const FROM_WAP 		= 2; 	//来源于WAP手机端
    const FROM_WEBPOS 	= 3;	//来源于WEBPOS线下下单


    //状态
    public static $state = array(
        '1' => 'wait_operate',
        //已出账
        '2' => 'seller_comfirmed',
        //商家已确认
        '3' => 'platform_comfirmed',
        //平台已审核
        '4' => 'finish',
        //结算完成
    );

    public static $orderType = array(
        //服务订单
        '0' => 'is_physical',
        //实物订单
        '1' => 'is_virtaul',
    );

    public static $orderEvaluatBuyer = array(
        //买家已评价
        '1' => 'is_evaluated',
        //买家未评价
        '0' => 'is_uevaluate',
    );

    public static $orderEvaluatSeller = array(
        //买家已评价
        '1' => 'is_evaluated',
        //买家未评价
        '0' => 'is_uevaluate',
    );

    public $cancelIdentity = null;
    public $goodsRefundState = null;//退货状态
    public $goodsReturnState = null;//退款状态

    public function __construct()
    {
        parent::__construct();


        $this->cancelIdentity = array(
            '1' => __('买家'),
            '2' => __('商家'),
            '3' => __('系统'),
        );

        $this->goodsRefundState = array(
            '0' => __("无退货"),
            //无退货
            '1' => __("退货中"),
            //退货中
            '2' => __("退货完成"),
            //退货完成
            '3' =>__("平台拒绝退货"),
        );

        $this->goodsReturnState = array(
            '0' => __("无退款"),
            //无退货
            '1' => __("退款中"),
            //退货中
            '2' => __("退款完成"),
            //退货完成
            '3' => __("平台拒绝退款"),
        );

    }

    /**
     * 读取分页列表
     * Zhuyt
     * @param  int $config_key 主键值
     * @return array $rows 返回的查询内容
     * @access public
     */
    public function getBaseList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
    {
        $data = $this->listByWhere($cond_row, $order_row, $page, $rows);

        $Shop_BaseModel = new Shop_BaseModel();
        $Order_GoodsModel = new Order_GoodsModel();
        $Order_StateModel = new Order_StateModel();
        $Goods_EvaluationModel = new Goods_EvaluationModel();
        if ($data['items']) {
            foreach ($data['items'] as $key => $val) {
                //若是待付款订单，计算系统取消订单时间
                if ($val['order_status'] == Order_StateModel::ORDER_WAIT_PAY) {
                    $data['items'][$key]['cancel_time'] = date('Y-m-d H:i:s', strtotime($val['order_create_time']) + Yf_Registry::get('wait_pay_time'));

                    if ($data['items'][$key]['cancel_time'] <= get_date_time()) {
                        //修改订单状态 - 将订单状态改为取消
                        $this->cancelOrder($val['order_id']);
                    }
                }

                //若是已发货订单，计算系统自动确认收货时间
                if ($val['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS) {
                    //$data['items'][$key]['confirm_time'] = date('Y-m-d H:i:s', strtotime($val['order_shipping_time']) + Yf_Registry::get('confirm_order_time'));

                    //if($data['items'][$key]['confirm_time'] <= get_date_time())
                    if ($val['order_receiver_date'] <= get_date_time()) {
                        //修改订单状态 - 将订单状态改为已收货
                        //服务订单过期自动退款（未退款）
                        if($val['order_is_virtual'] == Order_BaseModel::ORDER_IS_VIRTUAL && $val['order_refund_status'] == Order_BaseModel::REFUND_NO)
                        {
                            $this->virtualReturn($val['order_id']);
                        }
                        else
                        {
                            $this->confirmOrder($val['order_id']);
                        }
                    }
                }
                //取出物流公司名称
                if (!empty($val['order_shipping_express_id'])) {
                    $expressModel = new ExpressModel();
                    $express_base = $expressModel->getExpress($val['order_shipping_express_id']);
                    $express_base = pos($express_base);
                    $data['items'][$key]['express_name'] = $express_base['express_name'];
                } else {
                    $data['items'][$key]['express_name'] = '';
                }
            }
        }

        //取出所有shop_id 判断为哪家店铺的商品
        $shop_ids = array_column($data['items'], 'shop_id');
        if (!empty($shop_ids)) {
            $cond_row = array();
            $cond_row['shop_id:IN'] = $shop_ids;
            $shop_list = $Shop_BaseModel->getByWhere($cond_row, array());
        }
        $shop_name_list = array();
        if(!empty($shop_list)){
            foreach($shop_list as $val){
                $shop_name_list[$val['shop_id']] = $val['shop_name'];
            }
        }

        if ($data['items']) {
            foreach ($data['items'] as $key => $val) {
                $data['items'][$key]['order_state_con'] = $Order_StateModel->orderState[$val['order_status']];
                $data['items'][$key]['order_refund_status_con'] = $Order_StateModel->orderRefundState[$val['order_refund_status']];
                $data['items'][$key]['shop_names'] = $shop_name_list[$val['shop_id']];
                //若是待付款订单，计算系统取消订单时间
                if ($val['order_status'] == Order_StateModel::ORDER_WAIT_PAY) {
                    $data['items'][$key]['cancel_time'] = date('Y-m-d H:i:s', strtotime($val['order_create_time']) + Yf_Registry::get('wait_pay_time'));
                }

                //若是已发货订单，计算系统自动确认收货时间
                /*if ($val['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS)
                {
                    $data['items'][$key]['confirm_time'] = date('Y-m-d H:i:s', strtotime($val['order_shipping_time']) + Yf_Registry::get('confirm_order_time'));
                }*/

                //若为退款中订单，则查找退款单id
                if ($val['order_refund_status'] != Order_StateModel::ORDER_REFUND_NO) {
                    $Order_ReturnModel = new Order_ReturnModel();
                    $order_return_id = $Order_ReturnModel->getKeyByWhere(array('order_number' => $val['order_id'], 'order_goods_id' => '0'));
                    $data['items'][$key]['order_return_id'] = $order_return_id[0];
                }

                //放入店铺信息
                $order_goods[$key]['shop_self_support'] = $shop_list[$val['shop_id']]['shop_self_support'];
                //查找订单商品
                $order_goods = $Order_GoodsModel->getByWhere(array('order_id' => $val['order_id']));

                $data['items'][$key]['can_confirm_order'] = true;
                foreach ($order_goods as $okey => $oval) {
                    //判断该订单商品被评论的次数
                    $goods_evaluation_row = array();
                    $goods_evaluation_row['order_id'] = $val['order_id'];
                    $goods_evaluation_row['goods_id'] = $oval['goods_id'];
                    $goods_evaluation = $Goods_EvaluationModel->getByWhere($goods_evaluation_row);

                    $order_goods[$okey]['evaluation_count'] = count($goods_evaluation);

                    //判断订单商品的退货状态
                    $order_goods[$okey]['goods_refund_status_con'] = $this->goodsRefundState[$oval['goods_refund_status']];
                    $order_goods[$okey]['goods_return_status_con'] = $this->goodsReturnState[$oval['goods_return_status']];

                    //查找退货id
                    if ($oval['goods_refund_status'] !== Order_StateModel::ORDER_GOODS_RETURN_NO) {
                        $Order_ReturnModel = new Order_ReturnModel();
                        $order_goods_return_id = $Order_ReturnModel->getKeyByWhere(array('order_number' => $val['order_id'],
                                                                                          'order_goods_id' => $oval['order_goods_id'],
                                                                                          'return_type' => 2,
                                                                                   ));
                        $order_goods[$okey]['order_refund_id'] = $order_goods_return_id[0];
                        if($oval['goods_refund_status'] == Order_GoodsModel::REFUND_IN){
                            $data['items'][$key]['can_confirm_order'] = false;
                        }
                    }

                    //查找退款id
                    if ($oval['goods_return_status'] !== Order_StateModel::ORDER_REFUND_NO) {
                        $Order_ReturnModel = new Order_ReturnModel();
                        $order_goods_return_id = $Order_ReturnModel->getKeyByWhere(array('order_number' => $val['order_id'],
                                                                                       'order_goods_id' => $oval['order_goods_id'],
                                                                                       'return_type' => 1,));
                        $order_goods[$okey]['order_return_id'] = $order_goods_return_id[0];
                        if($oval['goods_return_status'] == Order_GoodsModel::REFUND_IN){
                            $data['items'][$key]['can_confirm_order'] = false;
                        }
                    }

                    //如果订单是服务订单
                    if($val['order_is_virtual'] && $oval['goods_refund_status'] !== Order_StateModel::ORDER_GOODS_RETURN_NO)
                    {
                        $Order_ReturnModel = new Order_ReturnModel();
                        $order_goods_return_id = $Order_ReturnModel->getKeyByWhere(array('order_number' => $val['order_id'],
                                                                                       'order_goods_id' => $oval['order_goods_id'],
                                                                                       'return_type' => 3,));
                        $order_goods[$okey]['order_return_id'] = $order_goods_return_id[0];
                    }

                }

                //若是该订单已完成，判断其交易投诉的有效时间
                $Web_ConfigModel = new Web_ConfigModel();
                $day = $Web_ConfigModel->getOne('complain_datetime');
                $day = $day['config_value'];
                $data['items'][$key]['complain_day'] = $day;
                if ($val['order_status'] == Order_StateModel::ORDER_FINISH) {
                    $comtime = $day * 86400;

                    $complain_time = strtotime($val['order_finished_time']) + $comtime;

                    //当前时间在投诉有效期内
                    if ($complain_time > time()) {
                        $data['items'][$key]['complain_status'] = 1;
                    } else {
                        $data['items'][$key]['complain_status'] = 0;
                    }
                } else {
                    $data['items'][$key]['complain_status'] = 0;
                }


                $data['items'][$key]['goods_list'] = array_values($order_goods);
            }
        }

        return $data;
    }

    /*
     *  zhuyt
     *
     * 订单详情
     */
    public function getOrderDetail($order_id = null)
    {
        $data = $this->getOneByWhere(array('order_id' => $order_id));

        $Order_StateModel = new Order_StateModel();

        $data['order_state_con'] = $Order_StateModel->orderState[$data['order_status']];

        //订单退款状态
        $data['order_refund_status_con'] = $Order_StateModel->orderRefundState[$data['order_refund_status']];

        //若为服务订单并且服务兑换码已发放,计算还有多少未使用的兑换码，服务商品是否支付过期退款，服务商品的过期时间
        if ($data['order_status'] != Order_StateModel::ORDER_WAIT_PAY && $data['order_is_virtual'] == Order_BaseModel::ORDER_IS_VIRTUAL) {
            $Order_GoodsVirtualCodeModel = new Order_GoodsVirtualCodeModel();
            $cond_row = array();
            $cond_row['order_id'] = $data['order_id'];

            $data['code_list'] = $Order_GoodsVirtualCodeModel->getVirtualCode($cond_row);

            $cond_row['virtual_code_status'] = Order_GoodsVirtualCodeModel::VIRTUAL_CODE_NEW;
            $new_code = $Order_GoodsVirtualCodeModel->getVirtualCode($cond_row);
            $data['new_code'] = count($new_code);


            //获取所有的订单商品id
            $code_order_goods_id = array_column($data['code_list'], 'order_goods_id');
            $Order_GoodsModel = new Order_GoodsModel();
            $Goods_CommonModel = new Goods_CommonModel();

            //查找订单商品
            $code_order_goods = $Order_GoodsModel->getByWhere(array('order_goods_id:IN' => $code_order_goods_id));

            //查找订单商品的common信息
            $code_order_goods_common = array_column($code_order_goods, 'common_id');

            foreach ($code_order_goods_common as $commonkey => $commonval) {
                $code_order_goods_common[$commonkey] = $Goods_CommonModel->getOne($commonval);
            }

            foreach ($data['code_list'] as $codekey => $codeval) {
                //查找订单商品
                $data['code_list'][$codekey]['common_virtual_refund'] = $code_order_goods_common[$data['code_list'][$codekey]['order_goods_id']]['common_virtual_refund'];
                $data['code_list'][$codekey]['common_virtual_date'] = $code_order_goods_common[$data['code_list'][$codekey]['order_goods_id']]['common_virtual_date'];
                $data['common_virtual_date'] = $code_order_goods_common[$data['code_list'][$codekey]['order_goods_id']]['common_virtual_date'];
            }
        }


        //若是待付款订单，计算系统取消订单时间
        if ($data['order_status'] == Order_StateModel::ORDER_WAIT_PAY) {
            $data['cancel_time'] = date('Y-m-d H:i', strtotime($data['order_create_time']) + Yf_Registry::get('wait_pay_time'));
        }

        //若是已发货订单，计算系统自动确认收货时间
        /*if ($data['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS)
        {
            $data['confirm_time'] = date('Y-m-d H:i:s', strtotime($data['order_shipping_time']) + Yf_Registry::get('confirm_order_time'));
        }*/

        //若为退款中订单，则查找退款单id
//        if ($data['order_return_status'] != Order_StateModel::ORDER_REFUND_NO) {
//            $Order_ReturnModel = new Order_ReturnModel();
//            $order_return_id = $Order_ReturnModel->getKeyByWhere(array('order_number' => $data['order_id']));
//            $data['order_return_id'] = $order_return_id[0];
//        }

        //若是已完成订单，计算交易投诉的有效时间
        $Web_ConfigModel = new Web_ConfigModel();
        $day = $Web_ConfigModel->getOne('complain_datetime');
        $day = $day['config_value'];
        $data['complain_day'] = $day;
        if ($data['order_status'] == Order_StateModel::ORDER_FINISH) {
            $comtime = $day * 86400;

            $complain_time = strtotime($data['order_finished_time']) + $comtime;

            //当前时间在投诉有效期内
            if ($complain_time > time()) {
                $data['complain_status'] = 1;
            } else {
                $data['complain_status'] = 0;
            }

            if($data['order_shipping_time'] == '0000-00-00 00:00:00'){
                $Order_ReturnModel = new Order_ReturnModel();
                $order_return = $Order_ReturnModel->getByWhere(array('order_number' => $data['order_id'],
                    'return_state' => 5));
                $order_return = current($order_return);
                $data['order_shipping_time'] = $order_return['return_finish_time'];
                $data['order_finished_time'] = $order_return['return_finish_time'];
            }
        } else {
            $data['complain_status'] = 0;
        }

        //获取订单评价状态
        $data['order_buyer_evaluation_status_con'] = Order_BaseModel::$orderEvaluatBuyer[$data['order_buyer_evaluation_status']];


        //获取订单取消者身份
        if ($data['order_cancel_identity']) {
            $data['cancel_identity'] = $this->cancelIdentity[$data['order_cancel_identity']];
        }


        //查找店铺信息
        $Shop_BaseModel = new Shop_BaseModel();
        $shop_base = $Shop_BaseModel->getOne($data['shop_id']);

        $data['shop_names'] = $shop_base['shop_name'];
        $data['shop_address'] = $shop_base['shop_region'] . $shop_base['shop_address'];
        $data['shop_phone'] = $shop_base['shop_tel'];
        $data['shop_self_support'] = $shop_base['shop_self_support'];
        $data['shop_logo'] = $shop_base['shop_logo'];

        //查找订单商品
        $Goods_CommonModel = new Goods_CommonModel();
        $Order_GoodsModel = new Order_GoodsModel();
        $Goods_EvaluationModel = new Goods_EvaluationModel();
        $order_goods = $Order_GoodsModel->getByWhere(array('order_id' => $data['order_id']));
        $data['can_confirm_order'] = true;
        foreach ($order_goods as $okey => $oval) {
            //判断该订单商品被评论的次数
            $goods_evaluation_row = array();
            $goods_evaluation_row['order_id'] = $data['order_id'];
            $goods_evaluation_row['goods_id'] = $oval['goods_id'];
            $goods_evaluation = $Goods_EvaluationModel->getByWhere($goods_evaluation_row);

            $order_goods[$okey]['evaluation_count'] = count($goods_evaluation);

            //获取订单商品退货状态
            $order_goods[$okey]['goods_refund_status_con'] = $this->goodsRefundState[$oval['goods_refund_status']];
            $order_goods[$okey]['goods_return_status_con'] = $this->goodsReturnState[$oval['goods_return_status']];

            if($oval['goods_refund_status'] == Order_GoodsModel::REFUND_IN){
                $data['can_confirm_order'] = false;
            }
            if($oval['goods_return_status'] == Order_GoodsModel::REFUND_IN){
                $data['can_confirm_order'] = false;
            }
            //若为退货中订单，则查找退货单id
            if ($oval['goods_refund_status'] !== Order_StateModel::ORDER_REFUND_NO) {
                $Order_ReturnModel = new Order_ReturnModel();
                $order_return_id = $Order_ReturnModel->getKeyByWhere(array('order_number' => $data['order_id'],
                                                                         'return_type' => 2,
                                                                         'order_goods_id' => $oval['order_goods_id']));
                $order_goods[$okey]['order_refund_id'] = $order_return_id[0];
            }

            //查找退款id
            if ($oval['goods_return_status'] !== Order_StateModel::ORDER_GOODS_RETURN_NO) {
                //判断是否是服务订单
                if($data['order_is_virtual'])
                {
                    $Order_ReturnModel = new Order_ReturnModel();
                    $order_goods_return_id = $Order_ReturnModel->getKeyByWhere(array('order_number' => $data['order_id'],
                                                                                   'return_type' => 3,
                                                                                   'order_goods_id' => $oval['order_goods_id']));
                    $order_goods[$okey]['order_return_id'] = $order_goods_return_id[0];
                }
                else
                {
                    $Order_ReturnModel = new Order_ReturnModel();
                    $order_goods_return_id = $Order_ReturnModel->getKeyByWhere(array('order_number' => $data['order_id'],
                                                                                   'return_type' => 1,
                                                                                   'order_goods_id' => $oval['order_goods_id']));
                    $order_goods[$okey]['order_return_id'] = $order_goods_return_id[0];
                }
            }
        }

        $data['goods_list'] = array_values($order_goods);

        return $data;
    }

    public function getBaseExcel($cond_row = array(), $order_row = array())
    {
        $data = $this->getByWhere($cond_row, $order_row);

        foreach ($data as $k => $v) {
            $data[$k]['order_id'] = " " . $v['order_id'] . " ";
        }

        return array_values($data);
    }

    /**
     * 读取分页列表
     *
     * @param  int $config_key 主键值
     * @return array $rows 返回的查询内容
     * @access public
     */
    public function getDetailList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
    {
        return $this->listByWhere($cond_row, $order_row, $page, $rows);
    }

    /*
     * 获取结算表下面的相关订单数据
     */

    public function getOrderDetailList($cond_row = array(), $order_row = array(), $page = 1, $rows = 10)
    {
        return $this->listByWhere($cond_row, $order_row, $page, $rows);
    }

    /*
     *  zhuyt
     *
     * 结算订单表
     * 10/22  修改订单中不再计算退款金额，退款金额通过退货单进行计算
     */
    public function settleOrder($cond_row = array(), $order_row = array())
    {
        $data = $this->getByWhere($cond_row, $order_row);

        //订单金额
        $order_amount = 0;
        //运费
        $shipping_amount = 0;
        //佣金金额
        $commission_amount = 0;
        //退款金额
        $return_amount = 0;
        //退款佣金
        $commission_return_amount = 0;

        //结算订单部分的费用
        $res = array(
            'order_amount' => array_sum(array_column($data,'order_payment_amount')),
            'shipping_amount' => array_sum(array_column($data,'order_shipping_fee')),
            'commission_amount' => array_sum(array_column($data,'order_commission_fee')),
            'redpacket_amount' => array_sum(array_column($data,'order_rpt_price')),
			'order_directseller_commission' => array_sum(array_column($data,'order_directseller_commission')),
        );

        //修改订单表中的结算时间与结算状态
        $id_row = array_keys($data);
        $this->editBase($id_row, array('order_settlement_time' => get_date_time() ,'order_is_settlement' =>Order_SettlementModel::SETTLEMENT_WAIT_OPERATE));

        return $res;

    }

    /*
     *  windfnn
     *
     * 获取买家订单列表
     */
    public function getOrderList($cond_row = array(), $order_row = array(), $page = 1, $rows = 15)
    {
        //分销商分销的商品
        $GoodsCommonModel        = new Goods_CommonModel();
        $Order_GoodsModel = new Order_GoodsModel();
        $dist_commons = $GoodsCommonModel->getByWhere(array('shop_id' => Perm::$shopId,"common_parent_id:>" => 0,'product_is_behalf_delivery' => 1));

        $dist_common_ids = array();
        if(!empty($dist_commons)){
            $dist_common_ids  = array_column($dist_commons,'common_id');
        }

        //分页
        $Yf_Page = new Yf_Page();
        $rows = $Yf_Page->listRows;
        $offset = request_int('firstRow', 0);
        $page = ceil_r($offset / $rows);

//        $User_InfoModel = new User_InfoModel();
//        $user_children_ids = $User_InfoModel->getUserChildren(Perm::$userId);
//        $cond_row['buyer_user_id:in'] = explode(',', $user_children_ids);

        $data = $this->listByWhere($cond_row, $order_row, $page, $rows);
        $Order_StateModel = new Order_StateModel();
        $Order_InvoiceModel = new Order_InvoiceModel();
        $Order_ReturnModel = new Order_ReturnModel();

        //分页
        $Yf_Page->totalRows = $data['totalsize'];
        $page_nav = $Yf_Page->prompt();
        $data['page_nav'] = $page_nav;

        $order_id_row = array_column($data['items'], 'order_id');
        $order_goods_list = $Order_GoodsModel->getByWhere(array('order_id:IN' => $order_id_row));

        $goods_list = array();

        foreach ($order_goods_list as $item) {
            $goods_list[$item['order_id']][] = $item;
        }

        $url = Yf_Registry::get('url');

        foreach ($data['items'] as $key => $val) {

            $data['items'][$key]['order_stauts_text'] = $Order_StateModel->orderState[$val['order_status']];
            $data['items'][$key]['order_stauts_const'] = $Order_StateModel->orderState[$val['order_status']];

            //订单详情URL
            $data['items'][$key]['info_url'] = $url . '?ctl=Seller_Trade_Order&met=physicalInfo&o&typ=e&order_id=' . $val['order_id'];
            //发货单URL
            $data['items'][$key]['delivery_url'] = $url . '?ctl=Seller_Trade_Order&met=getOrderPrint&typ=e&order_id=' . $val['order_id'];
            //设置发货URL
            $data['items'][$key]['send_url'] = $url . '?ctl=Seller_Trade_Order&met=send&typ=e&order_id=' . $val['order_id'];
            //收货人信息 名字 + 联系方式 + 地址 &nbsp
            $data['items'][$key]['receiver_info'] = $val['order_receiver_name'] . "&nbsp" . $val['order_receiver_contact'] . "&nbsp" . $val['order_receiver_address'];

            //订单发票信息
            if($val['order_invoice_id'])
            {
                $data['items'][$key]['invoice'] = $Order_InvoiceModel->getOne($val['order_invoice_id']);
                $data['items'][$key]['invoice']['invoice_statu_txt'] = $Order_InvoiceModel->invoiceState[$data['items'][$key]['invoice']['invoice_state']];
            }


            //发货人信息
            if (empty($val['order_seller_name'])) {
                $data['items'][$key]['shipper'] = 0;
                $data['items'][$key]['shipper_info'] = '还未设置发货地址，请进入发货设置 &gt 地址库中添加';
            } else {
                $data['items'][$key]['shipper'] = 1;
                $data['items'][$key]['shipper_info'] = $val['order_seller_name'] . "&nbsp" . $val['order_seller_address'] . "&nbsp" . $val['order_seller_contact'];
            }

            //运费信息
            if ($val['order_shipping_fee'] == 0) {
                $data['items'][$key]['shipping_info'] = "(免运费)";
            } else {
                $shipping_fee = @format_money($val['order_shipping_fee']);
                $data['items'][$key]['shipping_info'] = "(含运费$shipping_fee)";
            }

            /*
             * 订单操作
             * 待付款状态 ==> 取消订单
             * 待发货状态 ==> 设置发货
             * */
			
			if(Perm::$shopId){
				$shopBaseModel = new Shop_BaseModel();
				$shop_base  = $shopBaseModel->getOne(Perm::$shopId);
			}


            //获取订单产品列表
            //$goods_list                        = $Order_GoodsModel->getGoodsListByOrderId($val['order_id']);

            if(isset($goods_list[$val['order_id']]))
            {
                $data['items'][$key]['goods_list'] = $goods_list[$val['order_id']];

                $goods_cat_num = 0;
                foreach ($data['items'][$key]['goods_list'] as $k => $v) {
                    $data['items'][$key]['goods_list'][$k]['goods_link'] = $url . '?ctl=Goods_Goods&met=snapshot&goods_id=' . $v['goods_id'] . '&order_id=' . $val['order_id'];//商品链接
                    $goods_cat_num += 1;
                    if(is_array($data['items'][$key]['goods_list'][$k]['order_spec_info']) && $data['items'][$key]['goods_list'][$k]['order_spec_info']){
                        $data['items'][$key]['goods_list'][$k]['order_spec_info'] = implode('，',$data['items'][$key]['goods_list'][$k]['order_spec_info']);
                    }

                    //判断商品是否是一件代发分销商品，如果是一件代发分销商品，分销商无法发货
                    $deilve_able = 1;
                    if(in_array($v['common_id'],$dist_common_ids))
                    {
                        $deilve_able = 0;
                    }

                    //查找该订单商品是否存在退款/退货
                    $goods_return       = $Order_ReturnModel->getByWhere(array(
                                                                             'order_goods_id' => $v['order_goods_id'],
//                                                                             'return_type' => Order_ReturnModel::RETURN_TYPE_ORDER,
                                                                             'return_shop_handle:!=' => Order_ReturnModel::RETURN_PLAT_UNPASS,
                                                                         ), ['return_add_time'=>'desc']);

                    $return_txt = '';
                    if($goods_return)
                    {
                        $goods_return = current($goods_return);

                        if($goods_return['return_state'] == Order_ReturnModel::RETURN_PLAT_PASS)
                        {
                            $return_txt = "<span class='colred'>已退".$goods_return['order_goods_num']."件</span>";
                            //$data['items'][$key]['goods_list'][$k]['order_goods_num'] = $v['order_goods_num']*1 - $goods_return['order_goods_num']*1;
                        }else if($goods_return['return_state'] == Order_ReturnModel::RETURN_PLAT_UNPASS){
                            if($goods_return['return_type'] == Order_ReturnModel::RETURN_TYPE_GOODS){
                                $return_type_text = "平台拒绝退货";
                            }else{
                                $return_type_text = "平台拒绝退款";
                            }
                            $return_txt = "<span class='colred'>{$return_type_text}</span>";
                        }
                        else{
                            if($deilve_able)
                            {
                                if($goods_return['return_type'] == Order_ReturnModel::RETURN_TYPE_GOODS){
                                    $return_url = $url . '?ctl=Seller_Service_Return&met=goodsReturn&act=detail&id=' . $goods_return['order_return_id'];
                                    $return_txt = "<a class=\"ncbtn ncbtn-mint mt10 bbc_seller_btns\" href=\"$return_url\"><i class=\"icon-truck\"></i>处理退货</a>";
                                }else{
                                    $return_url = $url . '?ctl=Seller_Service_Return&met=orderReturn&act=detail&id=' . $goods_return['order_return_id'];
                                    $return_txt = "<a class=\"ncbtn ncbtn-mint mt10 bbc_seller_btns\" href=\"$return_url\"><i class=\"icon-truck\"></i>处理退款</a>";
                                }
                            }
                        }
                    }
                    $data['items'][$key]['goods_list'][$k]['return_txt'] = $return_txt;
                }
            }
            //商品种类数
            $data['items'][$key]['goods_cat_num'] = $goods_cat_num;
            $data['items'][$key]['deilve_able'] = $deilve_able;
			
            if ($val['order_status'] == Order_StateModel::ORDER_WAIT_PAY) {
                $order_id = $val['order_id'];
                $set_html = "<a href=\"javascript:void(0)\" data-order_id=$order_id dialog_id=\"seller_order_cancel_order\" class=\"ncbtn ncbtn-grapefruit mt5\"><i class=\"icon-remove-circle\"></i>取消订单</a>";

                $data['items'][$key]['set_html'] = $set_html;
            } elseif ($val['order_status'] == Order_StateModel::ORDER_WAIT_PREPARE_GOODS || $val['order_status'] == Order_StateModel::ORDER_PAYED) {
                //订单已经支付，判断已支付订单是否存在退款
                $return       = $Order_ReturnModel->getByWhere(array(
                                                                        'order_number' => $val['order_id'],
                                                                        'return_type' => Order_ReturnModel::RETURN_TYPE_ORDER,
                                                                        'return_state:<' => Order_ReturnModel::RETURN_PLAT_PASS
                                                                    ));

                if ($return) {
                    //查找退款单
                    $order_retuen_cond['order_number'] = $val['order_id'];
                    $order_retuen_cond['return_goods_return'] = Order_ReturnModel::RETURN_GOODS_ISRETURN;
                    $return_id = $Order_ReturnModel->getKeyByWhere($order_retuen_cond);
                    $return_id = $return_id['0'];

                    $data['items'][$key]['retund_url'] = $url . '?ctl=Seller_Service_Return&met=orderReturn&act=detail&id=' . $return_id;
                    $retund_url = $url . '?ctl=Seller_Service_Return&met=orderReturn&act=detail&id=' . $return_id;

                    $set_html = "<span class=\"ncbtn ncbtn-mint mt10 colred\"><i class=\"icon-truck\"></i>退款中</span>";

                } else {

                    if($data['items'][$key]['deilve_able'])
                    {
                        $send_url = $data['items'][$key]['send_url'];
                        $set_html = "<a class=\"ncbtn ncbtn-mint mt10 bbc_seller_btns\" href=\"$send_url\"><i class=\"icon-truck\"></i>设置发货</a>";
                    }
                    else
                    {
                        $set_html = '';
                    }
                }


                $data['items'][$key]['set_html'] = $set_html;
            } else {
                $data['items'][$key]['set_html'] = null;
            }

            //货到付款+待发货=> 可以取消订单
            if ( $val['payment_id'] == PaymentChannlModel::PAY_CONFIRM
                && $val['order_status'] == Order_StateModel::ORDER_WAIT_PREPARE_GOODS
            ) {
                $order_id = $val['order_id'];
                $set_html = "<a href=\"javascript:void(0)\" data-order_id=$order_id dialog_id=\"seller_order_cancel_order\" class=\"ncbtn ncbtn-grapefruit mt5\"><i class=\"icon-remove-circle\"></i>取消订单</a>";

                $data['items'][$key]['set_html'] .= $set_html;
            }


        }

        return $data;
    }

    /*
     *  windfnn
     *
     * 获取平台商品订单列表
     * @return array $data 订单列表
     * */
    public function getPlatOrderList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
    {
        $data = $this->getBaseList($cond_row, $order_row, $page, $rows);
        $Order_StateModel = new Order_StateModel();

        foreach ($data['items'] as $key => $val) {
            $data['items'][$key]['order_stauts_text'] = $Order_StateModel->orderState[$val['order_status']];
            $data['items'][$key]['order_from_text'] = $Order_StateModel->orderFrom[$val['order_from']];
            $data['items'][$key]['evaluation_status_text'] = $Order_StateModel->evaluationStatus[$val['order_buyer_evaluation_status']];
        }

        return $data;
    }

    /*
     *  ly
     *
     * 拼接筛选条件,多个方法公用
     * @param $condition 筛选条件
     * @return array $condition 筛选条件
     * */
    public function createSearchCondi(&$condition)
    {
        $query_start_date = request_string('query_start_date');
        $query_end_date = request_string('query_end_date');
        $buyer_name = request_string('buyer_name');
        $order_sn = request_string('order_sn');
        $skip_off = request_int('skip_off');            //是否显示已取消订单
        $chain_name = request_string('chain_name'); //门店名称

        if (!empty($query_start_date)) {
            $condition['order_create_time:>='] = $query_start_date;
        }

        if (!empty($query_end_date)) {
            $condition['order_create_time:<='] = date('Y-m-d 23:59:59',strtotime($query_end_date));
        }

        if (!empty($buyer_name)) {
            $condition['buyer_user_name:LIKE'] = "%$buyer_name%";
        }

        if (!empty($order_sn)) {
            $condition['order_id'] = $order_sn;
        }

        if ($skip_off) {
            $condition['order_status:<>'] = Order_StateModel::ORDER_CANCEL;
        }

        //门店名字
        if ($chain_name) {
            $chain_model = new Chain_BaseModel;
            $chain_rows = $chain_model->getByWhere(['entity_name:LIKE'=> '%'.$chain_name.'%']);
            $chain_ids = empty($chain_rows) ? [] : array_keys($chain_rows);
            $condition['chain_id:IN'] = $chain_ids;
        }
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

        $data = $this->getOrderList($condi);
        $data = pos($data['items']);

        switch ($data['order_status']) {
            case Order_StateModel::ORDER_WAIT_PAY :
                $order_create_time = time($data['order_create_time']);
                $order_close_data = date('Y-m-d H:i:00', $order_create_time + Yf_Registry::get('wait_pay_time'));
                $data['order_status_text'] = '订单已经提交，等待买家付款';
                $data['order_status_html'] = "<li>1. 买家尚未对该订单进行支付。</li><li>2. 如果买家未对该笔订单进行支付操作，系统将于<time>$order_close_data</time>自动关闭该订单。</li>";

                //页面的订单状态
                $data['order_payed'] = "";
                $data['order_wait_confirm_goods'] = "";
                $data['order_received'] = "";
                $data['order_evaluate'] = "";
                break;

            case Order_StateModel::ORDER_PAYED:
                $payment_name = $data['payment_name'];
                if (empty($payment_name)) {
                    $payment_name = 'XXX';
                }
                $data['order_status_text'] = '已经付款';
                $data['order_status_html'] = "<li>1. 买家已使用“" . $payment_name . "”方式成功对订单进行支付，支付单号 “" .$data['payment_other_number']. "”。</li><li>2. 订单已提交商家进行备货发货准备。</li>";

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
                $data['order_status_html'] = "<li>1. 买家已使用“" . $payment_name . "”方式成功对订单进行支付。</li><li>2. 订单已提交商家进行备货发货准备。</li>";

                //页面的订单状态
                $data['order_payed'] = "current";
                $data['order_wait_confirm_goods'] = "";
                $data['order_received'] = "";
                $data['order_evaluate'] = "";
                break;


            case Order_StateModel::ORDER_WAIT_CONFIRM_GOODS :
                $data['order_status_text'] = '已经发货';
                if (empty($data['order_receiver_date'])) {
                    $order_shipping_time = strtotime($data['order_shipping_time']);
                    $order_shipping_time = strtotime('+1 month', $order_shipping_time);
                    $order_shipping_time = date('Y-m-d', $order_shipping_time);
                    $data['order_receiver_date'] = $order_shipping_time;
                } else {
                    $order_shipping_time = $data['order_receiver_date'];
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
                else
                {
                    $data['order_status_html'] = "<li>1. 商品已发出；无需物流。</li><li>2. 如果买家没有及时进行收货，系统将于<time>$order_shipping_time</time>自动完成“确认收货”，完成交易。</li>";
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
                if ($data['order_buyer_evaluation_status'] != Order_BaseModel::BUYER_EVALUATE_NO) {
                    $data['order_evaluate'] = "current";
                } else {
                    $data['order_evaluate'] = "";
                }

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
        if (!empty($data['order_shipping_express_id'])) {
            $expressModel = new ExpressModel();
            $express_base = $expressModel->getExpress($data['order_shipping_express_id']);
            $express_base = pos($express_base);
            $data['express_name'] = $express_base['express_name'];
        } else {
            $data['express_name'] = '';
        }

        //店主名称
        $shopBaseModel = new Shop_BaseModel();
        $shop_base = $shopBaseModel->getBase($data['shop_id']);
        $shop_base = pos($shop_base);
        $data['shop_user_name'] = $shop_base['user_name'];
        $data['shop_tel'] = $shop_base['shop_tel'];

        return $data;
    }

    /*
  *  zcg
  *
  * 获取门店自提订单信息
  * @param $condition 筛选条件
  * @return array $data 订单信息
  * */
    public function getChainInfoData($condi = array())
    {

        $data = $this->getOrderList($condi);
        $data = pos($data['items']);

        switch ($data['order_status'])
        {
            case Order_StateModel::ORDER_WAIT_PAY :
                $order_create_time = time($data['order_create_time']);
                $order_close_data = date('Y-m-d H:i:00', $order_create_time + Yf_Registry::get('wait_pay_time'));
                $data['order_status_text'] = '订单已经提交，等待买家付款';
                $data['order_status_html'] = "<li>1. 买家尚未对该订单进行支付。</li><li>2. 如果买家未对该笔订单进行支付操作，系统将于<time>$order_close_data</time>自动关闭该订单。</li>";

                //页面的订单状态
                $data['order_payed']              = "";
                $data['order_wait_confirm_goods'] = "";
                $data['order_received']           = "";
                $data['order_evaluate']           = "";
                break;

            case Order_StateModel::ORDER_SELF_PICKUP :
                $payment_name = $data['payment_name'];
                if (empty($payment_name))
                {
                    $payment_name = 'XXX';
                }
                $data['order_status_text'] = '代自提';
                $data['order_status_html'] = "<li>买家还没有到门店自提。</li>";

                //页面的订单状态
                $data['order_payed']              = "current";
                $data['order_wait_confirm_goods'] = "";
                $data['order_received']           = "";
                $data['order_evaluate']           = "";
                break;

            case Order_StateModel::ORDER_RECEIVED || Order_StateModel::ORDER_FINISH :
                $data['order_status_text'] = '已经自提';
                $data['order_status_html'] = '<li>1. 交易已完成，买家可以对购买的商品及服务进行评价。</li><li>2. 评价后的情况会在商品详细页面中显示，以供其它会员在购买时参考。</li>';

                //页面的订单状态
                $data['order_payed']              = "current";
                $data['order_wait_confirm_goods'] = "current";
                $data['order_received']           = "current";
                if ($data['order_buyer_evaluation_status'] != Order_BaseModel::BUYER_EVALUATE_NO)
                {
                    $data['order_evaluate'] = "current";
                }
                else
                {
                    $data['order_evaluate'] = "";
                }

                break;

            case Order_StateModel::ORDER_CANCEL:
                $data['order_status_text'] = '交易关闭';
                $order_cancel_date         = $data['order_cancel_date'];
                $order_cancel_reason       = $data['order_cancel_reason'];

                //判断关闭者身份 1=>买家 2=>卖家 3=>系统
                if ($data['order_cancel_identity'] == Order_BaseModel::CANCEL_USER_BUYER)
                {
                    $identity = '买家';
                }
                else if ($data['order_cancel_identity'] == Order_BaseModel::CANCEL_USER_SELLER)
                {
                    $identity = '商家';
                }
                else if ($data['order_cancel_identity'] == Order_BaseModel::CANCEL_USER_SYSTEM)
                {
                    $identity = '系统';
                }

                $data['order_status_html'] = "<li> $identity 于 $order_cancel_date 取消了订单 ( $order_cancel_reason ) </li>";
                break;
        }

        //店主名称
        $shopBaseModel          = new Shop_BaseModel();
        $shop_base              = $shopBaseModel->getBase($data['shop_id']);
        $shop_base              = pos($shop_base);
        $data['shop_user_name'] = $shop_base['user_name'];
        $data['shop_tel']       = $shop_base['shop_tel'];

        return $data;
    }

    /*
     *  ly
     *
     * 拼接筛选条件,多个方法公用
     * @param $condition 筛选条件
     * @return array $data 订单信息 + 筛选条件
     * */
    public function getPhysicalList(&$condi,$order_row=array())
    {
        if(!isset($condi['shop_id']) || !$condi['shop_id']) {
            $condi['shop_id'] = Perm::$shopId;
        }
        $condi['seller_user_id'] = Perm::$userId;
//        if(!isset($condi['order_is_virtual']) || !$condi['order_is_virtual']){
//            $condi['order_is_virtual'] = 0;
//        }
        if(!isset($condi['order_shop_hidden']) || !$condi['order_shop_hidden']){
            $condi['order_shop_hidden'] = 0;
        }
        $condi['order_payment_amount - order_shipping_fee-order_refund_amount:>='] = '0';
        $this->createSearchCondi($condi); //生成查询条件
        $data = $this->getOrderList($condi,$order_row);
        $data['condi'] = $condi;

        return $data;
    }

    /**
     * 读数量
     *
     * @param  int $config_key 主键值
     * @return array $rows 返回的查询内容
     * @access public
     */
    public function getCount($cond_row = array())
    {
        return $this->getNum($cond_row);
    }


    //订单支付成功后修改订单状态
    public function editOrderStatusAferPay($order_id = null, $uorder_id = null)
    {
        Yf_Log::log('editOrderStatusAferPay 1', Yf_Log::LOG, 'debug');
        $flag = false;
        //查找订单信息
        $order_base = $this->getOne($order_id);
        if($order_base['order_status'] == Order_StateModel::ORDER_WAIT_PAY)
        {
            $edit_row = array('order_status' => Order_StateModel::ORDER_PAYED);
            $edit_row['payment_time'] = get_date_time();
            $edit_row['payment_other_number'] = $uorder_id;
            //修改订单状态
            $this->editBase($order_id, $edit_row);

            //修改订单商品状态
            $Order_GoodsModel = new Order_GoodsModel();
            $edit_goods_row = array('order_goods_status' => Order_StateModel::ORDER_PAYED);
            $order_goods_id = $Order_GoodsModel->getKeyByWhere(array('order_id' => $order_id));
            $flag = $Order_GoodsModel->editGoods($order_goods_id, $edit_goods_row);

            //修改商品的销量
            $Goods_BaseModel = new Goods_BaseModel();
            $Goods_BaseModel->editGoodsSale($order_goods_id);

            $Goods_CommonModel = new Goods_CommonModel();

            //判断是否是服务订单，若是服务订单则生成发送信息并修改订单状态为已发货
            if ($order_base['order_is_virtual'] == Order_BaseModel::ORDER_IS_VIRTUAL) {
                //循环订单商品
                $Text_Password = new Text_Password();
                $Order_GoodsVirtualCodeModel = new Order_GoodsVirtualCodeModel();

                $msg_str = '尊敬的用户您已在' . $order_base['shop_name'] . '成功购买';
                $goods_name_str = '';
                $virtual_code_str = '';
                foreach ($order_goods_id as $k => $v) {
                    $order_goods_base = $Order_GoodsModel->getOne($v);
                    //判断该商品是否是服务商品
                    $goods_common_base = $Goods_CommonModel->getOne($order_goods_base['common_id']);
                    if($goods_common_base['common_is_virtual'])
                    {
                        $num = $order_goods_base['order_goods_num'];
                        //获取购买的数量，循环生成服务兑换码，将信息插入服务兑换码表中

                        for ($i = 0; $i < $num; $i++) {
                            $virtual_code = $Text_Password->create(8, 'unpronounceable', 'numeric');
                            $virtual_code_str .= $virtual_code . ',';
                            $add_row = array();
                            $add_row['virtual_code_id'] = $virtual_code;
                            $add_row['order_id'] = $order_id;
                            $add_row['order_goods_id'] = $v;
                            $add_row['virtual_code_status'] = Order_GoodsVirtualCodeModel::VIRTUAL_CODE_NEW;

                            $Order_GoodsVirtualCodeModel->addCode($add_row);
                        }
                        $goods_name_str .= $order_goods_base['goods_name'] . '，';


                        //修改订单商品为已发货待收货4
                        $edit_order_goods_row['order_goods_status'] = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
                        $Order_GoodsModel->editGoods($v, $edit_order_goods_row);
                    }
                }

                $msg_str = $msg_str . $goods_name_str . '您可凭兑换码' . $virtual_code_str . '在本店进行消费。';
                Sms::send($order_base['order_receiver_contact'], $msg_str);
                //$str = Sms::send(13918675918,"尊敬的用户您已在【变量】成功购买【变量】，您可凭兑换码【变量】在本店进行消费。");

                $goods_common_id = array_column($Order_GoodsModel->get($order_goods_id),'common_id');
                $Goods_Common = new Goods_CommonModel();
                $goods_common = current($Goods_Common->get($goods_common_id));
                //修改订单状态为已发货等待收货4
                $edit_order_row['order_status'] = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
                $edit_order_row['order_shipping_time'] = get_date_time();
                $edit_order_row['order_receiver_date'] = $goods_common['common_virtual_date'];
                $this->editBase($order_id, $edit_order_row);
            }

            //判断是否是门店自提订单，若是门店自提订单则生成发送信息并修改订单状态为待自提
            if ($order_base['chain_id']) {
                $code     = VerifyCode::getCode($order_base['order_receiver_contact']);

                $Chain_BaseModel=new Chain_BaseModel();
                $chain_base = current($Chain_BaseModel->getByWhere(array('chain_id'=>$order_base['chain_id'])));

                $order_goods_base = $Order_GoodsModel->getOne($order_goods_id[0]);

                $Order_GoodsChainCodeModel = new Order_GoodsChainCodeModel();
                $code_data['order_id']=$order_id;
                $code_data['chain_id']=$order_base['chain_id'];
                $code_data['order_goods_id']=$order_goods_id[0];
                $code_data['chain_code_id']=$code;
                $Order_GoodsChainCodeModel->addGoodsChainCode($code_data);

                //修改订单状态为待自提11
                $edit_order_goods_row['order_goods_status'] = Order_StateModel::ORDER_SELF_PICKUP;
                $Order_GoodsModel->editGoods($order_goods_id, $edit_order_goods_row);

                $edit_order_row['order_status'] = Order_StateModel::ORDER_SELF_PICKUP;
                $this->editBase($order_id, $edit_order_row);

                $message = new MessageModel();
                $message->sendMessage('Self pick up code', Perm::$userId, Perm::$row['user_account'], $order_id = NULL, $shop_name = $order_base['shop_name'], 1, MessageModel::ORDER_MESSAGE,  NUll,NULL,NULL,NULL, Null,$goods_name=$order_goods_base['goods_name'], NULL,NULL,$ztm=$code,$chain_name=$chain_base['chain_name'],$order_phone=$order_base['order_receiver_contact']);
                //$str = Sms::send(13918675918,"尊敬的用户您已在[shop_name]成功购买[goods_name]，您可凭自提码[ztm]在[chain_name]自提。");
            }


            //判断此订单是否使用了代金券，如果使用，则改变代金券的使用状态
            /*if ($order_base['voucher_id']) {
                $Voucher_BaseModel = new Voucher_BaseModel();
                $Voucher_BaseModel->changeVoucherState($order_base['voucher_id'], $order_base['order_id']);

                //代金券使用提醒
                $message = new MessageModel();
                $message->sendMessage('The use of vouchers to remind', Perm::$userId, Perm::$row['user_account'], $order_id = NULL, $shop_name = NULL, 0, MessageModel::USER_MESSAGE);
            }*/
        }
        Yf_Log::log('editOrderStatusAferPay 0', Yf_Log::LOG, 'debug');
        return $flag;
    }

    //取消订单
    public function cancelOrder($order_id)
    {
        $condition['order_status'] = Order_StateModel::ORDER_CANCEL;
        $condition['order_cancel_reason'] = '支付超时自动取消';
        $condition['order_cancel_identity'] = Order_BaseModel::IS_ADMIN_CANCEL;
        $condition['order_cancel_date'] = get_date_time();

        $this->editBase($order_id, $condition);
        $order_base=current($this->getByWhere(array('order_id'=>$order_id)));

        //修改订单商品表中的订单状态
        $Order_GoodsModel = new Order_GoodsModel();
        $edit_row['order_goods_status'] = Order_StateModel::ORDER_CANCEL;
        $order_goods_id = $Order_GoodsModel->getKeyByWhere(array('order_id' => $order_id));

        $Order_GoodsModel->editGoods($order_goods_id, $edit_row);

        //退还订单商品的库存
        if($order_base['chain_id']!=0){
            $Chain_GoodsModel = new Chain_GoodsModel();
            $chain_row['chain_id:='] = $order_base['chain_id'];
            $chain_row['goods_id:='] = is_array($order_goods_id)?$order_goods_id[0]:$order_goods_id;
            $chain_row['shop_id:='] = $order_base['shop_id'];
            $chain_goods = current($Chain_GoodsModel->getByWhere($chain_row));
            $chain_goods_id = $chain_goods['chain_goods_id'];
            $goods_stock['goods_stock'] = $chain_goods['goods_stock'] + 1;
            $Chain_GoodsModel->editGoods($chain_goods_id, $goods_stock);
        }else{
            //$Goods_BaseModel = new Goods_BaseModel();
            //$Goods_BaseModel->returnGoodsStock($order_goods_id);
        }

        //远程关闭paycenter中的订单状态
        $key = Yf_Registry::get('paycenter_api_key');
        $url = Yf_Registry::get('paycenter_api_url');
        $paycenter_app_id = Yf_Registry::get('paycenter_app_id');
        $formvars = array();

        $formvars['order_id'] = $order_id;
        $formvars['app_id'] = $paycenter_app_id;

        fb($formvars);

        $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=cancelOrder&typ=json', $url), $formvars);
        fb($rs);
    }

    //确认收货
    public function confirmOrder($order_id)
    {
        $Order_GoodsModel = new Order_GoodsModel();
        $order_base = $this->getOne($order_id);
        $order_payment_amount = $order_base['order_payment_amount'];

        //查询是否有未完成的退款/退货订单
        $Order_ReturnModel = new Order_ReturnModel();
        $return_ids = $Order_ReturnModel->getKeyByWhere(['order_number'=>$order_id,
            'return_state:not in'=>[Order_ReturnModel::RETURN_PLAT_PASS, Order_ReturnModel::RETURN_PLAT_UNPASS]]);
        if(count($return_ids) > 0){
            foreach ($return_ids as $return_id){
                $return = $Order_ReturnModel->getOne($return_id);
                if($return['return_type'] == Order_ReturnModel::RETURN_TYPE_ORDER){
                    //如果退款，商家还未处理，或者商家拒绝。则相当于平台拒绝。否则即已同意
                    $no_reply_return = [Order_ReturnModel::RETURN_WAIT_PASS, Order_ReturnModel::RETURN_SELLER_UNPASS];
                    if(in_array($return['return_state'], $no_reply_return)){
                        $return_platform_message = "拒绝退款申请";
                        $rs = $this->refuse($return_id, $return,$return_platform_message);
                    }else{
                        $return_platform_message = "同意";
                        $rs = $this->agree($return_id, $return,$return_platform_message);
                    }
                }else{
                    //如果退货，卖家未退回货之前。则相当于平台拒绝。其他默认同意
                    $no_reply_return = [Order_ReturnModel::RETURN_WAIT_PASS,
                        Order_ReturnModel::RETURN_SELLER_PASS,
                        Order_ReturnModel::RETURN_SELLER_UNPASS];
                    if(in_array($return['return_state'], $no_reply_return)){
                        $return_platform_message = "拒绝退货申请";
                        $rs = $this->refuse($return_id, $return,$return_platform_message);
                    }else{
                        $return_platform_message = "同意";
                        $rs = $this->agree($return_id, $return,$return_platform_message);
                    }
                }
            }
        }

        $order_goods_data = $Order_GoodsModel->getByWhere(array('order_id' => $order_id));
        $order_directseller_commission = array_sum(array_column($order_goods_data, 'directseller_commission_0')) + array_sum(array_column($order_goods_data, 'directseller_commission_1')) + array_sum(array_column($order_goods_data, 'directseller_commission_2'));
        $condition['order_directseller_commission'] = $order_directseller_commission;

        //计算合伙人提成，高级合伙人提成
        $order_amount = $order_base['order_payment_amount']*1 - $order_base['order_refund_amount']*1;
        $User_InfoModel = new User_InfoModel();
        $user_g_info = $User_InfoModel->getOne($order_base['directseller_p_id']);
        $before_order_total_amount = $user_g_info['children_order_total_amount'] * 1;
        $total_children_order_total_amount = $before_order_total_amount + $order_amount;

        //获取合伙人的返利比例
        $User_GradeModel = new User_GradeModel();
        $user_grade = $User_GradeModel->getOne(3);
        $grade_order_amount = $user_grade['order_amount'] * 1;
        $grade_order_rebate1 = $user_grade['order_rebate1'] * 1 / 100;
        $grade_order_rebate2 = $user_grade['order_rebate2'] * 1 / 100;

        //不管超指标还是未超指标，都需要：当前金额*rebate1
        $rebate_value1 = $order_amount * $grade_order_rebate1;
        $rebate_value2 = 0;
        //如果上次累计订单总金额已超指标，则超过指标部分提成为:该次金额*rebate2
        if ($before_order_total_amount * 1 >= $grade_order_amount) {
            $rebate_value2 = $order_amount * $grade_order_rebate2;
        } else if ($total_children_order_total_amount > $grade_order_amount) {
            //如果该次累计总金额超过指标，则超过指标部分提成为:（累计总金额-指标部分）*rebate2
            $order_amount2 = $total_children_order_total_amount - $grade_order_amount;
            if ($order_amount2 > 0) {
                $rebate_value2 = $order_amount2 * $grade_order_rebate2;
            } else {
                $rebate_value2 = 0;
            }
        } else if ($total_children_order_total_amount <= $grade_order_amount) {
            //如果该次累计总金额未超过指标，则超过指标部分提成为:0
            $rebate_value2 = 0;
        }
        $order_rebate_value2 = $rebate_value1 + $rebate_value2;
        $condition['order_directseller_commission2'] = $order_rebate_value2;

        $user_gp_info = $User_InfoModel->getOne($order_base['directseller_gp_id']);
        $user_g_grade = $User_GradeModel->getOne(4);
        //计算高级合伙人的提成比例
        $grade_order_rebate1 = $user_g_grade['order_rebate1'] * 1/100;
        $grade_order_rebate2 = $user_g_grade['order_rebate2'] * 1/100;
        $grade_order_rebate_top = $user_g_grade['order_rebate_top'] * 1/100;
        $partner_count = $user_gp_info['current_year_partner_count'];
        $order_rebate = $grade_order_rebate1 * 1 + $grade_order_rebate2 * $partner_count;
        if ($order_rebate > $grade_order_rebate_top) {
            $order_rebate = $grade_order_rebate_top;
        }
        $order_rebate_value3 = $order_amount * $order_rebate;
        $condition['order_directseller_commission3'] = $order_rebate_value3;

        $condition['order_status'] = Order_StateModel::ORDER_FINISH;
        $condition['order_finished_time'] = get_date_time();
        $this->editBase($order_id, $condition);

        $Order_GoodsModel = new Order_GoodsModel();
        $order_goods_data = $Order_GoodsModel->getByWhere(array('order_id'=>$order_id));
        if(Web_ConfigModel::value('Plugin_Directseller'))
        {
            //确认收货以后将总佣金写入商品订单表
            $order_directseller_commission = array_sum(array_column($order_goods_data,'directseller_commission_0')) + array_sum(array_column($order_goods_data,'directseller_commission_1')) + array_sum(array_column($order_goods_data,'directseller_commission_2'));
            $condition['order_directseller_commission'] = $order_directseller_commission;
            //END
        }

        //修改订单商品表中的订单状态
        $edit_row['order_goods_status'] = Order_StateModel::ORDER_FINISH;
        $order_goods_id = $Order_GoodsModel->getKeyByWhere(array('order_id' => $order_id));
        $Order_GoodsModel->editGoods($order_goods_id, $edit_row);

        //远程修改paycenter中的订单状态
        $key      = Yf_Registry::get('shop_api_key');
        $url         = Yf_Registry::get('paycenter_api_url');
        $shop_app_id = Yf_Registry::get('shop_app_id');
        $formvars = array();

        $formvars['order_id']    = $order_id;
        $formvars['app_id']        = $shop_app_id;

        fb($formvars);

        $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=confirmOrder&typ=json', $url), $formvars);

        //添加到个人仓库
        $User_Stock_Model = new User_StockModel();
        $User_Stock_Model->editStockFromOrder($order_goods_data, $order_base['buyer_user_id'], $order_base['buyer_user_name']);

        $this->confirmOrder_user_log($order_payment_amount, $order_base['buyer_user_id'], $order_base['buyer_user_name']);
    }

    private function agree($order_return_id, $return, $return_platform_message)
    {
        $Order_ReturnModel =  new Order_ReturnModel();
        $Order_BaseModel         = new Order_BaseModel();
        $Order_GoodsModel        = new Order_GoodsModel();

        //判断平台是否已经审核过
        if($return['return_state'] < Order_ReturnModel::RETURN_PLAT_PASS || $return['return_state'] == Order_ReturnModel::RETURN_GOODS) {
            //判断商家是否同意退款，如果商家不同意，且是退货，则强制修改卖家状态为已同意；
            //其他就按平台已同意相当于商家已同意来处理
            if ($return['return_type'] == Order_ReturnModel::RETURN_TYPE_GOODS
                && $return['return_state'] == Order_ReturnModel::RETURN_SELLER_UNPASS) {
                //平台同意->将卖家强制改为同意
                $data = array();
                $data['return_platform_message'] = $return_platform_message;
                $data['return_state'] = Order_ReturnModel::RETURN_SELLER_PASS;
                //$data['return_finish_time']      = get_date_time();
                $rs_row = array();

                $edit_flag = $Order_ReturnModel->editReturn($order_return_id, $data);
                check_rs($edit_flag, $rs_row);

            } else {
                //同意
                $data = array();
                if ($return_platform_message) {
                    $data['return_platform_message'] = $return_platform_message;
                }
                $data['return_state'] = Order_ReturnModel::RETURN_PLAT_PASS;
                $data['return_finish_time'] = get_date_time();
                $rs_row = array();

                $edit_flag = $Order_ReturnModel->editReturn($order_return_id, $data);
                check_rs($edit_flag, $rs_row);

                //根据order_id查找订单信息
                $order_base = $Order_BaseModel->getOne($return['order_number']);
                $data['return_goods_return'] = $return['return_goods_return'];

//                if ($return['return_goods_return']) {
//                    $Shop_BaseModel = new Shop_BaseModel();
//                    $shop_detail = $Shop_BaseModel->getOne($order_base['shop_id']);
//                    if ($shop_detail['shop_type'] == 2) {//供应商店铺
//                        $flag = $this->edit_product($return['order_number'], $return['order_goods_num']);
//
//                        $data['edit_product'] = $flag;
//                    }
//                }

                //如果是分销商的进货单则同时退掉买家订单
//                fb($order_base);
//                if ($order_base['order_source_id']) {
//                    $dist_return = $Order_ReturnModel->getOneByWhere(array('order_number' => $order_base['order_source_id'], 'return_type' => $return['return_type']));
//                    $this->agreeDist($dist_return['order_return_id'], $data);
//                }

                if ($return['return_goods_return']) {
                    //商品退换情况为完成2
                    $goods_data['goods_refund_status'] = Order_GoodsModel::REFUND_COM;
                    $edit_flag = $Order_GoodsModel->editGoods($return['order_goods_id'], $goods_data);
                    check_rs($edit_flag, $rs_row);
                } else {
                    $goods_data['goods_return_status'] = Order_GoodsModel::REFUND_COM;
                    $edit_flag = $Order_GoodsModel->editGoods($return['order_goods_id'], $goods_data);
                    check_rs($edit_flag, $rs_row);
                }
                $ogoods_data = array();
                $ogoods_data['order_goods_returnnum'] = $return['order_goods_num'];
                $edit_flag = $Order_GoodsModel->editGoods($return['order_goods_id'], $ogoods_data, true);
                check_rs($edit_flag, $rs_row);

                //如果是退货，则恢复商品的库存
                if ($return['return_type'] == Order_ReturnModel::RETURN_TYPE_GOODS) {
                    $add_stock_num = $return['order_goods_num'];
                    $order_goods = $Order_GoodsModel->getOne($return['order_goods_id']);
                    $goods_id = $order_goods['goods_id'];
                    if ($return['seller_user_id'] == Web_ConfigModel::value('self_shop_id')) {
                        $Goods_CommonModel = new Goods_CommonModel();
                        $Goods_BaseModel = new Goods_BaseModel();
                        $goods_base = $Goods_BaseModel->getOne($goods_id);
                        $edit_flag11 = $Goods_CommonModel->editCommon($goods_base['common_id'], ['common_stock' => $add_stock_num], true);
                        $edit_flag12 = $Goods_BaseModel->editBase($goods_id, ['goods_stock' => $add_stock_num], true);
                        check_rs($edit_flag11, $rs_row);
                        check_rs($edit_flag12, $rs_row);
                    } else {
                        $User_StockModel = new User_StockModel();
                        $user_stock = $User_StockModel->getOneByWhere(['user_id' => $order_base['seller_user_id'], 'goods_id' => $goods_id]);
                        $edit_flag12 = $User_StockModel->editUserStock($user_stock['stock_id'], ['goods_stock' => $add_stock_num], true);
                        check_rs($edit_flag12, $rs_row);
                    }
                }

                //判断商品金额是否全都退还，如果全部退还订单状态修改为完成状态(用订单商品数判断)
                //订单中所有商品数量
//                $order_goods = $Order_GoodsModel->getByWhere(array('order_id' => $return['order_number'], 'order_goods_amount:>' => 0));
//                $order_all_goods_num = array_sum(array_column($order_goods, 'order_goods_num'));
//
//                //查找该笔订单已经完成的退款，退货
//                $order_return = $Order_ReturnModel->getByWhere(array(
//                    'order_number' => $return['order_number'],
//                    'return_state' => Order_ReturnModel::RETURN_PLAT_PASS
//                ));
//                //订单已经退还的商品数量
//                $order_return_num = array_sum(array_column($order_return, 'order_goods_num'));
//
//                if ($order_all_goods_num == $order_return_num && $order_base['order_status'] !== Order_StateModel::ORDER_FINISH) {
//                    $order_edit_row = array();
//                    $order_edit_row['order_status'] = Order_StateModel::ORDER_FINISH;
//                    $condition['order_finished_time'] = get_date_time();
//
//                    $edit_flag2 = $Order_BaseModel->editBase($return['order_number'], $order_edit_row);
//                    check_rs($edit_flag2, $rs_row);
//                }

                //退款金额，退货数量，交易佣金退款更新到订单表中
                $order_edit = array();
                $order_edit['order_refund_amount'] = $return['return_cash'];
                $order_edit['order_return_num'] = $return['order_goods_num'];
                $order_edit['order_commission_return_fee'] = $return['return_commision_fee'];
                $order_edit['order_rpt_return'] = $return['return_rpt_cash'];

                $edit_flag = $Order_BaseModel->editBase($return['order_number'], $order_edit, true);
                check_rs($edit_flag, $rs_row);

                if ($edit_flag) {
                    //判断该笔订单是否是主账号支付，如果是主账号支付，则将退款金额退还主账号
                    if ($order_base['order_sub_pay'] == Order_StateModel::SUB_SELF_PAY) {
                        $return_user_id = $return['buyer_user_id'];
                        $return_user_name = $return['buyer_user_account'];
                    }
                    if ($order_base['order_sub_pay'] == Order_StateModel::SUB_USER_PAY) {
                        //查找主管账户用户名
                        $User_BaseModel = new  User_BaseModel();
                        $sub_user_base = $User_BaseModel->getOne($order_base['order_sub_user']);

                        $return_user_id = $order_base['order_sub_user'];
                        $return_user_name = $sub_user_base['user_account'];
                    }

                    $key = Yf_Registry::get('shop_api_key');
                    $url = Yf_Registry::get('paycenter_api_url');
                    $shop_app_id = Yf_Registry::get('shop_app_id');

                    $formvars = array();
                    $formvars['app_id'] = $shop_app_id;
                    $formvars['user_id'] = $return_user_id;
                    $formvars['user_account'] = $return_user_name;
                    $formvars['seller_id'] = $order_base['seller_user_id'];
                    $formvars['seller_account'] = $order_base['seller_user_name'];
                    $formvars['amount'] = $return['return_cash'];
                    $formvars['return_commision_fee'] = $return['return_commision_fee'];
                    $formvars['order_id'] = $return['order_number'];
                    $formvars['goods_id'] = $return['order_goods_id'];
                    $formvars['payment_id'] = $order_base['payment_id'];

                    //SP分销单没有payment_other_number这个字段值会报错，所以在此做判断
                    if ($order_base['payment_other_number']) {
                        $formvars['uorder_id'] = $order_base['payment_other_number'];
                    } else {
                        $formvars['uorder_id'] = $order_base['payment_number'];
                    }

                    //平台同意退款（只增加买家的流水）
                    $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=refundBuyerTransfer&typ=json', $url), $formvars);
                    $data['for'] = $formvars;
                    if ($rs['status'] == 200) {
                        check_rs(true, $rs_row);
                    } else {
                        check_rs(false, $rs_row);
                    }
                    $edit_flag = is_ok($rs_row);
                }
            }


            $data['rs'] = $rs_row;

            $flag = is_ok($rs_row);
        }
        else
        {
            $flag = false;
        }

        if ($flag)
        {
            /**
             *  加入统计中心
             */
            //如果$return['order_goods_id']为0则为退款
            if($return['return_goods_return'])
            {
                $order_goods_data = $Order_GoodsModel->getOne($return['order_goods_id']);
                $order_return_goods_id = $order_goods_data['goods_id'];
                $order_goods_num = $return['order_goods_num'];
            }
            else
            {
                $order_goods_data = $Order_GoodsModel->getGoodsListByOrderId($return['order_number']);
                if(count($order_goods_data['items']) == 1)
                {
                    $order_return_goods_id = $order_goods_data['items'][0]['goods_id'];
                }
                else
                {
                    $order_return_goods_id = 0;
                }
                $order_goods_num = $order_goods_data['items'][0]['order_goods_num'];
            }

            $analytics_data = array(
                'order_id'=>array($return['order_number']),
                'return_cash'=>$return['return_cash'],
                'order_goods_num'=>$order_goods_num,
                'order_goods_id'=>$order_return_goods_id,
                'status'=>9	//暂时将退款退货统一处理
            );
            Yf_Plugin_Manager::getInstance()->trigger('analyticsUpdateOrderStatus',$analytics_data);
            /******************************************************************/
        }
    }

    private function refuse($order_return_id, $return, $return_platform_message)
    {
        $Order_GoodsModel = new Order_GoodsModel();
        $Order_ReturnModel = new Order_ReturnModel();

        $data['return_platform_message'] = $return_platform_message;
        $data['return_state']            = Order_ReturnModel::RETURN_PLAT_UNPASS;
        $data['return_finish_time']      = get_date_time();
        $rs_row                          = array();
        $edit_flag = $Order_ReturnModel->editReturn($order_return_id, $data);
        check_rs($edit_flag, $rs_row);

        //不同意
        if ($return['return_goods_return'])
        {
            //商家拒绝退款退货3
            $goods_data['goods_refund_status'] = Order_GoodsModel::REFUND_REF;
            $edit_flag                         = $Order_GoodsModel->editGoods($return['order_goods_id'], $goods_data);
            check_rs($edit_flag, $rs_row);
        }
        else
        {
            $goods_data['goods_return_status'] = Order_GoodsModel::REFUND_REF;
            $edit_flag                         = $Order_GoodsModel->editGoods($return['order_goods_id'], $goods_data);
            check_rs($edit_flag, $rs_row);
        }

        $data['rs'] = $rs_row;
        if ($edit_flag)
        {
            /**
             *  加入统计中心
             */
            //如果$return['order_goods_id']为0则为退款
            if($return['order_goods_id'])
            {
                $order_goods_data = $Order_GoodsModel->getOne($return['order_goods_id']);
                $order_return_goods_id = $order_goods_data['goods_id'];
                $order_goods_num = $return['order_goods_num'];
            }
            else
            {
                $order_goods_data = $Order_GoodsModel->getGoodsListByOrderId($return['order_number']);
                if(count($order_goods_data['items']) == 1)
                {
                    $order_return_goods_id = $order_goods_data['items'][0]['goods_id'];
                }
                else
                {
                    $order_return_goods_id = 0;
                }
                $order_goods_num = $order_goods_data['items'][0]['order_goods_num'];
            }

            $analytics_data = array(
                'order_id'=>array($return['order_number']),
                'return_cash'=>$return['return_cash'],
                'order_goods_num'=>$order_goods_num,
                'order_goods_id'=>$order_return_goods_id,
                'status'=>9	//暂时将退款退货统一处理
            );
            Yf_Plugin_Manager::getInstance()->trigger('analyticsUpdateOrderStatus',$analytics_data);
            /******************************************************************/
        }
    }

    /**
    *  confirm_order经验与成长值
    */
    public function confirmOrder_user_log($order_payment_amount, $buyer_user_id, $buyer_user_name)
    {
        $user_points = Web_ConfigModel::value("points_recharge");//订单每多少获取多少积分
        $user_points_amount = Web_ConfigModel::value("points_order");//订单每多少获取多少积分

        if ($order_payment_amount / $user_points < $user_points_amount) {
            $user_points = floor($order_payment_amount / $user_points);
        } else {
            $user_points = $user_points_amount;
        }

        $user_grade = Web_ConfigModel::value("grade_recharge");//订单每多少获取多少积分
        $user_grade_amount = Web_ConfigModel::value("grade_order");//订单每多少获取多少成长值

        if ($order_payment_amount / $user_grade < $user_grade_amount)
        {
            $user_grade = floor($order_payment_amount / $user_grade);
        }
        else
        {
            $user_grade = $user_grade_amount;
        }

        $User_ResourceModel = new User_ResourceModel();
        //获取积分经验值
        $ce = $User_ResourceModel->getResource($buyer_user_id);

        $resource_row['user_points'] = $ce[$buyer_user_id]['user_points'] * 1 + $user_points * 1;
        $resource_row['user_growth'] = $ce[$buyer_user_id]['user_growth'] * 1 + $user_grade * 1;

        $res_flag = $User_ResourceModel->editResource($buyer_user_id, $resource_row);

        $User_GradeModel = new User_GradeModel();
        //升级判断
        $res_flag = $User_GradeModel->upGrade($buyer_user_id, $resource_row['user_growth']);

        //积分
        $points_row['user_id'] = $buyer_user_id;
        $points_row['user_name'] = $buyer_user_name;
        $points_row['class_id'] = Points_LogModel::ONBUY;
        $points_row['points_log_points'] = $user_points;
        $points_row['points_log_time'] = get_date_time();
        $points_row['points_log_desc'] = '确认收货';
        $points_row['points_log_flag'] = 'confirmorder';

        $Points_LogModel = new Points_LogModel();

        $Points_LogModel->addLog($points_row);

        //成长值
        $grade_row['user_id'] = $buyer_user_id;
        $grade_row['user_name'] = $buyer_user_name;
        $grade_row['class_id'] = Grade_LogModel::ONBUY;
        $grade_row['grade_log_grade'] = $user_grade;
        $grade_row['grade_log_time'] = get_date_time();
        $grade_row['grade_log_desc'] = '确认收货';
        $grade_row['grade_log_flag'] = 'confirmorder';

        $Grade_LogModel = new Grade_LogModel();
        $Grade_LogModel->addLog($grade_row);
    }
	
	//获取推广订单数目
	function getPromotionOrderNum($cond_row)
	{
		return $this->getNum($cond_row);
	}

    //服务订单过期退货
    public function virtualReturn($order_id)
    {
        $Order_GoodsModel = new Order_GoodsModel();
        $Order_GoodsModel->sql->setWhere('order_id', $order_id);
        $goods_common_id = array_column($Order_GoodsModel->get('*'),'common_id');
        $Goods_Common = new Goods_CommonModel();
        $goods_common = current($Goods_Common->get($goods_common_id));
        if($goods_common['common_virtual_refund']){
            $Order_StateModel = new Order_StateModel();
            $flag2            = true;
            $Number_SeqModel  = new Number_SeqModel();

            $prefix           = sprintf('%s-', date('YmdHis'));
            $return_number    = $Number_SeqModel->createSeq($prefix);
            $return_id        = sprintf('%s-%s', 'TD', $return_number);

            $field['return_message']       = __('服务商品过期自动退款');
            $field['return_code']          = $return_id;
            $field['return_reason_id']     = 0;
            $field['return_reason']        = "";
            $field['order_number']         = $order_id;
            $this->orderBaseModel         = new Order_BaseModel();
            $order                         = $this->orderBaseModel->getOne($order_id);
            $field['return_type']          = Order_ReturnModel::RETURN_TYPE_VIRTUAL;
            $field['return_goods_return']  = 0;
            $field['return_cash']          = $order['order_payment_amount'];
            $field['order_amount']         = $order['order_payment_amount'];
            $field['seller_user_id']       = $order['shop_id'];
            $field['seller_user_account']  = $order['shop_name'];
            $field['buyer_user_id']        = $order['buyer_user_id'];
            $field['buyer_user_account']   = $order['buyer_user_name'];
            $field['return_add_time']      = get_date_time();
            $field['return_commision_fee'] = $order['order_commission_fee'];
            $field['return_state']         = Order_ReturnModel::RETURN_PLAT_PASS;
            $field['return_finish_time']   = get_date_time();

            $rs_row = array();
            $this->orderReturnModel = new Order_ReturnModel();
            $this->orderReturnModel->sql->startTransactionDb();

            $add_flag = $this->orderReturnModel->addReturn($field, true);
            check_rs($add_flag, $rs_row);

            $order_field['order_refund_status'] = Order_BaseModel::REFUND_IN;
            $order_field['order_refund_status'] = Order_BaseModel::REFUND_COM;
            $edit_flag                          = $this->orderBaseModel->editBase($order_id, $order_field);
            check_rs($edit_flag, $rs_row);

            $sum_data['order_refund_amount']         = $order['order_payment_amount'];
            $sum_data['order_commission_return_fee'] = $order['order_commission_fee'];
            $edit_flag                               = $this->orderBaseModel->editBase($order_id, $sum_data, true);
            check_rs($edit_flag, $rs_row);

            $key      = Yf_Registry::get('shop_api_key');
            $url         = Yf_Registry::get('paycenter_api_url');
            $shop_app_id = Yf_Registry::get('shop_app_id');

            $formvars             = array();
            $formvars['app_id']        = $shop_app_id;
            $formvars['user_id']  = $order['buyer_user_id'];
            $formvars['user_account'] = $order['buyer_user_name'];
            $formvars['seller_id'] = $order['seller_user_id'];
            $formvars['seller_account'] = $order['seller_user_name'];
            $formvars['amount']   = $order['order_payment_amount'];
            $formvars['order_id'] = $order_id;
            //$formvars['goods_id'] = $return['order_goods_id'];
            $formvars['uorder_id'] = $order['payment_number'];


            $rs                   = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=refundTransfer&typ=json', $url), $formvars);

            if ($rs['status'] == 200)
            {
                check_rs(true, $rs_row);
            }
            else
            {
                check_rs(false, $rs_row);
            }

            $flag = is_ok($rs_row);
            if ($flag && $this->orderReturnModel->sql->commitDb())
            {
                return true;
            }
            else
            {
                $this->orderReturnModel->sql->rollBackDb();
                return false;
            }
        }

    }


    public function getSubQuantity($cond_row)
    {
        return $this->getNum($cond_row);
    }

    /**
     * 按照订单编号或者订单商品名称搜索
     * @param $search_str string
     * @param $condition array 其他搜索条件
     * @return array $order_ids
     */
    public function searchNumOrGoodsName($search_str, $condition = [])
    {
        $orderGoodsModel = new Order_GoodsModel();

        $order_rows = $this->getByWhere($condition);
        $order_ids = array_keys($order_rows);

        $search_str = '%'.$search_str.'%';

        //订单号搜索
        $num_order_rows = $this->getByWhere(['order_id:LIKE'=> $search_str, 'order_id:IN'=> $order_ids]);
        $num_order_ids = array_keys($num_order_rows);

        //订单商品名称搜索
        $goods_rows = $orderGoodsModel->getByWhere(['goods_name:LIKE'=> $search_str, 'order_id:IN'=> $order_ids]);
        $g_order_ids = array_column($goods_rows, 'order_id');

        $order_ids = array_unique(array_merge($num_order_ids, $g_order_ids));

        return $order_ids;
    }

    /**
     * 根据条件获取对应订单的支付总金额
     * @param $cond_row
     * @return float|int
     */
    public function getSumOrderPaymentAmount($cond_row)
    {
        $order_list = $this->getByWhere($cond_row);
        $order_payment_amount = array_column($order_list, 'order_payment_amount');
        $order_refund_amount = array_column($order_list, 'order_refund_amount');

        $sum_amount = array_sum($order_payment_amount) - array_sum($order_refund_amount);

        return $sum_amount;
    }
}

?>