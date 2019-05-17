<?php  namespace modules\doc;
use cs\sync\order;
use models\wp_order as model;
use models\wp_order_value;
use DB;
use Exception;
use cookie;
use models\yf_shop_base;

/**
 * @desc 订单信息
 */
class wp_order extends \cs\controller_home{
   /**
     * @desc 订单管理
     */
    public function index(){
        return view('wp_order');
    }

   /**
     * @desc 订单列表
     */
    public function ajax(){
        $data = [];
        $model = model::DefaultWhere()->paginate(config('app.page_size'));
        //设置当前分页中的URL路径
        $model->setPath(url(url_string()) );
        $data['model'] = $model;
        $output['html']  = view('wp_order_ajax',$data);
        echo json_encode(['status'=>true,'html'=>$output['html'],'render'=>'ajax_load_table']);
        exit;

    }
   /**
     * @desc 自动补全
     */
    public function autocomplete(){
        $wq = get_data('wq');
        $data = [];
        if(trim($wq)){
            $top = get_data('top')?:10;
            $list  = model::DefaultWhere()->limit($top)->get();
            foreach ($list as $key => $value) {
                $data[] = [
                    'lable'=>$value->id,
                    'value'=>$value->num,
                ];
            }
        }
        echo json_encode(['lists'=>$data]);
        exit;
    }
   /**
     * @desc 同步订单
     */
    public function ajax_sync(){
        $f = $this->_sync() ;
        if($f){
            $insertnum = cookie('insertnum')?:0;
            $updatenum = cookie('updatenum')?:0;
            exit(json_encode(['status'=>1,'msg'=>__('本次同步插入')."$insertnum".__('条数据，更新')."$updatenum".__('条数据')]));
        }
        exit(json_encode(['status'=>0,'msg'=>__('数据导入失败')]));
    }


    protected function _sync(){

        cookie::delete('insertnum');
        cookie::delete('updatenum');
        try{
            DB::transaction(function()
            {
                $order_list = order::getOrderlist();
                if($order_list){
                    $insertnum = 0;
                    $updatenum = 0;

                    $order_value_list = order::getOrdervaluelist();
                    foreach($order_list as $k=>$v){
                        $li = model::where('order_id',$v['order_id'])->first();
                        $data['user_id'] = $v['seller_user_id'];
                        $data['created'] = strtotime($v['order_create_time']);
                        $data['ended'] = strtotime($v['payment_time']);
                        $data['good_price'] = $v['order_payment_amount'];
                        $data['good_price_ori'] = $v['order_goods_amount'];
                        $data['order_status'] = $v['order_status'];
                        $data['shop_id'] = yf_shop_base::where('yf_shop_id',$v['shop_id'])->first()->id;
                        $data['type'] = 1;

                        $num = 0;
                        foreach($order_value_list as $k=>$value) {
                            if($value['order_id'] == $v['order_id']){
                                $num += $value['order_goods_num'];
                            }
                        }
                        $data['good_num'] = $num;

                        if(!$li){
                            $data['order_id'] = $v['order_id'];
                            model::insert($data);
                            $insertnum++;
                        }else{
                            model::where('order_id',$v['order_id'])->update($data);
                            $updatenum++;
                        }

                    }

                    if($order_value_list){
                        $yf_order_value = new wp_order_value();
                        foreach($order_value_list as $k=>$v){
                            $li = $yf_order_value::where('order_id',$v['order_id'])->first();
                            $date['goods_id'] = $v['goods_id'];
                            $date['goods_name'] = $v['goods_name'];
                            $date['goods_values'] = $v['goods_spec'];
                            $date['goods_price'] = $v['order_goods_amount'];
                            $date['shop_id'] = yf_shop_base::where('yf_shop_id',$v['shop_id'])->first()->id;
                            $date['shop_name'] = $v['shop_name'];
                            $date['num'] = $v['order_goods_num'];
                            $date['status'] = $v['order_goods_status'];
                            $date['good_price_ori'] = $v['goods_price'];
                            $date['created'] = strtotime($v['order_goods_finish_time']);
                            $date['type'] = 1;
                            if(!$li){
                                $date['order_id'] = $v['order_id'];
                                $yf_order_value::insert($date);
                            }else{
                                $yf_order_value::where('id',$v['id'])->update($date);
                            }
                        }
                    }
                    cookie('insertnum',$insertnum);
                    cookie('updatenum',$updatenum);
                    return true;
                }else{
                    return false;
                }
            });
        }catch(Exception $e){
            return false;
        }
        return true;
    }
}