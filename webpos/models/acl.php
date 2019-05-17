<?php namespace models;

class acl extends base{
    
    protected $table = 'acl';
    //规则查看 https://github.com/vlucas/valitron 
    public $rules = [

    ];

    public function parent(){
        return $this->hasMany('models\acl','pid');
    }
    /*
     *@desc 获取权限列表数据 
     */
    static public function qx(){
        $list = self::where('pid',0)->get();
        foreach($list as $k=>$v){
            $list[$k]['list'] = $v->parent;
        }
        return $list;
    }
}