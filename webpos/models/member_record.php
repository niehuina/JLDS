<?php namespace models;
/*
 * @desc users表数据处理
 * */
use cs\login;
use Illuminate\Database\Eloquent\SoftDeletes;
use cs\ucenter as  api;
use cs\sync\pay as  apipay;
use DB;
use Exception;
class member_record extends base{
    //软删除
    //use SoftDeletes;
    //protected $dates = ['deleted_at'];
    protected $table = 'member_record';
    /*
     * @desc 保存表单数据
     *
     */
    static function saveForm(){
        try{
            DB::transaction(function()
            {
                $data = post_data();
                $model = new self;
                $user_id =$data['ucenter_id'];
                $user_nickname = $data['ucenter_name'];
                $amount=$data['amount'];
                $order_id=$data['order_id'];

                $model['wp_user_id']=$data['id'];
                $model['cardno']=$data['cardno'];
                $model['amount']=$data['amount'];
                $model['pay_way']=$data['pay_way'];
                $model['created']=date("Y-m-d H:i:s");

                $wp_user=new wp_users();
                $returnD=apipay::add_User_Record($user_id,$user_nickname,$amount,$data['pay_way'],$order_id);
                if($returnD['data']['id']){
                    $model['ucenter_record_id'] = $returnD['data']['id'];
                }
                if($model['ucenter_record_id']!=0){
                    //插入充值记录
                    $model->data($model)->save();
                    //更新webopos数据库，user 表账户余额
                    $wp_user = wp_users::where('id',$model->wp_user_id)->first();
                    $wp_user['account_balance']+=$model['amount'];
                    $wp_user->data($wp_user)->save();
                    //更新pacenter数据库 user_resource 表账户余额
                    $returnD=apipay::update_account_balance($user_id,$amount);
                }
            });
        }catch(Exception $e){
            return false;
        }
        return true;

    }
    /*
     * @desc 删除表单数据
     */
    static function deleteForm($id){
        $model = self::where('id',$id);
        $rs = $model->first();
        $rsz =  api::status($rs->ucenter_name,3);
        $yf_shop_base = yf_shop_base::where('user_id',$rs->ucenter_id);

        $model->delete();

        if($yf_shop_base->get()){
            foreach($yf_shop_base as $k=>$v){
                $shop_id[] = $v->id;
            }
            if($shop_id){
                foreach($shop_id as $k=>$v){
                    shop_users::where('yf_shop_base_id',$v)->delete();
                    yf_goods_shop_common::where('shop_id',$v)->delete();
                }
            }
        }

        $yf_shop_base->delete();
        if($rsz['status']!=200){
            $msg = $rsz['msg'];
            exit(json_encode(['status'=>0,'msg'=>__($msg)]));
        }

    }





}