<?php namespace models;

use cs\sync\order;
use cs\sync\pay;
use cs\sync\user;
use DB;
use Exception;
class wp_order extends base{

    protected $table = 'wp_order';
    /*
     *@desc 根据登录账号信息获取数据列表 
     */
    public function scopeDefaultWhere($query)
    {
        parent::scopeDefaultWhere($query);
        /*
         *@desc 根据订单编号查询
         */
        $wq = request_data('wq');
        if($wq){
            $query->where(function($q)use($wq){

                $q->where('order_id', 'like', "%".$wq."%");
                
            });
        }
        /*
        *@desc 根据订单状态查询
        */
        $status = request_data('status');
        if($status){
            if($status == 6){
                $query->where('order_status','>',1);
            }else{
                $query->where('order_status',$status);
            }

        }
        /*
        *@desc 根据订单时间查询 
        */
        $start_time = request_data('start_time')?:date('Y-m-d 00:00:00', time());
        $end_time   = request_data('end_time')?date('Y-m-d 23:59:59', strtotime(request_data('end_time'))):date('Y-m-d 23:59:59', time());
        $query->where('created','>=',strtotime($start_time))->where('created','<=',strtotime($end_time));
        $in = \cs\login::login_user(1);
        if($in){
            $query->whereIn('shop_id',$in);
        }
    }

    public function scopeOrdersWhere($query)
    {
        $wq = request_data('wq');
        if($wq){
            $query->where(function($q)use($wq){
                $q->where('order_id', 'like', "%".$wq."%");

            });
        }
        $query->orderBy('ended', 'desc');
        $query->where('type',0);
        $query->where('shop_id',cookie('shop_id'));

    }
    /*
     *
     *@desc 获取门店信息
     */
    public function yf_shop_base(){
        return $this->belongsTo('models\yf_shop_base','shop_id');
    }
    /*
     *
     *@desc 获取收银员信息
     */
    public function shop_users(){
        return $this->belongsTo('models\shop_users','shop_users_id');
    }
    /*
     *
     *@desc 关联订单详情
     */
    public function wp_order_value(){
        return $this->hasMany('models\wp_order_value','order_id','order_id');
    }


    /**
     * @desc获取会员信息
     */
    public function wp_users()
    {
        return $this->belongsTo('models\wp_users','user_id','ucenter_id');
    }




