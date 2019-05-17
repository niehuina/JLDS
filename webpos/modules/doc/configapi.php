<?php  namespace modules\doc;
use models\admin_users as model;
use validator,arr;

/**
 * @desc ucenter api设置
 *
 */
class configapi extends \cs\controller_home{
    protected $default = ['key'=>"" , 'url'=>"",'id'=>""];
    /**
     * @desc api url显示
     *
     */
    public function index(){
        $config = [
                'shop'           =>      ['label'=>"商城配置"],
                'ucenter'        =>      ['label'=>"用户中心配置"],
                'paycenter'      =>      ['label'=>"支付中心配置"],
        ];
        
        foreach ($config as $key => $value) {
            $config[$key]['form'] =  config($key)?:$this->default;
        }
        $data['config'] = $config;
        return view('configapi',$data);
    }
    /**
     * @desc 保存数据
     *
     */
    public function save(){

            $type = post_data('type');
            if(!$type){
               exit(json_encode(['status'=>0,'msg'=>__('修改失败')]));
            }else{
                $file = BASE.'/configs/'.$type.'.php';
                $data = post_data('form');
                foreach ($this->default as $key => $value) {
                    $new[$key] = trim($data[$key]);
                }

                $flag  = file_put_contents($file, "<?php\nreturn ".var_export($data,true)."\n;");
                if(!$flag){
                     exit(json_encode(['status'=>0,'msg'=>__('文件不可写')]));
               }

            }


            exit(json_encode(['status'=>1,'msg'=>__('操作成功')  ]));
        
    }

    public function config()
    {
        $config = [
            'only_to_member'           =>      ['label'=>"是否只允许会员购买"],
        ];

        foreach ($config as $key => $value) {
            $config[$key]['value'] =  config("config.".$key);
        }
        $data['config'] = $config;
        return view('configdata',$data);
    }

}