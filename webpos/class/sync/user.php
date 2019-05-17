<?php
/*
 *@desc 会员接口
 */
namespace cs\sync;
use cs\apitoken;
class user extends apitoken{
    /*
     *同步商家信息
     **/
    static function get_user(){

        $rs = self::_get('shop',"Api_Shop_Manage","shopIndex");

        return $rs;
    }

    /*
     * 同步会员信息
     * */
    static function get_wp_users(){
        //$rs = self::_get('shop',"Api_Shop_Info","getShopOrderBuyerInfoByUserId",['user_id'=>cookie('admin_ucenter_id')]);
        $rs = self::_get('shop',"Api_User_Info","getAllUserList");
        if($rs['status']==200 && cookie('admin_level')==2){
            return $rs['data'];
        }
    }

    /*
        * 获取会员账户余额
        * */
    static function get_account_balance($user_id){
        $rs = self::_get('paycenter',"Api_User_Info","getUserResourceInfo",['user_id'=>$user_id]);
        if($rs['status']==200 && cookie('admin_level')==2){
            return $rs['data']['user_money'];
        }
    }

    /**
     * 根据会员id取得会员的折扣
     * @param $ucenter_id
     * @return mixed
     */
    static public function getUserRate($ucenter_id){
        $data['user_id'] = $ucenter_id;
        $rs = self::_get('shop',"Api_User_Grade","getUserRate",$data);
        return $rs['data'];
    }

    /**
     * 获取会员账户余额
     * @param $user_id
     * @return mixed
     */
	static function getUserResourceMoney($user_id){
        $rs = self::_get('paycenter',"Api_User_Info","getUserResourceInfo",['user_id'=>$user_id]);
        if($rs['status']==200){
            return $rs['data']['user_money'];
        }
    }
}
