<?php
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));
if(!defined('PHP7'))define('PHP7',version_compare(PHP_VERSION, '7.0', '>='));  
define('WEB', realpath(__DIR__.'/../public') );
define('BASE',realpath(__DIR__.'/../'));   
cache::init();

/**
 * 初始化运行环境
 * 禁止 magic quotes
 */
if(function_exists('set_magic_quotes_runtime')){
	@set_magic_quotes_runtime(0);	
}


/**
 * 	check code time	
 * 		code_start('sql runt');
		models\user::all();
		code_end();

 */
if(DEBUG === TRUE && !is_ajax()){ 
			global $debugbar;
			$debugbar = new DebugBar\StandardDebugBar();
			$debugbarRenderer = $debugbar->getJavascriptRenderer();
 
			
			$bar_lists = ['SQL','CACHE','FILES'];
			foreach ($bar_lists as $key => $value) {
				 
				$debugbar->addCollector(new DebugBar\DataCollector\MessagesCollector($value));
			}
			
 
			function debug($msg,$type = 'messages'){
						global $debugbar;
						if($type != 'messages') return $debugbar[$type]->info($msg);
						$debugbar[$type]->info($msg);	
			}

			

			hook::add('view_header',function()use($debugbarRenderer){ 

					//create mysql table fields

					if(file_exists(WEB.'/assets/debug.css')){ 
						return;
					}
					$content = $debugbarRenderer->renderHead();
					if(!is_dir(WEB.'/assets/'))mkdir(WEB.'/assets/',0777,true);
					$preg = '/<\s*script\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i'; 
					preg_match_all($preg,$content,$out);
					$cs = "";
					foreach($out[2] as $css){
						if(strpos($css,'jquery.js')===false && strpos($css,'jquery.min.js')===false )
							$cs .=file_get_contents(BASE.$css);
					}

					file_put_contents(WEB.'/assets/debug.js', $cs);

					$preg = '/<\s*link\s+[^>]*?href\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i'; 
					preg_match_all($preg,$content,$out);
					$cs = "";
					foreach($out[2] as $css){
						$cs .=file_get_contents(BASE.$css);
					}
					file_put_contents(WEB.'/assets/debug.css', $cs);

					echo '<link  href="'.base_url().'/assets/debug.css"  media="all" rel="stylesheet" />';

					echo '<script  src="'.base_url().'/assets/debug.js"></script>';


			});

			hook::add('view_footer',function()use($debugbarRenderer,$debugbar,$bar_lists){  

					try { 
					    $qs = db::getQueryLog();
					    if($qs){ 
					      foreach($qs as $v){ 
					      	$ls = $v['query']."; ".__('bindings').":".var_export($v['bindings'],true)." time:".$v['time'].__('ms');
					        debug($ls,'SQL'); 
					        chrome($ls);
					      }
					    }

					}catch(Exception $e){

					} 
					foreach( get_included_files()  as $v){
						debug( $v,'FILES');	
					}
					 
				 	if(file_exists(WEB.'/assets/debug.css')){
						echo '<link  href="'.base_url().'/assets/debug.css"  media="all" rel="stylesheet" />';

						echo '<script  src="'.base_url().'/assets/debug.js" ></script>';
						echo $debugbarRenderer->render();
						echo "<style>div.phpdebugbar-widgets-messages div.phpdebugbar-widgets-toolbar{display:none;}</style>";
						return;
					}
				  
					echo $debugbarRenderer->render();
					echo "<style>div.phpdebugbar-widgets-messages div.phpdebugbar-widgets-toolbar{display:none;}</style>";
			});

			function code_start($msg = 'codeing run time'){
				global $debugbar;
				$debugbar['time']->startMeasure('longop', $msg); 
			}

			function code_end(){
				global $debugbar;
				$debugbar['time']->stopMeasure('longop');
			}

			function ex($e){
				global $debugbar;
				$debugbar['exceptions']->addException(new Exception($e));
			}

}else{
			function debug($msg,$type = 'messages'){}
			function code_start($msg = 'codeing run time'){}
			function ex($e){}
			function code_end(){}
}
 
// 处理被 magic quotes 自动转义过的数据
if (@get_magic_quotes_gpc())
{
		debug('建议关闭get_magic_quotes_gpc,PHP官方已移除该配置，这是不安全的！！！');
    $in = array(& $_GET, & $_POST, & $_COOKIE, & $_REQUEST);
    while (list ($k, $v) = each($in))
    {
        foreach ($v as $key => $val)
        {
            if (! is_array($val))
            {
            	// 过滤查询特殊字符
					    if (preg_match('/^(EXP|NEQ|GT|EGT|LT|ELT|OR|XOR|LIKE|NOTLIKE|NOT BETWEEN|NOTBETWEEN|BETWEEN|NOTIN|NOT IN|IN)$/i', $val)) {
					        debug('mysql 注入已被阻止,key:'.$key." val:".$val);
					        $val = ' ';  
					    } 
              $in[$k][$key] = trim(stripslashes($val));
              continue;
            }
            $in[] = & $in[$k][$key];
        }
    }
    unset($in);
}
  
 
if(!function_exists('jump_domain')){
	/**
	 * 子域名跳到www域名.
	 * @return  [type]
	 * @weichat sunkangchina
	 * @date    2017
	 */
	function jump_domain(){ 
		$domain = config::get('app.domain');
		$host = $_SERVER['HTTP_HOST']; 
		if($domain && !is_local()  && $domain!=$host && substr_count($host,'.') == 1){
			redirect(get_http().'://www.'. $host);
		}
		 
	}
}  