    /*
     * 插入订单数据
     *
     * */
    static function addOrderinfo(){
        try{
            DB::transaction(function()
            {
                $data = post_data();
                if($data['user']){
                    $user = $data['user'];
                }
                $wp_users = wp_users::where('id',$user)->first();

                if($data['kind'] == 'ticket'){

                    $datz['payid'] = intval($data['type']);
                    $datz['order_status'] = 6;
                    $datz['ended'] = time();
                    $datz['good_price'] = $data['price'];
                    $datz['user_price'] = $data['actual']?:0;
                    $datz['return_price'] = $data['actual'] >= $data['price']? $data['actual'] - $data['price']:0;
                    self::where('order_id',$data['order_id'])->update($datz);

                    $daty['status'] = 6;
                    $daty['updated'] = time();
                    wp_order_value::where('order_id',$data['order_id'])->update($daty);
                    $date['good_price'] = $data['price'];
                }else{
                    $order_info = self::where('order_id',$data['order_id'])->first();
                    if($order_info){
                        return false;
                    }
                    $date['order_id'] = $data['order_id'];
                    $date['shop_users_id'] = cookie('id');
                    $date['payid'] = $data['type'];
                    $date['good_num'] = $data['num'];
                    $date['created'] = time();
                    $date['ended'] = time();
                    $date['shop_id'] = cookie('shop_id');
                    if($data['type'] == config('payment.xpsm')){
                        $date['order_status'] = 1;
                        $date['payment_way'] = config('payment.xpsm');
                    }else{
                        $date['order_status'] = 6;
                    }
                    $date['good_price_ori'] = $data['totalprice'];
                    $date['good_price'] = $data['price'];
                    $date['user_price'] = $data['actual']?:$date['good_price'];
                    $date['return_price'] = $data['return_price'];

                    if($wp_users){
                        $date['user_id'] = $wp_users->ucenter_id;
                        $date['coupon'] = 1;
                    }
                    $id = self::insertGetId($date);
                    if ($id)
                    {
                        $order_id = wp_order::where('id', $id)->first()->order_id;
											
						//如果是账户余额支付，减少webpos账户余额并同步paycenter的账户余额
						if($data['type'] == config('payment.member')){
							$payvars  = array();
							$payvars['user_id']  = $wp_users->ucenter_id;
							$payvars['pay_amount']  =  $data['price'];
                            $payvars['order_id']  =  $data['order_id'];
							$rs = pay::syncUserResouce($payvars);
							if($rs['status']!=200){
								return false;
							}
							$wpuser =  array();
							$account_balance = $wp_users->account_balance;
							$wpuser['account_balance'] = $account_balance - $data['price'];
							wp_users::where('id',$user)->update($wpuser);
						}

						if($wp_users) {
							//平台红包更改状态
                            if($data['redpacket_id']){
                                $redpacket_id = $data['redpacket_id'];
                                order::updateStateForRP($redpacket_id, $order_id);
                            }
							//平台优惠券更改状态
                            if($data['voucher_id']){
                                $voucher_id = $data['voucher_id'];
                                order::updateStateForVoucher($voucher_id, $order_id);
                            }
						}
						
                        foreach ($data['goods'] as $k2 => $v2)
                        {
                            $good_id = $v2[0];
                            $num = $v2[1];
                            $good_price = $v2[2];

                            $goods_info = yf_goods_common::where('id', $good_id)->first();

                            $daty['order_id']       = $order_id;
                            $daty['goods_id']       = $goods_info->id;
                            $daty['goods_name']     = $goods_info->common_name;
                            $daty['goods_values']   = $goods_info->common_spec_name;
                            $daty['goods_price']    = $good_price*$num;
                            $daty['good_price_ori'] = $goods_info->common_price * $num;

                            $daty['shop_id']        = cookie('shop_id');
                            $daty['shop_name']      = cookie('shop_name');
                            $daty['shop_users_id']  = cookie('id');
                            $daty['num']            = $num;
                            if($data['type'] == config('payment.xpsm')){
                                $daty['status'] = 1;
                            }else{
                                $daty['status'] = 6;
                            }
                            $daty['created']        = time();
                            wp_order_value::insert($daty);

                            $str['common_stock'] = intval($goods_info->common_stock - $num);
                            $goods_info->where('id', $good_id)->update($str);
                            if ($goods_info->common_goods_from == 1)
                            {
                                //商城导入商品同步库存
                                order::getGoodscommon($goods_info->goods_id,'reduce',$num);
                            }
                        }								
                    }
                }
            });
        }catch(Exception $e){
            return false;
        }
        return true;
    }

    /*
     * 根据支付方式与订单状态获取订单信息
     * */
    static public function sweep_order($payid,$order_status){
        $list = self::where('payment_way',$payid)
            ->where('shop_users_id',cookie('id'))
            ->where('shop_id',cookie('shop_id'))
            ->where('order_status',$order_status)
            ->where('type','!=',1)
            ->where('created','>=',strtotime(date('Y-m-d')))
            ->get();

        if($payid == config('payment.xpsm') && $order_status == 1 && $list->toArray()){
            $arr = array();
            foreach($list as $k=>$v){
                $res = pay::getOrderstatus($v->order_id);

                if($res['data']['order_state_id'] == 2){
                    $data['order_status'] = 6;
                    $data['payid'] = $res['data']['payment_channel_id'];
                    self::where('order_id',$v->order_id)->update($data);
                    continue;
                }
                $arr[] = $v;
            }
            $list = $arr;
        }


        return $list;
    }
    
    /*
     * 将当前用户的所有订单拼成数据待使用
     * */
    static public function order_array($order_id,$payid = null){
        if($payid){
            $list = self::where('shop_id',cookie('shop_id'))
                ->where('created',strtotime(date('Y-m-d')))
                ->where('type','!=',1)
                ->where('payid',config('payment.xpsm'))
                ->get();
        }else{
            $list = self::where('shop_id',cookie('shop_id'))->where('type','!=',1)->get();
        }
        $arr = array();
        if($list){
            foreach ($list as $key=>$value)
            {
                $id = str_replace(array("YF-","-"),"",$value->order_id);
                $arr[$id] = $value;
            }
        }
        return $arr[$order_id];
    }


