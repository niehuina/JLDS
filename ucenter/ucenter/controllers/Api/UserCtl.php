<?php

class Api_UserCtl extends Api_Controller
{
    public function getUserInfo()
    {
        $user_id = request_int('user_id');

        if (!$user_id) {
            return $this->data->addBody(-140, array('user_id' => $user_id), __('数据有误'), 250);
        }
//        $user_info_row = array();

        $User_InfoModel = new User_InfoModel();
        $user_row = $User_InfoModel->getOne($user_id);

        if ($user_row) {
            $User_InfoDetailModel = new User_InfoDetailModel();
            $user_info_row = $User_InfoDetailModel->getOne($user_id);
            return $this->data->addBody(-140, $user_info_row);
        } else {
            return $this->data->addBody(-140, array('data' => $user_row), __('数据有误'), 250);
        }

    }


    //获取列表信息
    public function listUser()
    {
        $skey = request_string('skey');
        $page = $_REQUEST['page'];
        $rows = $_REQUEST['rows'];
        $asc = $_REQUEST['asc'];
        $userInfoModel = new User_InfoDetailModel();

        $cond_row = array();
        $order_row = array();
        $data = $userInfoModel->getUserDetailListByKeys($skey, $cond_row, $order_row, $page, $rows);

        if ($data) {
            $msg = 'success';
            $status = 200;
        } else {
            $msg = 'failure';
            $status = 250;
        }
        $this->data->addBody(-140, $data, $msg, $status);
    }


    function details()
    {
        $user_id = request_string('id');
        $status = $_REQUEST['server_status'];
        //开启事物
        $User_InfoDetailModel = new User_InfoDetailModel();

        $data = $User_InfoDetailModel->getOne($user_id);

        //扩展字段
//        $User_OptionModel = new User_OptionModel();
//        $user_option_rows = $User_OptionModel->getByWhere(array('user_id' => $user_id));
//
//
//        if ($user_option_rows) {
//            $Reg_OptionModel = new Reg_OptionModel();
//            $reg_opt_rows = $Reg_OptionModel->getByWhere(array('reg_option_active' => 1));
//
//
//            foreach ($user_option_rows as $user_option_id => $user_option_row) {
//                $user_option_row['reg_option_name'] = $reg_opt_rows[$user_option_row['reg_option_id']]['reg_option_name'];
//
//                $user_option_rows[$user_option_id] = $user_option_row;
//            }
//        }

        $data['user_option_rows'] = array();//$user_option_rows;

        $this->data->addBody(-140, $data);
    }

    function add()
    {
        $user_name = request_string('user_name');
        $password = request_string('password');
        $phone = request_string('phone');
        $User_InfoModel = new User_InfoModel();
        $User_InfoDetail = new User_InfoDetailModel();

        $Db = Yf_Db::get('ucenter');
        $seq_name = 'user_id';
        $user_id = $Db->nextId($seq_name);

        $cond_row = array();

        $cond_row['user_id'] = $user_id;
        $cond_row['user_name'] = $user_name;


        $user_info = $User_InfoModel->getOneByWhere($cond_row);
        $data = array();
        if (!$user_name || !$password) {
            $status = 250;
            $msg = '参数错误';
        } else {
            if ($user_info) {
                $msg = '用户已存在';
                $status = 250;

            } else {
                $session_id = uniqid();
                $cond_row['password'] = md5($password);
                $cond_row['session_id'] = $session_id;
                $last_id = $User_InfoModel->addInfo($cond_row, true);

                $arr_field_user_info_detail = array();
                $now_time = time();
                $ip = get_ip();
                $arr_field_user_info_detail['user_id'] = $user_id;
                $arr_field_user_info_detail['user_name'] = $user_name;
                $arr_field_user_info_detail['user_reg_time'] = $now_time;
                $arr_field_user_info_detail['user_count_login'] = 1;
                $arr_field_user_info_detail['user_lastlogin_time'] = $now_time;
                $arr_field_user_info_detail['user_lastlogin_ip'] = $ip;
                $arr_field_user_info_detail['user_reg_ip'] = $ip;
                $arr_field_user_info_detail['user_mobile'] = $phone;


                $User_InfoDetail->addInfoDetail($arr_field_user_info_detail);

                $msg = 'success';
                $status = 200;
                $data['id'] = $last_id;
            }
        }

        $this->data->addBody(-1, $data, $msg, $status);
    }

