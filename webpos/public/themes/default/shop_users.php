<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('员工账号设置');?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo __('员工账号设置'); ?>

        <small id="add">
            <a href="<?php echo $this->action('add');?>" class="btn bg-olive margin head-btns" data-url="doc/shop_users/add"><i class="iconfont icon-xinjian"></i>
                <?php echo __('新建'); ?>
            </a>
        </small>

        <small>
            <a class="btn_reload btn bg-orange margin head-btns" rel="<?php echo $this->action('ajax');?>"> <i class="iconfont icon-shuaxin"></i>
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
    <h2 class="module-title"><?php echo __('员工账号设置'); ?></h2>
    <section class="content-search" >
        <div class="box-header" >
            <h3 class="box-title"></h3>
            <div class="box-tools" style="float: right; width: 300px;">
                <?php echo form::open('form_search',['class'=>'ajax','render'=>'ajax_load_table','method'=>'get','action'=>$this->action('ajax')]); ?>
                <div class="input-group input-group-sm">
                    <input id="wq" class="form-control pull-right autocomplete " name="wq" placeholder="<?php echo __('输入员工账号,姓名或手机号搜索'); ?>"  type="text">
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="submit">
                            <i class="fa fa-search btn-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
    <div class='box box-primary ajax_load_table' id='ajax' rel="<?php echo $this->action('ajax');?>">
    </div>
</section>
<script type="text/javascript">
  var autocomplete_url = "<?php echo $this->action('autocomplete'); ?>";
</script>
<?php $this->end(); ?>
