<?php namespace models;
/*
 * @desc admin_users表数据处理
 */

use cs\login;
use cs\sync\user;

class admin_users extends base{

    protected $table = 'admin_users';
    //规则查看 https://github.com/vlucas/valitron
    public $rules = [
        'user'=>['required'],
    ];
    
    /*
     * @desc 保存表单数据
     *
     * */
    static function saveForm(){
        $data = post_data();
        if($data['pwd']){
            $data['pwd'] = password_hash($data['pwd'], PASSWORD_DEFAULT);
        }
        if($data['id']){
            if(!$data['pwd']){
                unset($data['pwd']);
            }
            $info = login::recharge($data['user'],2,'admin_users',$data['id']);
            if($info == false && $data['user']){
                exit(json_encode(['status'=>0,'msg'=>__('该账号已存在')]));
            }
            $model = self::find($data['id']);
        }else{
            $info = login::recharge($data['user'],1);
            if($info == false && $data['user']){
                exit(json_encode(['status'=>0,'msg'=>__('该账号已存在')]));
            }
            if(!$data['pwd']){
                exit(json_encode(['status'=>0,'msg'=>__('账号或者密码不能为空')]));
            }
            $data['created'] = time();
            $model = new self;
        }

        $model->data($data)->save();
    }

    /*
     *
     *@desc 验证登录账号
     *
     */
    static function login($user,$pwd){
        $one = self::where('user','=',$user)->first();
        
        if(!$one){
            return false;
        }else{
            if (password_verify($pwd, $one['pwd'])) {
                $login = new login();
                $login->admin_setcookie($one);
                return true;
            }else{
                return false;
            }
        }

    }

    /*
     *@desc 检测超级管理员账号是否存在，自动生成账号admin 密码111111
     */
    static function checkData(){
        $one = self::get()->toArray();
        if(!$one){
            $data['user'] = 'admin';
            $data['pwd'] = password_hash('yuanfeng021', PASSWORD_DEFAULT);
            $data['level'] = 1;
            $data['created'] = time();
            $model = new self;
            $model->insert($data);
        }

    }


}