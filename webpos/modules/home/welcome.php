<?php
namespace modules\home;
use cs\sync\pay;
use models\wp_order;
use models\wp_order_return;
use models\wp_order_value;
use models\wp_users;
use models\wp_users_discount;
use models\yf_goods_cat as model;
use models\yf_goods_common;
use models\yf_goods_shop_common;
use models\shop_users;
use models\record_succession;
use models\member_record;
use cookie;
use file;
use Requests;
use cs\sync\pay as apipay;
/**
 * @desc 首页
 *
 */
class welcome extends \cs\controller{

    protected $allowAction = [
        'ticket_code_pay'
    ];
    /**
     * @desc 商品一级分类
     *
     */
    public function index(){
        if(is_ajax()){
            $model = model::DefaultWhere()->get();
            exit(json_encode(['status'=>1,'data'=>$model]));
        }
        return view('index');
    }
    /**
     * @desc 登录员工信息
     *
     */
    public function shop_info(){
        $model = shop_users::where('id',cookie('id'))->get();
        exit(json_encode(['status'=>1,'data'=>$model]));
    }
    
    /*
    商品信息
    */
    public function products()
    {
        

        if (is_ajax())
        {
            $model = yf_goods_shop_common::WpusersWhere()->paginate(12);
            foreach ($model as $key => $value)
            {
                    $data[] = $value->yf_goods_common;
            }
            exit(json_encode(['status'=>1,'data'=>$data,'total'=>$model->lastPage() ]));
        }
    }
    /*
     * 结账页面
     * */
    public function pay(){
        if(is_ajax()){
            $date = post_data();
            $data = wp_order::order_payment($date);
            $output['html'] = view('pay',$data);
            echo json_encode(['status'=>true,'html'=>$output['html'],'render'=>'ajax_load_table']);
            exit;

        }

    }
    /*
     * 收银结束继续收银页面数据显示
     *
     * */
    public function pay_complete(){
        if(is_ajax()){
            $data = post_data();
            $arr = wp_order::order_detail($data['order_id']);

            $output['html'] = view('pay_complete',$arr);
            echo json_encode(['status'=>true,'html'=>$output['html'],'render'=>'ajax_load_table']);
            exit;

        }

    }

    public function user_index(){
        if(is_ajax()){
            $output['html'] = view('index');
            echo json_encode(['status'=>true,'html'=>$output['html'],'render'=>'ajax_load_table']);
            exit;

        }

    }
    /*
     * 支付宝微信支付宝二维码生成
     *
     * */
    public function show_code(){

        $res = pay::getPayorder(request_data('order_id'),request_data('amount'),request_data('payment_way'),request_data('title'));

        if(request_data('payment_way') == 'wx'){
            preg_match('/<img.*?src=[\"|\']?(.*?)[\"|\']?\s.*?>/i',$res,$macthes);
            echo json_encode($macthes[0]);
            exit;
        }else{
            if(get_data('payment_way') == 'alipay'){

                echo $res;exit;
            }
        }

    }


    /*
     * 获取支付宝微信支付订单状态
     *
     * */
    public function order_status()
    {
        if(is_ajax()){
            $data = post_data();
            $res = pay::getOrderstatus($data['order_id']);
            echo json_encode($res);
            exit;
        }

    }
    /*
     * 结算订单，提交订单,同步数据
     */
    public function settlement(){

        if(is_ajax()){
            $data = post_data();

            switch($data['type']){
                case config('payment.cash'):
                    $order = wp_order::addOrderinfo();
                    break;
                case config('payment.alipay'):

                    $order = wp_order::addOrderinfo();

                    break;
                case config('payment.wepay'):
                    $order = wp_order::addOrderinfo();

                    break;
                case config('payment.unionpay'):
                    $order = wp_order::addOrderinfo();

                    break;
                case config('payment.xpsm'):
                    $order = wp_order::addOrderinfo();

                    break;
                case config('payment.member'):
                    $pwd=md5($data['password']);
                    if($data['user']){
                        $user = $data['user'];
                    }
                    $wp_users = wp_users::where('id',$user)->first();
                    if($wp_users){
                        $rs=apipay::getUserInfo($wp_users->ucenter_id);
                        if($pwd==$rs['data']['user_pay_passwd']){
                            $order = wp_order::addOrderinfo();
                        }else{
                            return false;
                        }
                    }
                    //$order = wp_order::addOrderinfo();
                    break;
                default:
                    break;
            }

        }

        echo json_encode(['status'=>$order]);
        exit;

    }





