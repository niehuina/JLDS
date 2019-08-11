
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
    <script src="../js/libs/jquery.js"></script>
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
<div id="product_detail_spec_html" class="nctouch-bottom-mask"></div>

<div><img id="cat_banner" src="img/jiu.jpg" /></div>
<div id="cat-product" class="mb20"></div>
</body>

<script type="text/html" id="goods">
    <% for (var k in data) {
        var cat = data[k];
        %>
        <div class="bgf bort1 borb1 mt5 goods_list">
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
                        <div class="overhide bor0">
                            <div class="table img-area">
                                <img src="<%= goods.common_image %>" alt="">
                            </div>
                        </div>
                        <div>
                            <h5><%= goods.common_name %></h5>
                            <b>￥<%= goods.common_price %></b>
<!--                            <i class="icon fr"></i>-->
                        </div>
                    </a>
                </li>
                <% } %>
            </ul>
        </div>
    <% } %>
</script>


<script type="text/html" id="product_detail_sepc">
    <div class="nctouch-bottom-mask-bg"></div>
    <div class="nctouch-bottom-mask-block">
        <div class="nctouch-bottom-mask-tip"></div>
        <div class="nctouch-bottom-mask-top goods-options-info">
            <div class="goods-pic">
                <img src="<%=common_image[0]%>"/>
            </div>
            <dl>
                <dt><%= goods_info.goods_name; %></dt>
                <dd class="goods-price">
                    <% if (goods_info.promotion_type && goods_info.promotion_is_start == 1 ) {
                    var promo;
                    switch (goods_info.promotion_type)
                    {
                    case 'groupbuy':
                    promo = '团购';
                    break;
                    case 'xianshi':
                    promo = '限时折扣';
                    break;
                    }
                    %>
                    ￥<em><%=goods_info.promotion_price%></em>
                    <span class="activity">
                        <% if (promo) { %>
                            <%= promo %>
                        <% } %>
                        </span>
                    <% } else { %>
                    ￥<em><%=goods_info.goods_price%></em>
                    <% } %>
                </dd>
                <span class="goods-storage">库存：<%=goods_info.goods_stock%>件</span>
            </dl>
            <a href="javascript:void(0);" class="nctouch-bottom-mask-close"><i></i></a>
        </div>
        <div class="goods-option-value clearfix">购买数量
            <div class="value-box">
                <span class="minus">
                    <a href="javascript:void(0);">&nbsp;</a>
                </span>
                <span>
                    <% if(buyer_limit != 0)
                    {
                    if(buyer_limit >= goods_info.goods_stock){
                    data_max = goods_info.goods_stock;
                    }else{
                    data_max = buyer_limit;
                    }

                    }
                    else
                    {
                    data_max = goods_info.goods_stock;
                    }
                    if(goods_info.lower_limit > 1)
                    {
                    data_min = goods_info.lower_limit;
                    promotion = 1;
                    }
                    else
                    {
                    data_min = 1;
                    promotion = 0;
                    }
                    %>
                    <input type="text" pattern="[0-9]*" class="buy-num" promotion="<%=promotion%>"
                           data-max="<%=data_max%>" data-min="<%=data_min%>" id="buynum" value="<%=data_min%>"/>
                </span>
                <span class="add">
                    <a href="javascript:void(0);">&nbsp;</a>
                </span>
                <% if(buyer_limit != 0) { %>
                <div style="font-size: 0.5rem;text-align: center;">限购<%= buyer_limit; %>件</div>
                <% } %>
            </div>
        </div>
        <div class="goods-option-foot">
            <!--<div class="otreh-handle">
                <a href="javascript:void(0);" class="kefu">
                    <i></i>
                    <p>客服</p>
                </a>
                <a href="../tmpl/cart_list.html" class="cart">
                    <i></i>
                    <p>购物车</p>
                    <span id="cart_count1"></span>
                </a>
            </div>-->
            <div class="only-two-handle buy-handle <%if(!goods_hair_info.if_store || goods_info.goods_storage == 0){%>no-buy<%}%>">
                <% if (goods_info.cart == '1') { %>
                <a href="javascript:void(0);" class="add-cart" id="add-cart">加入购物车</a>
                <% } %>
                <a href="javascript:void(0);" style="float: left;"
                   class="buy-now <%if(goods_info.cart != '1'){%>wp100<%}%>" id="buy-now">立即购买</a>
            </div>
        </div>
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
            var banner_img = t.data.banner_img.banner_image;
            if(banner_img){
                $("#cat_banner").attr("src", banner_img);
            }
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
