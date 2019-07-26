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
        <form method="post" id="order_confirm_form" onsubmit="ajaxpost('order_confirm_form', '', '', 'onerror');return false;" >
            <input type="hidden" name="order_id" value="">
            <input type="hidden" name="shipping_code" value="">
            <dl class="title">
                <?=__('您是否确已收到以下订单的物流下的货品？')?>
            </dl>
            <dl style="text-align: center;">
                <dt><?=__('订单编号：')?><span class="num"></span></dt>
                <dt><?=__('物流号：')?><span class="code"></span>
                    <a class="shipping_desc" style="position:relative;">
                        <i class="iconfont icon-icowaitproduct rel_top2"></i><?=__('物流信息')?><?php if($is_show) echo $i+1; ?>
                        <div style="display: none;" id="info_order_id" class="prompt-01"></div>
                    </a>
                </dt>
            </dl>
            <dl>
                <dd>
                    <?=__('请注意，如果您尚未收到货品请不要点击“确认”，大部分被骗案件都是由于提前确认付款被骗的，请谨慎操作！')?>
                </dd>
            </dl>
        </form>
    </div>

</div>

</body>
</html>

<script>
    api = frameElement.api;
    order_id = api.data.order_id ;
    shipping_express_id = api.data.shipping_express_id;
    shipping_code = api.data.shipping_code;

    $(function () {

        $('span.num').html(order_id);
        $('input[name="order_id"]').val(order_id);

        $('span.code').html(shipping_code);
        $('input[name="shipping_code"]').val(shipping_code);

        $(".shipping_desc").mouseover(function () {
            show_logistic(order_id, shipping_express_id, shipping_code);
        }).mouseout(function () {
            hide_logistic(order_id);
        })
    })

    window.hide_logistic = function (order_id)
    {
        $("#info_order_id").hide();
        $("#info_order_id").html("");
    }

    window.show_logistic = function (order_id,express_id,shipping_code)
    {
        $("#info_order_id").show();
        var BASE_URL = "<?=Yf_Registry::get('base_url')?>";
        $.post(BASE_URL + "/shop/api/logistic.php",{"order_id":order_id,"express_id":express_id,"shipping_code":shipping_code} ,function(da) {

            if(da)
            {
                $("#info_order_id").html(da);
            }
            else
            {
                $("#info_order_id").html('<div class="error_msg"><?=__('接口出现异常')?></div>');
            }

        })
    }
</script>