<?php
namespace cs;
use Exception;
use log;
/**
 *
 * 取得用户信息
 * $rs = cs\apitoken::ucenter_get(10001);
 * 修改密码
 * $rs = cs\apitoken::ucenter_passwd('admin',"111111");
 *
 * 调用用户中心 支付中心 商城接口
 * @auth sunkangchina
 */
class apitoken{
    /**
     * 通用
     */
    static function _get($type='ucenter',$ctl,$met,$arr = []){
        $url  = config($type.'.url')."?ctl=".$ctl."&met=".$met."&typ=json";
        $formvars = [
                'app_id'    => config($type.'.id'),
            ]+$arr;
        $rs = self::url(config($type.'.key'),$url,$formvars);
        $rs =  json_decode($rs,true);
        if($rs['status'] == 250){
            log::api_error("url: ".$url. " key:". config($type.'.key') ." formvars:".json_encode($formvars));
        }
        return $rs;
    }

    /*----------------------------------------------------------------*/
//以下代码无需修改，作用为发起请求时自带token验证
    /*----------------------------------------------------------------*/
    static function get_time(){
        if (isset($_SERVER['REQUEST_TIME']))
        {
            $time = $_SERVER['REQUEST_TIME'];
        }
        else
        {
            $time = time();
        }
        return $time;
    }
    //可以判断请求时间是否超过某个期限
    static function url($key, $url, $formvars=array(), $typ='JSON', $method='POST')
    {
        $formvars['rtime'] = static::get_time();
        $hash_row = $formvars;
        self::array_multiksort($hash_row, SORT_STRING);
        $hash_row['key'] = $key;
        $tmp_str = http_build_query($hash_row);
        $formvars["token"] = md5($tmp_str);
        $rs = static::get_url($url, $formvars, $typ, $method);
        return $rs;
    }

    static function array_multiksort(&$rows)
    {
        foreach ($rows as $key => $row)
        {
            if (is_array($row))
            {
                self::array_multiksort($rows[$key]);
            }
        }

        ksort($rows, SORT_STRING);
    }


    static  function check($key, $formvars=array())
    {
        $token = $formvars['token'];
        unset($formvars['token']);
        $hash_row = $formvars;
        self::array_multiksort($hash_row, SORT_STRING);
        $hash_row['key'] = $key;
        $tmp_str = http_build_query($hash_row);
        //可以判断请求时间是否超过某个期限, 1分钟内
        if ((static::get_time() - $hash_row['rtime'] < 60000) && $token == md5($tmp_str))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    static function get_url($url, $arr_param=array(), $typ='JSON', $method='POST', $sign_key='', $timeout=10, $curl_header=array())
    {
        $params = '';

        if(is_array($arr_param))
        {
            if($arr_param)
            {
                $params = http_build_query($arr_param);
            }
        }
        else
        {
            $params = $arr_param;
        }


        $curl = curl_init();//初始化curl

        if ('get' == $method)//以GET方式发送请求
        {
            $pos = strpos($url, "?");

            if($pos === false)
            {
                $request_url = $url."?".$params;
            }
            else
            {
                $request_url = $url."&".$params;
            }

            curl_setopt($curl, CURLOPT_URL, $request_url);
        }
        else//以POST方式发送请求
        {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, 1);//post提交方式
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);//设置传送的参数
        }

        if ($curl_header)
        {
            curl_setopt($curl,CURLOPT_HTTPHEADER, $curl_header);
        }

        //curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers_login );
        curl_setopt($curl, CURLOPT_HEADER, false);//设置header
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);//设置等待时间
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        $res = curl_exec($curl);//运行curl
        $err = curl_error($curl);
        if($err)
        {

            throw new Exception($err);
        }
        curl_close($curl);//关闭curl
        return $res;

    }

}