function header_utf8(){
     header("Content-type: text/html; charset=utf-8");        
}

function yaml_dump($array){
	return  Symfony\Component\Yaml\Yaml::dump($array);
}

function yaml_load($file){ 
	try {
	    return Symfony\Component\Yaml\Yaml::parse(file_get_contents($file));
	} catch (Symfony\Component\Yaml\Exception\ParseException $e) {
	    ex("Unable to parse the YAML string: %s", $e->getMessage());
	}
} 


function h($string){
	return htmlspecialchars($string,ENT_QUOTES);
} 
  

if(!function_exists('is_local')){
	/**
	 * 是否是本机访问
	 * @return  boolean
	 * @weichat sunkangchina
	 * @date    2017
	 */
	function is_local(){
		if(in_array(ip(), ['127.0.0.1','::1','0.0.0.0'])){
			return true;
		}		
		return false;
	}
} 
 
 /**
 * 写入guest 这个cookie,10年的COOKIE
 */
if(!cookie::get('guest')){
	$r = str::id().'-'.date('YmdHis');
	cookie::set('guest',$r,time()+3600*30*12*10); 
}


/**
 * 导入不重复的文件
 * @param   [type]       $file
 * @return  [type]
 * @weichat sunkangchina
 * @date    2017 
 */
function import($file){
	static $m;
	$id = md5($file);
	if(!$m[$id]){
		include $file;
		$m[$id] = true;
	}
	 
}
/**
 * 实例化不重复的类
 * @param   [type]       $class
 * @return  [type]
 * @weichat sunkangchina
 * @date    2017
 */
function obj($class){
	$class = str_replace('.','\\',$class);
	static $m;
	if(!$m[$class]){
		$m[$class] = new $class;
	}
	return $m[$class];
}
 
/**
 * url函数生成时，总是会带的参数 
 * @param   [type]       $ar
 * @return  [type]
 * @weichat sunkangchina
 * @date    2017 
 */
function url_par($ar = null){
	static $arr = [];
	if(!$ar){
		return $arr;
	}
	$arr = $ar;
	return $arr;
}
/**
 * 生成URL
 * @param   [type]       $url
 * @param   array        $par
 * @return  [type]
 * @weichat sunkangchina
 * @date    2017 
 */
function url($url,$par=[]){
	if(url_par()){
		$par = $par+url_par();
	}
 	return router::url($url,$par);
}
/**
 * 当前URL的module/controller/action
 * @return  [type]
 * @weichat sunkangchina
 * @date    2017
 */
function url_string(){
	$mod = config('app.url_mode');
	if($mod == 1 || $mod == 2){
		return $_GET['m'].'/'.$_GET['c'] .'/'.$_GET['a'];
	}
	return router::string();
}

/**
 * 当前host,返回带http://...
 * @return  [type]
 * @weichat sunkangchina
 * @date    2017
 */
function host(){
	return router::host();
}
/**
 * URL,module controller action  
 * @return  array
 * @weichat sunkangchina
 * @date    2017
 */
function url_array(){
	$mod = config('app.url_mode');
	if($mod == 1 || $mod == 2){
		return ['module'=>$_GET['m'] , 'controller'=>$_GET['c'] ,'action'=>$_GET['a']];
	}
	return router::controller();
}
 
/**
 * 渲染视图
 * @param   [type]       $file
 * @param   array        $par
 * @return   
 * @weichat sunkangchina
 * @date    2017
 */
function view($file,$par = []){ 
	return view::make($file,$par); 
}
/**
 * 在视图中设置变量
 * @param   [type]       $name
 * @param   [type]       $value
 * @return  [type]
 * @weichat sunkangchina
 * @date    2017
 */
function view_set($name,$value){
	return view::set($name,$value);
}
/**
 * 视图缓存
 * @param   缓存时间      $timeSecond
 * @return   
 * @weichat sunkangchina
 * @date    2017
 */
function view_cache($timeSecond = 3600){
	return view::cache($timeSecond);
}
/**
 * 当前module路径
 * @param   [type]       $path
 * @return  [type]
 * @weichat sunkangchina
 * @date    2017
 */
function view_module_path($path){
	router::$module_path = $path;
}
/**
 * 设置或获取当前主题
 * @param   [type]       $name
 * @return   
 * @weichat sunkangchina
 * @date    2017
 */
