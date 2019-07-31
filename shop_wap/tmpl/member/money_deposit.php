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
        <title>账户充值</title>
        <link rel="stylesheet" type="text/css" href="../../css/base.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_cart.css">
    </head>

    <body>
    <header id="header">
        <div class="header-wrap">
            <div class="header-l">
                <a href="member_money.html"> <i class="back"></i> </a>
            </div>
            <div class="header-title">
                <h1>账户充值</h1>
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
        <div class="nctouch-inp-con">
            <ul class="form-box">
                <input type="hidden" id="money" value="money">
                <div id="moneyDiv">
                    <li class="form-item">
                        <h4>充值金额</h4>
                        <div class="input-box">
                            <input type="text" class="inp bgf" name="deposit_amount" id="deposit_amount"
                                   autocomplete="off" oninput="writeClear($(this));"/>
                            <span class="input-del"></span>
                        </div>
                    </li>
                    <ul id="invoice-list" class="nctouch-sel-list bort1 borb1">
                    </ul>
                </div>
                <div id="cardDiv" style="display: none;">
                    <li class="form-item">
                        <h4>购物卡号</h4>
                        <div class="input-box">
                            <input type="text" class="inp bgf" name="card_code" id="card_code"
                                   autocomplete="off" oninput="writeClear($(this));"  onblur="checkPassword();"/>
                            <span class="input-del"></span>
                        </div>
                    </li>
                    <li class="form-item">
                        <h4>购物卡密码</h4>
                        <div class="input-box">
                            <input type="text" class="inp bgf" name="card_password" id="card_password"
                                   autocomplete="off" oninput="writeClear($(this));" onblur="checkPassword();"/>
                            <span class="input-del"></span>
                        </div>
                    </li>
                </div>
            </ul>
            <div class="error-tips"></div>
            <input type="button" class="btn-green mt5" id="deposit_btn" value="下一步">
            <input type="button" class="btn-green mt5" id="deposit_card_btn" value="确认信息并充值" disabled="disabled"
                style="display:none;">
        </div>
    </div>
    <footer id="footer"></footer>

    <script type="text/javascript" src="../../js/libs/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/libs/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/tmpl/member/money_deposit.js"></script>
    <script type="text/javascript" src="../../js/tmpl/footer.js"></script>
    </body>

    </html>
<?php
include __DIR__.'/../../includes/footer.php';
?>