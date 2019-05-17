<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('退货表');?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo __('退货表'); ?>
        <small>
            <a href="<?php echo url('doc/wp_order/index',['id'=>$v->id]); ?>" class="btn bg-blue margin head-btns"><i class="iconfont icon-liebiao"></i>
                <?php echo __('列表'); ?>
            </a>
        </small>
    </h1>
</section>
<!-- Main content -->
<section class="content">
    <h2 class="module-title mb30"><?php echo __('退货表'); ?></h2>
    <div class='box box-primary'>
        <?php echo form::open('form1',['class'=>'ajax','action'=>$this->action('save')]); ?>
        <table class="table table-bordered table-hover dataTable" >
            <thead>
            <tr role="row">
                <th><?php echo __('订单编号'); ?></th>
                <th ><?php echo __('会员名称'); ?></th>
                <th class="wap_hide"><?php echo __('手机号码'); ?></th>
                <th class="wap_hide"><?php echo __('支付方式'); ?></th>
                <th><?php echo __('商品名称'); ?></th>
                <th ><?php echo __('商品数量'); ?></th>
                <th><?php echo __('商品价格'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            if($model){
                foreach($model as $v){ ?>
                    <tr role="row" class="odd">
                        <input  type="hidden"  name="order_id"  value="<?php echo $v->order_id;  ?>">
                        <input  type="hidden"  name="goods_id"  value="<?php echo $v->goods_id;  ?>">
                        <input  type="hidden"  name="shop_id"   value="<?php echo $v->shop_id;   ?>">
                        <input  type="hidden"  name="shop_name" value="<?php echo $v->shop_name; ?>">
                        <input  type="hidden"  name="shop_users_id" value="<?php echo $v->shop_users_id; ?>">
                        <input  type="hidden"  name="all_num" value="<?php echo $v->num; ?>">
                        <input  type="hidden"  name="all_goods_price" value="<?php echo $v->goods_price; ?>">
                        <input  id="price" type="hidden"  name="price" value="<?php echo $v->goods_info->common_price; ?>">

                        <td><?php echo $v->wp_order_info->order_id; ?></td>
                        <td ><input  readonly type="hidden"  name="ucenter_name" value="<?php echo $v->wp_order_info->wp_users->ucenter_name; ?>"><?php echo $v->wp_order_info->wp_users->ucenter_name; ?>
                        </td>
                        <td class="wap_hide"><input  readonly type="hidden"  name="phone" value="<?php echo $v->wp_order_info->wp_users->phone; ?>"><?php echo $v->wp_order_info->wp_users->phone; ?>
                        </td>
                        <td class="wap_hide"><input  readonly type="hidden"  name="payid" value="<?php echo $v->wp_order_info->payid; ?>"><?php echo array_search($v->wp_order_info->payid,config('payment')); ?>
                        </td>
                        <td class="product-buyer-name" title="<?php echo $v->goods_name; ?>"><input  readonly type="hidden"  name="goods_name" value="<?php echo $v->goods_name; ?>"><?php echo $v->goods_name; ?>
                        </td>

                        <td >
                            <div class="num-cli">
                                <a href="javascript:;"  id="jian">-</a>
                                <input class="tc num-change" readonly type="text" id="cont" name="num" value="<?php echo $v->num - $v->renum; ?>">
                                <a href="javascript:;" id="jia">+</a>
                            </div>
                        <td >
                            <input id="all_price"  readonly type="hidden"  name="goods_price" value="<?php echo ($v->num - $v->renum) * $v->goods_info->common_price; ?>">
                            <span id="one_price"><?php echo ($v->num - $v->renum) * $v->goods_info->common_price; ?></span>
                        </td>
                    </tr>
                <?php }} ?>
            </tbody>
            <tfoot>
            </tfoot>
        </table>
       
        <div class="box-footer ml0 mt30">
            <a href="<?php echo url('doc/wp_order_value/index',['order_id'=>$v->order_id]);?>" class="btn btn-default"><?php echo __('取消');?></a>
            <a  class="btn btn-primary order_return"><?php echo __('确认退货');?></a>
        </div>
        </form>
    </div>
</section>

<?php $this->end(); ?>
