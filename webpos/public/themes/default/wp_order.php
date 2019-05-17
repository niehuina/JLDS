<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('订单管理');?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo __('订单管理'); ?>
        <small>
            <a class="btn_reload btn bg-orange margin head-btns" rel="<?php echo $this->action('ajax');?>"><i class="iconfont icon-shuaxin"></i>
                <?php echo __('刷新'); ?>
            </a>
        </small>
        <?php if(cookie('admin_level') == 2 && cookie('admin_ucenter_id')){?>
            <small>
                <a class="btn bg-order margin sync head-btns"  rel="<?php echo base_url().'/doc/wp_order/ajax_sync'?>"><i class="iconfont icon-order"></i>
                    <?php echo __('同步订单'); ?>
                </a>
            </small>
        <?php }?>
    </h1>
</section>
<!-- Main content -->
<section class="content">
     <h2 class="module-title"><?php echo __('订单管理'); ?></h2>
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
                        <input id="wq" class="form-control  autocomplete" name="wq" placeholder="<?php echo __('输入订单账号进行搜索'); ?>"  type="text">
                    </div>
                    <div class="form-group pull-right wap_hide search mr30">
                        <input type="text" class="datepicker pull-right form-control"  value="<?php if(request_data('end_time')){ echo request_data('end_time');}else{echo date('Y-m-d');}?>" name='end_time' placeholder="<?php echo __('结束时间'); ?>">
                    </div>
                    <div class="form-group pull-right wap_hide search" >
                        <label><?php echo __('&nbsp;&nbsp;-&nbsp;&nbsp;'); ?></label>
                    </div>
                    <div class="form-group pull-right wap_hide search">
                        <input type="text" class="datepicker pull-right form-control"  value="<?php if(request_data('start_time')){ echo request_data('start_time');}else{echo date('Y-m-d');}?>" name='start_time' placeholder="<?php echo __('开始时间'); ?>">
                    </div>
                    <div class="form-group pull-right search mr30" >
                        <select name="status" class="form-control">
                            <option value=0><?php echo __('所有状态'); ?></option>
                            <option value=1 ><?php echo __('待支付'); ?></option>
                            <option value=6 ><?php echo __('已完成'); ?></option>
                        </select>
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
