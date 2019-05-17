<?php  namespace modules\doc;
use models\admin_users as model;
use models\users;
use models\yf_shop_base;
use validator,arr;
use cs\sync\store;
/**
 * @desc ucenter api设置
 *
 */
class bind_shop extends \cs\controller_home{
    public function index(){

        return view('bind_shop');
    }


    /*
         * @desc 同步商城店铺
         *
         * */
    public function sync_store(){
        $id = cookie('admin_id');
        $ucenter = users::where('id',$id)->first();
        if($ucenter['ucenter_id']){
            $model =  store::get_shop($ucenter['ucenter_id']);
        }else{
            exit(json_encode(['status'=>0,'msg'=>__('同步失败')]));
        }
        $data = [];
        $yf_shop_base = new yf_shop_base;
        $tong = $model['data']['shop_info'];

        if($tong){
            $store = yf_shop_base::where('yf_shop_id',$tong['shop_id'])->first();
            $data['user_id'] = $ucenter['id'];      //用户id
            $data['title']   = $tong['shop_name'];    //店铺名称
            $data['address'] = $tong['shop_company_address']." ".$tong['company_address_detail']; //店铺地址
            $data['phone']   = $tong['contacts_phone'];    //电话
            $data['created'] = strtotime($tong['shop_create_time']); //店铺开始时间
            $data['updated'] = strtotime($tong['shop_end_time']); //店铺到期时间
            $data['type_id'] = 1;
            if($store){
                $yf_shop_base->where('yf_shop_id',$tong['shop_id'])->update($data);
            }else{
                $data['yf_shop_id'] = $tong['shop_id'];
                $yf_shop_base->insert($data);
            }
            exit(json_encode(['status'=>1,'msg'=>__('同步成功')]));
        }else{
            exit(json_encode(['status'=>0,'msg'=>__('同步失败')]));
        }
    }

}