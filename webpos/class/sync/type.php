<?php
/*
 *@分类接口
 */
namespace cs\sync;
use cs\apitoken;
class type extends apitoken{
    /*
     *@desc 一键同步商品分类
     */
    static function getCat(){

        $rs = self::_get('shop',"Api_Goods_Cat","cat");

        return $rs['data']['items'];
    }


}
