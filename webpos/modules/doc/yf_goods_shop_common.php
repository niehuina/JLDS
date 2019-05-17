<?php  namespace modules\doc;
/**
 * @desc 本地商品库
 */
use models\yf_goods_common;
use models\yf_goods_shop_common as model;
use validator,arr;
use models\yf_goods_cat;
use models\yf_shop_base;
use cs\sync\product;
use DB;
use Exception;
use cookie;
class yf_goods_shop_common extends \cs\controller_home{
    /**
     * @desc 商品信息管理
     */
    public function index(){
        $data['shop_list'] = yf_shop_base::shop_list();
        return view('yf_goods_shop_common_list',$data);
    }

    /**
     * @desc 商品列表
     */
    public function ajax(){
        $model = model::DefaultWhere()->paginate(config('app.page_size'));
        //设置当前分页中的URL路径
        $model->setPath(url(url_string()) );
        $data['model'] = $model;
        $output['html']  = view('yf_goods_shop_common_ajax',$data);
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
            $top = get_data('top')?:20;
            $list  = model::DefaultWhere()->limit($top)->get();
            foreach ($list as $key => $value) {
                $data[] = [
                    'lable'=>$value->common_id,
                    'value'=>$value->yf_goods_common->common_name."|".$value->yf_goods_common->common_code,
                ];
            }
        }
        echo json_encode(['lists'=>$data]);
        exit;
    }
    /**
     * @desc 新建
     */

    public function add(){
        $data['yf_goods_cat'] = yf_goods_cat::_tree();
        $data['yf_shop_base'] = yf_shop_base::shop_list();
        return view('yf_goods_shop_common_save',$data);
    }
    /**
     * @desc 编辑
     */
    public function edit(){
        $id = get_data('id');
        $data['info'] = model::find($id);
        $data['yf_goods_cat'] = yf_goods_cat::_tree();
        $data['yf_shop_base'] = yf_shop_base::shop_list();
        return view('yf_goods_shop_common_save',$data);
    }


    /**
     * @desc 删除
     */
    public function delete(){
        $id = get_data('id');
        model::deleteForm($id);
        echo 1;
        exit;
    }

    /**
     * @desc 保存数据
     */
    public function save(){
        if(is_ajax()){
            model::saveForm();
            exit(json_encode(['status'=>1,'msg'=>__('操作成功') ,'url'=>url('doc/yf_goods_shop_common/index') ]));
        }
    }

    /**
     * @desc 商品导入
     */
    public function sync(){
        $model = product::getProduct();
        $data['model'] = $model;
        $data['yf_shop_base'] = yf_shop_base::shop_list();
        return view('yf_goods_shop_common_sync',$data);
    }

    /**
     * @desc  同步商品
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
                $product = product::getProduct();

                $date = post_data();
                $arr = array();
                if($date['sync'] && $product){
                    foreach($date['sync'] as $k1=>$v1){
                        foreach($product as $k=>$v){
                            if($v1 == $v['goods_id']){
                                $arr[] = $v;
                            }
                        }
                    }
                }
                if(!$date['sync']){
                    exit(json_encode(['status'=>0,'msg'=>__('导入商品不能为空')]));
                }
                if(!$date['shop_id']){
                    exit(json_encode(['status'=>0,'msg'=>__('请选择导入门店')]));
                }

                if($arr){
                    $models = new yf_goods_common;
                    $model = new model;
                    $insertnum = 0;
                    $updatenum = 0;

                    //把当前门店的商品软删除
                    $model::where('shop_id',$date['shop_id'])->delete();

                    foreach($arr as $k=>$v){
                        $goods_list = $models::where('goods_id',$v['goods_id'])->withTrashed()->first();
                        if($goods_list){
                            $li = $model::where('shop_id',$date['shop_id'])->where('common_id',$goods_list->id)->withTrashed()->first();
                        }
                        if(!$li){
                            if(!$goods_list){
                                $data['goods_id'] = $v['goods_id'];
                                $data['common_id'] = $v['common_id'];
                                $data['common_name'] = $v['goods_name'];
                                $data['cat_id'] = $v['cat_id'];
                                $data['common_spec_name'] = $v['goods_spec']?:__('无');
                                $data['file'] = $v['goods_image'];
                                $data['common_state'] = $v['goods_is_shelves'];
                                $data['common_add_time'] = time();
                                $data['common_sell_time'] = time();
                                $data['common_price'] = $v['goods_price'];
                                $data['common_market_price'] = $v['goods_market_price'];
                                $data['common_dibao_price'] = $v['common_dibao_price'];
                                $data['common_stock'] = $v['goods_stock'];
                                $data['common_alarm'] = $v['goods_alarm'];
                                $data['common_cubage'] = $v['common_cubage'];
                                $data['common_salenum'] = $v['goods_salenum']?:0;
                                $data['common_goods_from'] = 1;
                                $data['common_code'] = $v['goods_code'];
                                $id = $models->insertGetId($data);
                            }
                            $datz['shop_id'] = $date['shop_id'];
                            $datz['common_id'] = $id?:$goods_list->id;
                            $datz['type'] = 1;
                            $model->insert($datz);

                            $insertnum++;

                        }else{
                            $data['common_id'] = $v['common_id'];
                            $data['common_name'] = $v['goods_name'];
                            $data['cat_id'] = $v['cat_id'];
                            $data['common_spec_name'] = $v['goods_spec']?:__('无');
                            $data['file'] = $v['goods_image'];
                            $data['common_state'] = $v['goods_is_shelves'];
                            $data['common_add_time'] = time();
                            $data['common_sell_time'] = time();
                            $data['common_price'] = $v['goods_price'];
                            $data['common_market_price'] = $v['goods_market_price'];
                            $data['common_dibao_price'] = $v['common_dibao_price'];
                            $data['common_stock'] = $v['goods_stock']?:0;
                            $data['common_alarm'] = $v['goods_alarm'];
                            $data['common_cubage'] = $v['common_cubage'];
                            $data['common_salenum'] = $v['goods_salenum']?:0;
                            $data['common_goods_from'] = 1;
                            $data['common_code'] = $v['goods_code'];
                            $models->where('goods_id',$v['goods_id'])->update($data);
                            //恢复当前店铺的商品
                            $delte = $models->where('goods_id',$v['goods_id'])->first();
                            $model::where('shop_id',$date['shop_id'])->where('common_id',$delte['id'])->withTrashed()->restore();
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