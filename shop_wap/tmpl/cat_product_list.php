
<?php
include __DIR__.'/../includes/header.php';

if($_GET['qr']){
    setcookie('is_app_guest',1,time()+86400*366);
    $_COOKIE['is_app_guest'] = 1;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="format-detection" content="telephone=no"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="msapplication-tap-highlight" content="no" />
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />
    <title><?php echo __('首页');?></title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/new-style.css">
<!--    <link rel="stylesheet" href="../css/index-swiper.css">-->
<!--    <link rel="stylesheet" href="../css/swiper.min.css">-->
    <script src="../js/libs/jquery.js"></script>
    <!-- <script src="js/new-common.js"></script> -->
</head>

<body>
<header id="header" class="fixed">
    <div class="header-wrap">
        <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>
        <div class="header-inp clearfix"> <i class="icon"></i> <span class="search-input" id="keyword">请输入关键字</span> </div>
        <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>
    </div>
    <div class="nctouch-nav-layout">
        <div class="nctouch-nav-menu"> <span class="arrow"></span>
            <ul>
                <li><a href="../index.html"><i class="home"></i>首页</a></li>
                <li><a href="../tmpl/cart_list.html"><i class="cart"></i>购物车<sup></sup></a></li>
                <li><a href="../tmpl/member/member.html"><i class="member"></i>我的商城</a></li>
                <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
            </ul>
        </div>
    </div>
</header>
<div class="nctouch-home-layout mt20" id="main-container2">
</div>
<div><img id="cat_banner" src="img/G4.jpg" /></div>
<div id="cat-product" class="mb20"></div>
</body>

<script type="text/html" id="goods">
    <% for (var k in data) {
        var cat = data[k];
        %>
        <div class="bgf bort1 borb1 mt5">
            <% if (cat.cat_name) { %>
            <div class="common-tit tc borb1">
                <h4> —&nbsp;&nbsp;<%=cat.cat_name %>&nbsp;&nbsp;—</h4>
                <a href="product_list.html?cat_id=<%= cat.cat_id %>" class="more"><i class="more"></i></a>
            </div>
            <% } %>
            <ul class="new-goods clearfix wrap">
                <% for (var i in cat.rec_goods) {
                    var goods = cat.rec_goods[i];
                %>
                <li>
                    <a href="product_detail.html?goods_id=<%= goods.goods_id %>">
                        <div class="overhide">
                            <div class="table">
                                <span class="img-area">
                                    <img src="<%= goods.common_image %>" alt=""></span>
                            </div>
                        </div>
                        <h5><%= goods.common_name %></h5>
                        <b>￥<%= goods.common_price %></b>
                    </a>
                </li>
                <% } %>
            </ul>
        </div>
    <% } %>
</script>

<script type="text/javascript" src="../js/libs/zepto.min.js"></script>
<script type="text/javascript" src="../js/libs/template.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/libs/iscroll.js"></script>
<script>
    $(function () {
        $("#cat_banner").css("width", document.body.clientWidth);
        $("#header").on("click", ".header-inp", function ()
        {
            location.href = WapSiteUrl + "/tmpl/search.html"
        });
        $.getJSON(ApiUrl + "/index.php?ctl=Goods_Cat&met=goodsCatRecomendList&typ=json&cat_parent_id=0", function (t)
        {
            console.info(t);
            var r = t.data;
            // r.WapSiteUrl = WapSiteUrl;
            var a = template.render("goods", t);
            $("#cat-product").html(a);
            // e = new IScroll("#main-container2", {mouseWheel: true, click: true})
        });
    })
</script>
<?php
include __DIR__.'/../includes/footer_menu.php';
//?>
</html>
<?php
include __DIR__.'/../includes/footer.php';
?>
