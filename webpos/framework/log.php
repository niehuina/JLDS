<?php
/**
*  Log日志 
*  
*  　　
* @author Sun <sunkang@wstaichi.com>
* @copyright http://www.wstaichi.com 
* @time 2014-2015
*/
/**
*<code>
*需要定义 define('WEB',__DIR__);  
*	
*启用日志,无参数时将启用所有级别的日志，如为数组将只启用对应的日志
*
*Log::open(['test']);  
*   
*
*Log::info('test');
*Log::error('test');
*Log::read();
*	
*Route:
*
*	 
*
*</code>
*/
class log{
	static $path;  
	/**
	*是否开启日志 ，默认开启
	*/
	static $open = false;
	static $enable;
	static $object;
	static $time = [];
	static $elements;
	static $lg;
	 
	
	/**
	* 启用日志
	*
	* @param array $arr 　 
	* @return  void
	*/	
	static function open($arr = null ){
		if(!isset(static::$object)){
			static::init();
			static::$object = true;
		}
		static::$open = true;
		if($arr)
			static::$enable = $arr;
	}
 	/**
	* 初始化
	*/
 	static function init(){
 		if(static::$path) return;
 	    $path = BASE.'/runtime/logs'; 
 		if(!is_writable($path)){
	 		$root = substr($path,0,strrpos($path,'/'));
	 		exec("chmod 777 $root");
 		}  
 		if(!is_dir($path) ) { 
 			mkdir($path, 0777, true); 
 		} 

 		static::$path = realpath($path);  
 	}  
 	 
 	/**
	* 清空日志
	*
	* @param string $name 　 
	* @return  void
	*/
 	static function clean($name = null){
 		if(static::$open===false) return;
 		$dir = static::$path;
 		if($name){
 			$name = ucfirst($name);
 			$dir = $dir.'/'.$name;
 		}  
		file::rmdir($dir);
 	}
  
 	/**
	* 内部函数，写文件
	*
	* @param string $type 　 
	* @param string $str 　 
	* @return  void
	*/
 	static function set($type = 'info',$str ,$w = false){ 
 		self::$lg[$type][] = $str;
 		if(static::$open===false) {
 			return;
 		}
 		if(!$str) return ; 
 		if(false === $w && static::$open !== true) return ;
 		if(static::$enable && !in_array(strtolower($type),static::$enable)) return;   
 		$dir = static::$path.'/'.$type."/".date('Y-m').'/'.date('d');  
 		if(!is_dir($dir) ) { 
 			mkdir($dir, 0777, true); 
 		} 
 	 
  		$filename = $dir.'/'.date("Hi").".log"; 
 		if(is_array($str)) {
 			unset($new);
 			foreach($str as $k=>$v){
 				$k1[] = $k;
 				$v1[] = $v; 
 			}
 			if(!file_exists($filename)){
	 			foreach($k1 as $v){
	 				if(is_object($v)) $v= (array)$v;
	 				if(is_array($v)) $v = json_encode($v);
	 				$new .= $v."\t"; 
	 			}
	 			$new .= "\r";
 			}
 			foreach($v1 as $v){
 				if(is_object($v)) $v= (array)$v;
 				if(is_array($v)) $v = json_encode($v);
 				$new .= $v."\t"; 
 			} 
 			$str = $new;
 		}  
 		if(!$str) return;  
 		try{
 			 
 			$fh = fopen($filename, "a+"); 
 			$str = $str."\n";
 			self::$elements[$type][] = $str; 
			fwrite($fh, $str);
			fclose($fh);
 			 
				
 		}catch(Exception $e) { 
		    
		} 
		 
 	}
 	static function get($type = null){
 		return $type?self::$lg[$type]:self::$lg;
 	}
 	/**
	* 静态方法
	*/
 	static function __callStatic ($name ,$arg = [] ){ 
 		 $str = $arg[0];  
 		 if(is_array($str) && count($str)>1){$str = implode(" ",$str);}  
 		 if(strtolower(substr($name,0,4))=='json'){ 
 		 	$name = substr($name,4); 
 		 	static::json($str,$name);
 		 	return ;
 		 }
 		 static::set($name , $str);
	}
 	  
}