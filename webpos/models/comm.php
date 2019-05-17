<?php namespace models;
use str;
use cs\alisms;
use session;

class comm {
	/*
	*@desc	发送验证码
	*/
	public function send_sms($tel){
		$code = session::get('sms');
		if(!$code)
			$code = str::rand_number(6);

		if(alisms::send($tel,$code) !== true){
			log::system(__('发送验证失败了').$tel." [$code]");
			return false;
		}

		session::set('sms',$code,120);
		
		return true;
	}


}