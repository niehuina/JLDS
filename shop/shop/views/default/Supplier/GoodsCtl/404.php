<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>
<link rel="stylesheet" type="text/css" href="./shop/static/default/css/404.css" media="screen" />
<div id="da-wrapper" class="fluid">
	<div id="da-content">
		<div class="da-container clearfix">
			<div id="da-error-wrapper">
				<div id="da-error-pin"></div>
				<div id="da-error-code">
					<span><?=__('错误')?></span> </div>
				<h1 class="da-error-heading"><?=isset($_REQUEST['msg']) ? "<?=__('".$_REQUEST['msg']."')?>" : "<?=__('抱歉')?>".'，'."<?=__('该商品已下架或者该店铺已关闭')?>".'！'?></h1>
				<p> <a href="./index.php"><?=__('点击进入首页')?></a></p>
			</div>
		</div>
	</div>
</div>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>

