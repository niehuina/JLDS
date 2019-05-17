<?php
/*
 *@desc 订单支付会员充值
 */
namespace cs\sync;
use cs\apitoken;
use Requests;
class pay extends apitoken{
    /*
     * 获取支付二维码页面
     * */
    static public function getPayorder($order_id,$money,$pay_way,$total){
        $url = config('paycenter.url')."?ctl=Api_WebPos&met=createOrder&order_id=".$order_id."&amount=".$money."&payment_way=".$pay_way."&trade_title=".$total ;

        $headers = array('Accept' => 'application/json');
        $options = array('CURLOPT_FOLLOWLOCATION'=>1);

        $request = Requests::get($url, $headers, $options);
        $rs = $request->body;
        return $rs;
    }

    
    /*
     * 验证当前订单支付状态
     * 
     * */
    static public function getOrderstatus($order_id){
        $data['order_id'] = $order_id;
        $rs = self::_get('paycenter',"Api_WebPos","getOrderInfo",$data);
        return $rs;
    }

    static function _paycenter($met,$arr){

        $rs = self::_get('paycenter','Api_WebPos',$met,$arr);
        if($rs['status']!=200){
            $rs['msg'] = $rs['msg'];
        }
        return $rs;
    }

    static function _shop($met,$arr){

        $rs = self::_get('shop','Api_User_Info',$met,$arr);

        if($rs['status']!=200){
            $rs['msg'] = $rs['msg'];
        }
        return $rs;
    }

    //保存充值记录
    static function add_User_Record($user_id,$nick_name,$amount,$pay_way,$order_id){
        $formvars = [
            'user_id'=>$user_id,
            'user_nickname'=>$nick_name,
            'record_money'=>$amount,
            'pay_way'=>$pay_way,
            'order_id'=>$order_id,
        ];
        return self::_paycenter('addUserRecord',$formvars);
    }

    //更新账户余额
    static function update_account_balance($user_id,$amount){
        $formvars = [
            'user_id'=>$user_id,
            'record_money'=>$amount,
        ];
        return self::_paycenter('UpdateAccountBalance',$formvars);
    }

    //保存paycenter用户信息
    static function add_pay_user($user_id,$user_name,$user_realname,$user_phone,$passwd){
        $formvars = [
            'user_id'=>$user_id,
            'user_name'=>$user_name,
            'user_realname'=>$user_realname,
            'user_phone'=>$user_phone,
			'passwd'=>$passwd,
        ];
        return self::_paycenter('adduser',$formvars);
    }


    /*
     * 减少paycenter用户的余额
     */
    static public function syncUserResouce($data) {
        $rs = self::_get('paycenter',"Api_Pay_Pay","money",$data);
        return $rs;
    }

    //保存paycenter用户信息
    static function add_shop_user($user_id,$user_name,$user_realname,$user_phone,$passwd,$user_sex){
        $formvars = [
            'user_id'=>$user_id,
            'user_name'=>$user_name,
            'user_passwd'=>$passwd,
            'user_email'=>'',
            'user_realname'=>$user_realname,
            'user_phone'=>$user_phone,
            'user_sex'=>$user_sex,
        ];
        return self::_shop('addWebposUserInfo',$formvars);
    }
    /*
    * 验证当前订单支付状态
    *
    * */
    static public function getUserInfo($user_id){
        $formvars = [
            'user_id'=>$user_id,
        ];
        return self::_paycenter('getUserInfo',$formvars);
    }
}
