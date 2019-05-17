<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('超级管理员列表');?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo __('超级管理员列表'); ?>
        <small>
            <a href="<?php echo $this->action('add');?>" class="btn bg-olive margin head-btns">
                <i class="iconfont icon-xinjian"></i>
                <?php echo __('新建'); ?>
            </a>
        </small>
        <small>
            <a class="btn_reload btn bg-orange margin head-btns" rel="<?php echo $this->action('ajax');?>">
                <i class="iconfont icon-shuaxin"></i>
                <?php echo __('刷新'); ?>
            </a>
        </small>

    </h1>

</section>

<!-- Main content -->
<section class="content">
    <h2 class="module-title mb30"><?php echo __('超级管理员列表'); ?></h2>
    <div class='box box-primary ajax_load_table' id='ajax' rel="<?php echo $this->action('ajax');?>">

    </div>
</section>



<?php $this->end(); ?>

