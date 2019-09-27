<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

    <link href="<?= $this->view->css ?>/seller_center.css?ver=<?= VER ?>" rel="stylesheet">
    <link href="<?= $this->view->css ?>/base.css?ver=<?= VER ?>" rel="stylesheet">
    <link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css?ver=<?=VER?>" rel="stylesheet">
    <link href="<?= $this->view->css ?>/ui.min.css?ver=20140430" rel="stylesheet">

    <style>
        .dis_flag{display:inline-block;width:40px;background:red;color:#FFF;font-size:12px;text-align:center;}
        .input_text{
            border: grey solid 1px;
        }
    </style>

    <table class="search-form">
        <tbody>
        <tr>
            <td class="w100">
                <a class="button btn_stock_check" style="float: left;" href="index.php?ctl=Seller_Stock_Order&met=stock_self_use&typ=e"><i class="iconfont icon-querendingdan"></i><?= __('商品自用') ?></a>
            </td>
            <td class="w240">
                <input type="text" class="text w70 hasDatepicker heigh" placeholder="<?=__('起始时间')?>" name="query_start_date" id="query_start_date" value="<?=$_GET['query_start_date']?>" readonly="readonly">
                <label class="add-on"><i class="iconfont icon-rili"></i></label>
                <span class="rili_ge">–</span>
                <input type="text" class="text w70 hasDatepicker heigh" placeholder="<?=__('结束时间')?>" name="query_end_date" id="query_end_date" value="<?=$_GET['query_end_date']?>" readonly="readonly">
                <label class="add-on"><i class="iconfont icon-rili"></i></label>
            </td>
            <td class="fr">
                <a class="button refresh" href="index.php?ctl=Seller_Stock_Order&met=stock_check_log&typ=e">
                    <i class="iconfont icon-huanyipi"></i>
                </a>
                <a class="button btn_search_check" href="javascript:void(0);">
                    <i class="iconfont icon-btnsearch"></i><?= __('搜索') ?>
                </a>
            </td>
        </tr>
    </table>
    <div class="goods-category-list fn-clear clearfix">
        <div>
            <div class="grid-wrap">
                <table id="checks_grid">
                </table>
                <div id="checks_page"></div>
            </div>
        </div>
    </div>

    <script>
        $('.tabmenu > ul').find('li:gt(2)').remove();
    </script>
    <script src="<?= $this->view->js_com ?>/plugins/jquery.jqgrid.js"></script>
    <script type="text/javascript" src="<?=$this->view->js?>/stock_self_use_list.js" charset="utf-8"></script>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>