<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('交接班记录');?>
<section class="content-header">
    <h1>
        <?php echo __('交接班记录'); ?>
        <small>
            <a class="btn_reload btn bg-orange margin head-btns" rel="<?php echo $this->action('ajax');?>"><i class="iconfont icon-shuaxin"></i>
                <?php echo __('刷新'); ?>
            </a>
        </small>
    </h1>
</section>
<!-- Main content -->
<section class="content">
     <h2 class="module-title"><?php echo __('交接班记录'); ?></h2>
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
                    <div class="form-group pull-right">
                        <input id="wq" class="form-control  autocomplete" name="wq" placeholder="<?php echo __('输入收银员账号进行搜索'); ?>"  type="text">
                    </div>
                    <div class="form-group pull-right wap_hide search mr30">
                        <input type="text" class="datepicker pull-right form-control"  value="<?php if(request_data('end_time')){ echo request_data('end_time');}else{echo date('Y-m-d');}?>" name='end_time' placeholder="<?php echo __('结束时间'); ?>">
                    </div>
                    <div class="form-group pull-right wap_hide search">
                        <label><?php echo __('&nbsp;&nbsp;-&nbsp;&nbsp;'); ?></label>
                    </div>
                    <div class="form-group pull-right wap_hide search">
                        <input type="text" class="datepicker pull-right form-control"  value="<?php if(request_data('start_time')){ echo request_data('start_time');}else{echo date('Y-m-d');}?>" name='start_time' placeholder="<?php echo __('开始时间'); ?>">
                    </div>
                    <div class="form-group pull-right wap_hide search mr30" >
                        <select name="yf_shop_base_id" class="form-control">
                            <option value=""><?php echo __('所有门店'); ?></option>
                            <?php if($yf_shop_base){
                                foreach($yf_shop_base as $k=>$v){?>
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
    <script type="text/javascript">
        var autocomplete_url = "<?php echo $this->action('autocomplete'); ?>";
    </script>
    <?php $this->end(); ?>
