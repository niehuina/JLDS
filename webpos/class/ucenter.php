<?php 
namespace cs;
 
class ucenter extends apitoken{

		/**
		 * 修改用户状态
		 */
		static function status($user_name,$user_state){ 
			$formvars = [ 
					'id'=>$user_name, 
					'user_state'=>$user_state,
			];
			return self::_ucenter('change',$formvars);
		}

		/**
		 * 用户添加　
		 * $rs = api::add($name,$passwd);
         * $data['id'] = $rs['data']['id'];
		 */
		static function add($user_name,$user_password,$phone){
			
			$formvars = [ 
					'user_name'=>$user_name, 
					'password'=>$user_password,
                     'phone'=>$phone
			];
			return self::_ucenter('add',$formvars);
		}
	
		/**
		 * 用户登录　
		 */
		static function login($user_name,$user_password){
			
			$formvars = [ 
					'user_name'=>$user_name,
					'password'=>$user_password,
			];
			return self::_ucenter('checkUserAccount',$formvars);
		}

		/**
		 * 修改UCENTER用户密码
		 * @$user_name 是用户名。
		 */
		static function passwd($user_name,$user_password){ 
			$formvars = [ 
					'user_id'=>$user_name,
					'user_password'=>$user_password,
			];
			return self::_ucenter('editUserPassword',$formvars);

		}
		/**
		 * 取得ucenter用户信息
		 *  
		 */
		static function get($user_id){ 
			 $formvars = [ 
					'user_id'=>$user_id, 
			];
			return self::_ucenter('getUserInfo',$formvars);
		}
        /*
         * 根据用户名取得用户信息
         * */
        static function get_ucenter_id($user_name){
            $formvars = [
                'user_name'=>$user_name,
            ];
            return self::_ucenter('getUserIdByUsername',$formvars);
        }


		static function _ucenter($met,$arr){ 
		 	  $rs = self::_get('ucenter','Api_User',$met,$arr); 
				if($rs['status']!=200){
					$rs['msg'] = $rs['msg'];
				}
				return $rs;
		}




}