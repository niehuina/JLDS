<?php  namespace modules\doc;
use cs\login;
/**
 * @desc 关于我们
 */
class logo{
    /**
     *@desc 系统信息
     */
    public function index(){
        login::ts();
        return view('logo');
    }

}