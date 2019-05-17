<?php namespace models;
/*
 * @desc users表数据处理
 * */
use cs\login;
use Illuminate\Database\Eloquent\SoftDeletes;
use cs\ucenter as  api;
use DB;
use Exception;
class users extends base{
    //软删除
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'users';
    //规则查看 https://github.com/vlucas/valitron
    public $rules = [
        'ucenter_name'=>['required'],
        'service_start_time'=>['required'],
        'service_end_time'=>['required'],
        'authorization_module'=>['required'],
        'max_nums'=>['required','integer',['min',0]],
        'max_stores'=>['required','integer',['min',0]],

    ];


    /*
     *@desc 搜索
     *
     */
    public function scopeDefaultWhere($query)
    {
        parent::scopeDefaultWhere($query);
        $wq = request_data('wq');
        $query->orderby("service_end_time",'asc');
        $service_start_time = request_data('service_start_time');
        $service_end_time = request_data('service_end_time');
        if($wq){
            $query->where("ucenter_name",'Like',"%".$wq."%");
        }
        $query->orderby('service_end_time');
        $this->_status($query);
        
        if($service_start_time && $service_end_time){
            $service_start_time = date('Y-m-d 00:00:00',strtotime($service_start_time));
            $service_end_time = date('Y-m-d 23:59:59',strtotime($service_end_time));
            $query->where("service_start_time" , ">=",$service_start_time);
            $query->where("service_end_time" , "<=",$service_end_time);
        }
    }

    protected function _status($query){
        $status = request_data('status');
        if($status){
            switch ($status) {
                case 1:
                    $query->where("service_start_time" , "<", date('Y-m-d H:i:s',time()));
                    $query->where("service_end_time" , ">=",  date('Y-m-d H:i:s' ,time()+30*86400));
                    break;
                case 2:
                    $query->where("service_end_time" , "<", date('Y-m-d H:i:s' , time()+30*86400 ));
                    $query->where("service_end_time" , ">=", date('Y-m-d H:i:s' , time() ));
                    $query->where("service_start_time" , "<", date('Y-m-d H:i:s' , time() ));
                    break;
                case 3:
                    $query->where("service_end_time" , "<", date('Y-m-d H:i:s' , time() ));
                    break;
                case 4:
                    $query->where("service_start_time" , ">", date('Y-m-d H:i:s' ,  time() ));
                    break;
                default:
                    # code...
                    break;
            }
        }
    }

    /**
     *@desc 获取店铺信息
     */
    public function yf_shop_base()
    {
        return $this->hasMany('models\yf_shop_base','user_id','id');
    }
    /**
     *
     *@desc  剩余多少天
     */
    public function getAccountHasDaysAttribute()
    {
        $service_start_time = strtotime($this->service_start_time);
        $service_end_time = strtotime($this->service_end_time);
        if($service_end_time < time()+30*86400 && $service_start_time < time() && $service_end_time >= time()){
            return  ceil(($service_end_time-time())/86400);
        }
    }
    
    /**
     *
     *@desc 获取账号状态
     * @param  string  $value
     * @return string
     */
    public function getStatusNameAttribute()
    {
        $service_start_time = strtotime($this->service_start_time);
        $service_end_time = strtotime($this->service_end_time);
        if($service_end_time < time() ){
            $flag = "<span class='warn'>".__('已超期')."</span>";
        }elseif($service_end_time < time()+30*86400 && $service_start_time < time() && $service_end_time >= time()){
            $flag = "<span class='red'>".__('即将超期')."</span>";
        }elseif($service_start_time > time()){
            $flag = "<span class='yellow'>".__('未到服务开始时间')."</span>";
        }elseif($service_start_time < time() && $service_end_time >= time()+30*86400){
            $flag = "<span class='green'>".__('正常')."</span>";
        }
        return $flag;
    }
    /*
     * @desc 保存表单数据
     *
     */
    static function saveForm(){
        try{
            DB::transaction(function()
            {
                $data = post_data();
                if(strtotime($data['service_start_time']) >= strtotime($data['service_end_time']) && $data['ucenter_name'] && $data['service_start_time'] && $data['service_end_time']){
                    exit(json_encode(['status'=>0,'msg'=>__('服务开始时间不能小于或等于结束时间')]));
                }
                if($data['id']){
                    $info = login::recharge($data['ucenter_name'],2,'users',$data['id']);
                    if($info == false && $data['ucenter_name']){
                        exit(json_encode(['status'=>0,'msg'=>__('该账号已存在')]));
                    }
                    $data['updated'] = time();
                    $model = self::find($data['id']);
                }else{
                    $info = login::recharge($data['ucenter_name'],1);
                    if($info == false && $data['ucenter_name']){
                        exit(json_encode(['status'=>0,'msg'=>__('该账号已存在')]));
                    }
                    $data['created'] = time();
                    $model = new self;
                }

                //ucenter用户同步。
                //当用户不存在时，自动创建
                //用户已存在时，修改密码。
                $name  = $data['ucenter_name'];
                $passwd = trim($data['pwd']);
                if($passwd){
                    $add = false;
                    if($data['id']){
                        $rs =  api::passwd($name,$passwd);
                        if($rs['status']!=200){
                            $add = true;
                        }
                    }else{
                        $add = true;
                    }
                    if($add === true){
                        $rs = api::add($name,$passwd);
                        $data['local_ucenter_id'] = $rs['data']['id'];
                    }
                    $msg = $rs['msg'];
                    if($rs['status'] != 200){
                        $user_id = api::get_ucenter_id($name);

                        if($user_id['data']['user_id']){
                            $data['local_ucenter_id'] = $user_id['data']['user_id'];
                        }else{
                            exit(json_encode(['status'=>0,'msg'=>__($msg)]));
                        }
                    }
                }
                $model->data($data)->save();
            });
        }catch(Exception $e){
            return false;
        }
        return true;

    }
    /*
     * @desc 删除表单数据
     */
    static function deleteForm($id){
        $model = self::where('id',$id);
        $rs = $model->first();
        $rsz =  api::status($rs->ucenter_name,3);
        $yf_shop_base = yf_shop_base::where('user_id',$rs->ucenter_id);

        $model->delete();

        if($yf_shop_base->get()){
            foreach($yf_shop_base as $k=>$v){
                $shop_id[] = $v->id;
            }
            if($shop_id){
                foreach($shop_id as $k=>$v){
                    shop_users::where('yf_shop_base_id',$v)->delete();
                    yf_goods_shop_common::where('shop_id',$v)->delete();
                }
            }
        }

        $yf_shop_base->delete();
        if($rsz['status']!=200){
            $msg = $rsz['msg'];
            exit(json_encode(['status'=>0,'msg'=>__($msg)]));
        }

    }





}