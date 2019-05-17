<?php namespace models;
use cs\login;
use str,log,session;
use token;
use DB;
use Exception;
use cs\ucenter as  api;
class wp_users extends base{

    protected $table = 'wp_users';
    //规则查看 https://github.com/vlucas/valitron
    public $rules = [
        'ucenter_name'=>['required',['lengthBetween',1,20]],
        'bron'=>['required'],
        'sex'=>['required'],
        'phone'=>['required','is_phone'],
        'email'=>['email'],
    ];
    public function scopeDefaultWhere($query)
    {
        parent::scopeDefaultWhere($query);
       
        $wq = request_data('wq');
        if($wq){
            $query->where(function($q) use($wq)
            {
                $q->where('phone', 'like', "%".$wq."%");
                
            });
        }

        $in = \cs\login::login_user(2);
        if($in){
            $query->whereIn('users_id',$in);
        }
    }
    /*
    * @desc 退货总金额 
    */
    public function wp_order_return(){
        return $this->hasManyThrough('models\wp_order_return', 'models\wp_order', 'user_id', 'order_id');
    }

    /*
    * @desc 购买总金额 
    */
    public function  wp_order(){
        $in = \cs\login::login_user(1);
        if($in){
            return $this->hasMany('models\wp_order','user_id','ucenter_id')->whereIn('shop_id',$in)->where('type',0)->orderBy('ended', 'desc');
        }
            return $this->hasMany('models\wp_order','user_id','ucenter_id')->where('type',0)->orderBy('ended', 'desc');
    }
   /*
    *@desc 购买总金额 减去 退货总金额 
    */
    public function  getMoneyAttribute(){
    
        return $this->wp_order->sum('good_price') - $this->wp_order_return->sum('goods_price');
    }
    /*
    * @desc 折扣卡
    */
    public function  wp_users_discount(){
        return $this->belongsToMany('models\wp_users_discount','wp_user_id');
    }
}