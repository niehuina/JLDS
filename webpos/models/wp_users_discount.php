<?php namespace models;
use cs\login;
use str,log,session;
use token;
use DB;
use Exception;
use models\yf_shop_base;
use models\wp_users;
use cs\ucenter as  api;
use cs\sync\pay as apipay;
class wp_users_discount extends base{
    
    protected $table = 'wp_users_discount';
    public $rules = [
    
    ];
    /*
     *@desc 根据登录账号信息获取数据列表
     *
     */
    public function scopeDefaultWhere($query)
    {
        parent::scopeDefaultWhere($query);
        
        $wq = request_data('wq');
        if($wq){
            $query->whereHas("wp_users",function($q) use($wq)
            {
                $q->where('phone', 'like', "%".$wq."%");
            });
            $query->orWhereHas("wp_users",function($q) use($wq)
            {
                $q->where('ucenter_name', 'like', "%".$wq."%");
            });

        }

        $in = \cs\login::login_user(2);
        if($in){
            $query->whereIn('users_id',$in);
        }
    }
    /*
     *@desc 根据登录账号信息获取数据列表
     *
     */
    public function scopeWpusersWhere($query)
    {
        
        $users_id = cookie('users_id');
//        if($users_id){
//            $query->where('users_id', $users_id);
//        }
        $wq = request_data('wq');
        if($wq){
            $query->whereHas("wp_users",function($query) use($wq)
            {
                $query->where('phone', 'like', "%".$wq."%");

            });

            $query->orWhereHas("wp_users",function($query) use($wq)
            {
                $query->where('ucenter_name', 'like', "%".$wq."%");
            });

        }
        $query->orderBy('started', 'desc');
        $users_id = cookie('users_id');
//        if($users_id){
//            $query->where('users_id', $users_id);
//        }

    }
    /*
    * @desc 关联会员信息表
    */
    public function wp_users(){
        return $this->belongsTo('models\wp_users', 'wp_user_id')->orderBy('created', 'desc');
    }
    /*
     * @desc 开启事务 保存表单数据
     *
     */
    static function saveForm(){
        try{
            DB::transaction(function()
            {

                $code = cache("sms_".post_data('phone'));

//                if($code != post_data('code') && post_data('code')){
//                    exit(json_encode(['status'=>0,'msg'=>__('验证码有误,请重新输入')  ]));
//                }

                $data = post_data();
                $user_id =  login::getUid(null,1)?:cookie('users_id');
                if($data['sex']== "男"){
                    
                    $data['sex'] = 1 ;
                }elseif($data['sex']== "女"){

                    $data['sex'] = 2 ;
                }elseif($data['sex'] == "保密"){
                    $data['sex'] = 3 ;
                }else{
                    $data['sex'] = 0;
                }
                if($data['status']==1 || $data['discount'])
                {
                    if(!preg_match('/^(?:[1-9]\d|100)$/',$data['discount'])){
                        exit(json_encode(['status'=>0,'msg'=>__('折扣率格式错误，请输入10~100以内的数值')  ]));
                    }
                }
                $data['bron'] = strtotime($data['bron']);
                if($data['id']){//修改
                    if($data['phone']){

                        $model = self::find($data['id']);

                        $list = self::where('users_id',$user_id)->get();
                        if($list){
                            foreach($list as $k=>$v){
                                if($v->wp_user_id != $model->wp_user_id && $v->wp_users->phone == $data['phone']){
                                    $in[] = $v['wp_user_id'];
                                }
                            }
                        }

                        if($in){
                            exit(json_encode(['status'=>0,'msg'=>__('该手机号已存在')]));
                        }
                        $models = wp_users::where('id',$model->wp_user_id)->first();
                        $date['status'] = $data['status'];
                        $date['discount'] = $data['discount'];
                        unset($data['id']);
                        unset($data['get_code']);
                        unset($data['status']);
                        unset($data['discount']);
                        
                        $models->data($data)->save();

                        $model->data($date)->save();

                    }else{
                        exit(json_encode(['status'=>0,'msg'=>__('会员手机号不能为空')  ]));
                    }
                }else{//新增
                    $wp_users = new wp_users;
                    if(!post_data('ucenter_name')){
                        exit(json_encode(['msg'=>__('用户名不能为空')  ]));
                    }else{
                        $user= $wp_users->where('ucenter_name',$data['ucenter_name'])->first();
                        if($user){
                            exit(json_encode(['msg'=>__('用户名已存在，请重新输入')  ]));
                        }
                    }
                    if(!post_data('phone')){
                        exit(json_encode(['msg'=>__('手机号不能为空')  ]));
                    }
                    if(!post_data('password')){
                        exit(json_encode(['msg'=>__('密码不能为空')  ]));
                    }


                    $model = new self;
                    $phone = $wp_users->where('phone',$data['phone'])->first();
                    if($phone){
                        $new_phone = $model::where('users_id',$user_id)->where('wp_user_id',$phone->id)->first();
                        if($new_phone){
                            exit(json_encode(['status'=>0,'msg'=>__('该手机号已被注册')]));
                        }else{
                            $date['sex'] = $data['sex'];
//                            $date['email'] = $data['email'];
                            $date['realname'] = $data['realname'];
                            $date['ucenter_name'] = $data['ucenter_name'];
                            $date['phone'] = $data['phone'];
                            unset($data['sex']);
                            unset($data['phone']);
                            unset($data['email']);
                            unset($data['realname']);
                            unset($data['ucenter_name']);
                            unset($data['code']);
                            unset($data['bron']);

                            $data['users_id'] = cookie('admin_level')==1?post_data('users_id'):$user_id;;
                            $data['wp_user_id'] = $phone->id;
                            $data['numbers'] = 'YF-'.time().cookie('admin_id');
                            $data['started'] = time();
                            $data['ended'] = time()+365*84600*5;

                            $model->data($data)->save();
                            $wp_users->where('id',$phone->id)->update($date);
                        }

                    }else{
                        $name  = $data['ucenter_name'];

                        //$passwds =$data['password'];
                        $passwd = md5($data['password']);
                        $rs = api::add($name,$data['password'],$data['phone']);
                        $data['ucenter_id'] = $rs['data']['id'];
                        $msg = $rs['msg'];
                        if($rs['status'] != 200){
                            exit(json_encode(['status'=>0,'msg'=>__($msg)]));
                        }
                        if($data['status']==1 || $data['discount'])
                        {
                            $date['status']   = $data['status'];
                            $date['discount'] = $data['discount'];
                            unset($data['status']);
                            unset($data['discount']);
                        }
                        $pay_rs=apipay::add_pay_user($data['ucenter_id'],$data['ucenter_name'],$data['realname'],$data['phone'],$passwd);
                        $pay_msg = $pay_rs['msg'];
                        if($pay_rs['status'] != 200){
                            exit(json_encode(['status'=>0,'msg'=>__($pay_msg)]));
                        }
                        //shop数据库同步
                        $shop_rs=apipay::add_shop_user($data['ucenter_id'],$data['ucenter_name'],$data['realname'],$data['phone'],$passwd,$data['sex']);
                        $shop_msg = $shop_rs['msg'];
                        if($shop_rs['status'] != 200){
                            exit(json_encode(['status'=>0,'msg'=>__($shop_msg)]));
                        }

                        $data['created'] = time();
                        $data['password']=$passwd;
                        $wp_users->data($data)->save();
                        //$wp_users->insert($data);
                        $id = last_insert_id();
                        if($id){
                            $date['wp_user_id'] = $id;
                            $date['users_id'] = cookie('admin_level')==1?post_data('users_id'):$user_id;
                            $date['numbers'] = 'YF-'.time().cookie('admin_id');
                            $date['started'] = time();
                            $date['ended'] = time()+365*84600*5;

                            $model->insert($date);
                        }

                    }

                }


            });
        }catch(Exception $e){
            return false;
        }
        return true;
    }

}