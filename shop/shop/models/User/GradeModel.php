<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class User_GradeModel extends User_Grade
{

	/**
	 * 读取等级列表
	 *
	 * @param  array $cond_row 查询条件
	 * @param  array $order_row 排序信息
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getGradeList($cond_row = array(), $order_row = array())
	{
		return $this->getByWhere($cond_row, $order_row);
	}

	/**
	 * 读取等级信息
	 *
	 * @param  array $grade_row 查询条件
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getUserGrade($grade_row = array())
	{
		return $this->getOneByWhere($grade_row);


	}

	/**
	 * 获取会员期限--没有使用
	 *
	 * @param  array $data 会员的信息
	 * @return array $data 返回的查询内容
	 * @access public
	 */
	public function getUserExpire($data)
	{

		if ($data['user_grade_valid'] > 0)
		{
			$time           = strtotime($data['user_grade_time']);
			$data['expire'] = date("Y-m-d H:i:s", $time + 60 * 60 * 24 * 365 * $data['user_grade_valid']);
		}

		return $data;

	}

	/**
	 * 获取下一个等级
	 *
	 * @param  array $data会员等级信息, $gradeList等级列表, $re会员信息  查询条件
	 * @return array $data 返回的查询内容
	 * @access public
	 */
	public function getGradeGrowth($data, $gradeList, $re)
	{

		foreach ($gradeList as $val)
		{
			if ($val['id'] == ($re['user_grade'] + 1))
			{
				$data['next']   = $val['user_grade_name'];
				$data['growth'] = $val['user_grade_demand'] - $re['user_growth'];
			}
		}

		return $data;

	}

	/**
	 * 判断升级
	 * @param  int $user_id会员的id $user_growth会员现在的经验值  查询条件
	 * @return array $flag 升级成功返回的状态
	 * @param $grade_log_id
	 *
	 */
	public function upGrade($user_id, $user_growth)
	{
//		$User_InfoModel = new User_InfoModel();
//
//		$user = $User_InfoModel->getInfo($user_id);
//		//当前等级的下个等级
//		$user_grade = $user[$user_id]['user_grade'] * 1 + 1;
//
//		$Grade = $this->getGrade($user_grade);
//		//获取此等级经验值
//		$grade_le = $Grade [$user_grade]['user_grade_demand'] * 1;
//
//		if ($user_growth > $grade_le)
//		{ //传过的当前经验值大于下个等级经验值升级
//
//			$cond_row['user_grade'] = $user_grade;
//			$flag                   = $User_InfoModel->editInfo($user_id, $cond_row);
//			return $flag;
//		}

        return $this->updateGradeVip($user_id);
	}

    /**
     * 检查并给用户晋级
     * @param $user_id 会员的id
     * @return bool|null
     * @throws Exception
     */
    public function updateGradeVip($user_id, $is_need_search=true)
    {
        $User_InfoModel = new User_InfoModel();
        $User_GradeLogModel = new User_GradeLogModel();

        $user = $User_InfoModel->getOne($user_id);
        //当前等级的下个等级
        $user_grade = $user['user_grade'] * 1 + 1;
        $Grade = $this->getGrade($user_grade);

        $can_update_grade = false;

        if($user_grade == 2 && $is_need_search){//用户升级成为会员
            //1.获取用户的非账户余额支付的订单金额
            $order_Base = new Order_BaseModel();
            $cond_row['buyer_user_id'] = $user_id;
            $cond_row['order_status'] = Order_StateModel::ORDER_FINISH;
            $order_sum_amount = $order_Base->getSumOrderPaymentAmount($cond_row);
            Yf_Log::log('order_sum_amount:'.$order_sum_amount, Yf_Log::LOG, 'debug');

            //2.获取用户的储值金额
            //远程paycenter参数
            $key = Yf_Registry::get('paycenter_api_key');
            $url = Yf_Registry::get('paycenter_api_url');
            $paycenter_app_id = Yf_Registry::get('paycenter_app_id');
            $formvars = array();
            $formvars['app_id'] = $paycenter_app_id;
            $formvars['user_id'] = $user_id;
            $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Paycen_PayRecord&met=getDepositAmountByUserId&typ=json', $url), $formvars);
            $user_deposit_amount = 0;
            if($rs['status'] == "200"){
                $user_deposit_amount = $rs['data']['amount']*1;
            }

            //获取升级会员需要的金额
            $user_grade_trade = $Grade[$user_grade]['user_grade_trade'] * 1;

            //判断是否可以升级，用户的消费金额+储值金额 >= 升级所需要的金额
            $can_update_grade = $order_sum_amount + $user_deposit_amount >= $user_grade_trade;
        }else if($user_grade == 2){
            $can_update_grade = !$is_need_search;
        }else if($user_grade == 3){
            $this->updateGradePartner($user_id);
        }

        if($can_update_grade){
            Yf_Log::log('updateGradeVip:'.$user_id, Yf_Log::LOG, 'user_update');
            //更新用户级别
            $u_row['user_grade'] = $user_grade;
            $u_row['user_grade_update_date'] = get_date_time();
            $flag                   = $User_InfoModel->editInfo($user_id, $u_row);

            //添加用户晋级log
            $log['user_id'] = $user_id;
            $log['user_grade_pre'] = $user['user_grade'];
            $log['user_grade_to'] = $user_grade;
            $log['log_date_time'] = get_date_time();
            $flag1 = $User_GradeLogModel->addGradeLog($log);

            $this->updateUserGradeToUcenter($user_id, $user_grade);

            return $flag;
        }else{
            $this->updateUserGradeToUcenter($user_id, $user['user_grade']);
        }

        if($user_grade == 3){
            //检查上级会员是否可以晋级
            $user_parent_id = $user['user_parent_id'];
            if($user_parent_id){
                $this->updateGradePartner($user_parent_id);
            }
        }

        return null;
    }

    /**
     * 检查是否是会员，并升级为合伙人
     * @param $user_id
     * @return bool|null
     */
    public function updateGradePartner($user_id)
    {
        $User_InfoModel = new User_InfoModel();
        $User_GradeLogModel = new User_GradeLogModel();

        $user = $User_InfoModel->getInfo($user_id);
        //当前等级的下个等级
        $user_grade = $user[$user_id]['user_grade'] * 1 + 1;
        $Grade = $this->getGrade($user_grade);

        $can_update_grade = false;

        //下个等级为合伙人
        if($user_grade == 3) {
            $can_update_grade = $this->checkUpdateGradeToPartner($user_id, $user_grade, $Grade);
        }

        if($can_update_grade){
            Yf_Log::log('updateGradePartner:'.$user_id, Yf_Log::LOG, 'user_update');
            //更新用户级别
            $u_row['user_grade'] = $user_grade;
            $u_row['user_grade_update_date'] = get_date_time();
            $u_row['user_update_partner_date'] = get_date_time();
            $flag                   = $User_InfoModel->editInfo($user_id, $u_row);

            //添加用户晋级log
            $log['user_id'] = $user_id;
            $log['user_grade_pre'] = $user[$user_id]['user_grade'];
            $log['user_grade_to'] = $user_grade;
            $log['log_date_time'] = get_date_time();
            $flag1 = $User_GradeLogModel->addGradeLog($log);

            //更新上级高级合伙人的当前年度的发展合伙人数量
            $user_parent_g_partner_id = $User_InfoModel->getParentId($user_id);
            $flag2 = $User_InfoModel->edit($user_parent_g_partner_id, ['current_year_partner_count'=>1], true);

            $this->updateUserGradeToUcenter($user_id, $user_grade);

            return $flag;
        }else{
            return null;
        }
    }

    private function checkUpdateGradeToPartner($user_id,$user_grade,$Grade)
    {
        //如果要升级的等级不是合伙人等级，则跳出
        if($user_grade != 3) return false;

        $can_update_grade = false;

        //远程paycenter参数
        $key = Yf_Registry::get('paycenter_api_key');
        $url = Yf_Registry::get('paycenter_api_url');
        $paycenter_app_id = Yf_Registry::get('paycenter_app_id');

        //获取用户的股金是否达标
        $formvars = array();
        $formvars['app_id'] = $paycenter_app_id;
        $formvars['user_id'] = $user_id;
        $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_User_Info&met=getUserResourceInfo&typ=json', $url), $formvars);

        if ($rs['status'] == "200") {
            $user_rescouce = $rs['data'];
            $user_pay_shares_date = $user_rescouce['user_pay_shares_date'];

            //查看股金状态，判断是否需要升级
            if ($user_pay_shares_date) {
                //获取升级合伙人需要的条件
                $user_grade_year_num = $Grade[$user_grade]['user_grade_year_num'];
                $user_grade_per_year = $Grade[$user_grade]['user_grade_per_year'];

                //例如：前三年每年发展10个（及以上）会员
                $s_user_grade = 2; //1.普通用户;2:会员;3:合伙人;4:高级合伙人
                $can_update_grade = true;
                for ($y = 1, $temp_date = $user_pay_shares_date; $y <= $user_grade_year_num; $y++) {
                    $end_date = date('Y-m-d 00:00:00', strtotime('+1 year', strtotime($temp_date)));
                    $User_GradeLogModel = new User_GradeLogModel();
                    $user_count = $User_GradeLogModel->getUserCount($user_id, $s_user_grade, $temp_date, $end_date);
                    if ($user_count < $user_grade_per_year) {
                        $can_update_grade = false;
                        break;
                    }
                    $temp_date = $end_date;
                }
            }
        }

        return $can_update_grade;
    }

    /**
     * 用户直接升到高级合伙人
     * @param $user_id
     * @param $user_shares
     * @param $user_stocks
     * @return bool|null
     */
    public function updateGradeToGPartner($user_id, $user_shares, $user_stocks)
    {
        $User_InfoModel = new User_InfoModel();
        $user = $User_InfoModel->getInfo($user_id);
        $user_info = current($user);

        if($user_info['user_grade']*1 == 4){
            return true;
        }

        //当前等级的下个等级
        $user_grade = 4;
        $Grade = $this->getGrade($user_grade);

        $can_update_grade = false;

        //获取升级会员需要
        $user_grade_shares = $Grade [$user_grade]['user_grade_shares'] * 1;
        $user_grade_stocks = $Grade [$user_grade]['user_grade_stocks'] * 1;
        if ($user_shares >= $user_grade_shares && $user_stocks >= $user_grade_stocks) {
            $can_update_grade = true;
        }

        if ($can_update_grade) {
            Yf_Log::log('updateGradeGPartner:'.$user_id, Yf_Log::LOG, 'user_update');
            //更新用户级别
            $u_row['user_grade'] = $user_grade;
            $u_row['user_grade_update_date'] = get_date_time();
            $u_row['uset_update_g_partner_date'] = get_date_time();
            $u_row['user_parent_id'] = 0;
            $flag = $User_InfoModel->editInfo($user_id, $u_row);

            //添加用户晋级log
            $User_GradeLogModel = new User_GradeLogModel();
            $log['user_id'] = $user_id;
            $log['user_grade_pre'] = $user_info['user_grade'];
            $log['user_grade_to'] = $user_grade;
            $log['log_date_time'] = get_date_time();
            $flag1 = $User_GradeLogModel->addGradeLog($log);

            //添加店铺
            $Shop_BaseModel = new Shop_BaseModel();
            $new_shop['user_id'] = $user_id;
            $new_shop['user_name'] = $user_info['user_name'];
            $new_shop['shop_name'] = $user_info['user_name'].'的店铺';
            $new_shop['shop_all_class'] = 1;
            $new_shop['shop_self_support'] = Shop_BaseModel::SELF_SUPPORT_FALSE;
            $new_shop['shop_create_time'] = get_date_time();
            $new_shop['shop_status'] = Shop_BaseModel::SHOP_STATUS_OPEN;
            $Number_SeqModel  = new Number_SeqModel();
            $shop_id          = $Number_SeqModel->createSeq('shop_id', 4, false);
            $new_shop['shop_id'] = $shop_id;
            $shop_flag = $Shop_BaseModel->addBase($new_shop);

            $Seller_BaseModel = new Seller_BaseModel();
            $new_seller['shop_id'] = $shop_id;
            $new_seller['user_id'] = $user_id;
            $new_seller['seller_group_id'] = 0;
            $new_seller['seller_is_admin'] = 1;
            $new_seller['rights_group_id'] = 0;
            $flag2 = $Seller_BaseModel->addBase($new_seller);

            $this->updateUserGradeToUcenter($user_id, $user_grade);

            return $flag;
        } else {
            return null;
        }
    }

    public function updateUserGradeToUcenter($user_id, $user_grade){
        //本地读取远程信息
        $key = Yf_Registry::get('ucenter_api_key');
        $api_url = Yf_Registry::get('ucenter_api_url');
        $app_id = Yf_Registry::get('ucenter_app_id');

        $formvars            = array();
        $formvars['app_id']  = $app_id;
        $formvars['user_id'] = $user_id;
        $formvars['user_grade'] = $user_grade;

        $url = sprintf('%s?ctl=%s&met=%s&typ=%s', $api_url, 'Api_User', 'editUserGrade', 'json');
        $rs = get_url_with_encrypt($key, $url, $formvars);
    }

	//获取当前用户等级对应的折扣率
	public function getGradeRate($user_grade)
	{
		//获取用户等级表所有数据
		$user_grade_info = $this->getByWhere();
		//取出不同等级条件组成新数组
		$user_grade_demand_row = array_column($user_grade_info, 'user_grade_demand');
		$num = 0;
		//循环比较当前用户符合哪个等级
		foreach($user_grade_demand_row as $key=>$val)
		{
			if($user_grade > $key)
			{
				$num = $key;
				continue;
			}
			elseif($user_grade == $key)
			{
				$num = $key;
				break;
			}
			else
			{
				break;
			}
		}
		return $this->getOneByWhere(['user_grade_id'=>$num]);
	}

	public function getUserRate($user_grade)
	{
		$grade_info = $this->getOne($user_grade);

		return $grade_info;

	}
}

?>