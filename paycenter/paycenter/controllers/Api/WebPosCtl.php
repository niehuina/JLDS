<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

class Api_WebPosCtl extends Yf_AppController
{
    public $unionOrderModel;
    public $request_parameter;
    public $notifyUrlConfig;
    public $unionOrderData;

    public static $orderConfig = [
        'trade_desc'=> 'WEBPOS',
        'app_id'=> 207
    ];

    public static $paymentMethodConfig = [
        'alipay'=> Payment_ChannelModel::ALIPAY,
        'wx'=> Payment_ChannelModel::WECHAT_PAY,
        'cash'=>Payment_ChannelModel::MONEY,

    ];

    public $require_field = [ //必要字段
        'createOrder'=> [
            'order_id',
            'trade_title',
            'amount',
            'payment_way'
        ],
        'getOrderInfo'=> [
            'order_id'
        ]
    ];

    function __construct($ctl, $met, $typ)
    {
        Yf_Log::log('支付失败1', Yf_Log::INFO, 'error_wx3');
        parent::__construct($ctl, $met, $typ);
        $this->verify();
        $this->unionOrderModel = new Union_OrderModel();
        $this->notifyUrlConfig = [
            'alipay'=> Yf_Registry::get('base_url') . "/paycenter/api/payment/alipay/webpos_notify_url.php",
            'wx'=> Yf_Registry::get('base_url') . "/paycenter/api/payment/wx/webpos_notify_url.php?trade_type=JSAPI"
        ];
    }

    //验证
    private function verify ()
    {
        $this->request_parameter = $_REQUEST;
        if (isset($this->require_field[$this->request_parameter['met']])) {
            foreach ($this->require_field[$this->request_parameter['met']] as $field) {
                if (! isset($this->request_parameter[$field]) || empty($this->request_parameter[$field])) {
                    $this->showError('无效请求');
                }
            }
        }
    }

    private function showError ($msg)
    {
        $error_json = json_encode(array('cmd_id' => -140, 'status' => 250, 'msg' => $msg, 'data' => []));
        exit($error_json);
    }

    /**
     * webPos生成支付订单
     * 注意：
     * 1.buyer_id可能为空，为空为线下会员，未在payCenter注册
     * 2.app_id不清楚为什么等于102，翻遍这个项目没有找到相关注释，暂定102
     * 3.目前webPos只有两种支付方式支付宝、微信！该订单只有在线支付union_online_pay_amount
     * 4.新增交易类型trade_type_id = Trade_TypeModel::WEB_POS = 9
     * 5.in_array(payment_channel_id, Payment_ChannelModel::ALIPAY, Payment_ChannelModel::WECHAT_PAY)
     *
     * 目前确认该业务只和pay_union_order发生关联
     */
    public function createOrder ()
    {
        $param = [
            'order_id'=> request_string('order_id'),
            'trade_title'=> request_string('trade_title'),
            'amount'=> request_string('amount'),
            'buyer_id'=> request_string('buyer_id'),
            'payment_way'=> request_string('payment_way')
        ];
        
        if ($this->getOrder($param['order_id']) === false) {
            return $this->showError('服务器异常，请稍后重试');
        }

        if (! empty($this->unionOrderData)) {
            //如果订单已经存在且完成支付，则禁止本次访问，保证单号唯一性
            if ($this->unionOrderData['order_state_id'] == Union_OrderModel::PAYED) {
                return $this->showError('该订单已支付，请勿重复创建');
            }

            //如果订单存在且未完成支付，则判断订单支付信息是否一样。如果不一样先更新订单
            if ( $param['amount'] != $this->unionOrderData['union_online_pay_amount'] ||
                self::$paymentMethodConfig[$param['payment_way']] != $this->unionOrderData['payment_channel_id']
            ) {
                if ($this->updateUnionOrder($this->unionOrderData['union_order_id'], $param) === false) {
                    return $this->showError('更新订单失败，请稍后重试');
                }
            }
        } else {
            //如果订单不存在则创建订单
            if ($this->createUnionOrder($param) === false) {
                return $this->showError('生成支付订单失败');
            }
        }

        if ($param['payment_way'] === 'alipay') {
            $this->aliPay();
        } else {
            $this->wxPay();
        }
    }

    /**
     * @param $order_id
     * @return array
     * 获取订单信息
     */
    private function getOrder ($order_id)
    {
        return $this->unionOrderData = $this->unionOrderModel->getOneByWhere([
            'inorder'=> $order_id,
            'trade_type_id'=> Trade_TypeModel::WEB_POS,
        ]);
    }

