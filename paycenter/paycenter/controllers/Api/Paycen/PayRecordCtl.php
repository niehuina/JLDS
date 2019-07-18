<?php if (!defined('ROOT_PATH'))
{
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
class Api_Paycen_PayRecordCtl extends Api_Controller
{
    /**
     *交易流水
     *
     * @access public
     */

    function getRecordList() {
        $username  = request_string('userName');   //用户名称
        $payorder  = request_string('payOrder');   //支付单号
        $trade_type_id = request_int('trade_type_id');
        $page = request_int('page');
        $rows = request_int('rows');
        $cond_row = array();
        $Consume_RecordModel = new Consume_RecordModel();
        $data           = $Consume_RecordModel->getRecordList(null,null,null,$page,$rows,'asc',$username,$trade_type_id,$payorder);
        if ($data)
        {
            $msg    = 'success';
            $status = 200;
        }
        else
        {
            $msg    = 'failure';
            $status = 250;
        }
        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 获取符合条件的用户的交易记录
     * @throws Exception
     */
    function getRecordListByUserId()
    {
        $page = request_int('page');
        $rows = request_int('rows');
        $user_id  = request_string('user_id');   //用户Id
        $trade_type_id = request_int('trade_type_id'); //交易类型
        $user_type = request_int('user_type'); //用户入账类型
        $cond_row = array();
        $cond_row['user_id'] = $user_id;
        $cond_row['trade_type_id'] = $trade_type_id;
        $cond_row['user_type'] = $user_type;

        $order_row['record_time'] = 'desc';
        $Consume_RecordModel = new Consume_RecordModel();
        $data           = $Consume_RecordModel->listByWhere($cond_row,$order_row,$page,$rows);
        if ($data)
        {
            $amount_list = array_column($data['items'], 'record_money');
            $amount = array_sum($amount_list);
            $data['amount'] = $amount;
            $msg    = 'success';
            $status = 200;
        }
        else
        {
            $data['amount'] = 0;
            $msg    = 'failure';
            $status = 250;
        }
        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 获取符合条件的用户的交易金额
     * @throws Exception
     */
    function getRecordAmountByUserId()
    {
        $user_id  = request_string('user_id');   //用户Id
        $trade_type_id = request_row('trade_type_id'); //交易类型
        $user_type = request_int('user_type'); //用户入账类型
        $cond_row = array();
        $cond_row['user_id'] = $user_id;
        $cond_row['trade_type_id:in'] = $trade_type_id;
        $cond_row['user_type'] = $user_type;
        $Consume_RecordModel = new Consume_RecordModel();
        $data           = $Consume_RecordModel->getByWhere($cond_row);
        if ($data)
        {
            $amount_list = array_column($data, 'record_money');
            $amount = array_sum($amount_list);
            $data = array();
            $data['amount'] = $amount;
            $msg    = 'success';
            $status = 200;
        }
        else
        {
            $data['amount'] = 0.00;
            $msg    = 'failure';
            $status = 250;
        }
        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 获取用户成功充值的金额
     * @throws Exception
     */
    function getDepositAmountByUserId()
    {
        $deposit_user_id  = request_string('user_id');   //用户Id
        $cond_row = array();
        $cond_row['deposit_buyer_id'] = $deposit_user_id;
        $cond_row['deposit_trade_status'] = Consume_DepositModel::TRADE_STATUS_SUCCESS;
        $consume_DepositModel = new Consume_DepositModel();
        $data           = $consume_DepositModel->getByWhere($cond_row);
        if ($data)
        {
            $amount_list = array_column($data, 'deposit_total_fee');
            $amount = array_sum($amount_list);
            $data = array();
            $data['amount'] = $amount;
            $msg    = 'success';
            $status = 200;
        }
        else
        {
            $msg    = 'failure';
            $status = 250;
        }
        $this->data->addBody(-140, $data, $msg, $status);
    }

    //获取到还款期的白条订单
    public function getBtOrder()
    {
        $day_type = request_int('day_type');  //提醒还款：1：还有7天到还款限期  2：当天为还款期限
        $user_id = request_int('user_id');

        //查找用户的白条还款期限
        $User_ResourceModel = new User_ResourceModel();
        $user_res = $User_ResourceModel->getResource($user_id);
        $user_res = current($user_res);

        $user_credit_cycle = $user_res['user_credit_cycle'];

        if($day_type == 1)
        {
            $day = $user_credit_cycle*1 + 7;
        }
        else
        {
            $day = $user_credit_cycle;
        }

        $time1 = date("Y-m-d 00:00:00", strtotime('-'.$day.' day'));
        $time2 = date("Y-m-d 23:59:59", strtotime('-'.$day.' day'));

        $Consume_TradeModel = new Consume_TradeModel();
        $symbol = " and trade_create_time< '".$time2."' and trade_create_time>'".$time1."' and order_payment_amount>trade_payment_amount and buyer_id=".$user_id;

        $re = $Consume_TradeModel->getTradeId($symbol);

        //如果有到期需要还的订单，就将用户需要还的白条金额返回

        $result = array();
        if($re)
        {
            $result =  $user_res;
        }


        $this->data->addBody(-140, $result);
    }

    /**
     * 账单明细
     *
     * @access public
     */
    public function getRecordListForWap() {
        $page = request_int('page');
        $rows = request_int('rows');

        $cond_row = array();
        $cond_row['user_id'] = request_int('user_id');
        $cond_row['user_type'] = request_int('type_id');
        if(request_int('is_deliver')){
            $cond_row['trade_type_id'] = Trade_TypeModel::DELIVER;
        }

        $order_row['record_time'] = 'desc';
        $Consume_RecordModel = new Consume_RecordModel();
        $data           = $Consume_RecordModel->getRecordList1($cond_row,$order_row,$page,$rows);
        if ($data)
        {
            $msg    = 'success';
            $status = 200;
        }
        else
        {
            $msg    = 'failure';
            $status = 250;
        }
        $this->data->addBody(-140, $data, $msg, $status);
    }

    public function shares_profit()
    {
        $user_ids = request_row('user_ids');
        $desc = request_string('desc');
        $share_price = request_string('share_price');
        $shares_dividend = request_string('shares_dividend');

        $rs_row = array();
        $Consume_RecordModel = new Consume_RecordModel();
        //开启事物
        $Consume_RecordModel->sql->startTransactionDb();
        $total_amount = 0;
        if($user_ids){
            $User_ResourceModel = new User_ResourceModel();
            foreach ($user_ids as $user_id){
                $user_resource = $User_ResourceModel->getOne($user_id);
                $user_shares = $user_resource['user_shares']*1;
                $amount = $user_shares/($share_price*1)*($shares_dividend*1);

                //插入股金分红记录
                $record_row = array(
                    'user_id' => $user_id,
                    'record_money' => $amount,
                    'record_date' => date("Y-m-d"),
                    'record_year' => date("Y"),
                    'record_month' => date("m"),
                    'record_day' => date("d"),
                    'record_title' => $desc,
                    'record_desc' => $desc,
                    'record_time' => date('Y-m-d H:i:s'),
                    'trade_type_id' => Trade_TypeModel::SHARES_PROFIT,
                    'user_type' => '1',
                    'record_status' => RecordStatusModel::RECORD_FINISH,
                    'record_paytime' => date('Y-m-d H:i:s'),
                );

                $flag1 = $Consume_RecordModel->addRecord($record_row, true);
                check_rs($flag1,$rs_row);

                $edit_row['user_money'] = $amount;
                $edit_flag = $User_ResourceModel->editResource($user_id, $edit_row, true);
                check_rs($edit_flag,$rs_row);

                $total_amount = $total_amount + $amount;
            }
        }

        $flag = is_ok($rs_row);
        if ($flag && $Consume_RecordModel->sql->commitDb())
        {
            $msg    = 'success';
            $status = 200;
        }
        else
        {
            $Consume_RecordModel->sql->rollBackDb();
            $m      = $Consume_RecordModel->msg->getMessages();
            $msg    = $m ? $m[0] : _('failure');
            $status = 250;
        }
        $data = array();
        $data['total_amount'] = $total_amount;
        $this->data->addBody(-140, $data, $msg, $status);
    }


    public function deleteUser_index()
    {

    }

    /**
     * 获取退出用户
     * @throws Exception
     */
    public function getUserDeleteResourceInfo()
    {
        $status = request_int('status');
        $user_keys = request_int('user_keys');
        $user_cond_row['user_base.user_delete'] = 1;
        $user_cond_row['user_base.exit_settle_status'] = $status;

        $page = request_int('page', 1);
        $rows = request_int('rows', 20);
        $User_InfoModel = new User_InfoModel();
        $data = $User_InfoModel->getUserInfoListByKeys($user_keys, $user_cond_row, ['user_base.user_id'=>'desc'], $page, $rows);

        if ($data) {
            $msg = 'success';
            $status = 200;
        } else {
            $msg = 'failure';
            $status = 250;
        }
        $this->data->addBody(-140, $data, $msg, $status);
    }

    public function editExitUserResource()
    {
        $user_id = request_int('user_id');

        $User_BaseModel = new User_BaseModel();
        $User_ResourceModel = new User_ResourceModel();
        $Consume_RecordModel = new Consume_RecordModel();

        $rs_row = array();
        $User_ResourceModel->sql->startTransactionDb();
        $record_edit['record_status'] = RecordStatusModel::IN_HAND; //处理中
        $record_edit['record_paytime'] = get_date_time();
        $flag1 = $Consume_RecordModel->editRecord($user_id, $record_edit);
        check_rs($flag1, $rs_row);

        $user_resource_edit['user_money'] = 0;
        $user_resource_edit['user_money_frozen'] = 0;
        $flag2 = $User_ResourceModel->editResource($user_id, $user_resource_edit);
        check_rs($flag2, $rs_row);

        $flag3 = $User_BaseModel->editBase($user_id, ['exit_settle_status'=>1]);
        check_rs($flag3, $rs_row);

        if(is_ok($rs_row) && $User_ResourceModel->sql->commitDb()){
            Yf_Log::log("退出用户id：{$user_id}结算成功", Yf_Log::LOG, 'user_delete_settle');
            $msg = 'success';
            $status = 200;
        } else {
            $User_ResourceModel->sql->rollBackDb();
            Yf_Log::log("退出用户id：{$user_id}结算失败！", Yf_Log::LOG, 'user_delete_settle');
            $msg = 'failure';
            $status = 250;
        }
        $this->data->addBody(-140, array(), $msg, $status);
    }
}

?>