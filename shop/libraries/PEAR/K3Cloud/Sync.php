<?php

/**
 * @author xialei <xialeistudio@gmail.com>
 */
class K3Cloud_Sync
{
    private static $k3cloud_instance;
    const REQ_GET = 1;
    const REQ_POST = 2;
    const CLOUD_URL  = "http://123.183.136.65:8090/K3Cloud/";
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

    public function login(){

        //登陆参数
        $data = array(
            'acctID' =>   '5a17ec59dd4503',//帐套Id 正式
            'username' => 'admin',//用户名
            'password' =>  'ygf118',//密码
            'lcid' => 2052//语言标识
        );
         $cookie_jar = tempnam(DATA_PATH . '/k3cloud' ,'CloudSession');
         $post_content = $this->  create_postdata($data);

         $result = $this->  invoke_login(self::CLOUD_URL,$data,$cookie_jar);


//        $resp = $this-> async($cloudUrl,$data,true,self::REQ_POST,$cookie_jar,true);
//
          $array = json_decode($result,true);

          $formdata = "{\"FormId\":\"STK_Inventory\",\"TopRowCount\":0,\"Limit\":10,\"StartRow\":0,\"FilterString\":\"FMaterialId.FNumber='HG_TEST'\",\"OrderString\":\"FID ASC\",\"FieldKeys\":\"FID,FSupplierId,FMaterialId,FMaterialId.FNumber,FMaterialName\"}";
          $wullist = array(
              'data' => "{\"FormId\":\"STK_Inventory\",\"TopRowCount\":0,\"Limit\":10,\"StartRow\":0,\"FilterString\":\"\",\"OrderString\":\"\",\"FieldKeys\":\"\"}"
          );

         $wuliresult =   $this->invoke_query(self::CLOUD_URL,$wullist,$cookie_jar);
           $lisresult =   json_decode($wuliresult,true);
          return $lisresult;
    }

    //登陆
    function invoke_login($cloudUrl,$post_content,$cookie_jar)
    {
        $loginurl = $cloudUrl.'Kingdee.BOS.WebApi.ServicesStub.AuthService.ValidateUser.common.kdsvc';
         return  $this-> async($loginurl,$post_content,true,self::REQ_POST,$cookie_jar,true);
    }

    //保存
    function invoke_save($cloudUrl,$post_content,$cookie_jar)
    {
        $invokeurl = $cloudUrl.'Kingdee.BOS.WebApi.ServicesStub.DynamicFormService.Save.common.kdsvc';
        return invoke_post($invokeurl,$post_content,$cookie_jar,FALSE);
    }

    function invoke_query($cloudUrl,$post_content,$cookie_jar){

        $invkoeurl  = $cloudUrl. 'Kingdee.BOS.WebApi.ServicesStub.DynamicFormService.ExecuteBillQuery.common.kdsvc';
        return  $this-> async($invkoeurl,$post_content,true,self::REQ_POST,$cookie_jar,false);
    }
    //审核
    function invoke_audit($cloudUrl,$post_content,$cookie_jar)
    {
        $invokeurl = $cloudUrl.'Kingdee.BOS.WebApi.ServicesStub.DynamicFormService.Audit.common.kdsvc';
        return invoke_post($invokeurl,$post_content,$cookie_jar,FALSE);
    }


    private function async($url, $params = array(), $encode = true, $method = self::REQ_GET,$cookie_jar,$isLogin)
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
        curl_setopt($ch, CURLOPT_REFERER, 'k3 cloud referer');
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X; en-us) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if($isLogin){
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
        }
        else{
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
        }
        $resp = curl_exec($ch);
        curl_close($ch);
        return $resp;
    }

    //示例代码，不工作
    function invoke_post($url,$post_content,$cookie_jar,$isLogin)
    {
        $ch = curl_init($url);

        $this_header = array(
            'Content-Type: application/json',
            'Content-Length: '.strlen($post_content)
        );

         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $this_header);
        //curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_content);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if($isLogin){
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
        }
        else{
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
        }
        // curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    //构造Web API请求格式
    function create_postdata($args) {
        $postdata = array(
            'format'=>1,
            'useragent'=>'ApiClient',
            'rid'=>$this-> create_guid(),
            'parameters'=>$args,
            'timestamp'=>date('Y-m-d'),
            'v'=>'1.0'
        );
        return json_encode($postdata);

    }

    //生成guid
    function create_guid() {
        $charid = strtoupper(md5(uniqid(mt_rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
        return $uuid;
    }
}