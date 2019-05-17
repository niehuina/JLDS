<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('支付方式配置列表');?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo __('支付方式配置列表'); ?>
        <small>
            <a class="btn_reload btn bg-orange margin head-btns" rel="<?php echo $this->action('ajax');?>"><i class="iconfont icon-shuaxin"></i>
                <?php echo __('刷新'); ?>
            </a>
        </small>
    </h1>

</section>

<!-- Main content -->
<section class="content">
     <h2 class="module-title mb30"><?php echo __('支付方式配置列表'); ?></h2>
    <div class='box box-primary ajax_load_table' id='ajax' rel="<?php echo $this->action('ajax');?>">

        <?php //echo $model->lastPage(); ?>
    </div>
</section>

<script type="text/javascript">
    var autocomplete_url = "<?php echo $this->action('autocomplete'); ?>";
</script>
<?php $this->end(); ?>

