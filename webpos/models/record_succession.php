<?php namespace models;

class record_succession extends base{

	protected $table = 'record_succession';
    /*
    *@desc 根据登录账号信息获取数据列表
    *
    */
    public function scopeDefaultWhere($query)
    {
        parent::scopeDefaultWhere($query);
        $wq = request_data('wq');
        $yf_shop_base_id = request_data('yf_shop_base_id');
        if($wq){
            $query->whereHas("shop_users",function($q) use($wq)
                {
                    $q->where('user', 'like', "%".$wq."%");
                });   
        }

        if($yf_shop_base_id){
            $query->where('yf_shop_base_id',$yf_shop_base_id);
        }

        $start_time = request_data('start_time')?:date('Y-m-d 00:00:00', time());
        $end_time   = request_data('end_time')?:date('Y-m-d 23:59:59', time());
        $end_time = ($end_time == $start_time)?date('Y-m-d 23:59:59', strtotime($end_time)):$end_time;
        $query->where('start_time','>=',strtotime($start_time))->where('start_time','<=',strtotime($end_time));

        $in = \cs\login::login_user(1);
        if($in){
            $query->whereIn('yf_shop_base_id',$in);
        } 
    }
	/**
     * @desc 获取账号信息
     */
    public function shop_users()
    {
        return $this->hasOne('models\shop_users','id','shop_users_id');
        
    }
    /**
     *@desc  获取所属门店信息
     */
    public function yf_shop_base()
    {
        return $this->hasOne('models\yf_shop_base','id','yf_shop_base_id'); 
    }
    /*
     * @desc 删除表单数据
     * */
    static function deleteForm($id){
        $model = self::where('id',$id);
        $model->delete();
    }

    /*
     * 插入交接班记录信息
     * */
    static function saveForm(){
        $data = post_data();
        if(!$data['spare']){
            exit(json_encode(['status'=>0,'msg'=>__('请输入备用金') ]));
        }

        $date['end_time'] = time();
        $date['cash_payments'] = $data['cash_payments'];
        $date['unionpay_pay'] = $data['unionpay_pay'];
        $date['weixin_pay'] = $data['weixin_pay'];
        $date['alipay_pay'] = $data['alipay_pay'];
       /* $date['wp_users_pay'] = $data['wp_users_pay'];*/
        $date['standby_money'] = $data['spare'];

        $info = record_succession::where('id',cookie('record_succession_id'))->update($date);
        return $info;
    }

    /*
     * 插入登录时间用户信息
     * */
    static function login_time_info($one){
        $two = self::where('shop_users_id',$one->id)
            ->where('yf_shop_base_id',$one->yf_shop_base_id)
            ->where('start_time','>=',strtotime(date('Y-m-d')))
            ->first();
        if(!$two){
            $data['shop_users_id'] = $one->id;
            $data['yf_shop_base_id'] = $one->yf_shop_base_id;
            $data['start_time'] = time();
            $id = self::insertGetId($data);
            cookie('record_succession_id',$id);
            cookie('first_login_time',date('Y-m-d H:i',time()));
        }else{
            cookie('record_succession_id',$two->id);
            cookie('first_login_time',date('Y-m-d H:i',$two->start_time));
        }
    }

    /*
     * 员工交接班信息数据显示
     * */
    static public function shop_shift(){
        /*
             * 获取订单数据
             * */
        $order_list = wp_order::where('shop_users_id',cookie('id'))
            ->where('shop_id',cookie('shop_id'))
            ->where('created','>=',strtotime(date('Y-m-d')))
            ->where('type','!=',1)
            ->where('order_status',6)
            ->get();
        $cash_payments_num = 0;
        $unionpay_pay_num = 0;
        $weixin_pay_num = 0;
        $alipay_pay_num = 0;
        /*$wp_users_pay_num = 0;*/
        foreach($order_list as $k=>$v){
            if($v->payid == config('payment.cash')){
                $cash_payments_num += $v->good_price;
            }elseif($v->payid == config('payment.unionpay')){
                $unionpay_pay_num += $v->good_price;
            }elseif($v->payid == config('payment.wepay')){
                $weixin_pay_num += $v->good_price;
            }elseif($v->payid == config('payment.alipay')){
                $alipay_pay_num += $v->good_price;
            }/*elseif($v->payid == config('payment.member')){
                $wp_users_pay_num += $v->good_price;
            }*/

        }
        /*
         * 获取退货数据
         */
        $order_return_list = wp_order_return::where('shop_users_id',cookie('id'))
            ->where('shop_id',cookie('shop_id'))
            ->where('created','>=',strtotime(date('Y-m-d')))
            ->get();
        foreach($order_return_list as $k=>$v){
            if($v->return_payid == config('payment.cash')){
                $cash_payments_num -= $v->goods_price;
            }elseif($v->return_payid == config('payment.wepay')){
                $weixin_pay_num -= $v->goods_price;
            }elseif($v->return_payid == config('payment.alipay')){
                $alipay_pay_num -= $v->goods_price;
            }/*elseif($v->return_payid == config('payment.member')){
                $wp_users_pay_num -= $v->goods_price;
            }*/
        }
        $data['cash_payments'] = $cash_payments_num;//现金支付
        $data['unionpay_pay'] = $unionpay_pay_num;//银联支付
        $data['weixin_pay'] = $weixin_pay_num;//微信支付
        $data['alipay_pay'] = $alipay_pay_num;//支付宝支付
        /*$data['wp_users_pay'] = $wp_users_pay_num;*///会员余额支付
        $data['syze'] = $data['cash_payments']+$data['unionpay_pay']+$data['weixin_pay']+$data['alipay_pay'];//收银总额
        return $data;
    }
}