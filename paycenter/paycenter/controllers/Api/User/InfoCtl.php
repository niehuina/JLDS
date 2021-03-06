<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_User_InfoCtl extends Api_Controller
{
	public $userInfoModel     = null;
	public $userBaseModel     = null;

	/**
	 * Constructor
	 *
	 * @param  string $ctl 控制器目录
	 * @param  string $met 控制器方法
	 * @param  string $typ 返回数据类型
	 * @access public
	 */
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
		
		$this->userInfoModel     = new User_InfoModel();
		$this->userBaseModel     = new User_BaseModel();

	}

	/**
	 * 修改会员信息
	 *
	 * @access public
	 */
	public function editUserInfo()
	{
		$user_id = request_int('user_id');

		/*
		'app_id' => '105',
		'rtime' => 1471925935,
		'user_area' => '河北 唐山市 丰润区',
		'user_areaid' => '1150',
		'user_avatar' => 'http://127.0.0.1/pcenter/trunk/image.php/ucenter/data/upload/media/plantform/image/20160813/1471057867864788.jpg!120x120.jpg',
		'user_birthday' => '1989-10-03',
		'user_cityid' => '74',
		'user_delete' => 0,
		'user_email' => '323@fdsfa.com',
		'user_mobile' => '',
		'user_provinceid' => '3',
		'user_qq' => '15524721181',
		'user_realname' => 'zsd12111',
		'user_sex' => '0',
		'key' => 'HANZaFR0Aw08PV1U02RzCW114UWXa26AUiIO',
		*/
		$user_email    = request_string('user_email');
		$user_mobile    = request_string('user_mobile');

		$user_realname = request_string('user_realname');
		$user_sex      = request_int('user_sex');
		$user_qq       = request_string('user_qq');
		$user_logo     = request_string('user_avatar');
		$user_nickname     = request_string('user_nickname');

		$user_delete   = request_int('user_delete');



		//$cond_row['user_passwd'] = md5($user_passwd);
		$edit_user_row['user_mobile']     = $user_mobile;
		$edit_user_row['user_email']    = $user_email;
		
		if ($user_nickname)
        {
            //$edit_user_row['user_nickname']    = $user_nickname;
        }

		//$edit_user_row['user_sex']      = $user_sex;
		$edit_user_row['user_realname'] = $user_realname;
		$edit_user_row['user_qq']       = $user_qq;
		$edit_user_row['user_avatar']     = $user_logo;


		/*
		$edit_user_row['user_provinceid']     = $user_logo;
		$edit_user_row['user_cityid']     = $user_logo;
		$edit_user_row['user_areaid']     = $user_logo;
		$edit_user_row['user_area']     = $user_logo;
		$edit_user_row['user_birthday']     = $user_logo;
		*/

		$edit_base_row = array();
		isset($_REQUEST['user_delete']) ? $edit_base_row['user_delete'] = $user_delete : '';

		//开启事物
		$rs_row = array();
		$this->userInfoModel->sql->startTransactionDb();
		

		if ($edit_base_row)
		{
			$update_flag = $this->userBaseModel->editBase($user_id, $edit_base_row);
			check_rs($update_flag, $rs_row);
		}

		if ($edit_user_row)
		{
			$flag = $this->userInfoModel->editInfo($user_id, $edit_user_row);

			check_rs($flag, $rs_row);
		}


		$flag = is_ok($rs_row);

		if ($flag !== false && $this->userInfoModel->sql->commitDb())
		{
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$this->userInfoModel->sql->rollBackDb();

			$status = 250;
			$msg    = _('failure');
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	//获取用户资源信息
	public function getUserResourceInfo()
	{
		$user_id = request_int('user_id');


		$User_ResourceModel = new User_ResourceModel();

		$data = $User_ResourceModel->getOne($user_id);

		if ($data)
		{
			$msg    = 'success ';
			$status = 200;
		}
		else
		{
			$msg    = 'failure 250 '.$user_id;
			$status = 250;
		}


		$this->data->addBody(-140, $data, $msg, $status);

	}

	/*
     *通过用户id数组获取用户id对应的信息
     * @param array $user_id_row 用户id数组
     * @access public
     */
	public function getUserRowsResourceInfo()
	{
		$user_id_rows = request_row('user_id_row');
		$User_ResourceModel = new User_ResourceModel();
		foreach($user_id_rows as $v)
		{
			$data[$v] = $User_ResourceModel->getOne($v);
		}
		
		if ($data)
		{
			$msg    = 'success ';
			$status = 200;
		}
		else
		{
			$msg    = 'failure 250 ';
			$status = 250;
		}


		$this->data->addBody(-140, $data, $msg, $status);
	}


	//修改用户资源信息
	public function editUserResourceInfo()
	{
		$user_id = request_int('user_id');
		$user_name = request_string('user_name');

		$order_id = request_string('order_id');
		$goods_id = request_int('goods_id');
		$str = '';
		if($goods_id)
		{
			$str = "，商品id:" . $goods_id;
		}
		$money = request_float('money');
		$pay_type = request_string('pay_type');

		$reason = request_string('reason');


		$edit_row = array();
		//修改现金账户
		if($pay_type == 'cash')
		{
			$edit_row['user_money'] = $money;
		}
		if($pay_type == 'frozen_cash')
		{
			$edit_row['user_money_frozen'] = $money;
		}

		$User_ResourceModel = new User_ResourceModel();
		//开启事务
		$User_ResourceModel->sql->startTransactionDb();

		$User_ResourceModel->editResource($user_id,$edit_row,true);

		$flow_id = time();

		$record_add_seller_row                  = array();
		$record_add_seller_row['order_id']      = $flow_id;
		$record_add_seller_row['user_id']       = $user_id;
		$record_add_seller_row['user_nickname'] = $user_name;
		$record_add_seller_row['record_money']  = $money;
		$record_add_seller_row['record_date']   = date('Y-m-d');
		$record_add_seller_row['record_year']	   = date('Y');
		$record_add_seller_row['record_month']	= date('m');
		$record_add_seller_row['record_day']		=date('d');
		$record_add_seller_row['record_title']  = $reason;
		$record_add_seller_row['record_desc']  = "订单号:" . $order_id . $str;
		$record_add_seller_row['record_time']   = date('Y-m-d H:i:s');
		$record_add_seller_row['trade_type_id'] = Trade_TypeModel::REFUND;
		$record_add_seller_row['user_type']     = 2;	//付款方
		$record_add_seller_row['record_status'] = RecordStatusModel::RECORD_FINISH;

		$Consume_RecordModel = new Consume_RecordModel();
		$data = $Consume_RecordModel->addRecord($record_add_seller_row);


		if ($data && $User_ResourceModel->sql->commitDb())
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$User_ResourceModel->sql->rollBackDb();
			$m      = $User_ResourceModel->msg->getMessages();
			$msg    = $m ? $m[0] : 'failure';
			$status = 250;
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}

	//修改用户资源信息
	public function editReturnUserResourceInfo()
	{
		$rs_row = array();
		$user_id = request_int('user_id');
		$user_name = request_string('user_name');

		$order_id = request_string('order_id');
		$goods_id = request_int('goods_id');
		$str = '';
		if($goods_id)
		{
			$str = "，商品id:" . $goods_id;
		}
		$money = request_float('money');
		$abs_money = abs($money);
		$pay_type = request_string('pay_type');

		$reason = request_string('reason');

		$User_ResourceModel = new User_ResourceModel();
		//开启事务
		$User_ResourceModel->sql->startTransactionDb();

		//获取用户资金信息
		$user_data = $User_ResourceModel->getOne($user_id);

		//之前货到付款冻结资金会出现负数，现在先将为负的冻结资金从用户自己中扣除
		if($user_data['user_money_frozen'] < 0)
		{
			$edit = array();
			$edit['user_money'] = $user_data['user_money_frozen'];
			$edit_flag = $User_ResourceModel->editResource($user_id,$edit,true);
			check_rs($edit_flag,$rs_row);
		}

		$user_data = $User_ResourceModel->getOne($user_id);

		$edit_row = array();
		//修改现金账户
		if($pay_type == 'cash')
		{
			$edit_row['user_money'] = $money;
		}
		if($pay_type == 'frozen_cash')
		{
			if($user_data['user_money_frozen'] < $abs_money)
			{
				$edit_row['user_money_frozen'] = (-1)*$user_data['user_money_frozen'];
				$edit_row['user_money'] = ($abs_money - $user_data['user_money_frozen'])*(-1);
			}
			else
			{
				$edit_row['user_money_frozen'] = $money;
			}
		}


		$edit_flag = $User_ResourceModel->editResource($user_id,$edit_row,true);
		check_rs($edit_flag,$rs_row);

		$flow_id = time();

		$record_add_seller_row                  = array();
		$record_add_seller_row['order_id']      = $flow_id;
		$record_add_seller_row['user_id']       = $user_id;
		$record_add_seller_row['user_nickname'] = $user_name;
		$record_add_seller_row['record_money']  = $money;
		$record_add_seller_row['record_date']   = date('Y-m-d');
		$record_add_seller_row['record_year']	   = date('Y');
		$record_add_seller_row['record_month']	= date('m');
		$record_add_seller_row['record_day']		=date('d');
		$record_add_seller_row['record_title']  = $reason;
		$record_add_seller_row['record_desc']  = "订单号:" . $order_id . $str;
		$record_add_seller_row['record_time']   = date('Y-m-d H:i:s');
		$record_add_seller_row['trade_type_id'] = Trade_TypeModel::REFUND;
		$record_add_seller_row['user_type']     = 2;	//付款方
		$record_add_seller_row['record_status'] = RecordStatusModel::RECORD_FINISH;

		$Consume_RecordModel = new Consume_RecordModel();
		$add_flag = $Consume_RecordModel->addRecord($record_add_seller_row);

		check_rs($add_flag,$rs_row);

		$flag = is_ok($rs_row);

		if ($flag && $User_ResourceModel->sql->commitDb())
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$User_ResourceModel->sql->rollBackDb();
			$m      = $User_ResourceModel->msg->getMessages();
			$msg    = $m ? $m[0] : 'failure';
			$status = 250;
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}

    /**
     *  获取用户的信息
     *  用于实名认证
     */
    public function getUserInfo()
	{
        $user_id = request_int('user_id');
        $resource = request_int('resource');
        if(!$user_id){
            $this->data->addBody(-140, array(), 'failure', 250);
        }else{
            $User_Model = new User_InfoModel();
            $user_info = $User_Model->getOne($user_id);
            //获取白条信息
            if($resource){
                if(Payment_ChannelModel::status('baitiao') != Payment_ChannelModel::ENABLE_YES){
                    $user_info['baitiao_is_open'] = 0;
                }else{
                    $user_info['baitiao_is_open'] = 1;
                }
                $user_resource_model = new User_ResourceModel();
                $result = $user_resource_model->getOne($user_id);
                $user_info = array_merge($user_info,$result);
            }
            $this->data->addBody(-140, $user_info, '', 200);
        }
    }


	//实名认证插入资料
	public function editCertification()
	{
		//获取用户信息
		$user_id = request_int('user_id');
		$edit['user_realname'] = request_string('user_realname');
		$edit['user_identity_card'] = request_string('user_identity_card');
		$edit['user_identity_type'] = request_string('user_identity_type');
		$edit['user_identity_font_logo'] = request_string('user_identity_font_logo');
		$edit['user_identity_face_logo'] = request_string('user_identity_face_logo');
		$edit['user_identity_start_time'] = request_string('user_identity_start_time');
		$edit['user_identity_end_time'] = request_string('user_identity_end_time');
		$edit['user_identity_statu'] = 1;
		if(request_string('from') === 'bt')
		{
			//如果是白条，则更改白条状态
			$edit['user_bt_status'] = 1;
			$edit['user_btapply_time'] = date('Y-m-d H:i:s');
		}
		$User_InfoModel = new User_InfoModel();
		$flag     = $User_InfoModel->editInfo($user_id,$edit);
		if ($flag !== false)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}

    /**
     * 修改用户状态
     */
	public function editUserDelete()
    {
        $user_id = request_int('user_id');
        $user_delete = request_int('user_delete', '');
        $edit_base_row = array();
        $edit_base_row['user_delete'] = $user_delete;
        $flag = $this->userBaseModel->editBase($user_id, $edit_base_row);
        if ($flag !== false)
        {
            $status = 200;
            $msg    = _('success');
        }
        else
        {            $status = 250;
            $msg    = _('failure');
        }
        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 将可以结算的入账记录，从账户冻结余额中转移到账户余额中
     */
    public function editUserMoneyFrozen()
    {
        $Consume_RecordModel = new Consume_RecordModel();
        $User_ResourceModel = new User_ResourceModel();

        $time = time()-1*24*60*60;
        $N = date('Y-m-d H:i:s',$time);
        $cond_row['user_type'] = 1; //入账
        $cond_row['record_status'] = RecordStatusModel::IN_HAND; //处理中
        $cond_row['trade_type_id:in'] = [
            Trade_TypeModel::STOCK_ORDER_PROFIT,
            Trade_TypeModel::BUYER_ORDER_PROFIT,
            Trade_TypeModel::BUYER_ORDER_REBATE
        ];
        $cond_row['record_time:<='] = $N;
        $order_row['record_time'] = 'asc';

        $record_user_list = $Consume_RecordModel->getByWhere($cond_row, $order_row, 'user_id');
        Yf_Log::log($record_user_list, Yf_Log::LOG, 'user_settle');
//        $total_user = $Consume_RecordModel->getFoundRows();
//        $rows = 500;
//        $total_page = $total_user/$rows;
//        for($i=1; $i<=$total_page; $i=$i+1) {
//
//        }
        $user_ids = array_column($record_user_list, 'user_id');
        foreach ($user_ids as $user_id){
            $search_row = array();
            $search_row = $cond_row;
            $search_row['user_id'] = $user_id;
            $record_count = $Consume_RecordModel->get_count($search_row);
            $record_count = $record_count['num'];
            Yf_Log::log($record_count, Yf_Log::LOG, 'user_settle');
            if($record_count <= 0) continue;
            $user_resource = $User_ResourceModel->getOne($user_id);

            $rows = 500;
            $total_page = ceil_r($record_count / $rows);
            for($i=1; $i<=$total_page; $i=$i+1){
                $record_amount = 0;
                $User_ResourceModel->sql->startTransactionDb();
                $list = $Consume_RecordModel->listByWhere($search_row, $order_row, $i, $rows);
                $record_amount_array = array_column($list['items'], 'record_money');
                $record_amount = array_sum($record_amount_array);

                $user_resource_edit['user_money'] = $user_resource['user_money'] + $record_amount;
                $user_resource_edit['user_money_frozen'] = $user_resource['user_money_frozen'] - $record_amount;
                $flag1 = $User_ResourceModel->editResource($user_id, $user_resource_edit);

                $record_ids = array_column($list['items'], 'consume_record_id');
                $record_edit['record_status'] = RecordStatusModel::RECORD_FINISH; //处理中
                $record_edit['record_paytime'] = get_date_time();
                $flag2 = $Consume_RecordModel->editRecord($record_ids, $record_edit);

                if($flag1 !== false && $flag2 !== false && $User_ResourceModel->sql->commitDb()){
                    Yf_Log::log("用户id：{$user_id}结算金额￥{$record_amount}成功", Yf_Log::LOG, 'user_settle');
                }else{
                    $User_ResourceModel->sql->rollBackDb();
                    Yf_Log::log("用户id：{$user_id}结算金额￥{$record_amount}失败！", Yf_Log::LOG, 'user_settle');
                }
            }
        }

        $this->data->addBody(-140, array());
    }


    public function editCertificationForWap()
    {
        //获取用户信息
        $User_InfoModel = new User_InfoModel();
        $user_id = request_string('user_id');
        $edit['user_realname'] = request_string('user_realname');
        $edit['user_identity_card'] = request_string('user_identity_card');
        $edit['user_identity_type'] = request_string('user_identity_type');
//        $existUser=$User_InfoModel->getByWhere(array('user_identity_card'=>$edit['user_identity_card']));
//        if(count($existUser)>0 && current($existUser)['user_id']!=$user_id){
//            return $this->data->addBody(-140, array(), __('身份证号已存在'), 250);
//        }
        $edit['user_identity_font_logo'] = request_string('user_identity_font_logo');
        $edit['user_identity_face_logo'] = request_string('user_identity_face_logo');
        $edit['user_identity_start_time'] = request_string('user_identity_start_time');
        $edit['user_identity_end_time'] = request_string('user_identity_end_time');

        $edit['user_identity_statu'] = 1;

        $flag = $User_InfoModel->editInfo($user_id, $edit);
        if ($flag !== false) {
            $msg = 'success';
            $status = 200;
        } else {
            $msg = 'failure';
            $status = 250;
        }

        $data = array();
        return $this->data->addBody(-140, $data, $msg, $status);

    }

    //实名认证
    public function certificationForWap()
    {
        //获取用户信息
        $user_id = request_string('user_id');
        //$user_id = '1';
        $User_InfoModel = new User_InfoModel();
        $data = $User_InfoModel->getOne($user_id);
        return $this->data->addBody(-140, $data);
    }

    public function isExistIdentity()
    {
        $user_id = request_string('user_id');
        $User_InfoModel = new User_InfoModel();
        $cond_row['user_identity_card']= request_string('user_identity_card');
        $cond_row['user_identity_type']=  request_string('user_identity_type');
        $existUser = $User_InfoModel->getByWhere($cond_row);
        if (count($existUser) > 0 && current($existUser)['user_id'] != $user_id) {
            $data['isExist'] = true;
        } else {
            $data['isExist'] = false;
        }
        return $this->data->addBody(-140, $data);
    }

    public function updateUserInfoForWap()
    {
        $user_id = request_string('user_id');
        $user_nickname = request_string('user_nickname');
        $user_logo=request_string('user_logo');
        $user_mobile=request_string('user_mobile');
        $User_InfoModel = new User_InfoModel();

        $user=$User_InfoModel->getOne($user_id);
        if($user_nickname){
            $edit_row['user_nickname']     = $user_nickname;
        }
        if($user_logo){
            $edit_row['user_avatar']     = $user_logo;
        }
        if($user_mobile){
            $edit_row['user_mobile']     = $user_mobile;
        }
        $flag = $User_InfoModel->editInfo($user_id,$edit_row);
        if ($flag){
            $status = 200;
            $msg    = _('success');
        }
        else{
            $status = 250;
            $msg    = _('failure');
        }

        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);
    }

    public function addDeposit()
    {
        $deposit_amount = request_float('deposit_amount');
        $user_id = request_string('user_id');
        $user_account = request_string('user_account');

        $Union_OrderModel = new Union_OrderModel();
        //开启事务
        $Union_OrderModel->sql->startTransactionDb();

        //生成合并支付订单
        $uorder = "U" . date("Ymdhis", time()) . rand(100, 999);  //18位

        $trade_title = $uorder;
        $uprice = $deposit_amount;
        $buyer = $user_id;
        $buyer_name =$user_account;

        $add_row = array(
            'union_order_id' => $uorder,
            'trade_title' => $trade_title,
            'trade_payment_amount' => $uprice,
            'create_time' => date("Y-m-d H:i:s"),
            'buyer_id' => $buyer,
            'order_state_id' => Union_OrderModel::WAIT_PAY,
            'union_online_pay_amount' => $uprice,
            'trade_type_id' => Trade_TypeModel::DEPOSIT,
            'app_id' => Yf_Registry::get('paycenter_app_id'),
        );

        $flag = $Union_OrderModel->addUnionOrder($add_row);

        //添加充值表
        $Consume_DepositModel = new Consume_DepositModel();
        $add_deposit_row = array();
        $add_deposit_row['deposit_trade_no'] = $uorder;
        $add_deposit_row['deposit_buyer_id'] = $buyer;
        $add_deposit_row['deposit_total_fee'] = $deposit_amount;
        $add_deposit_row['deposit_gmt_create'] = date('Y-m-d H:i:s');
        $add_deposit_row['deposit_trade_status'] = RecordStatusModel::IN_HAND;
        $Consume_DepositModel->addDeposit($add_deposit_row);

        //添加交易明细
        $Consume_RecordModel = new Consume_RecordModel();
        $Trade_TypeModel = new Trade_TypeModel();
        $record_add_buy_row = array();
        $record_add_buy_row['order_id'] = $uorder;
        $record_add_buy_row['user_id'] = $buyer;
        $record_add_buy_row['user_nickname'] = $buyer_name;
        $record_add_buy_row['record_money'] = $deposit_amount;
        $record_add_buy_row['record_date'] = date('Y-m-d');
        $record_add_buy_row['record_year'] = date('Y');
        $record_add_buy_row['record_month'] = date('m');
        $record_add_buy_row['record_day'] = date('d');
        $record_add_buy_row['record_title'] = $Trade_TypeModel->trade_type[Trade_TypeModel::DEPOSIT];
        $record_add_buy_row['record_time'] = date('Y-m-d H:i:s');
        $record_add_buy_row['trade_type_id'] = Trade_TypeModel::DEPOSIT;
        $record_add_buy_row['user_type'] = 1;    //收款方
        $record_add_buy_row['record_status'] = RecordStatusModel::IN_HAND;

        $Consume_RecordModel->addRecord($record_add_buy_row);

        if ($flag && $Union_OrderModel->sql->commitDb()) {
            $key = Yf_Registry::get('shop_api_key');
            $url = Yf_Registry::get('shop_api_url');
            $shop_app_id = Yf_Registry::get('shop_app_id');
            //发送站内信
            get_url_with_encrypt($key, sprintf('%s?ctl=Api_User_Message&met=sendMessage&typ=json', $url), ['code' => 'deposit_reminder', 'user_id' => $buyer, 'user_name' => $buyer_name, 'status' => '', 'amount' => $deposit_amount, 'dhm' => '', 'app_id' => $shop_app_id]);

            $msg = 'success';
            $status = 200;
        } else {
            $Union_OrderModel->sql->rollBackDb();
            $m = $Union_OrderModel->msg->getMessages();
            $msg = $m ? $m[0] : _('failure');
            $status = 250;
        }

        $data = array('uorder' => $uorder);

        if ($_REQUEST['returnData'] == 1) {
            return $data;
        }
        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 修改支付密码
     */
    public function editUserPayPassword()
    {
        $user_id = request_string('user_id');
        $password = request_string('password');

        if ($password)
        {
            $User_BaseModel = new User_BaseModel();
            $user_base = $User_BaseModel->getOne($user_id);
            if($user_base['user_pay_passwd'] == md5($password)){
                $msg    = 'success';
                $status = 200;
            }else{
                $user_base_row = array();
                $user_base_row['user_pay_passwd'] = md5($password);
                $flag                             = $User_BaseModel->editBase($user_id, $user_base_row);

                if ($flag !== false)
                {
                    $msg    = 'success';
                    $status = 200;
                }
                else
                {
                    $msg    = 'failure';
                    $status = 250;
                }
            }
        }
        else
        {
            $msg = '密码不能为空';
            $status = 250;
        }

        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);
    }

}

?>