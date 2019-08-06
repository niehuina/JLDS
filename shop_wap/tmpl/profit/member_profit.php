<?php
include __DIR__ . '/../../includes/header.php';
?>
    <!doctype html>
    <html>

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="Author" contect="U2FsdGVkX1+liZRYkVWAWC6HsmKNJKZKIr5plAJdZUSg1A==">
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-touch-fullscreen" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
        <meta name="format-detection" content="telephone=no" />
        <meta name="msapplication-tap-highlight" content="no" />
        <meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />
        <title>我的钱包</title>
        <link rel="stylesheet" type="text/css" href="../../css/base.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
        <style>
            .member-info{width: 8rem;left:60%;margin: -4.5rem 0 0 -6rem;}
            .wallet-money{width: 60px;height: 37px; margin: 0 auto;}
            .wallet-money .icon {display: inline-block; width: 100%; height: 100%;overflow: hidden;}
            .wallet-money .icon-bg {background: url(../../images/bbc-bg21.png) no-repeat center;}
            .icon > .icon {
                position: relative;
                left: -60px;
                border-right: 60px solid transparent;
                -webkit-filter: drop-shadow(60px 0);
                filter: drop-shadow(60px 0);
                -webkit-filter: drop-shadow(#FFF 60px 0px);
            }
            /*.wallet-money img{height: 100%; width: 100%;-webkit-filter: drop-shadow(#FFF 20px 0px); filter: drop-shadow(#FFF 20px 0px);}*/
            .wallet-list ul{font-size: 0; height: 5rem; background: #fff;}
            .wallet-list ul li{display: inline-block; width: 49%; float: left; text-align: center; padding: 1.5rem 0;}
            .wallet-list ul li a{display: block;}
            .wallet-list ul li i{display: inline-block; vertical-align: top; height: 1rem; width:1rem;}
            .wallet-list ul li p{font-size: 0.65rem; line-height: 1rem; color: #666; height: 1rem;}
        </style>
    </head>

    <body>
    <header id="header-member" class="fixed write">
        <div class="header-wrap">
            <div class="header-l">
                <a href="../member/member.html"><i class="back"></i> </a>
            </div>
            <div class="header-title">
                <h1>下线返利</h1>
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout">
        <div class="member-top">
            <div class="member-info">
                <div class="wallet-money mt5">
                    <i class="icon"><i class="icon icon-bg"></i></i>
                </div>
                <div class="user-name">
                    <span class="mt10">
                        <strong>已返利</strong>
                         <em>¥</em>
                        <b id="profit">0.00</b>
                    </span>
                </div>
            </div>
        </div>
        <ul class="nctouch-default-list mt5 v2 v3 v4">
            <li>
                <a href="profit_buyer_order.html">
                    <h4>订单差价返利</h4>
                    <span class="arrow-r"></span></a>
                </a>
            </li>
        </ul>
        <ul class="nctouch-default-list mt5 v3 v4">
            <li>
                <a href="profit_order_rabate.html">
                    <h4>订单提成返利</h4>
                    <span class="arrow-r"></span></a>
                </a>
            </li>
        </ul>
        <ul class="nctouch-default-list mt5 v3 v4 shares">
            <li>
                <a href="profit_shares.html">
                    <h4>股金年度分红</h4>
                    <span class="arrow-r"></span></a>
                </a>
            </li>
        </ul>
        <ul class="nctouch-default-list mt5 v4 stock">
            <li>
                <a href="profit_stock_order.html">
                    <h4>备货差价返利</h4>
                    <span class="arrow-r"></span></a>
                </a>
            </li>
        </ul>
    </div>

    <script type="text/javascript" src="../../js/libs/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/libs/template.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/tmpl/footer.js"></script>
    <script type="text/javascript" src="../../js/tmpl/member/member_profit.js"></script>
    </body>

    </html>
<?php
include __DIR__ . '/../../includes/footer.php';
?>