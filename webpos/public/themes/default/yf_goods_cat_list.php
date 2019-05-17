<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('商品分类');?>


<input type="hidden" id="ztree" value="<?php echo $this->action('ztree');?>">
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo __('商品分类'); ?>
        <small>
            <a href="<?php echo $this->action('add'); ?>" class="btn bg-olive margin head-btns" data-url="doc/yf_goods_cat/add"> <i class="iconfont icon-xinjian"></i>
                <?php echo __('新建'); ?>
            </a>
        </small>
        <small>
            <a class="btn_reload btn bg-orange margin head-btns" rel="<?php echo $this->action('ajax');?>"> <i class="iconfont icon-shuaxin"></i>
                <?php echo __('刷新'); ?>
            </a>
        </small>
        <?php if(cookie('admin_cat_id')){?>
        <small>
            <a class="btn bg-inset margin sync head-btns"  rel="<?php echo base_url().'/doc/yf_goods_cat/ajax_sync'?>"><i class="iconfont icon-inset"></i>
                <?php echo __('一键导入'); ?>
            </a>
        </small>
        <?php }?>
    </h1>

</section>

<!-- Main content -->
<section class="content">
    <h2 class="module-title"><?php echo __('商品分类'); ?></h2>
    <p class="text-red"><?php echo __('注意：一级分类下最多支持三级子分类，需要添加子分类，选择上级分类点击添加子分类按钮即可');?></p>
    <div class="row box box-primary">
        <div class="col-xs-8  wap_100 ajax_load_table" id='ajax' rel="<?php echo $this->action('ajax');?>">

        </div>
        <div class="col-xs-4  wap_100 ">
            <table class="0">
                <input id="keyword" type="text" placeholder="<?php echo __('快速查找分类');?>" name="param">
                <button id="search-bt"><?php echo __('搜索');?></button>
                <ul id="tree-obj" class="ztree"></ul>
            </table>
        </div>

    </div>


</section>
<?php $this->end();?>

