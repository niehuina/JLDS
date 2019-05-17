<?php  namespace modules\doc;
use models\shop_users as model;
use models\yf_shop_base;
/**
 * @desc 销售单据
 */
class wp_order_all extends \cs\controller_home{
	/**
     * @desc 销售单据
     *
     */
	public function index(){
        $data['shop_list'] = yf_shop_base::shop_list();
		 return view('wp_order_all',$data);		
	}
	/**
     * @desc 销售单据列表
     */
    public function ajax(){
        $model = model::DocumentsWhere()->paginate(config('app.page_size'));
        //设置当前分页中的URL路径
        $model->setPath(url(url_string()));
        $data['model'] = $model;
        $output['html']  = view('wp_order_all_ajax',$data);
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
            $list  = model::DocumentsWhere()->limit($top)->get();
            foreach ($list as $key => $value) {
                $data[] = [
                    'lable'=>$value->id,
                    'value'=>$value->user,
                ];
            }
        }
        echo json_encode(['lists'=>$data]);
        exit;
    }


}