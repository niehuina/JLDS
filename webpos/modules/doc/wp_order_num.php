<?php  namespace modules\doc;
use models\yf_shop_base as model;
use models\yf_shop_base;
use cookie;
/**
 * @desc 营业状况显示
 *
 */
class wp_order_num extends \cs\controller_home{
    /**
     * @desc 营业状况
     *
     */
    public function index()
    {
       $data['shop_list'] = yf_shop_base::shop_list();
        return view('wp_order_num',$data);
    }
    /**
     * @desc 营业状况列表
     *
     */
    public function ajax(){
        $model = model::DefaultWhere()->paginate(100);
        //设置当前分页中的URL路径
        $model->setPath(url(url_string()));
        $data['model'] = model::ajax_data_reset( $model );
        $output['html']  = view('wp_order_num_ajax',$data);
        echo json_encode(['status'=>true,'html'=>$output['html'],'render'=>'ajax_load_table']);
        exit;
    }
}
