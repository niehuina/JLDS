<?php namespace  models;
use cs\sync\user;
use Illuminate\Database\Eloquent\Model;
use arr,validator,token,db;

class base extends Model{
    //是否启用自动维护created_at and update_at字段
    public $timestamps = false;
    /**
     * [$rules description]
     * @var array
    $rules = [
    'title'=>['required',['lengthMin',5]],
    ];
     */
    public $rules = [];
    protected $data = [];
    public function data($data){
        $allow_fields = cache("mysql_fields.mysql")[db::getTablePrefix().$this->table];
        if(!$allow_fields){
            create_mysql_table_fields();
        }
        $token = $_REQUEST[token::name()];
        if(!$token || !token::check($token)){
            echo json_encode(['status'=>0,'msg'=>__('Token is not validater')]);
            exit();
        }
        unset($data[token::name()]);
        if($data){
            $this->data = $data;
            foreach($data as $k=>$v){
                if(in_array($k,$allow_fields))
                    $this->$k = $v;
            }
        }
        return $this;
    }
    public function save(array $options = []){
        if($this->rules){ 
            $msg = validator::set($this->data,$this->rules); 
            if($msg){
                echo json_encode(['msg'=>arr::validator($msg),'vali'=>$msg,'status'=>0]);
                exit;
            }
        }

        return parent::save($options);
    } 
    /*
     *@desc 排序
     *
     */
    public function scopeDefaultWhere($query)
    {
        $order = get_data('order');
        if($order){
            $field = substr($order,0,strripos($order,'_'));
            $sort = substr($order,strripos($order,'_')+1);
            $sort_arr = [
                'asc'=>1,
                'desc'=>1,
            ];
            if(!$sort_arr[$sort]){
                return;
            }
            $sort_2 = 'desc';
            if($sort == 'desc'){
                $sort_2 = 'asc';
            }
            $_GET['sort'][$field] = $sort_2;
            $query->orderBy($field,$sort);
        }
    }



}