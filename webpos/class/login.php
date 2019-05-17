<?php
namespace cs;
/*
 *
 *@desc 登录处理
 */
use cookie;
use models\acl;
use models\shop_users;
use models\admin_users;
use models\users;
use file;
use cs\ucenter;
use cs\sync\product;
use cs\sync\store;
class login{
    /*
     *
     *@desc  后台登录设置cookie
     */
    static function admin_setcookie($one,$result=null){
        cookie('admin_id',$one->id);
        cookie('admin_user',$one->user?:$one->ucenter_name);
        cookie('admin_level',$one->level);//账号等级
        cookie('admin_role_id',$one->role_users->role_id?:'');//账号水平
        cookie('admin_shop_id',$one->yf_shop_base_id?:'');//员工账号登录所属店铺id
        cookie('admin_k',$result['data']['id']);
        cookie('admin_u',$result['data']['cookie']);
        cookie('admin_ucenter_id',$one->level==2?$one->ucenter_id:'');
    }

    /*
    *
    *@desc  前台登录设置cookie
    */
    static function setcookie($one){
        cookie('id',$one->id);
        cookie('home_user',$one->user);
        cookie('home_nickname',$one->nickname);
        cookie('home_num',$one->num);
        cookie('level',$one->level);
        cookie('shop_id',$one->yf_shop_base_id);
        cookie('shop_name',$one->yf_shop_base->title);
        cookie('users_id',$one->yf_shop_base->users->id);
    }
    /*
     *
     *@desc 验证登录账号
     *
     */
    static function login_account($user,$pwd){

        $one = users::where('ucenter_name','=',trim($user))->first();
        $start_time = strtotime($one->service_start_time);
        $end_time = strtotime($one->service_end_time);
        if(!$one){
            return false;
        }else{
            $res = ucenter::login($user,$pwd);
            $result = product::getUserinfo($user,$pwd);
            if( $end_time < time() || $start_time >time()){
                return __('账号异常，请联系商家客服');
            }elseif($result['cmd_id'] == -140 ){
                return $result['msg'];
            }else{
                self::admin_setcookie($one,$result);
                return true;
            }
        }
    }
    /*
     *获取当前登录用户信息
     */
    static function getUid($id = null,$a = null){
        $id = $id?:cookie('admin_id');
        $type = '\models\users';
        if(cookie('admin_shop_id')){
            $type = '\models\shop_users';
        }
        $one = $type::where('id',$id)->first();
        if($a == 1){
            if(cookie('admin_shop_id')){
                return $one->yf_shop_base->users->id;
            }elseif($one && !cookie('admin_shop_id') && cookie('admin_level') != 1){
                return $id;
            }
        }else{
            return $one;
        }
    }


    /*
    *@desc 当前登录用户所需要的不同数据
    */

    static function login_user($type){
        if(cookie('admin_level') == 1 || !cookie('admin_level')){
            return null;
        }
        $one = self::getUid();
        //员工账号登录获取所属店铺ID
        $shop_id = cookie('admin_shop_id');
        if($shop_id){
            $user_id = $one->yf_shop_base->users->id;
            unset($one);
        }
        // get shop_id .
        if($one){
            $list = $one->yf_shop_base;
            foreach($list as $v){
                $in[] = $v->id;
            }
            $user_id = cookie('admin_id');
            $shop_ids = $in;
        }
        //return shop_id . array
        if($type == 1){
            return $shop_ids?:[$shop_id];
        }
        return [$user_id];
    }

    /*
     *@desc 写入权限列表数据
     *
     */
    static public function ts(){
        $fs = file::find(BASE.'/modules/doc')['file'];
        foreach($fs as $f){
            $fname = file::name($f);
            if($fname == 'login.php'  || $fname == 'admin_users.php'  || $fname == 'yf_shop_base.php' || $fname == 'yf_goods_cat.php' || $fname == 'roles.php' || $fname == 'users.php'  || $fname == 'logo.php' || $fname == 'configapi.php' || $fname == 'bind_shop.php' || $fname == 'users_bind.php' ){
                continue;
            }
            $s1 = "doc/".substr($fname,0,strrpos($fname,'.'));
            $line  = file_get_contents($f);
            preg_match('/desc(.*)\n/', $line, $matche);
            preg_match_all('/desc(.*)\n+(.*\n?){0,5}public(.*)function(.*)\(/', $line, $matches);
            if($matches){
                $title   = $matches[1];
                $action  = $matches[4];
                $new[$s1]['top']['title'] = str_replace("\r"," " ,strip_tags($matche[1]));
                $new[$s1]['top']['address'] = 'top';
                foreach($title as $k=>$ti){
                    unset($out);
                    $out['address'] = trim($action[$k]);
                    $out['title'] = str_replace("\r","" ,strip_tags($ti));
                    $out['action'] = $s1."/".trim($action[$k]);
                    $new[$s1][] = $out;
                }

            }
        }
        if($new){
            $list = acl::get()->toArray();
            $role = new acl;
            if(!$list){
                foreach($new as $k=>$v){
                    $data['slug'] = $k;
                    $data['title'] = $v['top']['title'];
                    $pid = $role->insertGetId($data);
                    foreach($v as $k1=>$v1){
                        if($v1['address'] == 'top') continue;
                        $date['slug'] = $v1['action'];
                        $date['title'] = $v1['title'];
                        $date['pid'] = $pid;
                        $role->insert($date);
                    }
                }
            }else{
                foreach($new as $k=>$v){
                    $a = $role->where('slug',$k)->first();
                    if(!$a){
                        $data['slug'] = $k;
                        $data['title'] = $v['top']['title'];
                        $pid = $role->insertGetId($data);
                    }else{
                        $data['slug'] = $k;
                        $data['title'] = $v['top']['title'];
                        $role->where('slug',$k)->update($data);
                        $pid = $a->id;
                    }
                    foreach($v as $k1=>$v1){
                        if($v1['address'] == 'top') continue;
                        $b = $role->where('slug',$v1['action'])->first();
                        if(!$b){
                            $date['slug'] = $v1['action'];
                            $date['title'] = $v1['title'];
                            $date['pid'] = $pid;
                            $role->insert($date);
                        }else{
                            $date['slug'] = $v1['action'];
                            $date['title'] = $v1['title'];
                            $date['pid'] = $pid;
                            $role->where('slug',$v1['action'])->update($date);
                        }
                    }
                }
            }
        }
    }
    /*
     * 判断当前注册账号是否存在
     * */
    static public function recharge($user,$type,$li=null,$id=null){
        $admin_users = admin_users::where('user',$user)->first();
        $shop_users = shop_users::where('user',$user)->first();
        $users = users::where('ucenter_name',$user)->first();
        if($type == 1){
            if($admin_users || $shop_users || $users ){
                return false;
            }else{
                return true;
            }
        }else{
            if($li == 'admin_users'){
                $info = admin_users::where('user',$user)->where('id','!=',$id)->first();
                if($info || $shop_users || $users){
                    return false;
                }else{
                    return true;
                }
            }elseif($li == 'shop_users'){
                $info = shop_users::where('user',$user)->where('id','!=',$id)->first();
                if($info || $admin_users || $users){
                    return false;
                }else{
                    return true;
                }
            }elseif($li == 'users'){
                $info = users::where('ucenter_name',$user)->where('id','!=',$id)->first();
                if($info || $shop_users || $admin_users){
                    return false;
                }else{
                    return true;
                }
            }
        }

    }
}