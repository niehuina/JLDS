<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}

/**
 * Api接口, 让App等调用
 *
 *
 * @category   Game
 * @package    User
 * @author     banchangle <1427825015@qq.com>
 * @copyright  Copyright (c) 2016, 班常乐
 * @version    1.0
 * @todo
 */
class Api_Paycen_PayBaseCtl extends Api_Controller
{
    /**
     *支付会员
     *
     * @access public
     */

    function getPayBaseList()
    {
        $user_keys = request_string('user_keys');   //用户名称
        $cond_row = array();
        $cond_row['user_base.user_delete'] = 0;
        $User_InfoModel = new User_InfoModel();
        $page = request_int('page', 1);
        $rows = request_int('rows', 20);

        $data = $User_InfoModel->getUserInfoListByKeys($user_keys, $cond_row, ['user_base.user_id' => 'desc'], $page, $rows);
        if ($data) {
            $msg = 'success';
            $status = 200;
        } else {
            $msg = 'failure';
            $status = 250;
        }
        $this->data->addBody(-140, $data, $msg, $status);
    }

    function getEditBase()
    {
        $user_id = request_int("user_id");
        $User_BaseModel = new User_BaseModel();
        $data = $User_BaseModel->getOne($user_id);
        if ($data) {
            $msg = 'success';
            $status = 200;
        } else {
            $msg = 'failure';
            $status = 250;
        }
        $this->data->addBody(-140, $data, $msg, $status);
    }

    function editBaseRow()
    {
        $data['user_id'] = request_int("user_id");
        $data['user_nickname'] = request_string("user_account");
        $data['record_money'] = request_float("add_user_money");
        $data['record_desc'] = request_string("record_desc");
        $User_ResourceModel = new User_ResourceModel();
        $flag = $User_ResourceModel->editResource($data['user_id'], array("user_money" => $data['record_money']), true);
        if ($flag) {
            $data['order_id'] = "";
            $data['record_date'] = date("Y-m-d H:i:s");
            $data['record_time'] = date("Y-m-d H:i:s");
            $data['trade_type_id'] = 3;
            $data['user_type'] = 3;
            $data['record_status'] = 2;
            if ($data['record_money'] > 0) {
                $data['record_title'] = _("管理员增加用户余额");
            } else {
                $data['record_title'] = _("管理员减少用户余额");
            }
            $Consume_RecordModel = new Consume_RecordModel();
            $flag1 = $Consume_RecordModel->addRecord($data);
            if ($flag1) {
                $msg = 'success';
                $status = 200;
            } else {
                $msg = 'failure';
                $status = 250;
            }
        } else {
            $msg = 'failure';
            $status = 250;
        }

        $this->data->addBody(-140, $data, $msg, $status);

    }

    function getEditShares()
    {
        $this->getEditBase();
    }

