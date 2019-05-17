<?php
namespace modules\doc;
use models\acl;
use models\acl_roles as model;
use models\roles;
use cs\login;
/**
 * @desc 账号权限设置
 *
 */
class acl_roles extends \cs\controller_home{
    /**
     * @desc 账号权限
     *
     */
    public function index(){
        return view('acl_roles_list');
    }
    /**
     * @desc 账号权限设置列表
     *
     */
    public function ajax(){
        $data = [];
        $model = model::acl_list();
        $data['model'] = $model;
        $output['html']  = view('acl_roles_ajax',$data);
        echo json_encode(['status'=>true,'html'=>$output['html'],'render'=>'ajax_load_table']);
        exit;
    }


    /**
     * @desc 设置权限
     *
     */
    public function edit(){
        //通过acl表获取页面显示数据
        $data['qx'] = acl::qx();
        //获取当前登录用户的父级id,没有就是自己
        $data['uid'] = login::getUid(null,1);
        //获取当前用户权限数据
        $data['info'] = model::acl_roles_list();
        //获取角色列表
        $data['roles'] = roles::get();

        $data['infomation'] = model::where('roles_id',get_data('id'))->where('users_id',$data['uid'])->first();
        return view('acl_roles_save',$data);
    }

    /**
     * @desc 保存数据
     *
     */
    public function save(){
        if(is_ajax()){
            model::saveForm();
            exit(json_encode(['status'=>1,'msg'=>__('操作成功') ,'url'=>url('doc/acl_roles/index') ]));
        }
    }



}