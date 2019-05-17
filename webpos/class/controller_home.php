<?php
namespace cs;
use app\models\users;
use cs\login;
use models\acl;
use models\acl_roles;

class controller_home extends controller{
    protected $browser=false;
    public function init(){

        theme('default');
        $this->check_logined();
        $this->operation_authority();
    }

    /*
     *
     *@desc 验证用户是否登录
     */
    protected function check_logined(){
        $id = cookie('admin_id');
        if(!$id && !cookie('admin_user')){
            return redirect(url('doc/login/index'));
        }
        //商家登录

        if($this->find_login_ucenter() == true){
            cookie('admin_cat_id','102');
            if(cookie('admin_level') == 2 && $id && url_string()!="doc/users_bind/index" && url_string()!="doc/users_bind/edit"){
                $one = \models\users::where('id',$id)->first();
                if(! $one->ucenter_id ){
                    redirect(url('doc/users_bind/index'));
                }
            }
        }else{
            cookie('admin_cat_id','');
        }
    }

    protected $default = ['key'=>"" , 'url'=>"",'id'=>""];
    /*
     *
     *@desc 验证当前商品是否使用商城系统
     */
    public function find_login_ucenter(){
        $config = [
            'shop' => [],
        ];
        foreach ($config as $key => $value) {
            $config =  config($key)?:$this->default;
        }

        if($config['id'] == 102){
            return true;
        }
        
    }

    /*
     *@desc 检测用户有没有操作权限
     *
     */
    public function operation_authority($address=null,$string=null){
        $role_ids = cookie('admin_role_id');
        $arr = array();

        if($role_ids){
            $uid = login::getUid(null,1);
            $list = acl_roles::where('roles_id',$role_ids)->where('users_id',$uid)->where('status',1)->get();
            if($list){
                foreach($list as $v){
                    $arr[] = acl::where('id',$v->acl_id)->first()->slug;
                }
            }

            $url = url_string();
            if($address == 1){
                if($string == 1)
                {
                    $arr = implode(",",$arr);
                }
                return $arr;
            }else{
                if(!in_array($url,$arr) && $url != 'doc/logo/index')
                {
                    echo __('很抱歉，您没有该权限');exit;
                }
            }

        }
    }
}