    function editSharesRow()
    {
        $data['user_id'] = request_int("user_id");
        $data['user_nickname'] = request_string("user_account");
        $data['record_money'] = request_float("user_shares");
        $data['record_desc'] = request_string("record_desc");
        $User_ResourceModel = new User_ResourceModel();

        //开启事物
        $User_ResourceModel->sql->startTransactionDb();

        $data['order_id'] = "";
        $data['record_date'] = date("Y-m-d H:i:s");
        $data['record_year'] = date("Y");
        $data['record_month'] = date("m");
        $data['record_day'] = date("d");
        $data['record_time'] = date("Y-m-d H:i:s");
        $data['trade_type_id'] = Trade_TypeModel::SHARES;
        $data['user_type'] = Consume_RecordModel::RECORD;
        $data['record_status'] = 2;
        if ($data['record_money'] > 0) {
            $data['record_title'] = _("管理员增加用户股金");
        } else {
            $data['record_title'] = _("管理员减少用户股金");
        }

        //添加用户股金记录
        $Consume_RecordModel = new Consume_RecordModel();
        $flag = $Consume_RecordModel->addRecord($data);

        if ($flag) {
            //修改用户股金
            $flag1 = $User_ResourceModel->editResource($data['user_id'], array("user_shares" => $data['record_money']), true);
            $flag = $flag && $flag1;

            //用户股金是否已达标升级所需，如果未记录，则查询总股金是否达标
            $user_resource = $User_ResourceModel->getOne($data['user_id']);
            if ($user_resource['user_pay_shares_date'] == "") {
                //用户总股金
                $user_total_shares = $user_resource['user_shares'] * 1;

                //获取用户升级信息
                $key = Yf_Registry::get('shop_api_key');
                $url = Yf_Registry::get('shop_api_url');
                $shop_app_id = Yf_Registry::get('shop_app_id');
                $formvars = array();
                $formvars['app_id'] = $shop_app_id;
                $formvars['user_id'] = $data['user_id'];
                $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_User_Grade&met=getGrade3&typ=json', $url), $formvars);
                if ($rs['status'] == "200") {
                    $user_grade = $rs['data'];
                    $user_grade_shares = $user_grade['user_grade_shares'] * 1;
                    if ($user_total_shares >= $user_grade_shares) {
                        $user_pay_shares_date = get_date_time();

                        $rs1 = get_url_with_encrypt($key, sprintf('%s?ctl=Api_User_Info&met=updateUserToVIP&typ=json', $url), $formvars);

                        //修改用户股金达标的日期
                        $flag2 = $User_ResourceModel->editResource($data['user_id'], ['user_pay_shares_date' => $user_pay_shares_date]);
                        $flag = $flag && $flag2;
                    }
                }
            }
        }

        if ($flag && $User_ResourceModel->sql->commitDb()) {
            $msg = 'success';
            $status = 200;
        } else {
            $User_ResourceModel->sql->rollBackDb();
            $m = $User_ResourceModel->msg->getMessages();
            $msg = $m ? $m[0] : __('failure');
            $status = 250;
        }

        $this->data->addBody(-140, $data, $msg, $status);
    }

    function getEditStocks()
    {
        $this->getEditBase();
    }

    function editStocksRow()
    {
        $data['user_id'] = request_int("user_id");
        $data['user_nickname'] = request_string("user_account");
        $data['record_money'] = request_float("user_stocks");
        $data['record_desc'] = request_string("record_desc");
        $User_ResourceModel = new User_ResourceModel();

        //开启事物
        $User_ResourceModel->sql->startTransactionDb();

        $data['order_id'] = "";
        $data['record_date'] = date("Y-m-d H:i:s");
        $data['record_year'] = date("Y");
        $data['record_month'] = date("m");
        $data['record_day'] = date("d");
        $data['record_time'] = date("Y-m-d H:i:s");
        $data['trade_type_id'] = Trade_TypeModel::STOCKS;
        $data['user_type'] = Consume_RecordModel::RECORD;
        $data['record_status'] = 2;
        if ($data['record_money'] > 0) {
            $data['record_title'] = _("管理员增加用户备货金");
        } else {
            $data['record_title'] = _("管理员减少用户备货金");
        }

        //添加用户备货金记录
        $Consume_RecordModel = new Consume_RecordModel();
        $flag = $Consume_RecordModel->addRecord($data);

        if ($flag) {
            //修改用户备货金
            $flag1 = $User_ResourceModel->editResource($data['user_id'], array("user_stocks" => $data['record_money']), true);
            $flag = $flag && $flag1;

            //查询用户资金信息
            $user_resource = $User_ResourceModel->getOne($data['user_id']);

            //更新用户等级
            $key = Yf_Registry::get('shop_api_key');
            $url = Yf_Registry::get('shop_api_url');
            $shop_app_id = Yf_Registry::get('shop_app_id');
            $formvars = array();
            $formvars['app_id'] = $shop_app_id;
            $formvars['user_id'] = $data['user_id'];
            $formvars['user_shares'] = $user_resource['user_shares'];
            $formvars['user_stocks'] = $user_resource['user_stocks'];
            $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_User_Info&met=updateUserGradeToGPartner&typ=json', $url), $formvars);
            if ($rs['status'] == "200") {
                $flag = $flag && true;
            } else {
                $flag = false;
            }
        }

        if ($flag && $User_ResourceModel->sql->commitDb()) {
            $msg = 'success';
            $status = 200;
        } else {
            $User_ResourceModel->sql->rollBackDb();
            $m = $User_ResourceModel->msg->getMessages();
            $msg = $m ? $m[0] : __('failure');
            $status = 250;
        }

        $this->data->addBody(-140, $data, $msg, $status);
    }
}

?>