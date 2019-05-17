<?php   namespace models;
use models\role_users;
use models\yf_shop_base;
use models\acl_roles;
use models\users;
use models\wp_order;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Exception;
use cs\login;
use cs\ucenter;
class shop_users extends base{
    protected $table = 'shop_users';
    //软删除
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    //规则查看 https://github.com/vlucas/valitron
    public $rules = [
        'user'=>['required'],
        'role_id'=>['required'],
        'yf_shop_base_id'=>['required'],
        'num'=>['required',['lengthBetween',1,8]],
        'nickname'=>['required'],
        'sex'=>['required'],
        'phone'=>['required','is_phone'],
        'id_card'=>['required','is_card'],
    ];
    /*
     *@desc 门店员工账号- 根据登录账号信息获取数据列表 
     * 
     */
    public function scopeDefaultWhere($query)
    {
        parent::scopeDefaultWhere($query);
        $in = \cs\login::login_user(1);
        if($in){
            $query->whereIn('yf_shop_base_id',$in);
        }

        $wq = request_data('wq');
        if($wq){
            $query->where(function($query)use($wq){
                $query->where('nickname', 'like', "%".$wq."%");
                $query->orwhere('user', 'like', "%".$wq."%");
                $query->orwhere('phone', 'like', "%".$wq."%");
            });
        }

        $query->orderby('created','desc');

        $shop_id = request_data('shop_id');
        if($shop_id){
           $query->where('yf_shop_base_id',$shop_id);
        }
    }
    /*
     *@desc 销售单据- 根据登录账号信息获取收银员账号数据
     *
     */
    public function scopeDocumentsWhere($query)
    {
        $in = \cs\login::login_user(1);
        if($in){
            $query->whereIn('yf_shop_base_id',$in);
        }
        
        $wq = request_data('wq');
        if($wq){
            $query->where(function($query)use($wq){
                $query->orwhere('user', 'like', "%".$wq."%");
            });
        }
        $query->orderby('created','desc');

        $shop_id = request_data('shop_id');
        if($shop_id){
           $query->where('yf_shop_base_id',$shop_id);
        }
        //根据唯一标识查询收银员id
        $role = roles::where('slug','shouyin')->first();

        $role_users = role_users::where('role_id',$role->id)->get()->toArray();

        if($role_users){
            foreach ($role_users as $k => $v) {
                $in[] = $v['user_id'];
            }
            if($in){
                $query->whereIn('id',$in);
            }
        }
    }
    /**
     * @desc 订单总价
     *
     */
    public function  getSumAttribute(){
        //调用wp_order方法 判断是否为空
        $order = $this->wp_order;
        if(!$order) return 0;
        //如果不为空 计算其中的值
        foreach($order as $v){
            $sum += $v->good_price;
        }
        return $sum;
    }
    /*
     * @desc 订单退货总价
     */
    public function  getGoodAttribute($value){
        //调用wp_order_return 判断是否为空 
        $return = $this->wp_order_return;
        if(!($return)) return 0;
        foreach($return as $v){
            $num += $v->goods_price;
        }
        return $num;
    }
    /**
     * @desc 获取所属店铺信息
     */
    public function yf_shop_base()
    {
        return $this->hasOne('models\yf_shop_base','id','yf_shop_base_id');
    }
    /**
     * @desc 获取退货订单信息
     */
    public function wp_order_return()
    {
        $start_time = request_data('start_time')?:date('Y-m-d 00:00:00', time());
        $end_time   = request_data('end_time')?:date('Y-m-d 23:59:59', time());
        return $this->hasMany('models\wp_order_return','shop_users_id')
            ->where('created','>=',strtotime($start_time))
            ->where('created','<=',strtotime($end_time))
            ->where('type','!=',1);
    }
    /**
     *@desc  获取所属角色信息
     */
    public function role_users()
    {
        return $this->hasOne('models\role_users','user_id');
    }
    
