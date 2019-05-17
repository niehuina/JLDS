<?php

namespace cs;
use kamermans\Reflection\DocBlock;
use ReflectionClass;
use ReflectionMethod;

/*

使用方法
use cs\acl;
header_utf8();
$r = new acl();
$r->read("cs\TestClass");
 
composer包含
 kamermans/docblock-reflection
返回内容
 array:5 [▼
  "cls" => "cs.TestClass"
  "title" => "如果要将类的注释和方法的注释合并的话，添加了上面的注释，会将方法中的注释给覆盖掉"
  0 => array:4 [▼
    "method" => "getPublicMethod"
    "url" => "cs.TestClass.getPublicMethod"
    "desc" => "获取public方法"
    "method_flag" => false
  ]
  1 => array:4 [▼
    "method" => "getPrivateMethod"
    "url" => "cs.TestClass.getPrivateMethod"
    "desc" => "获取private方法"
    "method_flag" => false
  ]
  2 => array:4 [▼
    "method" => "getProtectedMethod"
    "url" => "cs.TestClass.getProtectedMethod"
    "desc" => "获取protected方法"
    "method_flag" => true
  ]
]
 */

class acl{
    /**
     * @ApiDescription(section="User", description="Get information about user")
     * @ApiMethod(type="get")
     * @ApiRoute(name="/user/get/{id}")
     * @ApiParams(name="id", type="integer", nullable=false, description="User id")
     * @ApiParams(name="data", type="object", sample="{'user_id':'int','user_name':'string','profile':{'email':'string','age':'integer'}}")
     * @ApiReturnHeaders(sample="HTTP 200 OK")
     * @ApiReturn(type="object", sample="{
     *  'transaction_id':'int',
     *  'transaction_status':'string'
     * }")
     */
    public function read($class_name){
        unset($reflect);
        $reflect = new ReflectionClass($class_name);
        $doc = new DocBlock($reflect);
        $list['cls'] = $top = str_replace("\\",'.',$class_name);
        $list['title'] = $doc->desc;
        $methods = $reflect->getMethods(
            ReflectionMethod::IS_PUBLIC + ReflectionMethod::IS_PROTECTED +
            ReflectionMethod::IS_PRIVATE);
        //遍历所有的方法
        foreach ($methods as $method) {
            $cname = $method->getName();
            $method_flag = $method->isProtected();//还可能是public,protected类型的
        //    $doc = new DocBlock($reflect->getMethod($cname));
            $list[] = [
                'method'=>$cname,
                'url'=>$top.".".$cname,
                'desc'=>$doc->desc,
                'protected'=>$method_flag
            ];
        }

        return $list;
    }

}