    /**
     * 员工交接班页面
     */
    public function shift(){

        if(is_ajax()){
            $data = record_succession::shop_shift();
            $output['html'] = view('shift',$data);
            echo json_encode(['status'=>true,'html'=>$output['html'],'render'=>'ajax_load_table']);
            exit;
        }
    }



    /**
     * 会员管理
     */
    public function member(){

        if(is_ajax()){
            $output['html'] = view('member_list');
            echo json_encode(['status'=>true,'html'=>$output['html'],'render'=>'ajax_load_table']);
            exit;

        }

    }
    /**
     * 关于我们
     */
    public function logo(){

        if(is_ajax()){
            $output['html'] = view('logo');
            echo json_encode(['status'=>true,'html'=>$output['html'],'render'=>'ajax_load_table']);
            exit;

        }

    }
    /**
     * 退货管理
     */
    public function return_products(){

        if(is_ajax()){
            $output['html'] = view('return_products');
            echo json_encode(['status'=>true,'html'=>$output['html'],'render'=>'ajax_load_table']);
            exit;
        }

    }


    /**
     * @desc 保存数据
     *
     */
    public function save(){
        if(is_ajax()){
            wp_users_discount::saveForm();
            exit(json_encode(['status'=>1,'msg'=>__('操作成功') ]));
        }
    }

    public function saveRecord(){
        if(is_ajax()){
            $result= member_record::saveForm();
            exit(json_encode(['status'=>$result,'msg'=>__('操作成功') ]));
        }
    }
    /**
     * @desc 商品退货
     *
     */
    public function order_return(){
        if(is_ajax()){
            $result = wp_order_return::saveForm();
            if($result == true){
                $output['html'] = view('return_products');
                echo json_encode(['status'=>true,'msg'=>__('退货成功'),'html'=>$output['html'],'render'=>'ajax_load_table']);
                exit;
            }else{
                exit(json_encode(['status'=>0,'msg'=>__('退货失败') ]));
            }

        }
    }

    /*
     * 用户交接班退出
     * */
    public function shiftPost(){
        if(is_ajax()){
            $info = record_succession::saveForm();
            if($info == true){
                $login = new login();
                $login->clear_cookie();
                exit(json_encode(['status'=>1,'msg'=>__('交接班退出成功') ,'url'=>url('home/login') ]));
            }
        }
    }
    

    /**
     * 小票扫码
     */
    public function ticket(){

        if(is_ajax()){

            $output['html'] = view('ticket');

            echo json_encode(['status'=>true,'html'=>$output['html'],'render'=>'ajax_load_table']);
            exit;
        }
    }

    
    /*
     * 小票订单数据
     *
     * */
    public function order_ticket(){
        if(is_ajax()){
            $data = post_data();
            if($data['type'] == 1){
                $list = wp_order::sweep_order(config('payment.xpsm'),1);
            }elseif($data['type'] == 6){
                $list = wp_order::sweep_order(config('payment.xpsm'),6);
            }elseif($data['order_id']){
                if($data['mold'] == 'detail'){

                    $list['detail'] = wp_order_value::where('order_id',$data['order_id'])->get();
                    foreach($list['detail'] as $k=>$v){
                        $list['detail'][$k]['info'] = $v->yf_goods_common;
                    }

                    $list['order'] = wp_order::where('order_id',$data['order_id'])
                        ->first();
                }elseif($data['mold'] == 'order'){
                    $list = wp_order::order_array($data['order_id'],config('payment.xpsm'));
                    if(!$list){
                        $list = wp_order::where('order_id',$data['order_id'])
                            ->where('created',strtotime(date('Y-m-d')))
                            ->where('payid',config('payment.xpsm'))
                            ->first();
                    }
                }
            }
            return json_encode($list);
        }
    }
    /*
     * 小票订单数据搜索订单手机号
     *
     * */
    public function order_serach(){
        if(is_ajax()){
            $model = wp_users_discount::WpusersWhere()->get();
            foreach ($model as $key => $value) {
                if($value->wp_users->phone ){
                    $wp_users[] = $value;
                }
            }
            
            if($wp_users){
                $order_list = $wp_users[0]->wp_users->wp_order->where('payid',6);
            }else{

                $order_list = wp_order::OrdersWhere()->where('payid',6)->get();

            }
            if($order_list){

                foreach ($order_list as $k1 => $v1) {
                    $data[] = $v1;
                }
            }
            exit(json_encode(['status'=>1,'data'=>$data]));
        }

    }


