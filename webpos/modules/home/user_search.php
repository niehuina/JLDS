<?php
namespace modules\home;
use models\wp_users_discount as model;
use models\wp_users;
use models\yf_shop_base;
use models\users;
use models\wp_order;
use models\wp_order_value;
use models\wp_order_return;
use models\yf_goods_common;
/**
 * @desc 会员页面
 *
 */
class user_search extends \cs\controller{
    /**
     * @desc 搜索会员
     *
     */
    public function index(){

        if(is_ajax()){
            $model = model::WpusersWhere()->get();
            foreach ($model as $key => $value) {
                $data[] = $value->wp_users;
            }
            exit(json_encode(['status'=>1,'data'=>$data]));
        }
        return view('user_search');
    }
    /*
    会员详细信息
    */
    public function infos(){
        if(is_ajax()){
            $user_id = get_data();
            $time = time();
            $model = model::where('wp_user_id',$user_id)
                ->where('started','<=',$time)
                ->where('ended','>=',$time)
                ->get();
            foreach ($model as $key => $value) {
                $data[] = $value;
                $data[$key]['started'] = date('Y-m-d',$value->started);
                $data[$key]['ended'] = date('Y-m-d',$value->ended);
                $data[$key]['discount'] = $value->discount/10;
                if($data[$key]['discount'] == 10){
                    $data[$key]['discount'] = 0 ;
                }
            }
            exit(json_encode(['status'=>1,'data'=>$data]));
        }
    }

    /*
    会员详细信息
    */
    public function plural(){
        if(is_ajax()){
            $id = get_data();
            $model = wp_users::where('id',$id)->get();
            foreach ($model as $key => $value) {
                $data[] = $value;
                $data[$key]['created'] = date('Y-m-d',$value->created);
                
                if($data[$key]['bron'] > 0){
                    $data[$key]['bron'] = date('Y-m-d',$value->bron);
                }else{
                    $data[$key]['bron'] =  "";
                }

                if($value->sex == 1){
                    $data[$key]['sex'] = "男";
                }elseif($value->sex == 2){
                    $data[$key]['sex'] = "女";
                }elseif($value->sex == 3){
                    $data[$key]['sex'] = "保密";
                }else{
                    $data[$key]['sex'] = " ";
                }

            }
            exit(json_encode(['status'=>1,'data'=>$data]));
        }
    }
    /*
    订单信息
    */
    public function order_list(){
        if(is_ajax()){
           $model = model::WpusersWhere()->get();
            foreach ($model as $key => $value) {
                if($value->wp_users->phone ){
                    $wp_users[] = $value;
                }
            }
            
            if($wp_users){
                $order_list = $wp_users[0]->wp_users->wp_order;
            }else{

                $order_list = wp_order::OrdersWhere()->get();

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
    商品退货详情
    */
    public function info_good(){
        if(is_ajax()){
            $info = get_data();
            $list = wp_order_value::where('order_id',$info['order_id'])->get();
            $return_num = 0;
            $return_price = 0;

            foreach ($list as $key => $value) {
                $wp_order_return = $value->wp_order_return->where('goods_id',$value->goods_id);

                $data[$key]['order_id'] = $value->order_id;
                $data[$key]['goods_id'] = $value->goods_id;
                $data[$key]['goods_name'] = $value->goods_info->common_name;
                $data[$key]['num'] = ($value->num - $wp_order_return->sum('num')) <= 0 ?0:$value->num - $wp_order_return->sum('num');//剩余数量
                $data[$key]['file'] = $value->goods_info->file;
                $data[$key]['return_num'] = $wp_order_return->sum('num')?:0;//已退数量
                $data[$key]['order_num'] = $value->num;//购买数量
                $data[$key]['goods_price'] = ($value->goods_price - $wp_order_return->sum('goods_price'))<0?0:$value->goods_price - $wp_order_return->sum('goods_price');//剩余退货产品总金额
                $data[$key]['price'] = $value->goods_price/$value->num;
                $data[$key]['tmp_num'] = ($value->num - $wp_order_return->sum('num')) <= 0 ?0:$value->num - $wp_order_return->sum('num');
                $data[$key]['state'] = true;
                $return_num += $data[$key]['num'];//退款总数量
                $return_price += $data[$key]['goods_price'];//退款总金额
            }

            $arr['order_id'] = $info['order_id'];
            $pay_id = wp_order::where('order_id',$info['order_id'])->first();
            $arr['payid'] = array_search($pay_id->payid,config('payment'));
            $arr['return_num'] = $return_num;
            $arr['return_price']  = $return_price;
            $arr[] = $data;

            exit(json_encode(['status'=>1,'data'=>$arr]));
        }
    }
    /*
    订单详情
    */
    public function info_value(){
        if(is_ajax()){
            $order_id = get_data();

            $model = wp_order_value::where('id',$order_id)->get();
            
            foreach ($model as $key => $value) {
                $data[] =  $value;
            }
            exit(json_encode(['status'=>1,'data'=>$data]));
        }
    }
    /*
    商品详情
    */
    public function goods(){
        if(is_ajax()){
            $goods_id = get_data();
            $yf_goods_common = yf_goods_common::where('id',$goods_id['goods'])->first();
            $data = $yf_goods_common;
            exit(json_encode(['status'=>1,'data'=>$data]));
        }
    }

    /*
    会员详细信息
    */
    public function getOrder_id(){
        if(is_ajax()){
            $order_id="U" . date("Ymdhis", time()) . rand(100, 999);
            $data['order_id']=$order_id;
            exit(json_encode(['status'=>1,'data'=>$data]));
        }
    }

    /*
    会员详细信息
    */
    public function getPara(){
        if(is_ajax()){
            $info = get_data();
            $data['url']= url('home/welcome/show_code',['order_id'=>$info['order_id'] ,'amount'=>$info['amount'],'payment_way'=>'alipay','title'=>'门店充值']);
            exit(json_encode(['status'=>1,'data'=>$data]));
        }
    }



}