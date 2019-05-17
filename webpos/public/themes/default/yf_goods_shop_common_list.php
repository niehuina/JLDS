<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('商品管理');?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo __('商品管理'); ?>
<!--        <small>-->
<!--            <a href="--><?php //echo $this->action('add'); ?><!--" class="btn bg-olive margin head-btns" data-url="doc/yf_goods_shop_common/add"><i class="iconfont icon-xinjian"></i>-->
<!--                --><?php //echo __('新建'); ?>
<!--            </a>-->
<!--        </small>-->
        <small>
            <a class="btn_reload btn bg-orange margin head-btns" rel="<?php echo $this->action('ajax');?>"><i class="iconfont icon-shuaxin"></i>
                <?php echo __('刷新'); ?>
            </a>
        </small>
        <?php if(cookie('admin_level') == 2 && cookie('admin_ucenter_id')){?>
            <small>
                <a href="<?php echo $this->action('sync'); ?>" class="btn bg-inset margin head-btns" data-url="doc/yf_goods_shop_common/sync" ><i class="iconfont icon-inset"></i>
                    <?php echo __('商品同步'); ?>
                </a>
            </small>
        <?php }?>
    </h1>

</section>

<!-- Main content -->
<section class="content">
    <h2 class="module-title"><?php echo __('商品管理'); ?></h2>
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
                        <input id="wq" class="form-control pull-right autocomplete" name="wq" placeholder="<?php echo __('请输入商品名称、条码'); ?>"  type="text">
                    </div>
                    <div class="form-group pull-right search mr30">
                        <select name="shop_id" class="form-control">
                            <option value=""><?php echo __('所有门店'); ?></option>
                            <?php if($shop_list){
                                foreach($shop_list as $k=>$v){ ?>
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
    <div class='box box-primary ajax_load_table' id='ajax' rel="<?php echo $this->action('ajax');?>">

        <?php //echo $model->lastPage(); ?>
    </div>
</section>

<script type="text/javascript">
    var autocomplete_url = "<?php echo $this->action('autocomplete'); ?>";
</script>
<?php $this->end(); ?>

