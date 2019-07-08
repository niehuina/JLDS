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

        $add_row['dividend_datetime'] = get_date_time();
        $add_row['dividend_year'] = $dividend_year;
        $add_row['shares_price'] = Web_ConfigModel::value('shares_price');
        $add_row['shares_dividend'] = Web_ConfigModel::value('shares_dividend');
        $Shares_DividendModel = new Shares_DividendModel();
        $id = $Shares_DividendModel->addSharesDividend($add_row, true);

        //Todo 保存用户，给用户分配分红，计算总分红金额
//        $User_InfoModel = new User_InfoModel();
//        $User_GradeLogModel = new User_GradeLogModel();
//        $cond_row = array();
//        if($type == "all"){
//            $cond_row['user_grade:>='] = 3;
//            $cond_row['user_grade:<='] = 4;
//        }else if($type == "all_g_partner"){
//            $cond_row['user_grade'] = 4;
//        }else if($type == "all_partner"){
//            $cond_row['user_grade'] = 3;
//        }else if($type == "all_one_year"){
//            $cond_row['user_grade:>='] = 3;
//            $cond_row['user_grade:<='] = 4;
//
//            //获取升级到3级后，>=1年时间的
//            $pre_year = date('Y-m-d H:i:s',strtotime("-1 year"));
//            $cond_row_log['user_grade_to'] = 3;
//            $cond_row_log['log_date_time:<='] = $pre_year;
//            $user_log_list = $User_GradeLogModel->getByWhere($cond_row_log);
//            $user_ids = array_column($user_log_list,'user_id');
//            if(count($user_ids) > 0){
//                $cond_row['user_id:in'] = $user_ids;
//            }else{
//                $cond_row['user_id'] = 'user_id';
//            }
//        }
//
//        $sort     = array();
//        $sort['user_regtime'] = 'desc';
//        $user_list = $User_InfoModel->getByWhere($cond_row, $sort);

        if($id){
            $status = 200;
            $message = __('success');
        }else{
            $status = 250;
            $message = __('failure');
        }
        $this->data->addBody(-140, array(), $message, $status);
    }

    public function get_user_list()
    {
        $User_InfoModel = new User_InfoModel();
        $User_GradeLogModel = new User_GradeLogModel();

        $page = request_int('page', 1);
        $rows = request_int('rows', 20);
        $type = request_string('type', 'all');

        $cond_row = array();
        if($type == "all"){
            $cond_row['user_grade:>='] = 3;
            $cond_row['user_grade:<='] = 4;
        }else if($type == "all_g_partner"){
            $cond_row['user_grade'] = 4;
        }else if($type == "all_partner"){
            $cond_row['user_grade'] = 3;
        }else if($type == "all_one_year"){
            $cond_row['user_grade:>='] = 3;
            $cond_row['user_grade:<='] = 4;

            //获取升级到3级后，>=1年时间的
            $pre_year = date('Y-m-d H:i:s',strtotime("-1 year"));
            $cond_row_log['user_grade_to'] = 3;
            $cond_row_log['log_date_time:<='] = $pre_year;
            $user_log_list = $User_GradeLogModel->getByWhere($cond_row_log);
            $user_ids = array_column($user_log_list,'user_id');
            if(count($user_ids) > 0){
                $cond_row['user_id:in'] = $user_ids;
            }else{
                $cond_row['user_id'] = 'user_id';
            }
        }

        $sort     = array();
        $sort['user_regtime'] = 'desc';
        $user_list = $User_InfoModel->listByWhere($cond_row, $sort, $page, $rows);
        foreach ($user_list['items'] as $key=>$user){
            $cond_log['user_id'] = $user['user_id'];
            $cond_log['user_grade_to'] = 3;
            $order['log_date_time'] = 'desc';
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