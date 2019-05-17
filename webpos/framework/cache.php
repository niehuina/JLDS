<?php   
use CacheCache\CacheManager;
/**
 * cache()->set('a',122,5);
 *
 * config/cache.php
$dir = __DIR__.'/../runtime/cache/'; 
return [
    'default'=>array(
        'backend' => 'CacheCache\Backends\File',
        'backend_args' => array(array(
            'dir' => $dir,  
        )) 
    ),
    'array' => 'CacheCache\Backends\Memory',
    'memcache' => array(
        'backend' => 'CacheCache\Backends\Memcache',
        'backend_args' => array(array(
            'host' => 'localhost',
            'port' => 11211
        ))
    )
];
http://maximebf.github.io/CacheCache/
 */
class cache{ 
 	

 	static function init(){
 		$cf = config::get('cache'); 
 		$cache = CacheManager::setup($cf); 
 		if(!config::get('cache')){
 				config::set('cache',$cache);	
 		} 
 	} 
  
}
//cache('default@a',122,5); 设置 如果存在@,前面的是缓存方式
//cache('a'); 获取
function cache(){
		$arg = func_get_args();
		$key = $arg[0];
		$value = $arg[1];
		$ttl = $arg[2];
		$at = strpos($key,'@');
		$name = "default";
		if($at !== false){
				$name = substr($key,0,$at);
				$key = substr($key,$at+1); 
		} 
		if($ttl || isset($value)){ 
				debug("SET $name  ,value: $value  ,ttl: $ttl",'CACHE');
				return CacheManager::get($name)->set($key,$value,$ttl);
		}
		$data = CacheManager::get($name)->get($key);
		debug("GET $name ,value:$data",'CACHE');
		return $data;
		
}