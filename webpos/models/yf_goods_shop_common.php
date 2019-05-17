<?php namespace models;
/*
 *yf_goods_shop_common表数据处理
 *
 */
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Exception;
class yf_goods_shop_common extends base{
    //软删除
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'yf_goods_shop_common';
    //规则查看 https://github.com/vlucas/valitron
    public $rules = [
        'shops'=>['required'],
    ];
    /*
     *@desc 根据登录账号信息获取数据列表 
     * 
     */
    public function scopeDefaultWhere($query)
    {
        parent::scopeDefaultWhere($query);
        $wq = request_data('wq');

        if($wq){
            $query->whereHas("yf_goods_common",function($q) use($wq)
            {
                $q->where('common_code', 'like', "%".$wq."%");
            });

            $query->orWhereHas("yf_goods_common",function($q) use($wq)
            {
                 $q->where('common_name', 'like', "%".$wq."%");
            });

        }


        $in = \cs\login::login_user(1);
        if($in){
            $query->whereIn('shop_id',$in);
        }

        $cat_id = request_data('cat_id');
        if($cat_id){
            $query->whereHas("yf_goods_common",function($q) use($cat_id)
            {
                $ins = yf_goods_cat::ajax_list($cat_id)?:[];
                $ins[] = (int)$cat_id;
                $q->whereIn('cat_id',$ins );
            });
        }
        $shop_id = request_data('shop_id');
        if($shop_id){
           $query->where('shop_id',$shop_id);
        }

    }
        /*
     *@desc 根据登录账号信息获取数据列表 
     * 
     */
    public function scopeWpusersWhere($query)
    {
        $cookie_shop_id = cookie('shop_id');
        if($cookie_shop_id){
            $query->where('shop_id',$cookie_shop_id);
        }
        $wq = request_data('wq');

        if($wq){
            $query->whereHas("yf_goods_common",function($q) use($wq)
            {
                $q->where('common_code', 'like', "%".$wq."%");
            });

            $query->orWhereHas("yf_goods_common",function($q) use($wq)
            {
                 $q->where('common_name', 'like', "%".$wq."%");
            });

        }

        $cookie_shop_id = cookie('shop_id');
        if($cookie_shop_id){
            $query->where('shop_id',$cookie_shop_id);
        }

        $cat_id = request_data('cat_id');
        if($cat_id){
            $query->whereHas("yf_goods_common",function($q) use($cat_id)
            {
                $ins = yf_goods_cat::ajax_list($cat_id)?:[];
                $ins[] = (int)$cat_id;
                $q->whereIn('cat_id',$ins );
            });
        }
    }
    /*
     *获取商品信息
     *
     */
    public function yf_goods_common(){

        return $this->belongsTo('models\yf_goods_common','common_id');
    }

    /*
    *获取店铺信息
    *
    */
    public function yf_shop_base(){
        return $this->belongsTo('models\yf_shop_base','shop_id');
    }
    /*
     * @desc 保存表单数据
     *
     */
    static function saveForm(){
        try{
            DB::transaction(function()
            {
                $data = post_data();
                if(!$data['shops']){
                    exit(json_encode(['status'=>0,'msg'=>__('所属门店不能为空')]));
                }
                $cat_id = yf_goods_cat::where('cat_parent_id',$data['cat_id'])->first();
                if($cat_id){
                    exit(json_encode(['status'=>0,'msg'=>__('请选择该分类下的最后一级分类')]));
                }
                if($data['id']){
                    $models = new yf_goods_common;
                    $data['common_state'] = $data['common_state']?:2;
                    $data['common_invoices'] = $data['common_invoices']?:0;
                    $data['common_discounts'] = $data['common_discounts']?:0;
                    $model = self::find($data['id']);

                    if($model->yf_goods_common->common_state != $data['common_state']){
                        $data['common_sell_time'] = time();
                    }
                    $list = $models::where('common_code',$data['common_code'])->get()->toArray();
                    if($list){
                        foreach($list as $k=>$v){
                            $in[] = $v['id'];
                        }
                        $li = $model::whereIn('common_id',$in)->where('common_id','!=',$model->common_id)->get()->toArray();
                        if($li){
                            exit(json_encode(['status'=>0,'msg'=>__('该条形编码已存在，不可重复')]));
                        }
                    }
                    unset($data['shops']);
                    unset($data['id']);
                    $models = $models::find($model->common_id);
                    $models->data($data)->save();
                    $del['shops'] = post_data('shops');

                    $model->where('common_id',post_data('common_id'))->delete();

                    foreach ($del['shops'] as $key => $value) {
                        $date['common_id'] = post_data('common_id');
                        $date['shop_id'] = $value;
                        $model->insert($date);
                    }
                    
                }else{
                    $list = yf_goods_common::where('common_code',$data['common_code'])->get()->toArray();
                    if($list){
                        foreach($list as $k=>$v){
                            $in[] = $v['id'];
                        }

                        $li = yf_goods_common::whereIn('common_id',$in)->first();
                        if($li){
                            exit(json_encode(['status'=>0,'msg'=>__('该条形编码已存在，不可重复')]));
                        }
                    }

                    if($data['common_state'] == 1){
                        $data['common_sell_time'] = time();
                    }
                    $data['common_add_time'] = time();

                    $model = new yf_goods_common;

                    unset($data['shop_id']);

                    $model->data($data)->save();

                    $id = last_insert_id();
                    if($id){
                        $date['common_id'] = $id;
                        $dal['shops'] = post_data('shops');
                        $models = new self;
                        foreach ($dal['shops'] as $key => $value) {
                            $date['shop_id'] = $value;
                            $models->insert($date);
                        }
                       
                        
                    }
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
        $model->delete();
    }
}