<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

    <link href="<?= $this->view->css ?>/seller_center.css?ver=<?= VER ?>" rel="stylesheet">
    <link href="<?= $this->view->css ?>/base.css?ver=<?= VER ?>" rel="stylesheet">
    <link href="<?= $this->view->css ?>/ui.min.css?ver=20140430" rel="stylesheet">

    <style>
        .dis_flag{display:inline-block;width:40px;background:red;color:#FFF;font-size:12px;text-align:center;}
        .input_text{
            border: grey solid 1px;
        }

        .search-form{
            font-size: 14px;
        }
    </style>
    <table class="search-form">
        <tbody>
        <tr>
            <td width="w100">
                <p>盘点时间：<?=($stock_check['check_date_time']);?></p>
            </td>
            <td>
                <p>盘点商品数量：<?=($goods_count);?></p>
            </td>
            <td>
                <div id="search_form" class="search_form_reset">
                    <input type="hidden" id="check_id" value="<?=($check_id);?>">
                    <input class="text w150" type="text" id="goods_key" value="<?=($goods_key?$goods_key:'');?>" placeholder="<?=__('请输入商品名称')?>"/>
                    <a class="button refresh" href="index.php?ctl=Seller_Stock_Order&met=stock_check&typ=e"><i
                                class="iconfont icon-huanyipi"></i></a>
                    <a class="button btn_search_check" href="javascript:void(0);">
                        <i class="iconfont icon-btnsearch"></i><?= __('搜索') ?>
                    </a>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="search fn-clear">
    </div>
    <div class="goods-category-list fn-clear clearfix">
        <div>
            <div class="grid-wrap">
                <table id="checks_grid">
                </table>
                <div id="checks_page"></div>
            </div>
        </div>
    </div>

    <script src="<?= $this->view->js_com ?>/plugins/jquery.jqgrid.js"></script>
    <script type="text/javascript" src="<?=$this->view->js?>/stock_check_detail.js" charset="utf-8"></script>

    <script>
        $('.tabmenu > ul').find('li:lt(4)').remove();
        var href = window.location.href;
        $('.tabmenu > ul > li > a').attr('href',href);
    </script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>