<?php
namespace modules\doc;
use models\admin_users;
use cookie;
use models\shop_users;

/*
 * 用户登录类
 */

class login {
    //允许访问的URL
    public $allowAction = ['index'];
    public function index(){
        admin_users::checkData();
        if(is_ajax()){
            $user = post_data('user');
            $pwd  = post_data('pwd');
            if(!$user || !$pwd){
                exit(json_encode(['status'=>0,'msg'=>__('账号或密码不能为空')]));
            }
            $logined  = \cs\login::login_account($user,$pwd);
            $shop_users = shop_users::login($user,$pwd);
            if(admin_users::login($user,$pwd)===true || $logined===true || $shop_users===true){

                exit(json_encode(['status'=>1,'msg'=>__('登录成功') ,'url'=>url('doc/logo') ]));

            }elseif($logined){
                exit(json_encode(['status'=>0,'msg'=>$logined ]));
            }elseif($shop_users){
                exit(json_encode(['status'=>0,'msg'=>__('账号或密码错误') ]));
            }else{
                exit(json_encode(['status'=>0,'msg'=>__('账号或密码错误')]));
            }
        }
        return view('login');
    }


    /*
     *@desc 退出登录
     */
    public function loginout()
    {
        cookie::delete('admin_id');
        cookie::delete('admin_user');
        cookie::delete('admin_level');//账号等级
        cookie::delete('admin_role_id');
        cookie::delete('admin_shop_id');
        cookie::delete('admin_k');
        cookie::delete('admin_u');
        cookie::delete('admin_cat_id');
        cookie::delete('admin_ucenter_id');
        cookie::delete('insertnum');
        cookie::delete('updatenum');
        redirect(url('doc/login/index'));
    }


}