    function change()
    {
        $user_id = request_string('id');
        $status = $_REQUEST['server_status'];
        $userInfoModel = new User_InfoModel();

        if ($user_id) {
            $userInfoModel->sql->startTransactionDb();

            $data['user_state'] = $status;
            $flag = $userInfoModel->editInfo($user_id, $data);

            //如果是用户退出，则要把下属的用户，迁移到该用户的上一级
            if($status == 4) {
                $key = Yf_Registry::get('shop_api_key');
                $url = Yf_Registry::get('shop_api_url');
                $shop_app_id = Yf_Registry::get('shop_app_id');
                $formvars = array();
                $formvars['app_id'] = $shop_app_id;
                $formvars['user_id'] = $user_id;

                $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_User_Info&met=changeUserParentId&typ=json', $url), $formvars);
                if($rs['status'] != "200")  $flag = false;

                //更新paycenter用户状态
                $key = Yf_Registry::get('paycenter_api_key');
                $url = Yf_Registry::get('paycenter_api_url');
                $app_id = Yf_Registry::get('paycenter_app_id');
                $formvars = array();
                $formvars['app_id'] = $app_id;
                $formvars['user_id'] = $user_id;
                $formvars['user_delete'] = 1;
                $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_User_Info&met=editUserDelete&typ=json', $url), $formvars);
                if($rs['status'] != "200")  $flag = false;
            }

            if (false !== $flag && $userInfoModel->sql->commitDb()) {
                $msg = 'success';
                $status = 200;
            } else {
                $userInfoModel->sql->rollBackDb();
                $m      = $userInfoModel->msg->getMessages();
                $msg    = $m ? $m[0] : __('failure');
                $status = 250;
            }
        }
        $this->data->addBody(-140, array(), $msg, $status);
    }

    //解除绑定,生成验证码,并且发送验证码
    public function getYzm()
    {
        $type = request_string('type');
        $val = request_string('val');

        $cond_row['code'] = 'Lift verification';

        $Message_TemplateModel = new Message_TemplateModel();

        $de = $Message_TemplateModel->getTemplateDetail($cond_row);

        fb($de);
        if ($type == 'mobile') {
            $me = $de['content_phone'];

            $code_key = $val;
            $code = VerifyCode::getCode($code_key);
            $me = str_replace("[weburl_name]", $this->web['web_name'], $me);
            $me = str_replace("[yzm]", $code, $me);

            $str = Sms::send($val, $me);
        } else {
            $me = $de['content_email'];
            $title = $de['title'];

            $code_key = $val;
            $code = VerifyCode::getCode($code_key);
            $me = str_replace("[weburl_name]", Web_ConfigModel::value("site_name"), $me);
            $me = str_replace("[yzm]", $code, $me);
            $title = str_replace("[weburl_name]", Web_ConfigModel::value("site_name"), $title);

            $str = Email::send($val, Perm::$row['user_account'], $title, $me);
        }
        $status = 200;
        $data = array($code);
        $msg = "success";
        $this->data->addBody(-140, $data, $msg, $status);

    }

