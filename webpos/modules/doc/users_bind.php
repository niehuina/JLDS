<?php  namespace modules\doc;
use cs\ucenter;
use models\users as model;
use cs\sync\store;
use validator,arr;
/**
 * @desc 授权账号设置类
 */
class users_bind extends \cs\controller_home{

    /**
     * @desc 绑定账号页面
     */
    public function index(){
        $info = model::where('id',cookie('admin_id'))->first();
        $data['ucenter_id'] = $info->ucenter_id;
        if($data['ucenter_id']){
            $ucenter_info = ucenter::get($data['ucenter_id']);
            $data['ucenter_name'] = $ucenter_info['data']['user_name'] ;
        }
        return view('users_bind',$data);

    }
    /**
     * @desc 绑定账号
     */
    public function edit(){
        if(is_ajax()){
            $data = post_data();
            if(!$data['user'] || !$data['pwd']){
                exit(json_encode(['status'=>0,'msg'=>'账号或者密码不能为空']));
            }
            $info = ucenter::login($data['user'],$data['pwd']);
            if($info['status'] == 200){
                $li = model::where('ucenter_id',$info['data']['user_id'])->first();
                if($li->ucenter_id){
                    exit(json_encode(['status'=>0,'msg'=>'该账号已被绑定']));
                }else{
                    $store = store::get_shop($info['data']['user_id']);
                    if($store['data']['shop_info']){
                        $date['ucenter_id'] = $info['data']['user_id'];
                        model::where('id',cookie('admin_id'))->update($date);
                        cookie('admin_ucenter_id',$date['ucenter_id']);
                        exit(json_encode(['status'=>1,'msg'=>__('账号绑定成功') ,'url'=>url('doc/logo/index') ]));
                    }else{
                        exit(json_encode(['status'=>0,'msg'=>'该账号没有商城店铺，绑定失败']));
                    }
                }
            }else{
                exit(json_encode(['status'=>0,'msg'=>__('该账号不存在')  ]));
            }
        }
    }

}