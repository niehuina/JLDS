<?php  namespace modules\doc;
use models\wp_users;
use models\wp_users_discount as model;
use cs\sync\user;
use models\users;
use token;
use DB;
use Exception;
use cs\login;
use cookie;

class wp_users_discount extends \cs\controller_home{
   /**
     * @desc 会员管理
     */
    public function index(){
        return view('wp_users_discount_list');
    }
   /**
     * @desc 会员列表
     */
    public function ajax(){
        $data = [];
        $model = model::DefaultWhere()->paginate(config('app.page_size'));
        $model->setPath(url(url_string()) );
        $data['model'] = $model;
        $output['html']  = view('wp_users_discount_ajax',$data);
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
            foreach ($list as $key => $value) {
                $data[] = [
                    'label'=>$value->phone,
                    'value'=>$value->realname."|".$value->phone,
                ];
            }
        }
        echo json_encode(['lists'=>$data]);
        exit;
    }
   /**
     * @desc 新增
     */
    public function add(){
        $data['user'] = users::get();
        return view('wp_users_discount_save',$data);
    }
   /**
     * @desc 编辑
     */
    public function edit(){
        $id = get_data('id');
        $data['model'] = model::find($id);
        $data['user'] = users::get();
        return view('wp_users_discount_save',$data);
    }
   /**
     * @desc 保存数据
     */
    public function save(){
        if(is_ajax()){
            model::saveForm();
            exit(json_encode(['status'=>1,'msg'=>__('操作成功') ,'url'=>url('doc/wp_users_discount/index') ]));
        }
    }
   /**
     * @desc  同步会员
     */
    public function ajax_sync(){
        $f = $this->_sync() ;
        if($f){
            $insertnum = cookie('insertnum')?:0;
            $updatenum = cookie('updatenum')?:0;
            exit(json_encode(['status'=>1,'msg'=>__('本次同步插入')."$insertnum".__('条数据，更新')."$updatenum".__('条数据')]));
        }
        exit(json_encode(['status'=>0,'msg'=>__('数据导入失败')]));

    }

    protected function _sync(){

        cookie::delete('insertnum');
        cookie::delete('updatenum');
        try{
            DB::transaction(function()
            {
                $user_id =  login::getUid(null,1);
                $wp_users = user::get_wp_users();

                if($wp_users){
                    $insertnum = 0;
                    $updatenum = 0;
                    $num = 0;
                    foreach($wp_users as $k=>$v){
                        $li = wp_users::where('ucenter_id',$v['user_id'])->first();
                        $account = user::get_account_balance($v['user_id']);
                        if(!$li){
                            $data['ucenter_id'] = $v['user_id'];
                            $data['sex'] = $v['user_sex'];
                            $data['phone'] = $v['user_mobile'];
                            $data['email'] = $v['payment_time'];
                            $data['realname'] = $v['user_realname']?:__('无');
                            $data['bron'] = strtotime($v['user_birthday']);
                            $data['ucenter_name'] = $v['user_name'];
                            $data['user_minimum_living_status'] = $v['user_minimum_living_status'];
                            $data['created'] = strtotime($v['user_regtime']);
                            $data['type'] = 1;
                            $data['account_balance'] = $account;
                            $id = wp_users::insertGetId($data);
                            if($id){
                                $date['numbers'] = 'YF-'.time().cookie('admin_id').$num;
                                $date['wp_user_id'] = $id;
                                $date['users_id'] = $user_id;
                                $date['type'] = 1;
                                $date['started'] = time();
                                $date['ended'] = time()+365*84600*5;
                                model::insert($date);
                            }
                            $insertnum++;
                            $num++;

                        }else{
                            $data['sex'] = $v['user_sex'];
                            $data['phone'] = $v['user_mobile'];
                            $data['email'] = $v['payment_time'];
                            $data['realname'] = $v['user_realname']?:__('无');
                            $data['bron'] = strtotime($v['user_birthday']);
                            $data['ucenter_name'] = $v['user_name'];
                            $data['user_minimum_living_status'] = $v['user_minimum_living_status'];
                            $data['account_balance'] = $account;
                            wp_users::where('ucenter_id',$v['user_id'])->update($data);
                            $updatenum++;
                        }

                    }

                    cookie('insertnum',$insertnum);
                    cookie('updatenum',$updatenum);
                    return true;
                }else{
                    return false;
                }
            });
        }catch(Exception $e){
            return false;
        }
        return true;
    }
}