<?php  namespace modules\doc;
/**
 * @desc 退货管理
 */
use models\wp_order_value as model;

class wp_order_return extends \cs\controller_home{

    /**
     * @desc 退货列表
     *
     */
    public function index(){
        $id = get_data('id');
        $model = model::where('id',$id)->paginate(config('app.page_size'));
        $model->setPath(url(url_string()) );
        $data['model'] = $model;
        return view('wp_order_return',$data);
    }
    /**
     * @desc 退货处理
     *
     */
    public function save(){
        if(is_ajax()){
            model::saveForm();
            exit(json_encode(['status'=>1,'msg'=>__('退货成功') ,'url'=>url('doc/wp_order_value/index') ]));
        }
    }

}