<?php namespace modules\fun;

use models\comm;
use validator;
class verify{
    /*
     *@desc 发送短信验证码
     *
     */

    public function send_sms(){
        $value = post_data('phone');
        validator::set(['phone'=>$value],['phone'=>['is_phone']]);
        if(validator::get() || empty($value)){
            exit(json_encode(['status'=>false,'msg'=>'手机号码格式错误！！！'])) ;
        }
        if((new comm)->send_sms($value)){
            exit(json_encode(['status'=>true,'msg'=>'验证码已发送成功，请查收！']));
        }
        exit(json_encode(['status'=>false,'msg'=>'验证码发送失败啦！！！'])) ;

    }
}