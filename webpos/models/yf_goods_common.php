<?php namespace models;
/*
 *yf_goods_common表数据处理
 *
 */
use Illuminate\Database\Eloquent\SoftDeletes;
class yf_goods_common extends base{
    //软删除
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'yf_goods_common';
    //规则查看 https://github.com/vlucas/valitron
    public $rules = [
        'file'=>['required'],
        'common_code'=>['required',['length',13]],
        'common_name'=>['required',['lengthBetween',0,32]],
        'cat_id'=>['required'],
        'common_cubage'=>['required'],
        'common_price'=>['required',['numeric']],
        'common_market_price'=>['required',['numeric']],
        'common_stock'=>['required','integer'],
        'common_spec_name'=>['required'],
    ];


    /*
     *获取商品分类信息
     *
     */
    public function yf_goods_cat(){
        return $this->hasOne('models\yf_goods_cat','id','cat_id');
    }

}