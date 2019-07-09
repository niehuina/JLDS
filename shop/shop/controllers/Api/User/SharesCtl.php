<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

class Api_User_SharesCtl extends Yf_AppController
{
    public $shares_DividendModel = null;
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
        $this->shares_DividendModel = new Shares_DividendModel();
    }

    public function getList()
    {
        $page = request_int('page', 1);
        $rows = request_int('rows', 20);
        $year = request_string('year');

        $cond_row = array();
        if($year){
            $cond_row['dividend_year'] = $year;
        }
        $sort     = array();
        $sort['dividend_datetime'] = 'desc';

        $data = $this->shares_DividendModel->listByWhere($cond_row, $sort, $page, $rows);
        $this->data->addBody(-140, $data);
    }

    public function addSharesDividend()
    {
        $data =null;
        $this->data->addBody(-140, $data);
    }

    public function save()
    {
        $dividend_year = request_string('dividend_year');
        $type = request_string('type');

        $flag = true;
        $share_price = Web_ConfigModel::value('shares_price');
        $shares_dividend = Web_ConfigModel::value('shares_dividend');
        $User_InfoModel = new User_InfoModel();
        $User_GradeLogModel = new User_GradeLogModel();
        if(array_search($type, ['all', 'all_g_partner', 'all_partner']) !== false) {
            $cond_row = array();
            if ($type == "all") {
                $cond_row['user_grade:>='] = 3;
                $cond_row['user_grade:<='] = 4;
            } else if ($type == "all_g_partner") {
                $cond_row['user_grade'] = 4;
            } else if ($type == "all_partner") {
                $cond_row['user_grade'] = 3;
            }
            $sort = array();
            $sort['user_regtime'] = 'desc';
            $user_num = $User_InfoModel->user_count($cond_row);

            $rows = 500;
            $total_page = ceil($user_num / $rows);
            Yf_Log::log($total_page, Yf_Log::LOG, 'shares');
            $total_amount = 0;
            for ($i = 1; $i <= $total_page; $i = $i + 1) {
                $user_list = $User_InfoModel->listByWhere($cond_row, $sort, $i, $rows);
                $user_ids = array_column($user_list['items'], 'user_id');
                Yf_Log::log($user_ids, Yf_Log::LOG, 'shares');

                $rs = self::shares_profit($user_ids, $dividend_year, $share_price, $shares_dividend);
                if ($rs['status'] == 250) {
                    $flag = false;
                    break;
                } else {
                    $total_amount += $rs['data']['total_amount'] * 1;
                }
            }
        }else if(array_search($type, ['all_one_year', 'part']) !== false){
            $cond_row = array();
            $user_ids = array();
            if($type == "all_one_year"){
                $cond_row['user_grade:>='] = 3;
                $cond_row['user_grade:<='] = 4;

                //获取升级到3级后，>=1年时间的股东
                $pre_year = date('Y-m-d H:i:s',strtotime("-1 year"));
                $cond_row_log['user_grade_to:in'] = [3,4];
                $cond_row_log['log_date_time:<='] = $pre_year;
                $order_row['log_date_time'] = 'asc';
                $user_ids_list = $User_GradeLogModel->getUserIdBySql($cond_row_log, $order_row);
                $user_ids = array_column($user_ids_list,'user_id');
                Yf_Log::log($user_ids, Yf_Log::LOG, 'shares');
            }else if($type == "part"){
                $user_ids = request_string('user_list');
                $user_ids = decode_json($user_ids);
                Yf_Log::log($user_ids, Yf_Log::LOG, 'shares');
            }

            $rows = 500;
            $total_amount = 0;
            for($i = 0; $i < count($user_ids); $i = $i + $rows) {
                $user_ids_temps = array_slice($user_ids,$i,$rows);
                $rs = self::shares_profit($user_ids_temps, $dividend_year, $share_price, $shares_dividend);
                if ($rs['status'] == 250) {
                    $flag = false;
                    break;
                } else {
                    $total_amount += $rs['data']['total_amount'] * 1;
                }
            }
        }

        if($flag){
            $Shares_DividendModel = new Shares_DividendModel();
            $add_row['dividend_datetime'] = get_date_time();
            $add_row['dividend_year'] = $dividend_year;
            $add_row['shares_price'] = $share_price;
            $add_row['shares_dividend'] = $shares_dividend;
            $add_row['dividend_amount'] = $total_amount;
            $id = $Shares_DividendModel->addSharesDividend($add_row, true);
        }

        if($flag && $id){
            $status = 200;
            $message = __('success');
        }else{
            $status = 250;
            $message = __('failure');
        }
        $this->data->addBody(-140, array(), $message, $status);
    }

    private function shares_profit($user_ids, $dividend_year, $share_price, $shares_dividend)
    {
        //将需要确认的订单号远程发送给Paycenter修改订单状态
        //远程修改paycenter中的订单状态
        $key = Yf_Registry::get('paycenter_api_key');
        $url = Yf_Registry::get('paycenter_api_url');
        $paycenter_app_id = Yf_Registry::get('paycenter_app_id');

        $formvars = array();
        $formvars['user_ids'] = $user_ids;
        $formvars['app_id'] = $paycenter_app_id;
        $formvars['desc'] = "{$dividend_year}年度股金分红";
        $formvars['share_price'] = $share_price;
        $formvars['shares_dividend'] = $shares_dividend;

        $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Paycen_PayRecord&met=shares_profit&typ=json', $url), $formvars);
        return $rs;
    }

    public function get_user_list()
    {
        $User_InfoModel = new User_InfoModel();
        $User_GradeLogModel = new User_GradeLogModel();

        $page = request_int('page', 1);
        $rows = request_int('rows', 20);

        $cond_row = array();
        $cond_row['user_grade:>='] = 3;
        $cond_row['user_grade:<='] = 4;

        $sort     = array();
        $sort['user_regtime'] = 'desc';
        $user_list = $User_InfoModel->listByWhere($cond_row, $sort, $page, $rows);
        foreach ($user_list['items'] as $key=>$user){
            $cond_log['user_id'] = $user['user_id'];
            $cond_log['user_grade_to:in'] = [3,4];
            $order['log_date_time'] = 'asc';
            $user_grade_logs =  $User_GradeLogModel->getByWhere($cond_log, $order);
            $user_grade_log = current($user_grade_logs);

            $log_date_time = $user_grade_log['log_date_time'];
            $diffDate = self::diffDate($log_date_time, get_date_time());
            $year = $diffDate['year'];
            $user_list['items'][$key]['update_date'] = $year;
        }
        $this->data->addBody(-140, $user_list);
    }

    /*
    *function：计算两个日期相隔多少年，多少月，多少天
    *param string $date1[格式如：2011-11-5]
    *param string $date2[格式如：2012-12-01]
    *return array array('年','月','日');
    */
    private function diffDate($date1,$date2){
        if(strtotime($date1)>strtotime($date2)){
            $tmp=$date2;
            $date2=$date1;
            $date1=$tmp;
        }
        list($Y1,$m1,$d1)=explode('-',$date1);
        list($Y2,$m2,$d2)=explode('-',$date2);
        $Y=$Y2-$Y1;
        $m=$m2-$m1;
        $d=$d2-$d1;
        if($d<0){
            $d+=(int)date('t',strtotime("-1 month $date2"));
            $m--;
        }
        if($m<0){
            $m+=12;
            $Y--;
        }
        return array('year'=>$Y,'month'=>$m,'day'=>$d);
    }
}