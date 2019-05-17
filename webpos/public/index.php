<?php
//生产环境要改为 false
define('DEBUG',false); 
//系统出错，如需在生产环境输出请在此处操作，不建议输出内容，可
//显示404等页面
function myErrorHandler($errno, $errstr, $errfile, $errline) {
    
}



require __DIR__.'/../app.php';
 