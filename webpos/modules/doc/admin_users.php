<?php  namespace modules\doc;
use models\admin_users as model;
use validator,arr;

/**
 * @desc 超级管理员账号设置
 *
 */
class admin_users extends \cs\controller_home{
    /**
     * @desc 列表页数据显示
     *
     */
    public function index(){

        return view('admin_users_list');
    }
    /**
     * @desc ajax列表页搜索
     *
     */
    public function ajax(){
        $data = [];
        $model = model::DefaultWhere()->paginate(config('app.page_size'));
        //设置当前分页中的URL路径
        $model->setPath(url(url_string()) );

        $data['model'] = $model;
        $output['html']  = view('admin_users_ajax',$data);
        echo json_encode(['status'=>true,'html'=>$output['html'],'render'=>'ajax_load_table']);
        exit;
    }

    /**
     * @desc 添加数据
     *
     */

    public function add(){
        return view('admin_users_save');
    }
    /**
     * @desc 编辑数据
     *
     */
    public function edit(){
        $id = get_data('id');
        $data['info'] = model::find($id);
        return view('admin_users_save',$data);
    }

    /**
     * @desc 保存数据
     *
     */
    public function save(){
        if(is_ajax()){
            model::saveForm();
            exit(json_encode(['status'=>1,'msg'=>__('操作成功') ,'url'=>url('doc/admin_users/index') ]));
        }
    }


}