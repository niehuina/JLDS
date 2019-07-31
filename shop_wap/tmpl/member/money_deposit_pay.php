<?php
include __DIR__.'/../../includes/header.php';
?>
    <!doctype html>
    <html>

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-touch-fullscreen" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <meta name="format-detection" content="telephone=no" />
        <meta name="msapplication-tap-highlight" content="no" />
        <meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />
        <title>设置</title>
        <link rel="stylesheet" type="text/css" href="../../css/base.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
    </head>

    <body>
    <header id="header">
        <div class="header-wrap">
            <div class="header-l">
                <a href="javascript:history.go(-1);"> <i class="back"></i> </a>
            </div>
            <div class="header-title">
                <h1>充值支付</h1>
            </div>
        </div>
        <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>
        <div class="nctouch-nav-layout">
            <div class="nctouch-nav-menu"> <span class="arrow"></span>
                <ul>
                    <li><a href="../../index.html"><i class="home"></i>首页</a></li>
                    <li><a href="../search.html"><i class="search"></i>搜索</a></li>
                    <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
                </ul>
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout">
        <div class="nctouch-vr-order-codes">
            <div class="tit">
                <h3>支付单号：</h3>
                <span id="uorder"></span>
            </div>
            <div class="tit">
                <h3>支付金额：</h3>
                <span id="uorder_amount"></span>
            </div>
        </div>
        <div class="nctouch-inp-con bgf">
            <div class="nctouch-pay">
                <div class="spacing-div"><span>在线支付方式</span></div>
                <input type="hidden" name="payment_code" value="wx_native">
                <div class="pay-sel">
                    <label>
                        <span class="wxpay">微信</span>
                        <span class="sel_icon"></span>
                    </label>
                </div>
            </div>
            <input type="button" class="btn-green mt5" id="deposit_btn" value="确认付款">
        </div>
    </div>
    <footer id="footer"></footer>

    <script type="text/javascript" src="../../js/libs/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/libs/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/tmpl/member/money_deposit_pay.js"></script>
    <script type="text/javascript" src="../../js/tmpl/footer.js"></script>
    </body>
    <script>
        $(function(){
            var parms = {k:getCookie('key'),u:getCookie('id'), uorder:'<?=$_GET['uorder']?>'}
            $.post(PayCenterWapUrl + "?ctl=Pay&met=getUnionOrderInfo&typ=json", parms,
                function (result) {
                    if(result.status = '200'){
                        var uorderInfo = result.data;
                        $("#uorder").text(uorderInfo.union_order_id);
                        $("#uorder_amount").text(uorderInfo.trade_payment_amount);
                    }
                }
            );
        });
    </script>
    </html>
<?php
include __DIR__.'/../../includes/footer.php';
?>