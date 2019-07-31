<?php

class Sms
{
//	public static function send($mob, $content, $tple_id = null)
//	{
//		if (is_array($content))
//		{
//			$content = encode_json($content);
//		}
//
//		$sms_config = Yf_Registry::get('sms_config');
//
//		$name     = $sms_config['sms_account'];
//		$password = md5($sms_config['sms_pass']);
//
//		$mob     = $mob;
//		$content = urlencode($content);
//		$content = iconv("utf-8", "gb2312//IGNORE", $content);
//
//		$url = "http://sms.b2b-builder.com/sms.php?name=" . $name . "&password=" . $password . "&mob=" . $mob . "&content=" . $content;
//
//		if ($tple_id)
//		{
//			$url = $url . '&tpl_id' . $tple_id;
//		}
//
//		fb($url);
//		$ch = curl_init();
//		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//		curl_setopt($ch, CURLOPT_URL, $url);
//		$result = curl_exec($ch);
//		curl_close($ch);
//		fb($result);
//		return $result;
//	}

    public function send($mobiles, $content, $sendtime='', $extno=null)
    {
        $sms_config = Yf_Registry::get('sms_config');

        $url = $sms_config['sms_url'];
        $account = $sms_config['sms_account'];
        $password = md5($sms_config['sms_pass']);
        $content = $sms_config['sms_signature'].$content;

        $body=array(
            'action'=>'send',
            'userid'=>'',
            'account'=>$account,
            'password'=>$password,
            'mobile'=>$mobiles,
            'extno'=>$extno,
            'content'=>$content,
            'sendtime'=>$sendtime
        );

        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result,true);
        if($result && $result['returnstatus'] == "Success"){
            return true;
        }else{
            return false;
        }
    }
}

?>