    /**
     * 修改会员密码
     *
     * @access public
     */
    public function editUserPassword()
    {
        $user_id = request_string('user_id');
        $user_password = request_string('user_password');

        $User_InfoModel = new User_InfoModel();
        $rs_row = array();

        //开启事务
        $User_InfoModel->sql->startTransactionDb();

        if ($user_id && $user_password) {
            $edit_user['password'] = md5($user_password);
            $flag = $User_InfoModel->editInfo($user_id, $edit_user);
            check_rs($flag, $rs_row);
        }

        $flag = is_ok($rs_row);

        if ($flag && $User_InfoModel->sql->commitDb()) {
            $status = 200;
            $msg = _('success');
        } else {
            $User_InfoModel->sql->rollBackDb();
            $status = 250;
            $msg = _('failure');
        }

        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 修改会员头像
     *
     * @access public
     */
    public function editUserImg()
    {
        $user_id = request_int('user_id');
        $User_Info = new User_Info();
        $user_info = current($User_Info->getInfo($user_id));
        $user_name = $user_info['user_name'];

        $userInfoModel = new User_InfoDetailModel();
        $edit_user_row['user_avatar'] = request_string('user_avatar');

        $flag = $userInfoModel->editInfoDetail($user_id, $edit_user_row);
//		$data = array();
//		$data[0] = $user_name;
//		$this->data->addBody(-140, $edit_user_row);
        $data = array();
        //echo '<pre>';print_r($flag);exit;
        if ($flag === false) {
            $status = 250;
            $msg = _('failure');
        } else {
            $status = 200;
            $msg = _('success');
            $data[0] = $flag;
            $res = $userInfoModel->sync($user_id);
            //$userInfoModel->sync($user_id);
        }

        $this->data->addBody(-140, $data, $msg, $status);
    }


    /**
     * 修改会员信息
     *
     * @access public
     */
    public function editUserInfoDetail()
    {
        $user_id = request_int('user_id');
        $User_Info = new User_Info();
        $user_info = current($User_Info->getInfo($user_id));
        $user_name = $user_info['user_name'];

//		$year    = request_int('year');
//		$month   = request_int('month');
//		$day     = request_int('day');
//		$user_qq = request_string('user_qq');

        $edit_user_row['user_birth'] = request_string('user_birth');
        $edit_user_row['user_gender'] = request_int('user_gender');
        $edit_user_row['user_truename'] = request_string('user_truename');
        $edit_user_row['user_provinceid'] = request_int('province_id');
        $edit_user_row['user_cityid'] = request_int('city_id');
        $edit_user_row['user_areaid'] = request_int('area_id');
        $edit_user_row['user_area'] = request_string('user_area');
        $edit_user_row['nickname'] = request_string('nickname');
        $edit_user_row['user_sign'] = request_string('user_sign');
        $edit_user_row['user_province'] = request_string('user_province');
        $edit_user_row['user_city'] = request_string('user_city');

        //$edit_user_row['user_ww'] = $user_ww;
        //echo '<pre>';print_r($edit_user_row);exit;
        $userInfoModel = new User_InfoDetailModel();
        $userPrivacyModel = new User_PrivacyModel();

        if (!$userPrivacyModel->getOne($user_id)) {
            $userPrivacyModel->addPrivacy(array('user_id' => $user_id));
        }

        if (!$userInfoModel->getOne($user_name)) {
            $userInfoModel->addInfoDetail(array('user_id' => $user_id,'user_name' => $user_name));
        }

        //开启事物
        $rs_row = array();
        $userInfoModel->sql->startTransactionDb();

        //$flagPrivacy = $this->userPrivacyModel->editPrivacy($user_id, $rows);
        //check_rs($flagPrivacy, $rs_row);
        $flag = $userInfoModel->editInfoDetail($user_id, $edit_user_row);
        check_rs($flag, $rs_row);
        $flag_status = array();
        $flag_status[0] = $flag;

        $flag = is_ok($rs_row);
        $flag_status[1] = $flag;
        $res = array();
        if ($flag && $userInfoModel->sql->commitDb()) {
            $status = 200;
            $msg = _('success');

            $res = $userInfoModel->sync($user_id);
        } else {
            $userInfoModel->sql->rollBackDb();
            $status = 250;
            $msg = _('failure');

        }


        $this->data->addBody(-140, $flag_status, $msg, $status);
    }


    /**
     * 修改会员信息
     *
     * @access public
     */
    public function editUserInfo()
    {
        $user_id = request_int('user_id');
        $edit_user_row['user_gender'] = request_int('user_gender');
        $edit_user_row['user_avatar'] = request_string('user_logo');
        $user_delete = request_int('user_delete');

        //开启事物
        $rs_row = array();
        $User_InfoDetailModel = new User_InfoDetailModel();
        $User_InfoModel = new User_InfoModel();

        $User_InfoDetailModel->sql->startTransactionDb();
        $user_row = $User_InfoModel->getOne($user_id);
        if ($user_delete) {
            $edit_user['user_state'] = 3;
            $flagState = $User_InfoModel->editInfo($user_id, $edit_user);
            check_rs($flagState, $rs_row);
        } else {
            if ($user_row['user_state'] == 3) {
                $edit_user['user_state'] = 0;  //解禁后用户状态恢复到未激活
                $flagState = $User_InfoModel->editInfo($user_id, $edit_user);
                check_rs($flagState, $rs_row);
            }
        }

        $flag = $User_InfoDetailModel->editInfoDetail($user_id, $edit_user_row);
        check_rs($flag, $rs_row);

        $flag = is_ok($rs_row);

        if ($flag && $User_InfoDetailModel->sql->commitDb()) {
            $status = 200;
            $msg = _('success');
            $User_InfoDetailModel->sync($user_id);
        } else {
            $User_InfoDetailModel->sql->rollBackDb();
            $status = 250;
            $msg = _('failure');

        }

        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);
    }

