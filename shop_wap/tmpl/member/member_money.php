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
        <title>余额</title>
        <link rel="stylesheet" type="text/css" href="../../css/base.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
    </head>

    <body>
    <header id="header-member">
        <div class="header-wrap">
            <div class="header-l">
                <a href="member.html"> <i class="back"></i> </a>
            </div>
            <div class="header-title">
                <h1>余额</h1>
            </div>
        </div>
        <div class="header-r">
            <a href="moneylog_list.html" class="text">明细</a>
        </div>
    </header>
    <div class="nctouch-main-layout">
        <div class="nctouch-money">
            <div class="money-title">账户余额</div>
            <div class="money-content mt5">
                <em>¥</em>
                <span id="member_money">0.00</span>
            </div>
        </div>
        <div class="error-tips"></div>
        <div class="nctouch-inp-con tc">
            <div class="form-btn ok">
                <button class="btn mt5 wp50" id="depositBtn" value="deposit">充值</button>
                <button class="btn mt10 wp50" id="withdrawBtn" value="withdraw">提现</button>
            </div>
        </div>
    </div>
    <footer id="footer"></footer>

    <script type="text/javascript" src="../../js/libs/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/tmpl/member/member_money.js"></script>
    <script type="text/javascript" src="../../js/tmpl/footer.js"></script>
    </body>

    </html>
<?php
include __DIR__.'/../../includes/footer.php';
?>