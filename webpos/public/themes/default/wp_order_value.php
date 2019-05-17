<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('订单详情');?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo __('订单详情'); ?>
    <small>
      <a href="<?php echo url('doc/wp_order/index'); ?>" class="btn bg-back margin head-btns"><i class="iconfont icon-fanhui"></i>
        <?php echo __('上一步'); ?>
      </a>
    </small>
  </h1>
</section>
<!-- Main content -->
<section class="content">
   <h2 class="module-title"><?php echo __('订单详情'); ?></h2>
  <section class="content-search" >
    <div class="box-header" >
      <h3 class="box-title"></h3>
      <div class="box-tools" >
        <?php echo form::open('form_search',['class'=>'ajax','render'=>'ajax_load_table','method'=>'get','action'=>$this->action('ajax')]); ?>
        <div class="row">
          <div class="form-group pull-right">
            <button class="btn btn-default" type="submit">
              <i class="fa fa-search btn-search"></i>
            </button>
          </div>
          <div class="form-group pull-right">
            <input id="wq" class="form-control  autocomplete" name="wq" placeholder="<?php echo __('输入商品名称进行搜索'); ?>"  type="text">
          </div>
        </div>
      </form>
    </div>
  </div>
</section>
<div class='box box-primary ajax_load_table' id='ajax' rel="<?php echo $this->action('ajax',['order_id'=>$order_id]);?>">
  
</div>
</section>
<script type="text/javascript">
  var autocomplete_url = "<?php echo $this->action('autocomplete'); ?>";
</script>
<?php $this->end(); ?>
