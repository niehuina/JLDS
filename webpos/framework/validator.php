<?php
//https://github.com/vlucas/valitron
use Valitron\Validator as V;
class validator{
    /*
     $rules = [
        'foo' => ['required', 'integer'],
        'bar'=>['email', ['lengthMin', 4]]
    ];
     */
    static $msg;
    static $init = false;
 
    static function set($data,$rules){ 
            if(!static::$init){
                static::$init = true;
                self::load_validate();
                V::lang(config('app.lang')); 
            }
            
            $v = new \Valitron\Validator($data);
            $v->mapFieldsRules($rules);  
            $keys = array_keys($rules);
            foreach($keys as $vo){
                $labels[$vo] = __(ucfirst(trim($vo)));
            }
            $v->labels($labels);  
            $vali = $v->validate();
            $e =  $v->errors();
            if($e){
                static::$msg = $e;
                return $e;
            } 
    }
   


    static function get(){
        if(static::$msg)
            return static::$msg;
        return;
    }

    static function load_validate(){
        $arr = [
            'uniqid',
            'is_domain' ,
            'is_phone',
            'is_card'
        ];
        foreach ($arr as $v) {
            self::$v();
        }
    }
    //'ucenter_name'=>['required',['uniqid','users','ucenter_name'] ],
    static function uniqid(){

        Valitron\Validator::addRule('uniqid', function($field, $value, array $params, array $fields) {
            $tab = $params[0];
            $fs = $params[1]?:$field;
            $id = $params[2]?:$_POST['id'];
            $get = \DB::table($tab)->where($field, $value)
                ->where('id','!=',$id)
                ->first();

            if($get)
                return false;
            else
                return true;
        }, __("已存在"));
    }


    static function is_domain()
    {

        Valitron\Validator::addRule('is_domain', function($field, $value, array $params, array $fields) {
            $regular = "/^([0-9a-z\-]{1,}\.)?[0-9a-z\-]{2,}\.([0-9a-z\-]{2,}\.)?[a-z]{2,}$/i";
            return preg_match($regular, $value);
        }, __("域名格式错误"));
    }

    static function is_phone()
    {

        Valitron\Validator::addRule('is_phone', function($field, $value, array $params, array $fields) {
            return !empty($value) && preg_match('/^1[3-9]\d{9}$/', $value);
        }, __("手机号格式错误"));

    }

    

    static function is_card()
    {

        Valitron\Validator::addRule('is_card', function($field, $value, array $params, array $fields) {
            return !empty($value) && preg_match('/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$|^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/', $value);
        }, __("身份证号格式错误"));

    }
}