<?php
require __DIR__.'/framework/license.php';


//时区
date_default_timezone_set('Asia/Shanghai'); 
/**
 * 过滤MONGODB ARRAY中的KEY为$的 $_GET POST COOKIE REQUEST
 *  
 */
function clean_mongo_array_injection(){
	$in = array(& $_GET, & $_POST, & $_COOKIE, & $_REQUEST);
	while (list ($k, $v) = each($in))
	{
		if(is_array($v)){
			foreach ($v as $key => $val)
			{
				if(strpos($key,'$')!==false){
					unset($in[$k][$key]);
					$key = str_replace('$','',$key);
				}
				$in[$k][$key] = $val;
				$in[] = & $in[$k][$key];
			}
		}
	}
}
clean_mongo_array_injection(); 

function page_opt(){
    unset($_GET[config('app.token_name')],$_GET['page']);
    return $_GET?:[];
}