    /**
     *@desc 获取订单信息
     */
    public function wp_order()
    {
        $start_time = request_data('start_time')?:date('Y-m-d 00:00:00', time());
        $end_time   = request_data('end_time')?:date('Y-m-d 23:59:59', time());
        return  $this->hasMany('models\wp_order','shop_users_id')
            ->where('ended','>=',strtotime($start_time))
            ->where('ended','<=',strtotime($end_time))
            ->where('type','!=',1);
    }
    /*
     * @desc 开启事务 保存表单数据
     *
     */
    static function saveForm(){
        try{
            DB::transaction(function()
            {
                $data = post_data();
                if($data['pwd']){
                    $data['pwd'] = password_hash($data['pwd'], PASSWORD_DEFAULT);
                }
                if($data['id']){
                    $info = login::recharge($data['user'],2,'shop_users',$data['id']);
                    if($info == false && $data['user']){
                        exit(json_encode(['status'=>0,'msg'=>__('该账号已存在')]));
                    }
                    $num = self::where('yf_shop_base_id',$data['yf_shop_base_id'])
                        ->where('id','!=',$data['id'])
                        ->where('num' , '=' ,$data['num'])
                        ->first();
                    if ($num ) {
                        exit(json_encode(['status'=>0,'msg'=>'员工编号必须唯一']));
                    }
                    $card = self::where('id','!=',$data['id'])
                        ->where('id_card' , '=' ,$data['id_card'])
                        ->first();
                    if ($card ) {
                        exit(json_encode(['status'=>0,'msg'=>'该身份证号码已存在']));
                    }
                    if(!$data['pwd']){
                        unset($data['pwd']);
                    }
                    $model = self::find($data['id']);
                }else{
                    $info = login::recharge($data['user'],1);
                    if($info == false && $data['user']){
                        exit(json_encode(['status'=>0,'msg'=>__('该账号已存在')]));
                    }

                    if($data['yf_shop_base_id']){
                        $base = yf_shop_base::where('id',$data['yf_shop_base_id'])->first();
                        $shop_num = $base->shop_num; //允许的最大店员数
                        $shop_user_num = $base->shop_users->count(); //当前店员数
                        if($shop_user_num >= $shop_num){
                            exit(json_encode(['status'=>0,'msg'=>'员工账号添加失败，已超出最大店员数']));
                        }
                    }
                    //验证员工编号
                    $num = self::where('yf_shop_base_id',$data['yf_shop_base_id'])
                        ->where('num',$data['num'])
                        ->first();
                    if($num){
                        exit(json_encode(['status'=>0,'msg'=>'员工编号必须唯一']));
                    }
                    $card = self::where('id_card',$data['id_card'])->first();
                    if($card){
                        exit(json_encode(['status'=>0,'msg'=>'该身份证号码已存在']));
                    }
                    $data['created'] = time();
                    $model = new self;
                }

                $model->data($data)->save();
                //插入到role_users表中 
                $role_all = new role_users;
                $role_info = $role_all->where('user_id','=',$model->id)->first();
                if($role_info->id){
                    $wula = $role_all->find($role_info->id);
                }else{
                    $wula = $role_all;
                }
                $role_date['user_id'] = $model->id;
                $role_date['role_id'] = post_data('role_id');
                $wula->data($role_date)->save();
            });
        }catch(Exception $e){

            exit(json_encode(['status'=>0,'msg'=>__($e->getMessage())]));
            return false;
        }
        return true;
    }
    /*
     * @desc 删除表单数据
     * */
    static function deleteForm($id){
        $model = self::where('id',$id);
        $model->delete();
    }

    /*
     *@desc 登录
     *
     */
    static function login($user,$pwd,$type=null){
        $one = self::where('user',$user)->first();
        $start_time = strtotime($one->yf_shop_base->users->service_start_time);
        $end_time = strtotime($one->yf_shop_base->users->service_end_time);
        if(!$one){
            return __('账号不存在');
        }else{
            if (password_verify($pwd, $one['pwd'])) {
                if($end_time < time() || $start_time >time()){
                    return __('该账号不在服务期范围');
                }else{
                    if($type==1){
                        if($one->role_users->roles->slug != 'shouyin'){
                            return __('该账号不是收银员账号');
                        }else{
                            $login = new login();
                            $login->setcookie($one);
                            record_succession::login_time_info($one);
                            return true;
                        }
                        
                    }else{
                        $login = new login();
                        $login->admin_setcookie($one);
                        return true;
                    }
                    
                }
            }else{
                return __('密码错误');
            }
        }
    }



}

