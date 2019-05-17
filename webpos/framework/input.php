<?php  

/**
 * @author Sun Kang <68103403@qq.com>
 * @copyright 2017
 */
/*
	过滤规则不满足的字段 
 */
use Valitron\Validator as V;
class input{
  
	//过滤数据
	static $filters = [
		'numeric'=>[
			'id'
		],
	]; 

	public static function get($data ,$key,$xss_clean = null){  
			$v = new V($data);
			if(static::$filters){
				foreach(static::$filters as $valikey=>$fieds){
					$v->rule($valikey, $fieds );
				}
			}  
			if(!$v->validate()) {
					$un  = array_keys($v->errors());
					if($un){ 
						foreach ($un as$value) {
							 unset($data[$value]);
						}
					} 
			}  
			if($key && $data){  
					$value =  $data[$key];
			}else{
					$value =  $data; 	
			} 
			if($xss_clean === false){
				return $value;
			}
			if(config('app.global_xss_clean') || $xss_clean === true){
					return xss_clean($value);
			}
	}
	 
 

	

	

}