<?php
namespace modules\home;
use models\shop_users;
use cookie;
use file;
/**
 * @desc 登录
 *
 */
class login extends \cs\controller{
	/**
	 * @desc 登录
	 *
	 */
	public function index(){
		if(is_ajax()){
			$user = post_data('user_name');
			$pwd =  post_data('user_pwd');
			if(!$user || !$pwd){
                exit(json_encode(['status'=>0,'msg'=>__('账号或密码不能为空')]));
            }
			$shop_users = shop_users::login($user,$pwd,1);

            if($shop_users===true){
                exit(json_encode(['status'=>1,'msg'=>__('登录成功') ,'url'=>url('home/welcome') ]));
   
            }else{
                exit(json_encode(['status'=>0,'msg'=>$shop_users ]));
            }
            
		}
		return view('login');
	}


    /*
     *@desc 退出登录
     */
    public function loginout()
    {
        $this->clear_cookie();
        redirect(url('home/login/index'));
    }

    /*
     * 退出登录清除cookie 缓存
     * */
    public function clear_cookie(){
        cookie::delete('id');
        cookie::delete('home_user');
        cookie::delete('home_nickname');
        cookie::delete('home_num');
        cookie::delete('level');
        cookie::delete('shop_id');
        cookie::delete('shop_name');
        cookie::delete('users_id');

    }


}