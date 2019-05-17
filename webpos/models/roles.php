<?php namespace models;
/*
 *@desc roles表数据处理
 */
use Illuminate\Database\Eloquent\SoftDeletes;
class roles extends base{
    //软删除
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'roles';
    //规则查看 https://github.com/vlucas/valitron
    public $rules = [
        'title'=>['required'],
        'slug'=>['required','slug'],
    ];
    /*
    *@desc 搜索
    *
    */
    public function scopeDefaultWhere($query)
    {
        parent::scopeDefaultWhere($query);
        $wq = request_data('wq');
        if($wq){
            $query->where(function($query)use($wq){
                $query->where("title",'Like',"%".$wq."%");
                $query->orWhere("slug",'Like',"%".$wq."%");
            });
        }
    }
    /*
    *@desc 关联role_users
    *
    */
    public function role_users(){
        return $this->hasMany('models\role_users','role_id');
    }
    /*
     * @desc 保存表单数据
     *
     */
    static function saveForm(){
        $data = post_data();
        if($data['id']){
            $data['updated'] = time();
            $model = self::find($data['id']);
            $roles_id = $model->role_users->toArray();
            if($roles_id){
                exit(json_encode(['status'=>0,'msg'=>__('门店角色已被使用，无法进行删除或者修改')]));
            }
        }else{
            $slug = self::where('slug','=',$data['slug'])->first()->slug;
            if($slug){
                exit(json_encode(['status'=>0,'msg'=>__('唯一标识不能重复')]));
            }
            $data['created'] = time();
            $model = new self;
        }

        $model->data($data)->save();
    }
    /*
     * @desc 删除表单数据
     */
    static function deleteForm($id){
        $roles_id = role_users::where('role_id',$id)->first();
        if($roles_id){
            return 1;
        }else{
            $model = self::where('id',$id);
            $model->delete();
        }

    }

}