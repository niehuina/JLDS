<?php 
//cache('default@a',122,5); 设置 如果存在@,前面的是缓存方式
//cache('a'); 获取
$dir = __DIR__.'/../runtime/cache/'; 
return [
    'default'=>array(
        'backend' => 'CacheCache\Backends\File',
        'backend_args' => array(array(
            'dir' => $dir,  
        )) 
    ),
    //mysql字段缓存
    'mysql_fields'=>array(
        'backend' => 'CacheCache\Backends\File',
        'backend_args' => array(array(
            'dir' => $dir,  
        )) 
    ),
    'array' => 'CacheCache\Backends\Memory',
    /*'memcache' => array(
        'backend' => 'CacheCache\Backends\Memcache',
        'backend_args' => array(array(
            'host' => 'localhost',
            'port' => 11211
        ))
    )*/
];