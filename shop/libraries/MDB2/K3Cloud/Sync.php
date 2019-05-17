<?php

/**
 * @author xialei <xialeistudio@gmail.com>
 */
class K3Cloud_Sync
{
    private static $k3cloud_instance;
    const REQ_GET = 1;
    const REQ_POST = 2;

    /**
     * 单例模式
     * @return map
     */
    public static function instance()
    {
        if (!self::$k3cloud_instance instanceof self)
        {
            self::$k3cloud_instance = new self;
        }
        return self::$k3cloud_instance;
    }

    function login(){
        $cloudUrl = "http://123.183.136.65:8090/K3Cloud/Kingdee.BOS.WebApi.ServicesStub.AuthService.ValidateUser.common.kdsvc";

        //登陆参数
        $data = array(
           'acctID' => '59f866a999c091',
           'username' => 'admin',
           'password' => 'ygf118',
           'lcid' => 2052
        );
//        $data = array(
//            '59f866a999c091',//帐套Id 正式
//            'admin',//用户名
//            'ygf118',//密码
//            2052//语言标识
//        );
        //定义记录Cloud服务端返回的Session

        $resp = $this-> async($cloudUrl,$data,false,self.REQ_POST);

        //$array = json_decode($result,true);
//        header("Content-type: text/html; charset=gb2312");
//        echo '<pre>';print_r('登陆请求数据：');
//        echo '<pre>';print_r($post_content);
//
//        echo '<pre>';print_r('登陆返回结果：');
//        echo '<pre>';print_r($result);
        return $resp;
    }

    /**
     * 执行CURL请求
     * @author: xialei<xialeistudio@gmail.com>
     * @param $url
     * @param array $params
     * @param bool $encode
     * @param int $method
     * @return mixed
     */
    private function async($url, $params = array(), $encode = true, $method = self::REQ_GET)
    {
        $ch = curl_init();
        if ($method == self::REQ_GET)
        {
            $url = $url . '?' . http_build_query($params);
            $url = $encode ? $url : urldecode($url);
            curl_setopt($ch, CURLOPT_URL, $url);
        }
        else
        {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        curl_setopt($ch, CURLOPT_REFERER, '百度地图referer');
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X; en-us) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $resp = curl_exec($ch);
        curl_close($ch);
        return $resp;
    }

}