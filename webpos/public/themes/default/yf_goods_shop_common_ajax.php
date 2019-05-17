<?php
$sort['wq'] = request_data('wq');
$qx = new \cs\controller_home();
$arr = $qx->operation_authority( 1,1);
?>

<table class="table table-bordered table-hover dataTable" >
    <thead>
    <tr role="row">
        <input type="hidden" id="qxz" value=<?php echo $arr;?>>
        <th class="wap_hide"><?php echo __('序号'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'id_'.($_GET['sort']['id']?:'desc') ]); ?>" ></a>
        </th>
        <th><?php echo __('图片'); ?></th>
        <th><?php echo __('名称'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'common_id_'.($_GET['sort']['common_id']?:'desc') ]); ?>" ></a>
        </th>
        <th  class="wap_hide"><?php echo __('条码'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'common_id_'.($_GET['sort']['common_id']?:'desc') ]); ?>" ></a>
        </th>
        <th  class="wap_hide"><?php echo __('分类'); ?></th>
        <th  class="wap_hide"><?php echo __('库存'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'id_'.($_GET['sort']['id']?:'desc') ]); ?>" ></a>
        </th>

        <th  class="wap_hide"><?php echo __('规格'); ?></th>
        <th><?php echo __('商品价格(￥)'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'id_'.($_GET['sort']['id']?:'desc') ]); ?>" ></a>
        </th>
        <th  class="wap_hide"><?php echo __('市场价格(￥)'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'id_'.($_GET['sort']['id']?:'desc') ]); ?>" ></a>
        </th>
        <th  class="wap_hide"><?php echo __('重量(kg)'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'id_'.($_GET['sort']['id']?:'desc') ]); ?>" ></a>
        </th>
        <!-- <th  class="wap_hide"><?php //echo __('是否提供发票'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php //echo $this->action('ajax',$sort+['order'=>'id_'.($_GET['sort']['id']?:'desc') ]); ?>" ></a>
        </th> -->
        <th  class="wap_hide"><?php echo __('会员折扣'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'id_'.($_GET['sort']['id']?:'desc') ]); ?>" ></a>
        </th>
        <th><?php echo __('所属店铺'); ?>
            <a class="fa fa-fw fa-sort ajax" href="<?php echo $this->action('ajax',$sort+['order'=>'shop_id_'.($_GET['sort']['shop_id']?:'desc') ]); ?>" ></a>
        </th>
<!--        <th>--><?php //echo __('操作'); ?><!--</th>-->
    </tr>
    </thead>
    <tbody>



    <?php
    if($model){
        foreach($model as $v){ ?>
            <tr role="row" class="odd">
                <td  class="wap_hide"><?php echo $v->id; ?></td>
                    <!-- cssz -->
                <td class="ace"><img src="<?php echo $v->yf_goods_common->file; ?>"></td>
                <td class="product-buyer-name" title="<?php echo $v->yf_goods_common->common_name; ?>"><?php echo $v->yf_goods_common->common_name; ?></td>
                <td  class="wap_hide"><?php echo $v->yf_goods_common->common_code; ?></td>
                <td  class="wap_hide"><?php echo $v->yf_goods_common->yf_goods_cat->cat_name; ?></td>
                <td  class="wap_hide"><?php echo $v->yf_goods_common->common_stock; ?></td>
                <td  class="wap_hide"><?php echo $v->yf_goods_common->common_spec_name; ?></td>
                <td>￥<?php echo $v->yf_goods_common->common_price; ?></td>
                <td  class="wap_hide">￥<?php echo $v->yf_goods_common->common_market_price; ?></td>
                <td  class="wap_hide"><?php echo $v->yf_goods_common->common_cubage; ?>kg</td>
                <!-- <td  class="wap_hide"><?php// echo $v->yf_goods_common->common_invoices ==1?'是':'否'; ?></td> -->
                <td  class="wap_hide"><?php echo $v->yf_goods_common->common_discounts ==1?'是':'否';?></td>
                <td><?php echo $v->yf_shop_base->title;?></td>
<!--                <td>-->
<!--                    <a href="--><?php //echo $this->action('edit',['id'=>$v->id]); ?><!--" data-url="doc/yf_goods_shop_common/edit">-->
<!--                        <i class="iconfont icon-bianji" title="--><?php //echo __('编辑'); ?><!--"></i>-->
<!--                    </a>-->
<!--                    <a class="del" data-id="--><?php //echo $v->id;?><!--" rel="--><?php //echo $this->action('delete',['id'=>$v->id]); ?><!--" data-url="doc/yf_goods_shop_common/delete">-->
<!--                        <i class="iconfont icon-shanchu" title="--><?php //echo __('删除'); ?><!--"></i>-->
<!--                    </a>-->
<!--                </td>-->
            </tr>
        <?php }} ?>

    </tbody>
    <tfoot>

    </tfoot>
</table>
<script src="<?php echo theme_url().'/qx.js';?>"></script>
<script type="text/javascript">
    $('.table-bordered td img').hover(function() {
        $(this).css({"transform":"scale(6.1)","transition":"0.1s","box-shadow":"0 0 10px #d5d5d5","position":"relative","zIndex":"9999","height":"auto"});
    },function(){
        $(this).css({"transform":"scale(1)","transition":"0.1s","box-shadow":"0","position":"relative","zIndex":"1","height":"25px"});
    })
</script>
<?php
echo $model->appends(page_opt())->render();
?>