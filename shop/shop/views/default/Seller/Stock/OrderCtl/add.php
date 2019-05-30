<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

<link href="<?= $this->view->css ?>/ui.min.css?ver=20140430" rel="stylesheet">
<script src="<?=$this->view->js_com?>/plugins/jquery.jqgrid.js" ></script>
<div class="goods-category">
    <ol class="step_order fn-clear add-goods-step clearfix">
        <li class="cur">
            <i class="icon iconfont icon-icoordermsg bbc_seller_color"></i>
            <h6 class="bbc_seller_color"><?=__('STEP 1')?></h6>

            <h2 class="bbc_seller_color"><?=__('选择高级合伙人')?></h2>
            <i class="arrow iconfont icon-btnrightarrow"></i>
        </li>
        <li>
            <i class="icon iconfont icon-shangjiaruzhushenqing"></i>
            <h6><?=__('STEP 2')?></h6>

            <h2><?=__('选择商品')?></h2>
            <i class="arrow iconfont icon-btnrightarrow"></i>
        </li>
        <li>
            <i class="icon iconfont icon-icoduigou"></i>
            <h6><?=__('STEP 3')?></h6>

            <h2><?=__('完成')?></h2>
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
        <form method="post" action="<?=Yf_Registry::get('url')?>?ctl=Seller_Stock_Order&met=add&typ=e">
            <input type="hidden" name="order_id" value="<?php if ( !empty($order_data) ) { echo $order_data['stock_order_id']; } ?>"/>
            <input type="hidden" name="action" value="<?php if ( !empty($order_data) ) { echo 'edit'; } ?>"/>
            <input type="hidden" id="user_id" name="user_id" />
            <input type="hidden" id="shop_id" name="shop_id" />
            <input type="submit" class="button  bbc_seller_submit_btns" id="button_next_step" style="display: none;">
        </form>
    </div>
</div>
<script type="text/javascript" src="<?=$this->view->js?>/order_add_step1.js" charset="utf-8"></script>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>



