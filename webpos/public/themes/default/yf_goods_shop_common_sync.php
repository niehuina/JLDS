<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('商品管理');?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo __('商品管理'); ?>
        <small>
            <a href="<?php echo $this->action('index');?>" class="btn bg-blue margin head-btns"><i class="iconfont icon-liebiao"></i>
                <?php echo __('列表'); ?>
            </a>
        </small>
    </h1>

</section>

<!-- Main content -->
<section class="content">
     <h2 class="module-title mb30"><?php echo __('商品管理'); ?></h2>
    <div class='box box-primary'>
        <?php echo form::open('form1',['class'=>'ajax','action'=>$this->action('ajax_sync')]); ?>
        <table class="table table-bordered table-hover dataTable" >
            <thead>
            <tr role="row">
                <th><button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></th>
                <th class="wap_hide"><?php echo __('序号'); ?></th>
                <th><?php echo __('图片'); ?></th>
                <th><?php echo __('名称'); ?></th>
                <th  class="wap_hide"><?php echo __('分类'); ?></th>
                <th  class="wap_hide"><?php echo __('库存'); ?></th>
                <th  class="wap_hide"><?php echo __('规格'); ?></th>
                <th><?php echo __('商品价格(￥)'); ?></th>
                <th  class="wap_hide"><?php echo __('市场价格(￥)'); ?></th>
                <th  class="wap_hide"><?php echo __('低保价格(￥)'); ?></th>
                <th  class="wap_hide"><?php echo __('重量(kg)'); ?></th>
            </tr>
            </thead>
            <tbody>

            <?php
            if($model){
                foreach($model as $v){ ?>
                    <tr role="row">
                        <td>
                            <input class="iCheck" name="sync[]" type="checkbox" value="<?php echo $v['goods_id']; ?>">
                        </td>
                        <td  class="wap_hide"><?php echo $v['id']; ?></td>
                        <!-- cssz -->
                        <td class="ace"><img src="<?php echo $v['goods_image']; ?>"></td>
                        <td class="product-buyer-name" title="<?php echo $v['goods_name']; ?>"><?php echo $v['goods_name']; ?></td>
                        <td  class="wap_hide"><?php echo $v['cat_name']; ?></td>
                        <td  class="wap_hide"><?php echo $v['goods_stock']; ?></td>
                        <td  class="wap_hide"><?php echo $v['goods_spec']?:__('无'); ?></td>
                        <td>￥<?php echo $v['goods_price']; ?></td>
                        <td  class="wap_hide">￥<?php echo $v['goods_market_price']; ?></td>
                        <td  class="wap_hide">￥<?php echo $v['common_dibao_price']; ?></td>
                        <td  class="wap_hide"><?php echo $v['common_cubage']; ?>kg</td>
                    </tr>
                <?php }} ?>

            </tbody>
            <tfoot>

            </tfoot>
        </table>
        <div class="box-footer">
            <select name="shop_id" class="select-style">
                <option value=""><?php echo __('请选择导入门店');?></option>
                <?php if($yf_shop_base){
                    foreach($yf_shop_base as $k=>$v){?>
                        <option value="<?php echo $v['id'];?>"><?php echo $v['title'];?></option>
                    <?php }
                }?>
            </select>
            <button type="submit" class="btn btn-primary "><?php echo __('开始同步');?></button>
        </div>
        </form>
    </div>
</section>

<?php $this->end(); ?>

