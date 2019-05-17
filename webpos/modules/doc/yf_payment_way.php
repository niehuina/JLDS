<?php  namespace modules\doc;
/**
 * @desc  支付方式设置
 */
use models\yf_payment_way as model;

class yf_payment_way extends \cs\controller_home{
    /**
     * @desc 支付方式设置
     *
     */
    public function index(){
        model::pay_list();
        return view('yf_payment_way_list');
    }

    /**
     * @desc 支付方式列表
     */
    public function ajax(){
        $data = [];
        $model = model::DefaultWhere()->paginate(config('app.page_size'));
        //设置当前分页中的URL路径
        $model->setPath(url(url_string()));
        $data['model'] = $model;
        $output['html']  = view('yf_payment_way_ajax',$data);
        echo json_encode(['status'=>true,'html'=>$output['html'],'render'=>'ajax_load_table']);
        exit;
    }


}