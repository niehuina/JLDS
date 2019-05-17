<?php namespace models;
/*
 *@desc yf_shop_base表数据处理
 * */
use models\users;
use models\wp_order;
use Illuminate\Database\Eloquent\SoftDeletes;

class yf_shop_base extends base{
    //软删除
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'yf_shop_base';
    //规则查看 https://github.com/vlucas/valitron
    public $rules = [
        'user_id'=>['required'],
        'title'=>['required',['lengthMax',75]],
        'address'=>['required'],
        'phone'=>['required','is_phone'],
        'shop_num'=>['required','integer',['min',0]],
    ];
    /*
    *@desc 搜索
    */
    public function scopeDefaultWhere($query)
    {
        parent::scopeDefaultWhere($query);
        $wq = request_data('wq');
        if($wq){
            $query->whereHas("users",function($q) use($wq)
            {
                $q->where('ucenter_name', 'like', "%".$wq."%");
            });
        }

        $shop_id = request_data('shop_id');
        if($shop_id){
            $query->where('id',$shop_id);
        }

        $in = \cs\login::login_user(1);
        if($in){
            $query->whereIn('id',$in);
        }   
    }
    /*
     *@desc 获取店铺账号信息
     */
    public function shop_users()
    {
        return $this->hasMany('models\shop_users','yf_shop_base_id');
    }
    /**
     *@desc 用户
     */ 
    public function users()
    {
        return $this->belongsTo('models\users','user_id','id');
    }
    /**
     *@desc 订单 
     */
    public function wp_order()
    {
        $start_time = request_data('start_time')?:date('Y-m-d 00:00:00', time());
        $end_time   = request_data('end_time')?:date('Y-m-d 23:59:59', time());
        $end_time = ($end_time == $start_time)?date('Y-m-d 23:59:59', strtotime($end_time)):$end_time;
        return $this->hasMany('models\wp_order','shop_id')
                    ->where('created','>=',strtotime($start_time))
                    ->where('created','<=',strtotime($end_time))
                    ->where('type','!=',1);
    }
    /**
     *@desc 订单 
     */
    public function wp_order_return()
    {
        $start_time = request_data('start_time')?:date('Y-m-d 00:00:00', time());
        $end_time   = request_data('end_time')?:date('Y-m-d 23:59:59', time());
        $end_time = ($end_time == $start_time)?date('Y-m-d 23:59:59', strtotime($end_time)):$end_time;
        return $this->hasMany('models\wp_order_return','shop_id')
                    ->where('created','>=',strtotime($start_time))
                    ->where('created','<=',strtotime($end_time))
                    ->where('type','!=',1);
    }

    /*
     *@desc ajax列表页面数据处理
     */
    static function ajax_data_reset($data){
        $payment_way = config('payment');
        $arr = [
            '商品销售'=>'wp_order,payid,good_price,ended,wp_order_return,goods_price',
            /*'会员充值'=>'wp_recharge,payment_way,money,created',*/
            '现金收支'=>[ 
              'user_price',
              'return_price'
            ],
            '总计'=>'wp_order',
        ];


        foreach ($data as $v){ 
            unset($new_data);
            $new_data['title'] = $title = $v->title; 
            $new_data['first'] = "-";
            $i = 0; 
            $total = [];
            foreach($arr as $k1=>$relative){ 
                //各种支付方式，对应的总数sum
                foreach($payment_way as $_k => $payid){ 
                    $new_data[$_k] = "-";  
                }
                $flag = false;
                switch ($k1) {
                    case '商品销售':
                        $flag = true;
                        $xiao = $v->wp_order->sum('good_price') - $v->wp_order_return->sum('goods_price');
                        $new_data['first'] = __("销售额")." ".$xiao." ".__("订单数")." ".$v->wp_order->count();
                        break;

                    case '现金收支':
                        $flag = false;
                        $obj = $v->wp_order->where('payid',strval($payment_way['cash']));
                        $a = $obj->sum('user_price');
                        $b = $obj->sum('return_price');
                        $new_data['first'] = __("收入")." ".$a ." ".__("支出")." ".$b;
                        $new_data['cash'] = $obj->sum('good_price');

                        break;
                    case '总计':
                        $flag = true;
                        $new_data['first'] = "-";
                        break;
                }

               
                if($flag === true){  
                    if(strpos($relative,',')!==false){
                        $ex = explode(',', $relative); 
                        $orm = $ex[0];
                        $whereKey = $ex[1];
                        $sum = $ex[2];
                        //各种支付方式，对应的总数sum 
                        foreach($payment_way as $_k => $payid){ 
                            $new_data[$_k] = $v->$orm->where($whereKey,strval($payid))->sum($sum);
                        }
                    } 
                }
                if($k1 == '总计'){
                    foreach($payment_way as $_k => $payid){ 
                        $new_data[$_k] = "-";  
                    }
                }
                $output[$title][$k1] = (object)$new_data;
                $i++;
            }
        }
        return $output;
    }
    
    /*
     *@desc 根据登录账号获取所属门店信息
     *
     */
    static function shop_list(){
        $one = users::where('id',cookie('admin_id'))->where('ucenter_name',cookie('admin_user'))->first();
        if($one){
            $list = $one->yf_shop_base;
        }else{
            $two = shop_users::where('id',cookie('admin_id'))->where('user',cookie('admin_user'))->first();
            if($two){
                $list[] = yf_shop_base::where('id',$two->yf_shop_base_id)->first();
            }else{
                $list = yf_shop_base::get();
            }
        }
        return $list;
    }
    /*
     * @desc 保存表单数据
     *
     */
    static function saveForm(){
        $data = post_data();
        $max = users::where('id',$data['user_id'])->first();
        if($max){
            $max_stores = $max->yf_shop_base->count();
        }else{
            $max_stores = 0;
        }
        if($data['shop_num'] > $max['max_nums']){
            exit(json_encode(['status'=>0,'msg'=>__('店员数不能超过授权账号最大店员数')]));
        }
        if($data['id']){
            $data['updated'] = time();
            $model = self::find($data['id']);
        }else{
            if($max_stores >= $max['max_stores']){
                exit(json_encode(['status'=>0,'msg'=>__('已超出该账号的最大店铺数')]));
            }
            $data['created'] = time();
            $model = new self;
        }
        $model->data($data)->save();
    }
    /*
     * @desc 删除表单数据
     */
    static function deleteForm($id){
        $model = self::where('id',$id);
        $model->delete();
        $shop_user = shop_users::where('yf_shop_base_id',$id);
        $shop_user->delete();
        $yf_goods_shop_common = yf_goods_shop_common::where('shop_id',$id);
        $yf_goods_shop_common->delete();
    }

}