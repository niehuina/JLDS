<?php
namespace modules\home;
use models\wp_users as model;
use models\wp_users_discount;
use models\yf_shop_base;
use models\users;
/**
 * @desc 会员页面
 *
 */
class member_list extends \cs\controller{
    /**
     * @desc 搜索会员
     *
     */
    public function index(){
        
        return view('member_list');
    }

    /**
     * @desc 保存数据
     *
     */
    public function save(){
        if(is_ajax()){
            wp_users_discount::saveForm();
            exit(json_encode(['status'=>1,'msg'=>__('操作成功') ,'url'=>url('home/welcome/member') ]));
        }
    }
}