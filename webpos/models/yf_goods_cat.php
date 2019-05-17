<?php namespace models;
/*
 *@desc yf_goods_cat表数据处理
 *
 * */

use cs\tree;
use Illuminate\Database\Eloquent\SoftDeletes;
class yf_goods_cat extends base{
    //软删除
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'yf_goods_cat';
    //规则查看 https://github.com/vlucas/valitron
    public $rules = [
        'cat_name'=>['required'],

    ];

    /*
     *@desc 门店员工账号- 根据登录账号信息获取数据列表 
     * 
     */
    public function scopeDefaultWhere($query)
    {
        $query->where('level',1);

    }
    /**
     * @desc 获取上一层关联
     */
    public function parent()
    {
        return $this->belongsTo('models\yf_goods_cat','cat_parent_id');
    }
    /*
     *关联商品列表
     */
    public function yf_goods_common(){
        return $this->hasMany('models\yf_goods_common','cat_id');
    }


    /*
     * @desc 保存表单数据
     *
     */
    static function saveForm(){
        $data = post_data();
        if($data['id']){
            $model = self::find($data['id']);
        }elseif($data['add']){
            $cat_parent_info = self::where('id','=',$data['add'])->first();
            if($cat_parent_info){
                if($cat_parent_info['level'] == 4){
                    exit(json_encode(['status'=>0,'msg'=>__('注意:一级分类下最多支持三级子分类')]));
                }
                $data['level'] = $cat_parent_info['level']+1;
                $data['cat_parent_id'] = $cat_parent_info['id'];
                $model = new self;
            }
        }else{
            $data['level'] = 1;
            $model = new self;
        }

        $model->data($data)->save();
    }
    /*
     * @desc 删除表单数据
     */
    static function deleteForm(){
        $list = self::ajax_list();
        if($list){
            return 2;//分类下有子分类不可以删除
        }
        $id = get_data('id');
        if($id){
            $arr = self::find($id)->yf_goods_common->toArray();
            if($arr){
                return 1;//分类下有商品不可以删除
            }else{
                $model = self::where('id',$id);
                $model->delete();
            }
        }
    }
    /*
     *@desc 分类数
     *
     */
    static function ajax_tree($pid){
        $wq = get_data('wq');
        $flag = false;
        if($pid<1 && $wq){
            $flag = true;
            $rs = self::where('cat_name','like',$wq)->get();
        }else{
            $rs = self::where('cat_parent_id','=',$pid)->get();
        }
        $open = true;
        if($rs){
            foreach($rs as $v){
                $hasTree  = self::where('cat_parent_id','=',$v->id)->first()?true:false;
                if($flag === true){
                    $open = false;
                }
                $list[] = [
                    'cat_name' => $v->cat_name,
                    'id' => $v->id,
                    'hasTree' => $hasTree,
                    'open'=>$open,
                ];
            }
        }
        return $list;
    }
    /*
     *@desc 根据子级选择上一级
     *
     *
     */
    static function _tree( $pid = 0 ,$line = null ){
        if($line == true){
            tree::$string = "";
        }
        //SELECT框选选择里面带有层级关系
        $a = self::where('cat_parent_id','>=',0)->get()->toArray();
        $tree = [];
        if($a){
            //设置对应的字段，只可修改VALUE
            tree::$set = [
                'id' => 'id',
                'pid' => 'cat_parent_id',
                'title' => 'cat_name',
            ];
            $tree = tree::render($a , $pid);
        }
        return $tree;
    }


    
    /*
     *@desc  根据分类Id获取子集id集合
     */
    static function ajax_list($id = null){
        $id = $id?:get_data('id');
        $list = self::_tree($id);
        if($list){
            foreach($list as $v){
                $in[] = $v['id'];
            }
        }
        return $in;
    }

}