    public function checkUserAccount()
    {
        $user_name = request_string('user_name');
        $password = request_string('password');

        $User_InfoModel = new User_InfoModel();
        $cond_row = array();
        $cond_row['user_name'] = $user_name;
        $cond_row['password'] = md5($password);

        $user_info = $User_InfoModel->getOneByWhere($cond_row);

        $data = array();
        if ($user_info) {
            $data['user_id'] = $user_info['user_id'];
            $data['user_name'] = $user_info['user_name'];
            $msg = 'success';
            $status = 200;

            // cookie login

            $session_id = $user_info['session_id'];
            $d = array();
            $d['user_id'] = $user_info['user_id'];
            $data['k'] = Perm::encryptUserInfo($d, $session_id);


            //
        } else {
            $msg = '用户不存在';
            $status = 250;
        }
        $this->data->addBody(-1, $data, $msg, $status);

    }


    /*webpos通过用户名获取用户id
 * */
    public function getUserIdByUsername()
    {
        $user_name = request_string('user_name');
        $User_InfoModel = new User_InfoModel();
        $user_info_data = $User_InfoModel->getOneByWhere(['user_name' => $user_name]);
        $data = [];
        if ($user_info_data) {
            $data['user_id'] = $user_info_data['user_id'];
            $msg = 'success';
            $status = 200;
        } else {
            $msg = 'failure:用户还没注册';
            $status = 250;
        }
        $this->data->addBody(-140, $data, $msg, $status);
    }

    public function editUserDetailTrueName()
    {
        $user_id = request_int('user_id');
        $user_realname = request_string('user_realname');

        $User_InfoDetailModel = new User_InfoDetailModel();
        $flag = $User_InfoDetailModel->editInfoDetail($user_id, ['user_truename'=>$user_realname]);
        if ($flag !== false)
        {
            $status = 200;
            $msg    = __('success');
        }
        else
        {
            $status = 250;
            $msg    = __('failure');
        }

        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);
    }

    public function editUserGrade()
    {
        $user_id = request_int('user_id');
        $user_grade = request_string('user_grade');

        $User_InfoDetailModel = new User_InfoDetailModel();
        $flag = $User_InfoDetailModel->editInfoDetail($user_id, ['user_grade'=>$user_grade]);
        if ($flag !== false)
        {
            $status = 200;
            $msg    = __('success');
        }
        else
        {
            $status = 250;
            $msg    = __('failure');
        }

        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);
    }

    public function updateUserInfoForWap()
    {
        $user_id = request_string('user_id');
        $user_nickname = request_string('user_nickname');
        $user_birthday = request_string('user_birthday');
        $user_logo = request_string('user_logo');
        $user_realname = request_string('user_realname');
        $User_InfoModel = new User_InfoModel();
        $User_infoDetailModel = new User_InfoDetail();

        $user = $User_InfoModel->getOne($user_id);
        if ($user_nickname) {
            $edit_row['nickname'] = $user_nickname;
        }
        if ($user_birthday) {
            $edit_row['user_birth'] = $user_birthday;
        }
        if ($user_logo) {
            $edit_row['user_avatar'] = $user_logo;
        }
        if ($user_realname) {
            $edit_row['user_truename'] = $user_realname;
        }
        $flag = $User_infoDetailModel->editInfoDetail($user_id, $edit_row);
        $data = array();
        $this->data->addBody(-140, $data, '', 200);
    }
}

?>