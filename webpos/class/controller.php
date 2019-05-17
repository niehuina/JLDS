<?php
namespace cs;
class controller{
    protected  $allowAction = [];
    protected  $browser=true;
    public function __construct(){

        $this->init();
    }
    public function init(){
        theme('home');

        if($this->browser == true){
            $this->get_client_browser();
        }
        if($this->allowAction && in_array(url_array()['action'],$this->allowAction)){
            return true;
        }
        if(url_string() != 'home/login/index'){
            $this->check_login();
        }

    }
    /*
         *
         *@desc 验证用户是否登录
         */
    protected function check_login(){
        if(!cookie('id') && !cookie('home_user')){
            return redirect(url('home/login'));
        }
    }
    /*
     * 获取当前浏览器类型
     * */
    protected function get_client_browser($glue = null) {
        $browser = array();
        $agent = $_SERVER['HTTP_USER_AGENT']; //获取客户端信息

        if(false!==strpos($agent,'MSIE 10.0') || strpos($agent,'MSIE 9.0') ||strpos($agent,'rv:11.0') || strpos($agent,'Chrome') || strpos($agent,'Safari') || strpos($agent,'iPhone')){
            return true;
         }else{
            echo '本系统仅支持IE8以上版本(不包括IE8)、苹果浏览器（Safari）、谷歌（Chrome）浏览器';exit;
        }
    }


}