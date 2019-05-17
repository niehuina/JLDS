<?php namespace cs;
/** 
 * @desc 如果要将类的注释和方法的注释合并的话，添加了上面的注释，会将方法中的注释给覆盖掉 
 */  
class TestClass { 
    /**
     * @ApiDescription(section="User22", description="Get information about user")
     * @ApiMethod(type="get")
     * @ApiRoute(name="/test/get/{id}")
     * @ApiParams(name="id", type="integer", nullable=false, description="User id")
     * @ApiParams(name="data", type="object", sample="{'user_id':'int','user_name':'string','profile':{'email':'string','age':'integer'}}")
     * @ApiReturnHeaders(sample="HTTP 200 OK")
     * @ApiReturn(type="object", sample="{
     *  'transaction_id':'int',
     *  'transaction_status':'string'
     * }")
     */  
    public function getPublicMethod($no_default,$add_time = '0000-00-00') {  
        echo "public";  
    }  
    /** 
     * @desc 获取private方法 
     * 
     * @url GET private_test 
     * @return int id 
     */  
    private function getPrivateMethod($no_default,$time = '0000-00-00') {  
        echo "private";  
    }  
  
    /** 
     * @desc 获取protected方法 
     * 
     * @url GET protected_test 
     * @param $no_defalut,$time 
     * @return int id 
     */  
    protected function getProtectedMethod($no_default,$time = '0000-00-00') {  
        echo "protected";  
    }  
}  