    /**
     * 更新订单信息
     * @param order_id
     * @param $param array
     * @return boolean
     */
    public function updateUnionOrder ($order_id, $param)
    {
        $payment_channel_id = self::$paymentMethodConfig[$param['payment_way']];

        $update_data = [
            'trade_title'=> $param['trade_title'],
            'create_time'=> date('Y-m-d H:i:s'),
            'buyer_id'=> $param['buyer_id'],
            'payment_channel_id'=> $payment_channel_id,
            'union_online_pay_amount'=> $param['amount']
        ];

        return $this->unionOrderModel->editUnionOrder($order_id, $update_data);
    }

    private function createUnionOrder ($param)
    {
        $payment_channel_id = self::$paymentMethodConfig[$param['payment_way']];

        $insert_row = [
            'union_order_id'=> Union_OrderModel::createUnionOrderId(),
            'inorder'=> $param['order_id'],
            'trade_title'=> $param['trade_title'],
            'trade_payment_amount'=> $param['amount'],
            'create_time'=> date('Y-m-d H:i:s'),
            'buyer_id'=> $param['buyer_id'],
            'trade_desc'=> self::$orderConfig['trade_desc'],
            'order_state_id'=> Union_OrderModel::WAIT_PAY,
            'payment_channel_id'=> $payment_channel_id,
            'app_id'=> self::$orderConfig['app_id'],
            'trade_type_id'=> Trade_TypeModel::WEB_POS,
            'union_online_pay_amount'=> $param['amount']
        ];

        $this->unionOrderData = $insert_row;
        return $this->unionOrderModel->addUnionOrder($insert_row);
    }

    private function aliPay ()
    {
        $payment = PaymentModel::create('alipay', [
            'notify_url'=> $this->notifyUrlConfig['alipay']
        ]);
        $payment->pay($this->unionOrderData);
    }

    private function wxPay ()
    {
        $payment = PaymentModel::create('wx_native', [
            'notify_url'=> $this->notifyUrlConfig['wx']
        ]);
        $payment->pay($this->unionOrderData);
    }

    public function getOrderInfo ()
    {
        $order_id = request_string('order_id');
        $data = $this->unionOrderModel->getOneByWhere([
            'inorder'=> $order_id,
            'trade_type_id'=> Trade_TypeModel::WEB_POS
        ]);

        if (empty($data)) {
            return $this->showError('未找到此订单');
        }
        $this->data->addBody(-140, $data, 'success', 200);
    }

    public function addUserRecord()
    {
        $deposit_amount = request_string('record_money');
        $pay_way=request_string('pay_way');
        $uorder=request_string('order_id');

        $Union_OrderModel = new Union_OrderModel();
        //开启事务
        $Union_OrderModel->sql->startTransactionDb();

        //生成合并支付订单
        //$uorder      = "U" . date("Ymdhis", time()) . rand(100, 999);  //18位

       // $trade_title = $uorder;
        $uprice      = $deposit_amount;
        $buyer       = request_string('user_id');
        $buyer_name = request_string('user_nickname');
        $payment_channel_id = self::$paymentMethodConfig[$pay_way];


        if ($this->getOrder($uorder) === false) {
            return $this->showError('服务器异常，请稍后重试');
        }
        $flag=true;
        if(empty($this->unionOrderData)){
            $add_row = array(
                'union_order_id'=>$uorder,
                'trade_title' => '门店充值',
                'trade_payment_amount' => $uprice,
                'create_time' => date("Y-m-d H:i:s"),
                'buyer_id' => $buyer,
                'order_state_id' =>($pay_way!="cash")? Union_OrderModel::WAIT_PAY:Union_OrderModel::FINISH,
                'union_online_pay_amount' => $uprice,
                'trade_type_id' => Trade_TypeModel::DEPOSIT,
                'app_id' => Yf_Registry::get('paycenter_app_id'),
                'payment_channel_id'=>$payment_channel_id,
            );
            $flag            = $Union_OrderModel->addUnionOrder($add_row);

        }else{
            $update_data = [
                'trade_title'=> '门店充值',
                'payment_way'=>$pay_way ,
                'amount'=> $uprice,
                'buyer_id' => $buyer,
            ];
            if ($this->updateUnionOrder($this->unionOrderData['union_order_id'], $update_data) === false) {
                return $this->showError('更新订单失败，请稍后重试');
            }
        }
//        $add_row['union_order_id']=$uorder;
//        $flag            = $Union_OrderModel->addUnionOrder($add_row);
        //添加充值表
        $Consume_DepositModel = new Consume_DepositModel();
        $add_deposit_row = array();
        $add_deposit_row['deposit_trade_no'] = $uorder;
        $add_deposit_row['deposit_buyer_id'] = $buyer;
        $add_deposit_row['deposit_total_fee'] = $deposit_amount;
        $add_deposit_row['deposit_gmt_create'] = date('Y-m-d H:i:s');
        $add_deposit_row['deposit_trade_status'] = RecordStatusModel::RECORD_FINISH;
        $Consume_DepositModel->addDeposit($add_deposit_row);

        $para                  = array();
        $para['user_id']=request_string('user_id');//cookie('users_id');
        $para['user_nickname']=request_string('user_nickname');//cookie('home_nickname');
        $para['record_money']=$deposit_amount;
        $para['record_date']=date("Y-m-d H:i:s");

        $para['record_year']=date('Y');
        $para['record_month']=date('m');
        $para['record_day']=date('d');
        $para['record_title']='门店充值';
        $para['record_desc']='';
        $para['record_time']=date("Y-m-d H:i:s");
        $para['trade_type_id']=Trade_TypeModel::DEPOSIT;//充值为3，提现为4，购物为1，付款为7
        $para['user_type']=1;
        $para['record_status']=RecordStatusModel::RECORD_FINISH;
        $para['record_payorder']='';
        $para['record_paytime']='';
        $para['record_delete']='0';
        $para['order_id']='';
        $Consume_RecordModel = new Consume_RecordModel();
        $data = array();
        $last_id = $Consume_RecordModel->addRecord($para,true);
        $data['id'] = $last_id;

        if ($flag && $Union_OrderModel->sql->commitDb())
        {
            $msg    = 'success';
            $status = 200;
        }
        else
        {
            $Union_OrderModel->sql->rollBackDb();
            $m      = $Union_OrderModel->msg->getMessages();
            $msg    = $m ? $m[0] : _('failure');
            $status = 250;
        }
        $this->data->addBody(-1, $data, $msg, $status);
    }

