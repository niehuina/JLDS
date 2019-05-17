<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

<link href="<?= $this->view->css ?>/ui.min.css?ver=20140430" rel="stylesheet">
<script src="<?=$this->view->js_com?>/plugins/jquery.jqgrid.js" ></script>

<div class="goods-category">
	<ol class="step fn-clear add-goods-step clearfix">
		<li class="cur">
			<i class="icon iconfont icon-icoordermsg bbc_seller_color"></i>
			<h6 class="bbc_seller_color"><?=__('STEP 1')?></h6>

			<h2 class="bbc_seller_color"><?=__('选择分类')?></h2>
			<i class="arrow iconfont icon-btnrightarrow"></i>
		</li>
		<li>
			<i class="icon iconfont icon-shangjiaruzhushenqing"></i>
			<h6><?=__('STEP 2')?></h6>

			<h2><?=__('填写信息')?></h2>
			<i class="arrow iconfont icon-btnrightarrow"></i>
		</li>
		<li>
			<i class="icon iconfont icon-zhaoxiangji "></i>
			<h6><?=__('STEP 3')?></h6>

			<h2><?=__('上传图片')?></h2>
			<i class="arrow iconfont icon-btnrightarrow"></i>
		</li>
		<li>
			<i class="icon iconfont icon-icoduigou"></i>
			<h6><?=__('STEP 4')?></h6>

			<h2><?=__('发布成功')?></h2>
			<i class="arrow iconfont icon-btnrightarrow"></i>
		</li>
		<li>
			<i class="icon iconfont icon-pingtaishenhe"></i>
			<h6><?=__('STEP 5')?></h6>

			<h2><?=__('平台审核')?></h2>
		</li>
	</ol>
	<div class="dataLoading" id="dataLoading"><p><?=__('加载中')?>...</p></div>
	<div class="goods-category-list fn-clear clearfix">
	<div>
        <div class="grid-wrap">
            <table id="grid">
            </table>
            <div id="page"></div>
        </div>

    </div>
	</div>

	<div class="button_next_step">
		<form method="post" action="<?=Yf_Registry::get('url')?>?ctl=Seller_Goods&met=add&typ=e">

            <input type="hidden" name="common_id" value="<?php if ( !empty($common_data) ) { echo $common_data['common_id']; } ?>"/>
            <input type="hidden" name="action" value="<?php if ( !empty($common_data) ) { echo 'edit'; } ?>"/>
            <input type="hidden" name="cat_id" value="<?php echo $cat_id ?>"/>
            <input type="hidden" name="erp_material_fnumber" id = "erp_material_fnumber" value=""/>
            <input type="hidden" name="erp_material_name" id = "erp_material_name" value=""/>
			<!--修改商品分类-->
			<?php if ( !empty($common_id) ) { ?>
				<input type="hidden" name="common_id" value="<?= $common_id ?>" />
				<input type="hidden" name="action" value="edit_goods_cat" />
			<?php } ?>
			<input   type="submit"  id="button_next_step" name="button_next_step"  style="visibility: hidden;" >
		</form>
	</div>
</div>
<script type="text/javascript" src="<?=$this->view->js?>/material_selection_list.js" charset="utf-8"></script>


<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>



