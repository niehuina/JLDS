<!DOCTYPE HTML>
<html>
<head>
    <script type="text/javascript" src="<?=$this->view->js_com?>/jquery.js" charset="utf-8"></script>
    <link href="<?= $this->view->css ?>/ui.min.css?ver=20140430" rel="stylesheet">
    <style>
        .eject_con {
            background-color: #FFF;
            overflow: hidden;
            font: 12px/20px "Hiragino Sans GB","Microsoft Yahei",arial,宋体,"Helvetica Neue",Helvetica,STHeiTi,sans-serif;
        }

        .eject_con dl {
            line-height: 20px;
            display: block;
            clear: both;
            overflow: hidden;
        }

        .title {
            text-align: center;
            color: #FF892A;
        }

        .eject_con dl dd {
            font-size: 12px;
            vertical-align: top;
            letter-spacing: normal;
            word-spacing: normal;
            display: inline-block;
            width: 69%;
            padding: 0 76px;
            zoom: 1;
            margin-left: 0px;
            color: #BBBBC5;
        }

        .eject_con dl dt {
            font-size: 12px;
            line-height: 32px;
            vertical-align: top;
            letter-spacing: normal;
            word-spacing: normal;
            text-align: right;
            display: inline-block;
            width: 100%;
            padding: 10px 1% 10px 0;
            margin: 0;
            zoom: 1;
            text-align:center;
        }

        .eject_con span.num {
            font-weight: 600;
            color: #390;
        }
    </style>
</head>
<body>

<div class="dialog_content" style="margin: 0px; padding: 0px;">
    <div class="eject_con">
        <div id="warning"></div>
        <form method="post" id="choose_goods_form" onsubmit="ajaxpost('choose_goods_form', '', '', 'onerror');return false;" >
            <input type="hidden" name="order_id" value="" />
            <dl style="text-align: center;">
                <dt><?=__('订单编号：')?><span class="num"></span></dt>
            </dl>
            <dl>
            <div class="goods-category-list fn-clear clearfix">
                <div>
                    <div class="grid-wrap">
                        <table id="goods_grid">
                        </table>
                        <div id="goods_page"></div>
                    </div>
                </div>
            </div>
            </dl>
        </form>
    </div>

</div>
</body>
</html>

<script src="<?= $this->view->js_com ?>/plugins/jquery.jqgrid.js"></script>
<script>
    api = frameElement.api;
    order_id = api.data.order_id;
    select_goods_list = api.data.select_goods_list;

    $(function () {

        $('span.num').html(order_id);
        $('input[name="order_id"]').val(order_id);

    })

</script>
<script type="text/javascript" src="<?=$this->view->js?>/stock_orderGoods.js" charset="utf-8"></script>