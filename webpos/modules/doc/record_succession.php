<?php  namespace modules\doc;
/**
 * @desc 交接班管理
 */
use models\record_succession as model;
use models\yf_shop_base;
class record_succession extends \cs\controller_home{
    /**
     * @desc 交接班记录
     *
     */
    public function index(){
        $data['yf_shop_base'] = yf_shop_base::shop_list();
        return view('record_succession_list',$data);
    }
    /**
     * @desc 交接班记录列表
     */
    public function ajax(){
        $data = [];
        $model = model::DefaultWhere()->paginate(config('app.page_size'));
        //设置当前分页中的URL路径
        $model->setPath(url(url_string()) );
        $data['model'] = $model;
        $output['html']  = view('record_succession_list_ajax',$data);
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
            $arr = [];
            foreach ($list as $key => $value) {
                if($arr && $arr[$value->shop_users_id]){
                    continue;
                }
                $arr[$value->shop_users_id] = true;
                $data[] = [
                    'lable'=>$value->shop_users->id,
                    'value'=>$value->shop_users->user,
                ];
            }
        }
        echo json_encode(['lists'=>$data]);
        exit;
    }


}