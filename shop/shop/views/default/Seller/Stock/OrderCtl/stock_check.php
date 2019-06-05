<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

    <style>
        .form-style dl dt, .form-style dl dd {
            padding: 0;
        }

        .goods .form-style dl dt, .goods .form-style dl dd {
            padding: 0;
        }

        .goods .form-style dl dd {
            padding: 0 12px;
        }

        a.ncbtn-mini {
            line-height: 16px;
            height: 16px;
            padding: 3px 7px;
            border-radius: 2px;
        }
    </style>

    <link href="<?= $this->view->css ?>/ui.min.css?ver=20140430" rel="stylesheet">

    <style>
        .dis_flag{display:inline-block;width:40px;background:red;color:#FFF;font-size:12px;text-align:center;}
        .input_text{
            border: grey solid 1px;
        }
    </style>
    <div class="search fn-clear">
        <div id="search_form" class="search_form_reset">
            <input class="text w150" type="text" id="goods_key" value="<?=($goods_key?$goods_key:'');?>" placeholder="<?=__('请输入商品名称')?>"/>
            <a class="button refresh" href="index.php?ctl=Seller_Stock_Order&met=stock_check&typ=e"><i
                        class="iconfont icon-huanyipi"></i></a>
            <a class="button btn_search_goods" href="javascript:void(0);">
                <i class="iconfont icon-btnsearch"></i><?= __('搜索') ?>
            </a>
        </div>
    </div>
    <script type="text/javascript">
        $(".search").on("click", "a.button", function ()
        {
            $("#search_form").submit();
        });
    </script>
    <form id="form" method="post" action="index.php?ctl=Seller_Stock_Order&met=stock_check&typ=json" onsubmit="return recordPageAll();">
        <div class="goods-category-list fn-clear clearfix">
            <div>
                <div class="grid-wrap">
                    <table id="goods_grid">
                    </table>
                    <div id="goods_page"></div>
                </div>
            </div>
        </div>
        <input type="hidden" id="real_stock_list" name="real_stock_list" />
        <input type="submit" class="button bbc_seller_submit_btns" id="button_submit" value="<?=__('提交')?>">
    </form>

    <script src="<?= $this->view->js_com ?>/plugins/jquery.jqgrid.js"></script>
    <script type="text/javascript" src="<?=$this->view->js?>/stock_check.js" charset="utf-8"></script>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>