<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('授权账号列表');?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo __('授权账号设置'); ?>
        <small>
            <a href="<?php echo $this->action('add');?>" class="btn bg-olive margin head-btns">
                <i class="iconfont icon-xinjian"></i>
                <?php echo __('新建'); ?>
            </a>
        </small>
        <small>
            <a class="btn_reload btn bg-orange margin head-btns" rel="<?php echo $this->action('ajax');?>" data-url="doc/users/add"> <i class="iconfont icon-shuaxin"></i>
                <?php echo __('刷新'); ?>
            </a>
        </small>

    </h1>

</section>

<!-- Main content -->
<section class="content">
    <h2 class="module-title"><?php echo __('授权账号设置'); ?></h2>
    <section class="content-search" >
        <div class="box-header" >
            <h3 class="box-title"></h3>
            <div class="box-tools" >
                <?php echo form::open('form_search',['class'=>'ajax','render'=>'ajax_load_table','method'=>'get','action'=>$this->action('ajax')]); ?>
                <div class="row">
                    <div class="form-group pull-right">
                        <button class="btn btn-default bl0 bradius0" type="submit">
                            <i class="fa fa-search btn-search"></i>
                        </button>
                    </div>
                    <div class="form-group pull-right ml40">
                        <input id="wq" class="form-control pull-right autocomplete" name="wq" placeholder="<?php echo __('请输入账号'); ?>"  type="text">
                    </div>
                    <div class="form-group pull-right wap_hide search ml30">
                        <select name="status" class="form-control pull-right" >
                            <option value = ""><?php echo __('账号状态'); ?></option>
                            <option value = 1><?php echo __('正常'); ?></option>
                            <option value = 2><?php echo __('即将超期'); ?></option>
                            <option value = 3><?php echo __('已超期'); ?></option>
                            <option value = 4><?php echo __('未到服务开始时间'); ?></option>
                        </select>
                    </div>
                    <div class="form-group pull-right wap_hide search">
                        <input type="text" class="datepicker pull-right form-control"  value="" name='service_end_time' placeholder="<?php echo __('结束时间'); ?>">
                    </div>
                    <div class="form-group pull-right wap_hide search" >
                        <label><?php echo __('&nbsp;&nbsp;-&nbsp;&nbsp;'); ?></label>
                    </div>
                    <div class="form-group pull-right wap_hide search">
                        <input type="text" class="datepicker pull-right form-control"  value="" name='service_start_time' placeholder="<?php echo __('开始时间'); ?>">
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

