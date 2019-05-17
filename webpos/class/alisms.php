<?php   namespace cs;
use Sms\Request\V20160927 as SmsAli;
include_once __DIR__.'/aliyun-php-sdk-core/Config.php';
/**
 * SMS发送
 * @weichat  sunkangchina
 * @datetime 2017-04-07T14:11:06+0800
 */
class alisms{

 
	/**
	 * 发送手机短信
	 * @param    $tel
	 * @param    $code
	 * @return   bool
	 */
	static function  send($tel,$code){  
 
		    $flag = false; 
		    $iClientProfile = \DefaultProfile::getProfile("cn-hangzhou", config('app.accessKeyId'), config('app.accessSecret'));
		    $client = new \DefaultAcsClient($iClientProfile);    
		    $request = new SmsAli\SingleSendSmsRequest();
		    $request->setSignName(config('app.sms_name'));/*签名名称*/
		    $request->setTemplateCode(config('app.sms_code'));/*模板code*/
		    $request->setRecNum($tel);/*目标手机号*/
		    $request->setParamString("{\"code\":\"$code\"}");/*模板变量，数字一定要转换为字符串*/
		    try {
		        $response = $client->getAcsResponse($request);
		        $flag = true; 
		        cache("sms_".$tel,$code,120);
		    }
		    catch (ClientException  $e) {
		    	//print_r($e);
		        $flag = false;
		    }
		    catch (ServerException  $e) {       
			    //print_r($e); 
		     	$flag = false;
		    }

		    return $flag;

	}

}