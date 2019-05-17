<?php    
/**
*  session 
*  
*  　　
* @author Sun <sunkang@wstaichi.com>
* @license  
* @copyright  
* @time 2014-2015
*/
/** 
*<code> 
*	需要先session_start();
*	使用方法
*</code>
*
*/
if(!class_exists('session')){
	class session
	{  
		static function init(){
			return obj('crypt');
		}
		/**
		* 启动时session
		*/
		static function start(){
			session_start();
		} 
	 	/**
		* 是否存在 flash message session 
		*
		* @param string $name 　 
		* @return  bool
		*/
		static function has_flash($name){ 
			$name = 'flash_message_'.$name;
			if(static::get($name))
				return true;
			return false;
		} 
		/**
		* 设置 flash message session 
		*
		* @param string $name 　 
		* @param string $value 　 
		* @return  string
		*/
		static function flash($name,$value = null){
			$name = 'flash_message_'.$name;
			if($value){		
				static::set($name,$value);
			}else{		 
				$value = static::get($name);  
		 		static::delete($name); 
			}
			return $value;
		}
		 
	 	/**
		* 设置SESSION
		*
		* @param string $name 　 
		* @param string $value 　 
		* @return  string
		*/
		static function set($name,$value,$exp = 0){    
			$value = static::init()->encode($value); 
			$_SESSION[$name]['data'] = $value;
			if($exp!=0){
				$exp  = time()+$exp;
			}
			$_SESSION[$name]['expire'] = $exp;	
		} 
		/**
		* 读取SESSION
		*
		* @param string $name 　 
		* @return  object/string
		*/
		static function get($name = null){ 
			if(!$name && $_SESSION) { 
				foreach($_SESSION as $k=>$v){  
					if($v['expire']==0 || $v['expire']>time()){
						$data[$k] = static::init()->decode($v['data']);
					}
				} 
				return  (object)$data;
			} 
			elseif(is_array($name) && $_COOKIE) {   
				foreach($name as $k){
					$v = $_SESSION[$k];  
					if($v['expire']==0 || $v['expire']>time()){
						$data[$k] = static::init()->decode($v['data']);
					}
				} 
				return  (object)$data;
			} 
			$value = $_SESSION[$name]; 

			if($value['expire']==0 || $value['expire']>time()){
				return static::init()->decode($value['data']);
			}	 
			 
		} 
		/**
		* 删除SESSION,一个或多个或所有
		*
		* @param string $name 　 字符/数组/null
		* @return  void
		*/
		static function delete($name = null){
			if(!$name)
				$values = $_SESSION;
			elseif(is_array($name))
				$values = array_flip($name);
			if($values){
				foreach($values as $name=>$value){
					unset($_SESSION[$name]); 
				}
				return;
			}  
			unset($_SESSION[$name]); 
		}
		
		
	 
	}
}
