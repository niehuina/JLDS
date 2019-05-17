<?php namespace models;
use cs\login;
class acl_roles extends base{
    
    protected $table = 'acl_roles';
    //规则查看 https://github.com/vlucas/valitron 
    public $rules = [
        'acl_id'=>['required'],
    ];

    /*
     *@desc 关联权限表
     */
    public function acl(){
        return $this->hasOne('models\acl');
    }

    /*
     * @desc 获取当前用户权限列表数据
     * */
    static public function acl_roles_list(){
        $uid = login::getUid(null,1);
        $info = self::where('roles_id',get_data('id'))->where('users_id',$uid)->get()->toArray();
        if($info){
            foreach($info as $k=>$v){
                $arr[] = acl::where('id',$v['acl_id'])->first()->slug;
            }
        }
        return $arr;
    }
    /*
     *@desc 权限列表
     */
    static public function acl_list(){
        $id = login::getUid(null,1);
        $list = users::where('id',$id)->first()->yf_shop_base;
        if($list){
            foreach($list as $k=>$v){
                $arr[] = yf_shop_base::where('id',$v->id)->first()->shop_users;
            }
        }
        if($arr){
            foreach($arr as $k=>$v){
                foreach($v as $k1=>$v1){
                    $in[] = $v1->id;
                }
            }
        }
        if($in){
            foreach($in as $v){
                $role_list[] = role_users::where('user_id',$v)->first();
            }
        }
        if($role_list){
            foreach($role_list as $k=>$v){
                $role[] = roles::where('id',$v->role_id)->first();
            }
        }
        if($role){
            $role = array_unique($role);
            return $role;
        }else{
            return array();
        }

    }

    /*
     * @desc 保存表单数据
     *
     */
    static function saveForm(){
        $data = post_data();
        if(!$data['slug']){
            exit(json_encode(['status'=>0,'msg'=>__('列表数据不能为空')]));
        }
        $model = new self;
        $model::where('users_id',$data['users_id'])->where('roles_id',$data['roles_id'])->delete();
        foreach($data['slug'] as $v){
            $date['users_id'] = $data['users_id'];
            $date['acl_id'] = $v;
            $date['roles_id'] = $data['roles_id'];
            $date['status'] = $data['status'];
            $date['created'] = time();
            $model->insert($date);
        }
    }
    
}