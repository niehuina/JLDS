<?php

namespace cs\sync;
use cs\apitoken;
class store extends apitoken{

    static function get_shop($ucenter){
        $data['user_id'] = $ucenter;
        $rs = static::_store("Api_Shop_Info","getSellledInfo",$data);
        return $rs;
    }


    static function _store($ctl , $met,$arr = []){

        $url  = config('shop.url')."?ctl=".$ctl."&met=".$met."&typ=json";

        $formvars = [
                'app_id'    => config('shop.id'),
            ]+$arr;
        $rs = self::url(config('shop.key'),$url,$formvars);

        $rs =  json_decode($rs,true);

        return $rs;
    }




}