function theme($name = null){
	$version = config::get('app.theme_version');
	if($version && $name){
		$name = $version.'/'.$name; 
	}
	return view::theme($name);
}
/**
 * 当前主题对应的URL
 * @param   [type]       $url
 * @return  [type]
 * @weichat sunkangchina
 * @date    2017
 */
function theme_url($url = null){
	if($url && substr($url,0,1)!='/'){
		$url = '/'.$url;
	}
	return view::themeUrl().$url;
}
/**
 * 主题路径
 * @param          $url
 * @return  
 * @weichat sunkangchina
 * @date    2017-04-07
 */
function theme_path($url = null){
	 
	return  WEB.theme_url($url); 
}
/**
 * 网站基础URL
 * @return  
 * @weichat sunkangchina
 * @date    2017
 */
function base_url(){
	$s = $_SERVER['SCRIPT_NAME'];  
	if(substr_count($s,'/')>1){
		return substr($s,0,strrpos($s,'/'));
	}
	return;
}

/**
 * 设置或获取config
 * @param          $name
 * @param          $value
 * @return  
 * @weichat sunkangchina
 * @date    2017
 */
function config($name  = null , $value = null){
	return $value?config::set($name,$value):config::get($name);
}
/**
 * 设置或获取 cookie 
 * @param          $name
 * @param          $value
 * @param          $exp
 * @return  
 * @weichat sunkangchina
 * @date    2017
 */
function cookie($name  = null , $value = null,$exp = 0){
	return $value?cookie::set($name,$value,$exp):cookie::get($name);
}
/**
 * 设置seesion
 * @param   [type]       $name
 * @param   [type]       $value
 * @param   integer      $exp
 * @return  [type]
 * @weichat sunkangchina
 * @date    2017 
 */
function session($name  = null , $value = null , $exp = 0){
	return $value?session::set($name,$value,$exp):session::get($name);
}
  

 
/**
 * url跳转
 * @param   [type]       $url
 * @param   bool默认false      $e301
 * @return  
 * @weichat sunkangchina
 * @date    2017
 */
function redirect($url,$e301 = false){
	if($e301 === true){
		header( "HTTP/1.1 301 Moved Permanently" );
	}
	header("location:$url");
	exit;
}
/**
 * 判断是否是post请求
 * @return  boolean
 * @weichat sunkangchina
 * @date    2017
 */
function is_post(){
	return $_SERVER['REQUEST_METHOD']=='POST'?true:false;
}
/**
 * 判断是否是ajax请求
 * @return  boolean
 * @weichat sunkangchina
 * @date    2017
 */
function is_ajax(){
	if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
	{
		return true;
	}
	else
	{
		return false;
	}
}

 
/**
 * 获取客户端IP地址
 * @param   返回类型 0 返回IP地址 1 返回IPV4地址数字      $type
 * @return  [type]
 * @weichat sunkangchina
 * @date    2017-04-07
 */
function ip($type = 0)
{
	$type      = $type ? 1 : 0;
	static $ip = null;
	if (null !== $ip) {
		return $ip[$type];
	}
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
		$pos = array_search('unknown', $arr);
		if (false !== $pos) {
			unset($arr[$pos]);
		}
		$ip = trim($arr[0]);
	} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (isset($_SERVER['REMOTE_ADDR'])) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	// IP地址合法验证
	$long = sprintf("%u", ip2long($ip));
	$ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
	return $ip[$type];
}
 
/**
 * 字符加密
 * @param   [type]       $value
 * @param   [type]       $key
 * @return  [type]
 * @weichat sunkangchina
 * @date    2017
 */
function encode($value,$key = null){
	$c = obj('crypt');
	if($key){
		$par['key'] = $key;
	}
	return $c->encode($value,$par);
}
/**
 * 字符解密
 * @param   [type]       $value
 * @param   [type]       $key
 * @return  [type]
 * @weichat sunkangchina
 * @date    2017
 */
function decode($value,$key = null){
	$c = obj('crypt');
	if($key){
		$par['key'] = $key;
	}
	return $c->decode($value,$par);
}

/**
 * 格式化输出
 * @param   arr       $s
 */
if(!function_exists('dump')){
			function dump($s){
					print_r('<pre>');
					print_r($s);
					print_r('</pre>');
		  }
}



////////////////////////////////////////
 
/**
* 判断http https
* @return  http或https
* @weichat sunkangchina
* @date    2017
*/
function get_http(){
		$top = 'http';
		if($_SERVER['SERVER_PORT'] == 443 || $_SERVER['HTTPS'] == 1 ||$_SERVER['HTTPS'] == 'on')
			$top = 'https';
		return $top; 
}
 
//xss过滤
function xss_clean($str,$is_image = false){
		return security::xss_clean($str);
}

function request_data($key,$xss_clean = null ){  
	return  post_data($key , $xss_clean)?:get_data($key,$xss_clean);
}


function get_data($key = null,$xss_clean = null ){  
		return  input::get($_GET,$key,$xss_clean);
}

function post_data($key = null ,$xss_clean = null  ){  
		 return input::get($_POST,$key,$xss_clean);
}


function last_insert_id(){
	return DB::getPdo()->lastInsertId();
}


 