<?php  namespace modules\doc;
use models\roles as model;
use validator,arr;
/**
 * @desc 基础数据配置
 *
 */
class roles extends \cs\controller_home{
    /**
     * @desc 列表页数据显示
     *
     */
    public function index(){

        return view('roles_list');
    }

    /**
     * @desc ajax列表搜索
     *
     */
    public function ajax(){
        //分页
        $data = [];
        $model = model::DefaultWhere()->paginate(config('app.page_size'));
        //设置当前分页中的URL路径
        $model->setPath(url(url_string()) );
        $data['model'] = $model;
        $output['html']  = view('roles_ajax',$data);
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
            foreach ($list as $key => $value) {
                $data[] = [
                    'lable'=>$value->id,
                    'value'=>$value->title,
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
        return view('roles_save');
    }
    /**
     * @desc 编辑数据
     *
     */
    public function edit(){
        $id = get_data('id');
        $data['info'] = model::find($id);
        return view('roles_save',$data);
    }
    /**
     * @desc 删除数据
     *
     */
    public function delete(){
        $id = get_data('id');
        $ret = model::deleteForm($id);
        if($ret==1){
            echo 2;
        }else{
            echo 1;
        }
        exit;
    }

    /**
     * @desc 保存数据
     *
     */
    public function save(){
        if(is_ajax()){
            model::saveForm();
            exit(json_encode(['status'=>1,'msg'=>__('操作成功') ,'url'=>url('doc/roles/index') ]));
        }
    }


}