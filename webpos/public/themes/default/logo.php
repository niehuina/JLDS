<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('关于我们');?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo __('系统信息'); ?>
    </h1>

</section>

<!-- Main content -->
<section class="content">
    <div class="lockscreen-wrapper">
        <div class="lockscreen-logo">
            <img src="<?php echo base_url();?>/misc/AdminLTE-2.3.11/dist/img/logo.png" alt="User profile picture">
        </div>
        <div class="lockscreen-logo">
            <?php echo __('WebPos收银系统');?>
        </div>
        <!-- User name -->
        <div class="lockscreen-name"></div>

        <!-- START LOCK SCREEN ITEM -->
        <div class="lockscreen-item">

        </div>
        <!-- /.lockscreen-item -->
        <div class="help-block text-center">

        </div>
<!--        <div class="text-center">-->
<!--            --><?php //echo __('当前版本号V');?><!----><?php //echo config('app.version');?>
<!--        </div>-->
<!--        <div class="lockscreen-footer text-center">-->
<!--            --><?php //echo __('客服热线：400-8581598');?>
<!--        </div>-->
    </div>
</section>




<?php $this->end();?>
