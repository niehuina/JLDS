<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('角色权限列表');?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo __('角色权限列表'); ?>
        <small>
            <a class="btn_reload btn bg-orange margin head-btns" rel="<?php echo $this->action('ajax');?>"><i class="iconfont icon-shuaxin"></i>
                <?php echo __('刷新'); ?>
            </a>
        </small>
    </h1>
    <div class="breadcrumb">
        <?php
        $u = cs\login::getUid();
        if(cookie('admin_shop_id')){
            $label = $u->yf_shop_base->users->AccountHasDays;
        }else{
            $label = $u->AccountHasDays;
        }

        if($label && cookie('admin_level') != 1){
            ?>
            <button class="btn btn-block btn-danger btn-sm" type="button"><?php echo __('剩余'); ?><?php echo $label;?><?php echo __('天，请及时续费！'); ?></button>
        <?php } ?>
    </div>
</section>
<!-- Main content -->
<section class="content">
    <h2 class="module-title mb30"><?php echo __('角色权限列表'); ?></h2>
    <div class='box box-primary ajax_load_table' id='ajax' rel="<?php echo $this->action('ajax');?>">

    </div>
</section>

<script type="text/javascript">
    var autocomplete_url = "<?php echo $this->action('autocomplete'); ?>";
</script>

<?php $this->end(); ?>

