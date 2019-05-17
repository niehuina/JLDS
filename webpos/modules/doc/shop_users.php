<?php  namespace modules\doc;
use models\shop_users as model;
use models\roles;
use models\yf_shop_base;
/**
 * @desc 门店员工账号设置
 */
class shop_users extends \cs\controller_home{
    /**
     * @desc 门店账号设置
     */
    public function index(){
        $data['roles'] = roles::all()->toArray();
        return view('shop_users',$data);
    }
    /**
     * @desc 门店账号设置列表
     */
    public function ajax(){
        $data = [];
        //分页
        $model = model::DefaultWhere()->paginate(config('app.page_size'));
        //设置当前分页中的URL路径
        $model->setPath(url(url_string()) );
        $data['model'] = $model;
        $output['html']  = view('shop_users_ajax',$data);
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
            foreach ($list as $key => $value){
                $data[] = [
                    'label'=>$value->phone,
                    'value'=>$value->phone."|".$value->user,
                ];
            }
        }
        echo json_encode(['lists'=>$data]);
        exit;
    }
    /**
     * @desc 查询角色表
     */
    protected function _tree(){
        $a = roles::all()->toArray();
        return  $a;
    }

    /**
     * @desc 新建
     */
    public function add(){
        $data['tree'] = $this->_tree();
        $data['stores'] = yf_shop_base::shop_list();
        return view('shop_users_add',$data);
    }
    /**
     * @desc 编辑
     */
    public function edit(){
        $data['tree'] = $this->_tree();
        $data['stores'] = yf_shop_base::shop_list();
        $id = get_data('id');
        $data['model'] = model::find($id);
        return view('shop_users_add',$data);
    }
    /**
     * @desc 删除
     */
    public function delete(){
        $id = get_data('id');
        model::deleteForm($id);
        echo 1;
        exit;
    }

    /**
     * @desc 保存数据
     */
    public function save(){
        if(is_ajax()){
            model::saveForm();
            exit(json_encode(['status'=>1,'msg'=>__('操作成功') ,'url'=>url('doc/shop_users/index') ]));
        }
    }
}
