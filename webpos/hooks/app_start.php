<?php 
define('DEBUG',true);




function create_mysql_table_fields(){ 
	$r = db::select("show tables");
	$db = config('db.default.database');

	foreach($r as $v){
		$v = (object)$v;
		$key = 'Tables_in_'.$db; 
		$a = $v->$key;
 
		$b = db::select("SHOW COLUMNS FROM ".$a);
	 	unset($fs);
		foreach($b as $vo){
			$vo = (object)$vo;
			$fs[$vo->Field] = $vo->Field;
		}
		$list[$a] = $fs;
	}
 
	$time = 86400*300;
	if(DEBUG === TRUE){
		$time = 60;
	}
	cache("mysql_fields.mysql",$list,$time); 
}

cache("mysql_fields.mysql",[]);
if(!cache("mysql_fields.mysql"))
	create_mysql_table_fields(); 