<?php
include __DIR__ . '/../includes/header.php';
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
        <title>商品分类</title>
        <link rel="stylesheet" href="../css/footer.css">
        <link rel="stylesheet" type="text/css" href="../css/base.css">
        <link rel="stylesheet" type="text/css" href="../css/nctouch_categroy.css">
        <link rel="stylesheet" type="text/css" href="../css/iconfont/iconfont.css">


    </head>
    <body>
    <header id="header">
        <div class="header-wrap">
            <div class="header-l"><a href="javascript:history.go(-1)"> <i class="back"></i> </a></div>
            <div class="header-inp clearfix"><i class="icon"></i> <span class="search-input" id="keyword">请输入关键字</span>
            </div>
            <div class="header-r"><a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a>
            </div>
        </div>
        <div class="nctouch-nav-layout">
            <div class="nctouch-nav-menu"><span class="arrow"></span>
                <ul>
                    <li><a href="../index.html"><i class="home"></i>首页</a></li>
                    <li><a href="../tmpl/cart_list.html"><i class="cart"></i>购物车<sup></sup></a></li>
                    <li><a href="../tmpl/member/member.html"><i class="member"></i>我的商城</a></li>
                    <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
                </ul>
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout">
<!--        <div class="categroy-cnt" id="categroy-cnt"></div>-->
        <div class="categroy-rgt" id="categroy-rgt"></div>
        <div class="pre-loading">
            <div class="pre-block">
                <div class="spinner"><i></i></div>
                分类数据读取中...
            </div>
        </div>
    </div>

    </body>
    <script type="text/html" id="cat-one">
        <dl class="brands-recommend">
            <% for(var i = 0;i<items.length;i++){ %>
            <dd>
                <a href="<%= WapSiteUrl %>/tmpl/product_list.html?brand_id=<%= items[i].brand_id %>">
                    <img src="<%= items[i].brand_pic %>">
                    <p><%= items[i].brand_name %></p>
                </a>
            </dd>
            <% } %>
        </dl>
    </script>
    <!-- 底部 -->
    <?php
    include __DIR__ . '/../includes/footer_menu.php';
    ?>
    <script type="text/javascript" src="../js/libs/zepto.min.js"></script>
    <script type="text/javascript" src="../js/libs/template.js"></script>
    <script type="text/javascript" src="../js/common.js"></script>
    <script type="text/javascript" src="../js/libs/iscroll.js"></script>
    <script type="text/javascript" src="../js/categroy-frist-list.js"></script>

    </body></html>
<?php
include __DIR__ . '/../includes/footer.php';
?>