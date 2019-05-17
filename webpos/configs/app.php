<?php 

return [
	'lang' 							=>  'zh-cn',
	'csrf_ttl'					=>	1440,
	'csrf_key'					=>	md5('admin'),
	'token_name'				=>	'__csrf_token__',
	'url_mode'					=>	3,// 1 2 3
	'url_pathinfo'			=>	'/',
	'minify_js'					=>	false,
	'minify_css'				=>	false,
	'minify_html'				=>  false,
	'minify_version'    =>  "2",
	'global_xss_clean' 	=>  true, //全站XSS过滤
	'version'						=> '2.0.0',
	'view_json'					=> false,
	'page_size'					=> 10 , //每页显示多少
    'sms_name'                  =>'远丰集团',//短信模板签名
    'sms_code'                  =>'SMS_57045029',/*模板code*/
    'accessKeyId'               =>'RYmTnrWcEPkc8TCy',//短信模板key
    'accessSecret'              =>'EvHPMjHb9iKNJHekZWoaI4FMalZps4'//短信模板secret
];