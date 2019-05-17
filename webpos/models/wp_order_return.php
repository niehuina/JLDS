<?php namespace models;
use cs\sync\order;
use DB;
use Exception;
class wp_order_return extends base{
    protected $table = 'wp_order_return';



    /*
     * 关联订单表
     * */
    public function wp_order(){
        return $this->belongsTo('models\wp_order','order_id','order_id','goods_id');
    }
    /*
     * 退货处理
     * */
    static public function saveForm(){
        try{
            DB::transaction(function()
            {
                $data = get_data();

                if($data){
                    foreach($data['goods_id'] as $k=>$v){
                        if($data['goods_num'][$k] != 0 && $data['goods_price'][$k] != 0){
                            $arr[$k]['goods_id'] = $v;
                            $arr[$k]['goods_num'] = $data['goods_num'][$k];
                            $arr[$k]['goods_price'] = $data['goods_price'][$k];
                        }
                    }
                }

                if($arr){

                    foreach($arr as $k=>$v){
                        $li = yf_goods_common::where('id',$v['goods_id'])->first();
                        $date['common_stock'] = $li->common_stock + $v['goods_num'];
                        $li->where('id',$v['goods_id'])->update($date);
                        if($li->common_goods_from == 1){
                           order::getGoodscommon($li->goods_id,'add',$v['goods_num']);
                        }
                        $list['num'] = $v['goods_num'];
                        $list['order_id'] = $data['order_id'];
                        $list['goods_id'] = $v['goods_id'];
                        $list['goods_name'] = $li->common_name;
                        $list['goods_values'] = $li->common_spec_name;
                        $list['shop_id'] = cookie('shop_id');
                        $list['shop_name'] = cookie('shop_name');
                        $list['status'] = 2;
                        $list['return_payid'] = config('payment.cash');
                        $list['good_price_ori'] = $v['goods_price'];
                        $list['goods_price'] = $v['goods_price'];
                        $list['shop_users_id'] = cookie('id');
                        $list['created'] = time();
                        $model = new self;
                        if($v['goods_num'] == 0) continue;
                        $model->insert($list);
                    }
                }else{
                    exit(json_encode(['status'=>0,'msg'=>__('请选择退货商品') ]));
                }

            });
        }catch(Exception $e){
            return false;
        }
        return true;

    }
}