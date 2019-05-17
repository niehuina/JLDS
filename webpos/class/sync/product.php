<?php
/*
 *@desc 商品接口
 */
namespace cs\sync;
use cs\apitoken;
class product extends apitoken{
    /*
    *@desc 获取同步的商品信息
    */
    static function getProduct(){

        $rs = self::_get('shop',"Api_Shop_Info","getShopGoodsByUserId",['user_id'=>cookie('admin_ucenter_id')]);
        if($rs['status'] == 200 && cookie('admin_level') == 2){
            return $rs['data'];
        }
    }
    /*
     * 获取登录用户返回的k,u
     * */
    static function getUserinfo($user_name,$user_password){

        $formvars['k'] = cookie('admin_k');
        $formvars['u'] = cookie('admin_u');

        $data['user_name'] = $user_name;
        $data['user_password'] = $user_password;

        $rs = self::_get('shop',"Index","checkApp",$data,$formvars);

        return $rs;
    }


}
