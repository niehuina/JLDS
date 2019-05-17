<?php
use Schnittstabil\Csrf\TokenService;
/**
 * composer require volnix/csrf
 * token表单验证
 * @weichat sunkangchina
 * @date    2017
 */
class token{

	/**
	 * 取得token 名
	 * @return  [type]
	 * @weichat sunkangchina
	 * @date    2017
	 */
	static function name(){
		return config('app.token_name')?:"__csrf_token__";
	}
	static function ins(){
			if(config('tokenService'))
			{
				return config('tokenService');
			}
			$ttl = config('app.csrf_ttl')?:1440;  
			$key = config('app.csrf_key')?:md5('token');
			config('tokenService',  new TokenService($key, $ttl) ); 
			return config('tokenService');
	}
	/**
	 * 生成token
	 * @param   boolean      $new
	 * @return  
	 * @weichat sunkangchina
	 * @date    2017
	 */
	static function create( ){ 
			return self::ins()->generate();
	}
	/**
	 * 判断token是否正确
	 * @param   [type]       $value
	 * @return  bool
	 * @weichat sunkangchina
	 * @date    2017
	 */
	static function check($token){
		if (self::ins()->validate($token)) {
				return true;
		}
		return false;
	}

}