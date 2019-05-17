<?php
/*
 *@desc 订单接口
 */
namespace cs\sync;
use cs\apitoken;
class order extends apitoken{
    /*
     * 获取订单列表
     * */
    static public function getOrderlist(){
        $rs = self::_get('shop',"Api_Shop_Info","getShopOrderByUserId",['user_id'=>cookie('admin_ucenter_id')]);
        if($rs['data'] && cookie('admin_level')==2){
            return $rs['data'];
        }
    }
    /*
     * 获取订单商品信息
     * */
    static public function getOrdervaluelist(){
        $rs = self::_get('shop',"Api_Shop_Info","getShopOrdergoodsByUserId",['user_id'=>cookie('admin_ucenter_id')]);
        if($rs['data'] && cookie('admin_level')==2){
            return $rs['data'];
        }
    }
    /*
     * 
     * 改变商品库存
     * 
     * */
    static public function getGoodscommon($goods_id,$type,$goods_num){
        $data['goods_id'] = $goods_id;
        $data['op'] = $type;
        $data['goods_num'] = $goods_num;
        $rs = self::_get('shop',"Api_Shop_Info","reduceOrAddStock",$data);
        return $rs['data'];
    }

    /**
     * 根据goods_id取得限时折扣的价格
     * @param $goods_id
     * @return int
     */
    static public function getXianShiInfo($goods_id){
        $data['goods_id'] = $goods_id;
        $rs = self::_get('shop',"Api_Goods_Goods","getXianShiInfo",$data);
        if($rs['data']){
            return $rs['data']['discount_price'];
        }else{
            return 0;
        }
    }

    /**
     * 根据门店对应的开店者id,及订单金额，计算出是否满即送的状态
     * @param $shop_user_id
     * @param $orderPrice
     * @return mixed
     */
    static public function getMansongInfo($shop_user_id, $orderPrice){
        $data['shop_user_id'] = $shop_user_id;
        $data['orderPrice'] = $orderPrice;
        $rs = self::_get('shop',"Api_Goods_Goods","getMansongInfo",$data);
        return $rs['data'];
    }

    /**
     * 根据门店对应的开店者id,会员Id及订单金额，查询出可使用的平台红包及优惠券的信息
     * @param $shop_user_id
     * @param $ucenter_id
     * @param $orderPrice
     * @return mixed
     */
    static public function getVouchersByUcenterId($shop_user_id, $ucenter_id,$orderPrice)
    {
        $data['shop_user_id'] = $shop_user_id;
        $data['user_id'] = $ucenter_id;
        $data['orderPrice'] = $orderPrice;
        $rs = self::_get('shop',"Api_Goods_Goods","getVouchersByUcenterId",$data);
        return $rs['data'];
    }

    /**
     * 使用平台红包下单成功，更改状态
     * @param $redpacket_id
     * @param $order_id
     * @return mixed
     */
    static public function updateStateForRP($redpacket_id, $order_id)
    {
        $data['redpacket_id'] = $redpacket_id;
        $data['order_id'] = $order_id;
        $rs = self::_get('shop',"Api_Trade_Order","updateStateForRP",$data);
        return $rs;
    }

    /**
     * 使用优惠券下单成功，更改状态
     * @param $voucher_id
     * @param $order_id
     * @return mixed
     */
    static public function updateStateForVoucher($voucher_id, $order_id)
    {
        $data['voucher_id'] = $voucher_id;
        $data['order_id'] = $order_id;
        $rs = self::_get('shop',"Api_Trade_Order","updateStateForVoucher",$data);
        return $rs;
    }
}