    /*
     * 根据订单order_Id返回订单详细信息列表
     *
     * */
    static public function order_detail($order_id){
        $arr = array();
        $common = array();
        $list = self::where('order_id',$order_id)->first();
        $arr['goods_info']['num'] = $list->good_num;//订单商品数量
        $arr['goods_info']['order_id'] = $order_id;//订单编号
        $arr['goods_info']['created'] = date('Y-m-d H:i:s',$list->created);//订单创建时间
        $arr['goods_info']['shop_users_name'] = cookie('home_nickname');//收银员
        $arr['goods_info']['price'] = $list->good_price;//商品总价
        if($list->user_id){
            $info = wp_users_discount::where('wp_user_id',$list->wp_users->id)->where('users_id',cookie('users_id'))->first();
            $numbers = $info->numbers;
            if($info->status == 1){
                $yh = $info->discount == 100?__('无'):$info->discount/10;
            }else{
                $yh = __('无');
            }

        }else{
            $numbers = __('无');
            $yh = __('无');
        }
        $arr['goods_info']['numbers'] = $numbers;//会员编号
        $arr['goods_info']['yh'] = $yh;//优惠
        $arr['goods_info']['type'] = $list->payid;//支付方式
        $arr['goods_info']['sjbh'] = $list->id;//收据编号
        $arr['goods_info']['shop_name'] = $list->yf_shop_base->title;
        $goods_list = wp_order_value::where('order_id',$order_id)->get();
        foreach($goods_list as $k=>$v){
            $common[$v->id]['name'] = $v->yf_goods_common->common_name;
            $common[$v->id]['nums'] = $v->num;
            $common[$v->id]['goods_price'] = $v->goods_price;
            $common[$v->id]['common_price'] = $v->yf_goods_common->common_price;
            $common[$v->id]['common_code'] = $v->yf_goods_common->common_code;
        }
        $arr['goods_info']['goods'] = $common;
        $payment = array_search($list->payid,config('payment'));
        switch($payment){
            case 'wepay':
                $payment = __('微信');
                break;
            case 'alipay':
                $payment = __('支付宝');
                break;
            case 'cash':
                $payment = __('现金');
                break;
            case 'member':
                $payment = __('账户余额');
                break;
            case 'xpsm':
                $payment = __('小票扫码');
                break;
            case 'unionpay':
                $payment = __('银行卡');
                break;
            default:
                break;

        }
        $arr['goods_info']['status'] = $list->order_status;
        $arr['goods_info']['payid'] = $payment;
        $arr['goods_info']['payment_id'] = $list->payid;

        return $arr;
    }


