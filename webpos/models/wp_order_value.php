<?php namespace models;
use models\yf_goods_common;
use DB;
use Exception;
class wp_order_value extends base{

    protected $table = 'wp_order_value';
    public $rules = [
    ];
    /*
     *@desc 根据登录账号信息获取数据列表 
     * 
     */
    public function scopeDefaultWhere($query)
    {
        parent::scopeDefaultWhere($query);
        $wq = request_data('wq');
        if($wq){
            $query->where('goods_name', 'like', "%".$wq."%");
        }
        $order_id = request_data('order_id');
        if($order_id){

            $query->where('order_id',$order_id);
        }
    }
    /*
     *获取商品信息
     * */
    public function yf_goods_common(){
        if($this->type == 1){
            return $this->belongsTo('models\yf_goods_common','goods_id','goods_id');
        }else{
            return $this->belongsTo('models\yf_goods_common','goods_id');
        }

    }
    /**
     * 获取订单信息
     */
    public function wp_order_info()
    {
        return $this->belongsTo('models\wp_order','order_id','order_id');
    }
    /**
     * 获取退货订单信息
     */
    public function wp_order_return()
    {
        return $this->hasMany('models\wp_order_return','order_id','order_id');
    }
    /**
     * @desc 退货数量
     *
     */
    public function getRenumAttribute(){
        $order = $this->wp_order_return;

        if(!$order) return 0;

        $sum += $order->where('goods_id',$this->goods_id)->sum('num');
        
        return $sum;
       

    }
    /**
     * 获取商品信息
     */
    public function goods_info()
    {
        return $this->belongsTo('models\yf_goods_common','goods_id');
    }
    /*
     * @desc 开启事务 保存表单数据
     *
     * */
    static function saveForm(){
        try{
            DB::transaction(function()
            {
                $data = post_data();
                if(!$data['num']){
                    exit(json_encode(['status'=>0,'msg'=>__('请选择退货数量') ]));
                }
                $data['created'] = time();
                $data['status'] = 1;
                $model = new wp_order_return;
                $model->data($data)->save();
                //改变商品库存
                $yf_goods_common = new yf_goods_common;
                $good_info = $yf_goods_common->where('id',$data['goods_id'])->first();
                $common_stock = $yf_goods_common->find($good_info->id) ;
                $stock = $data['num'];
                $goods['common_stock'] = $good_info->common_stock + $stock;
                $yf_goods_common->where('id',$good_info->id)->update($goods);
            });
        }catch(Exception $e){
            return false;
        }
        return true;
    }
}