    /*
     * 员工交接班小票页面打印
     */
    public function shift_ticket(){
        $id = cookie('record_succession_id');
        $info = record_succession::where('id',$id)->first();
        if($info){
            $data['id'] = $info->id;
            $data['shop_name'] = cookie('shop_name');
            $data['home_nickname'] = cookie('home_nickname');
            $data['start_time'] = date('Y-m-d H:i',$info->start_time);
            $data['cash_payments'] = $info->cash_payments;
            $data['unionpay_pay'] = $info->unionpay_pay;
            $data['weixin_pay'] = $info->weixin_pay;
            $data['alipay_pay'] = $info->alipay_pay;
            $data['wp_users_pay'] = $info->wp_users_pay;
            $data['standby_money'] = $info->standby_money;
            $data['yjje'] = $info->standby_money + $info->cash_payments;
            $data['syze'] = $info->cash_payments + $info->unionpay_pay + $info->weixin_pay + $info->alipay_pay + $info->wp_users_pay;
        }

        return view('shift_ticket',$data);

    }


    /*
     * 判断扫码客户端是微信还是支付宝
     * */
    public function ticket_code_pay(){
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {

            $result = 'wx';

        }
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'AlipayClient') !== false) {

            $result = 'alipay';

        }
        if(!$result){
            return false;
        }

        $info = wp_order::where('order_id',request_data('order_id'))->first();

        $url = config('paycenter.url')."?ctl=Api_WebPos&met=createOrder&order_id=".request_data('order_id')."&amount=".$info->good_price."&payment_way=".$result."&trade_title=".cookie('shop_name').__('订单支付');

        redirect($url);

    }

    /*
     * 小票扫码订单
     * */
    public function small_ticket_sweep(){

        $order_id = request_data('order_id');
        if($order_id){
            $info = wp_order::where('order_id',$order_id)->first();
            $data['order_id'] = $info->order_id;
            $data['time'] = date('Y-m-d H:i:s',$info->created);
            $data['shop_users_name'] = $info->shop_users->nickname;
            $data['payid'] = array_search($info->payid,config('payment'));
            $data['id'] = $info->id;
            $wp_users = wp_users_discount::where('users_id',cookie('users_id'))->where('wp_user_id',$info->wp_users->id)->first();
            $data['numbers'] = $wp_users->numbers?:__('无');
            if($info->coupon == 1){
                $data['discount'] = $wp_users->discount == 100 ?__('无优惠'):__('会员').($wp_users->discount/10).__('折优惠');
            }else{
                $data['discount'] = __('无优惠');
            }
            $data['goods'] = $info->wp_order_value;
            $data['goods_price'] = $info->good_price;
            $data['ljxf'] = wp_order::where('user_id',$info->user_id)
                ->where('order_status',6)
                ->where('type','!=',1)
                ->get()
                ->sum('good_price')?:0;
            $data['shop_name'] = $info->yf_shop_base->title;

        }
        return view('small_ticket_sweep',$data);
    }


}