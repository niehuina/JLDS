<?php 
namespace cs;
use MatthiasMullie\Minify as MinifyCore;
use file;
/*
$css = [
	'themes/business-casual/misc/css/business-casual.css',
	'themes/business-casual/misc/css/bootstrap.css',
];
echo  minify::css($css);
 */
class minify{
	static $file;
	 
	static function set($file){
		static::$file[$file] = $file;
	}
	static function output($type = 'css'){

		if(!static::$file){
			return;
		}
		foreach(static::$file as $v){
			if(strpos($v,'.'.$type) !== false){
				$links[$v] = $v;
			}
		}

		
		$url = minify::$type($links)."?version=".minify_version();
		if($type=='css' && config('app.minify_css')){
			return  '<link rel="stylesheet" href="'.(config('app.host')?:base_url().'/').$url.'">';			
		}elseif($type == 'js' && config('app.minify_js')){
			return '<script type="text/javascript" src="'.(config('app.host')?:base_url().'/').$url.'"></script>';	
		}
	}
	static function __find($file){
		if(strpos($file,'?')!==false){
			$file = substr($file,0,strpos($file,'?'));
		}
		return $file;
	}
	static function css($files){
			$key = 'css_'.md5(json_encode($files));
			$url = 'assets/minify-'.$key.'.css';
			$minifiedPath = WEB.'/'.$url;
			if(is_file($minifiedPath)){
				return $url;
			}
			$dir = file::dir($minifiedPath);
			if(!is_dir($dir)){ mkdir($dir,0777,true); }
			$minifier = new MinifyCore\CSS();
			if(!$files)return;
			$PHP_SELF = $_SERVER['PHP_SELF'];
			$PHP_SELF = str_replace('/index.php','',$PHP_SELF);
 			
			foreach($files as $v){
				$v = static::__find($v);
				
				if($PHP_SELF){  
					$load = substr(WEB,0,-(strlen($PHP_SELF))); 
					$load = $load.$v;
				}else{
					$load = WEB.'/'.$v;	
				}
				
				
				if(is_file($load)){ 
					$nw = substr($v,0,strrpos($v,'/'));
					$nw = substr($nw,0,strrpos($nw,'/')); 
					$v = file_get_contents($load);
					$v = str_replace("../",$nw."/",$v);
					 
				}
				
				$minifier->add($v);	
			}
			 
			$minifier->minify($minifiedPath);
			return $url;
	}
	static function js($files){
			$key = 'js_'.md5(json_encode($files));
			$url = 'assets/minify-'.$key.'.js';
			$minifiedPath = WEB.'/'.$url;
			if(is_file($minifiedPath)){
				return $url;
			}
			$dir = file::dir($minifiedPath);
			if(!is_dir($dir)){ mkdir($dir,0777,true); }
			$minifier = new MinifyCore\JS();
			if(!$files)return;
			$PHP_SELF = $_SERVER['PHP_SELF'];
			$PHP_SELF = str_replace('/index.php','',$PHP_SELF);
			foreach($files as $v){
				$v = static::__find($v);
				if($PHP_SELF){  
					$load = substr(WEB,0,-(strlen($PHP_SELF))); 
					$load = $load.$v;
				}else{
					$load = WEB.'/'.$v;	
				}
				
				
				 
				if(is_file($load)){
					$v = file_get_contents($load);
				}
				 
				$minifier->add($v);	
			}
			 
			$minifier->minify($minifiedPath);
			return $url;
	}
	static function html($data){
		$replace = array(
            '/<!--[^\[](.*?)[^\]]-->/s' => '',
            "/<\?php/"                  => '<?php ',
            "/\n([\S])/"                => ' $1',
            "/\r/"                      => '',
            "/\n/"                      => '',
            "/\t/"                      => ' ',
            "/ +/"                      => ' ',
        );
        $data =  preg_replace(
            array_keys($replace), array_values($replace), $data
        );
        return $data;
	}
}