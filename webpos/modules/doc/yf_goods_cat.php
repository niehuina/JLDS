<?php  namespace modules\doc;
use models\yf_goods_cat as model;
use validator,arr;
use cs\sync\type;
/*
 *@desc 商品分类管理
 */
class yf_goods_cat extends \cs\controller_home{
    /**
     * @desc 列表页数据显示
     *
     */
    public function index(){
        return view('yf_goods_cat_list');
    }
    /**
     * @desc 分类树
     *
     */
    public function ztree(){
        $pid = request_data('id')?:0;
        $data = model::ajax_tree($pid);
        if($data){
            foreach($data as $v){
                $name = $v['cat_name'];
                $id = $v['id'];
                $isParent = $v['hasTree']?true:false;
                $json[]  = [
                    'id'=>$id,
                    'name'=>$name,
                    'open'=>$v['open'],
                    'isParent'=>$isParent,
                ];
            }
        }
        exit(json_encode($json));
    }

    /**
     * @desc ajax列表
     */
    public function ajax(){
        $model          = model::_tree(0,true);
        $data['model']  = $model;
        $output['html'] = view('yf_goods_cat_ajax', $data);
        echo json_encode(['status' => true, 'html' => $output['html'], 'render' => 'ajax_load_table']);
        exit;
    }


    /**
     * @desc 根据父类获取子类id数组列表
     */

    public function ajax_list(){
        $in = model::ajax_list();
        echo json_encode($in);
        exit;
    }

    /**
     * @desc 添加分类信息
     *
     */

    public function add(){
        $data['info']=model::find(get_data('cat_parent_id'));
        return view('yf_goods_cat_save',$data);
    }
    /**
     * @desc 编辑分类信息
     *
     */
    public function edit(){
        $id = get_data('id');
        $data['info'] = model::find($id);
        return view('yf_goods_cat_save',$data);
    }
    /**
     * @desc 删除分类信息
     *
     */
    public function delete(){
        $ret = model::deleteForm();
        if($ret == 1){
            echo 4;
        }elseif($ret == 2){
            echo 3;
        }else{
            echo 1;
        }

        exit;
    }

    /**
     * @desc 保存数据
     *
     */
    public function save(){
        if(is_ajax()){
            model::saveForm();
            exit(json_encode(['status'=>1,'msg'=>__('操作成功') ,'url'=>url('doc/yf_goods_cat/index') ]));
        }
    }

    /**
     * @desc 同步分类
     */
    public function ajax_sync(){
        $model          = type::getCat();
        $data = [];
        if($model){
            $insertnum = 0;
            $updatenum = 0;
            foreach($model as $k=>$v){

                $li = model::where('id',$v['cat_id'])->withTrashed()->first();
                $data['cat_name'] = $v['cat_name'];
                $data['cat_parent_id'] = $v['cat_parent_id'];
                $data['cat_displayorder'] = $v['cat_displayorder'];
                $data['level'] = $v['level'];
                $data['type'] = 1;
                if(!$li){
                    $data['id'] = $v['cat_id'];
                    model::insert($data);
                    $insertnum++;

                }else{
                    model::where('id',$v['cat_id'])->update($data);
                    $updatenum++;
                }

            }
            exit(json_encode(['status'=>1,'msg'=>__('本次同步插入')."$insertnum".__('条数据，更新')."$updatenum".__('条数据')]));
        }else{
            exit(json_encode(['status'=>0,'msg'=>__('无数据导入')]));
        }
    }

}