    /*
     * 订单结算数据处理
     * */
    static public function order_payment($data){
        $totalPrice = $data['price']; //订单应付金额
        $yhje = 0.00; //订单折扣
        $good_num = 0;//订单总数量

        if($data['type'] == 'ticket'){
            $order_info = self::where('order_id',$data['order_id'])->first();
            if($order_info){
                $price = $order_info->good_price;
                $money = $order_info->good_price;//支付金额
                $good_num = $order_info->good_num;//产品数量
                $date['order_id'] = $data['order_id'];//订单id
                $date['type'] = 'ticket';//区分支付
                if($order_info->user_id){
                    $date['last_money'] = $order_info->wp_users->account_balance;//会员余额
                    $date['wp_users_id'] = $order_info->wp_users->id;//会员id
                    $date['phone'] = $order_info->wp_users->phone;//会员手机号
                    if($order_info->wp_users->wp_users_discount->status==1){
                        $discount = ($order_info->wp_users->wp_users_discount->discount==100 ?__('无折扣'):$order_info->wp_users->wp_users_discount->discount/10).'折';
                        $yhje = $order_info->good_price_ori - $order_info->good_price;
                    }else{
                        $discount = __('无折扣');
                        $yhje = 0;
                    }
                }else{
                    $discount = __('无折扣');
                    $yhje = 0;
                }
            }else{
                exit(json_encode(['status'=>0,'msg'=>__('此订单不存在')]));
            }
        }else{
            if($data['user']){
                $user = $data['user'];
            }

            $manjian_discount = 0;//满减金额
            $rpt_price = 0;//红包金额
            $voucher_price = 0;//代金券金额
            $wp_users = wp_users::where('id', $user)->first();
            if($wp_users) {
                //用会员结账时，更新会员的最新账户余额
                $account = user::getUserResourceMoney($wp_users->ucenter_id);
                $useracc['account_balance'] = $account;
                wp_users::where('id', $user)->update($useracc);
				
                $date['last_money'] = $account;//会员余额
                $date['wp_users_id'] = $wp_users->id;//会员id
                $date['phone'] = $wp_users->phone;//会员手机号

                foreach ($data['goods'] as $k => $v) {
                    $id = $v[0];
                    $num = $v[1];
                    $price = $v[2];
                    $good_num += $num;

                    $goods_common = yf_goods_common::where('id', $id)->first();
                    $goods_id = $goods_common->goods_id;

                    //限时折扣
                    $discountPrice = order::getXianShiInfo($goods_id);

                    //如果有活动价就用活动价
                    if ($discountPrice > 0) {
                        $disPrice = ($price - $discountPrice) * $num;
                        $totalPrice = $totalPrice - $disPrice;
                        $data['price'] = $data['price'] - $disPrice;
                        $price = $discountPrice;
                    }

                    $data['goods'][$k][2] = $price;
                }

                //满即送
                $shop = yf_shop_base::where("id", cookie('shop_id'))->first();
                $shop_user_id = $shop->users->ucenter_id;
                $mansongInfos = order::getMansongInfo($shop_user_id, $totalPrice);
				
                if ($mansongInfos) {
                    $manjian_discount = $mansongInfos['rule_discount'];
                    $totalPrice -= $mansongInfos['rule_discount'];

                    $gif_goods_id = $mansongInfos['gift_goods_id'];
                    if($gif_goods_id > 0) {
                        $goods_common = yf_goods_common::where('goods_id', $gif_goods_id)->first();
                        if($goods_common) {
                            $good = array();
                            array_push($good, $goods_common->id);
                            array_push($good, "1");
                            array_push($good, "0");
                            array_push($data['goods'], $good);
                            $good_num += 1;
                        }
                    }
                }

                //获取会员折扣
                $user_grade = user::getUserRate($wp_users->ucenter_id);
                if ($user_grade){
                    $userRate = $user_grade['user_grade_rate'];
                    $data['discount_status'] = 1;
                    $disRatePrice = number_format($totalPrice * (100 - $userRate) / 100, 2,".","");
					$yhje += $disRatePrice;
                    $totalPrice -= $disRatePrice;

                    foreach ($data['goods'] as $i => $goods) {
                        $price = $goods[2];
                        $data['goods'][$i][2] = number_format($price * $userRate/100, 2,".","");
                    }
                    $discount = ($userRate/10).'折';
                }else{
                    $discount = __('无折扣');
                }

                //获取平台红包及优惠券
                $user_dis = order::getVouchersByUcenterId($shop_user_id, $wp_users->ucenter_id, $totalPrice);
                if($user_dis){
                    if($user_dis['rpt_info']){
                        $redpacket_id = $user_dis['rpt_info']['redpacket_id'];
                        $rpt_price = $user_dis['rpt_info_price'];
                    }
                    if($user_dis['voucher_base']){
                        $voucher_id = $user_dis['voucher_base']['voucher_id'];
                        $voucher_price = $user_dis['voucher_price'];
					}
                }
            }else{
                foreach ($data['goods'] as $k => $v) {
                    $num = $v[1];
                    $good_num += $num;
                }
                $discount = __('无折扣');
            }

            //满减
            $date['manjian'] = $manjian_discount;

            //平台红包
            $date['redpacket_id'] = $redpacket_id ?:0;
            $date['rpt_price'] = $rpt_price;

            //应付金额-红包-代金券
            if($rpt_price > $totalPrice){
                $totalPrice = 0;
                $date['voucher_id'] = 0;
                $date['voucher_price'] = 0;
            }else{
                $totalPrice -= $rpt_price;

                //代金券
                $date['voucher_id'] = $voucher_id ?:0;
                $date['voucher_price'] = $voucher_price;
                if($voucher_price > $totalPrice){
                    $totalPrice = 0;
                }
                else{
                    $totalPrice -= $voucher_price;
                }
            }
        }
        $date['all_price'] = $data['price'];//订单总金额
        $date['order_id'] = $data['order_id']?:'YF-'.date('YmdHis').'-'.cookie('id').'-'.cookie('shop_id');
        $date['good_num'] = $good_num;//产品数量
        $date['money'] = $totalPrice;//支付金额
        $date['discount'] = $discount;//折扣
        $date['yhje'] = $yhje;//优惠金额
        $date['type'] = $data['type'];//支付类型
        $date['shop_name'] = cookie('shop_name');//店铺名
        $date['discount_status'] = $data['discount_status'];//当前订单是否参与折扣
        $date['goods'] = $data['goods'];

        return $date;
    }
}