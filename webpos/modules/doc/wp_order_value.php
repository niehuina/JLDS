<?php  namespace modules\doc;
/**
 * @desc 订单详情
 */
use models\wp_order_value as model;

class wp_order_value extends \cs\controller_home{
    /**
     * @desc 订单信息
     */
    public function index(){
        $data['order_id'] = get_data('order_id');
        return view('wp_order_value',$data);
    }
    /**
     * @desc 订单列表
     */
    public function ajax(){
        $model = model::DefaultWhere()->paginate(config('app.page_size'));
        //设置当前分页中的URL路径
        $model->setPath(url(url_string()) );
        $data['model'] = $model;
        $output['html']  = view('wp_order_value_ajax',$data);
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
                    'value'=>$value->goods_name,
                ];
            }
        }
        echo json_encode(['lists'=>$data]);
        exit;
    }
    /**
     * @desc 保存数据
     */
    public function save(){
        if(is_ajax()){
            model::saveForm();
            exit(json_encode(['status'=>1,'msg'=>__('退货成功') ,'url'=>url('doc/wp_order_value/index') ]));
        }
    }

}