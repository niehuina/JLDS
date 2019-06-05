<!DOCTYPE HTML>
<html>
<head>
    <script type="text/javascript" src="<?=$this->view->js_com?>/jquery.js" charset="utf-8"></script>
    <style>
        .eject_con {
            background-color: #FFF;
            overflow: hidden;
            font: 12px/20px "Hiragino Sans GB","Microsoft Yahei",arial,宋体,"Helvetica Neue",Helvetica,STHeiTi,sans-serif;
        }
        .eject_con dl{
            clear:left;
            font-size: 12px;
            line-height: 32px;
            vertical-align: top;
            padding-left: 10px;
        }
        .eject_con dt,.eject_con dd{
            float:left;
        }

        .eject_con dl dt {
            text-align: right;
            width: 20%;
        }

        .eject_con dl dd {
            width: 75%;
            color: #BBBBC5;
            padding: 0 10px;
            -webkit-line-clamp:2;
            margin-inline-start: unset;
        }
    </style>
</head>
<body>

<div class="dialog_content" style="margin: 0px; padding: 0px;">
    <div class="eject_con">
        <div id="warning"></div>
        <form method="post" id="set_alarm_form" onsubmit="ajaxpost('set_alarm_form', '', '', 'onerror');return false;" >
            <input type="hidden" name="goods_id" value="">
            <dl style="text-align: left;">
                <dt><?=__('商品名称：')?></dt>
                <dd><span class="goods_name"><?= $goods_stock['goods_name']?></span></dd>
            </dl>
            <dl style="text-align: left;">
                <dt><?=__('当前商品库存：')?></dt>
                <dd><span class="goods_stock"><?= $goods_stock['goods_stock']?></span></dd>
            </dl>
            <dl style="text-align: left;">
                <dt><?=__('库存预警值：')?></dt>
                <dd><input type="text" name="alarm_stock" value="<?= $goods_stock['alarm_stock']?>" /></dd>
            </dl>
        </form>
    </div>

</div>

</body>
</html>

<script>
    api = frameElement.api;
    goods_id = api.data.goods_id ;

    $(function () {
        $('input[name="goods_id"]').val(goods_id);

    })
</script>