<?php  namespace modules\doc;
use models\shop_users;
use models\yf_shop_base as model;
use models\users;
use validator,arr;
use cs\sync\store;
/**
 *@desc 门店详情类
 *
 */
class yf_shop_base extends \cs\controller_home{
    /**
     * @desc 列表页数据显示
     *
     */
    public function index(){
        $data['wq'] = get_data('wq');
        return view('yf_shop_base_list',$data);
    }
    /**
     * @desc ajax列表搜索
     *
     */
    public function ajax(){
        $data = [];
        $model = model::DefaultWhere()->paginate(config('app.page_size'));
        //设置当前分页中的URL路径
        $model->setPath(url(url_string()) );
        $data['model'] = $model;

        $output['html']  = view('yf_shop_base_ajax',$data);
        echo json_encode(['status'=>true,'html'=>$output['html'],'render'=>'ajax_load_table']);
        exit;
    }
    /**
     * @desc 自动补全
     *
     */
    public function autocomplete(){
        $wq = get_data('wq');
        $data = [];
        if(trim($wq)){
            $top = get_data('top')?:20;
            $list  = model::DefaultWhere()->limit($top)->get();
            $arr = [];
            foreach ($list as $key => $value) {
                if($arr && $arr[$value->user_id]){
                    continue;
                }
                $arr[$value->user_id] = true;
                $data[] = [
                    'lable'=>$value->user_id,
                    'value'=>$value->users->ucenter_name,
                ];
            }
        }
        echo json_encode(['lists'=>$data]);
        exit;
    }
    /**
     * @desc 添加数据
     *
     */
    public function add(){
        $data['user'] = users::get();
        return view('yf_shop_base_save',$data);
    }
    /**
     * @desc 编辑数据
     *
     */
    public function edit(){
        $id = get_data('id');
        $data['info'] = model::find($id);
        $data['user'] = users::get();
        return view('yf_shop_base_save',$data);
    }
    /**
     * @desc 删除数据
     *
     */
    public function delete(){
        $id = get_data('id');
        model::deleteForm($id);
        echo 1;
        exit;
    }
    /**
     * @desc 保存数据
     *
     */
    public function save(){
        if(is_ajax()){
            model::saveForm();
            exit(json_encode(['status'=>1,'msg'=>__('操作成功') ,'url'=>url('doc/yf_shop_base/index') ]));
        }
    }
    /*
     * @desc 通过授权账号获取授权信息
     *
     * */
    public function ajax_users(){
        return users::where('id',post_data('id'))->first()->max_stores;
    }


}