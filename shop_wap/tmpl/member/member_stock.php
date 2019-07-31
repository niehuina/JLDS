<?php
include __DIR__.'/../../includes/header.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1">
    <title>我的商城</title>
    <link rel="stylesheet" type="text/css" href="../../css/base.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">

    <style>
        .nctouch-main-layout{
            margin-top: 0px;
        }
    </style>
</head>
<body>
<header id="header" class="transparent">
    <div class="header-wrap">
        <div class="header-l">
            <a href="member.html">
                <i class="back back2"></i>
            </a>
        </div>
        <div class="header-title">
            <h1>我的商城</h1>
        </div>
        <div class="header-r">
            <a href="member_setting.html"><img src="../../../images/new/setting.png" width="20px" height="20px" ></a>
            <a href="chat_list.html"><img src="../../images/message_b.png" width="20px" height="20px"></a>
        </div>
    </div>
    <div class="nctouch-nav-layout">
        <div class="nctouch-nav-menu"> <span class="arrow"></span>
            <ul>
                <li><a href="../../index.html"><i class="home"></i>首页</a></li>
                <li><a href="../search.html"><i class="search"></i>搜索</a></li>
                <li><a href="../tmpl/cart_list.html"><i class="cart"></i>购物车<sup style="display: inline;"></sup></a></li>
                <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
            </ul>
        </div>
    </div>
</header>
<div class="scroller-body">
    <div class="scroller-box">
        <div class="member-top member-top1"></div>
        <div class="nctouch-main-layout">
            <ul class="nctouch-default-list mt5">
                <li>
                    <a href="member_stock_goods.html">
                        <h4>仓库商品</h4>
                        <span class="arrow-r"></span></a>
                    </a>
                </li>
            </ul>
            <ul class="nctouch-default-list mt5">
                <li>
                    <a href="member_stock_check.html">
                        <h4>库存盘点</h4>
                        <span class="arrow-r"></span></a>
                    </a>
                </li>
            </ul>
            <ul class="nctouch-default-list mt5">
                <li>
                    <a href="member_stock_self_use.html">
                        <h4>商品自用</h4>
                        <span class="arrow-r"></span></a>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<script type="text/javascript" src="../../js/libs/zepto.min.js"></script>
<script type="text/javascript" src="../../js/common.js"></script>
<script type="text/javascript" src="../../js/libs/template.js"></script>
<script type="text/javascript" src="../../js/tmpl/member/member_stock.js"></script>
<script type="text/javascript" src="../../js/tmpl/footer.js"></script>
</body>
</html>
<?php
include __DIR__.'/../../includes/footer.php';
?>