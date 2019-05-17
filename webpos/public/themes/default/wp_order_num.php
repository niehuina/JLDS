<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('营业状况');?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo __('营业状况'); ?>
        <small>
            <a class="btn_reload btn bg-orange margin head-btns" rel="<?php echo $this->action('ajax');?>"><i class="iconfont icon-shuaxin"></i>
                <?php echo __('刷新'); ?>
            </a>
        </small>
    </h1>
</section>
<section class="content">
    <h2 class="module-title"><?php echo __('营业状况'); ?></h2>
    <section class="content-search" >
        <div class="box-header" >
            <h3 class="box-title"></h3>
            <div class="box-tools">
                <?php echo form::open('form_search',['class'=>'ajax','render'=>'ajax_load_table','method'=>'get','action'=>$this->action('ajax')]); ?>
                <div class="row">
                    <div class="form-group pull-right">
                        <button class="btn btn-default" type="submit">
                            <i class="fa fa-search btn-search"></i>
                        </button>
                    </div>
                    <div class="form-group pull-right wap_hide">
                        <input type="text"  class="datepicker pull-right form-control "  value="<?php if(request_data('end_time')){ echo request_data('end_time');}else{echo date('Y-m-d');}?>" name='end_time' >
                    </div>
                    <div class="form-group pull-right wap_hide search" >
                        <label><?php echo __('&nbsp;&nbsp;-&nbsp;&nbsp;'); ?></label>
                    </div>
                    <div class="form-group pull-right wap_hide search">
                        <input type="text" class="datepicker pull-right form-control "  value="<?php if(request_data('start_time')){ echo request_data('start_time');}else{echo date('Y-m-d');}?>" name='start_time' >
                    </div>
                    <div class="form-group pull-right search mr30">
                        <select name="shop_id" class="form-control">
                            <option value=""><?php echo __('所有门店'); ?></option>
                            <?php if($shop_list){
                                foreach($shop_list as $k=>$v){?>
                                <option value = "<?php echo $v['id'];?>"><?php echo $v['title'];?></option>
                                <?php }
                            }?>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<section>
    <div class='box box-primary ajax_load_table' id='ajax' rel="<?php echo $this->action('ajax');?>">

    </div>
</section>



<?php $this->end(); ?>