    public function UpdateAccountBalance()
    {
        $user_id=request_string('user_id');
        $amount=request_float('record_money');

        $User_ResourceModel = new User_ResourceModel();

        $data = array();
        //用户资源中账户余额增加
        $update_flag= $User_ResourceModel->editResource($user_id,array('user_money'=>$amount),true);

        if ($update_flag){
            $msg    = $amount;
            $status = 200;
        }
        else{
            $msg    = 'failure';
            $status = 250;
        }
        $this->data->addBody(-140, $data,$msg,$status);
    }

    public  function  adduser(){
        $userBaseModel = new User_BaseModel();
        $userBaseModel->sql->startTransactionDb();
        $data = array();
        $user_id=request_string('user_id');
        $user_name=request_string('user_name');
        $realname=request_string('user_realname');
        $phone=request_string('user_phone');
		$passwd=request_string('passwd');

        $data['user_id']      = $user_id; // 用户id
        $data['user_account'] = $user_name; // 用户帐号ucenter_name

        $data['user_delete'] = 0; // 用户状态
        $data['user_number']='';
        $data['user_passwd']=$passwd;
        $data['user_pay_passwd']=$passwd;
        $data['user_key']='';
        $data['user_login_times']='';
        $data['user_login_time']='';
        $data['user_number']='';
        $flag             = $userBaseModel->addBase($data, true);
        $data['id'] = $user_id;

        //初始化用户信息
        if($flag){
            $add_user_info                  = array();
            $add_user_info['user_id']       = $user_id;
            $add_user_info['user_nickname'] = $user_name;
            $add_user_info['user_active_time'] = date('Y-m-d H:i:s');
            $add_user_info['user_realname'] = $realname;
            $add_user_info['user_mobile']   = $phone;
            $add_user_info['user_qq']       = '';
            $add_user_info['user_avatar']   = '';
            $add_user_info['user_identity_card'] = '';


            $User_InfoModel                 = new User_InfoModel();
            $info_flag                      = $User_InfoModel->addInfo($add_user_info);

            $user_resource_row                = array();
            $user_resource_row['user_id']     = $user_id;

            $User_ResourceModel = new User_ResourceModel();
            $res_flag           = $User_ResourceModel->addResource($user_resource_row);
        }
        if ($flag && $userBaseModel->sql->commitDb())
        {
            $msg    = 'success';
            $status = 200;
        }
        else
        {
            $userBaseModel->sql->rollBackDb();
            $m      = $userBaseModel->msg->getMessages();
            $msg    = $m ? $m[0] : _('failure');
            $status = 250;
        }
        $this->data->addBody(-1, $data, $msg, $status);
    }

    public function getUserInfo ()
    {
        $user_id = request_string('user_id');
        $UserBaseModel = new User_BaseModel();
        $data = $UserBaseModel->getOne($user_id);

        if (empty($data)) {
            $msg    = 'failure';
            $status = 250;
            return $this->showError('未找到此用户');
        }else{
            $msg    = 'success';
            $status = 200;
        }
        $this->data->addBody(-140, $data, $msg, $status);
    }
}