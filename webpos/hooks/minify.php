<?php 


if(!function_exists('minify_html')){
	function minify_html($data){
		return cs\minify::html($data);
	}
}
if(!function_exists('minify_css')){
	function minify_css(){
		return cs\minify::output('css');
	}
}
if(!function_exists('minify_js')){
	function minify_js(){
		return cs\minify::output('js');
	}
}
if(!function_exists('links')){
	//生成不重复的JS CSS
	function links($url,$version = null){
		static $all;
		$type = 'css';
		if(is_string($url) && strpos($url,'.js')!==false){
			$type = 'js';
		}
		
		if(is_array($url)){	
			
			$i++;
			$q = 0;
			foreach($url as $v){
				if($q == 0){
					$type = 'css';
					if(is_string($v) && strpos($v,'.js')!==false){
						$type = 'js';
					}
				}
				
				$q++;
				$link = links($v,$version);
				if(!$all[$link])
					$links[] = $link;
				$all[$link] = $link;
				
			}
			if(config('app.minify_'.$type) === true){
				return;
			}
			
		
			$go =  implode("\n",$links);
			 
			return $go;
		}
		if(is_local() === true && !$version){
			$version = '2.0';
		}
		if(!$version){
			$version = minify_version();
		}
		if(config('app.minify_js') === true || config('app.minify_css') === true){
			cs\minify::set($url);
		}
		$url = $url."?v=".$version;
		switch ($type) {
			case 'js':
				if(config('minify_js') === true){
					$link = $url;
				}else{
					$link = '<script type="text/javascript" src="'.$url.'"></script>';	
				}
				
				break;
			
			default:
				if(config('minify_css') === true){
					$link = $url;
				}else{
					$link = '<link rel="stylesheet" href="'.$url.'">';
				}
				break;
		}
		return $link."\n";
	}
}
if(!function_exists('minify_version')){
		function minify_version(){
			return config('app.minify_version')?:"2.0";
		}
}
