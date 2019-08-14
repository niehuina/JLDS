<?php
include __DIR__ . '/../../includes/header.php';
?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="Author" contect="U2FsdGVkX1+liZRYkVWAWC6HsmKNJKZKIr5plAJdZUSg1A==">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-touch-fullscreen" content="yes">
        <meta name="format-detection" content="telephone=no">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="format-detection" content="telephone=no">
        <meta name="msapplication-tap-highlight" content="no">
        <meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1">
        <title>个人仓储管理</title>
        <link rel="stylesheet" type="text/css" href="../../css/base.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_products_list.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_common.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_cart.css">
        <style type="text/css">
            .secreen-layout .bottom {
                padding: 0.5rem 0;
            }

            .nctouch-cart-item li .goods-info{
                width: 76%;
            }

            .nctouch-cart-item li .goods-info dt.goods-name a{
                height: 2rem;
                max-height: 2.0rem;
                line-height: 1rem;
            }

            .nctouch-cart-item li .edit-area {
                display: block;
                position: relative;
                width: 6rem !important;
                float: right;
                top: -0.3rem;
            }
            .edit-area .value-box span{
                width: 50%;
            }
            .value-box span .buy-num, .edit-area .value-box span .buy-num{
                width: 100%;
            }
            .edit-area .value-box .minus, .edit-area .value-box .add{
                width: 25%;
            }
            .edit-area .goods-subtotal{
                width: 100%;
            }
        </style>
    </head>
    <body>
    <header id="header" class="nctouch-product-header fixed">
        <div class="header-wrap">
            <div class="header-l"><a href="member_stock.html"> <i class="back"></i> </a></div>
            <div class="header-title">
                <h1>库存盘点</h1>
            </div>
            <div class="header-r">
                <a href="javascript:void(0);" class="JS-edit fr text">提交</a>
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout mb20">
        <div class="nctouch-order-search">
            <span class="ser-area "><i class="icon-ser"></i>
                <input type="text" autocomplete="on" maxlength="50" placeholder="输入商品名称"
                       name="goods_key" id="goods_key" oninput="writeClear($(this));" >
                <span class="input-del"></span>
            </span>
            <input type="button" id="search_btn" value="搜索">
        </div>
        <div id="product_list" class="list">
            <ul class="nctouch-cart-item"></ul>
        </div>
    </div>
    <div class="fix-block-r">
        <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>
    </div>
    </body>
    <script type="text/html" id="home_body">
        <% var goods_list = data.items; %>
        <% if(data.records >0){%>
        <%for(j=0;j<goods_list.length;j++){%>
        <% var goods = goods_list[j];
            if(goods_list[j].common_info){
                var goods = goods_list[j].common_info;
                goods.goods_stock = goods_list[j].goods_stock;
                goods.goods_id = goods_list[j].goods_id;
                goods.stock_id = goods_list[j].stock_id;
            }
        %>
        <li class="cart-litemw-cnt" id="<%=goods.stock_id;%>">
            <div class="buy-li">
                <div class="goods-pic">
                    <a href="<%=WapSiteUrl%>/tmpl/product_detail.html?goods_id=<%=goods.goods_id%>">
                        <img src="<%=goods.common_image%>" /> </a>
                </div>
                <dl class="goods-info">
                    <dt class="goods-name">
                        <a href="<%=WapSiteUrl%>/tmpl/product_detail.html?goods_id=<%=goods.goods_id%>">
                            <%=goods.common_name%>
                        </a>
                    </dt>
                    <span class="goods-price">库存：<em><%=goods.goods_stock%></em></span>
                    <div class="edit-area">
                        <div class="goods-subtotal">
                            <div class="value-box">
                                <span class="minus"><a href="javascript:reduceNum('<%=goods.stock_id;%>');">&nbsp;</a></span>
                                <!-- s 获取并设置限用数量 -->
                                <span>
                                <input type="text" min="0" class="buy-num buynum" name="check_num"
                                       data-good_name="<%=goods.common_name%>" onblur="formatNum(this);"
                                       data-max="<%=goods.goods_stock%>" data-min="0" value="<%=goods.goods_stock%>" />
                                </span>
                                <!-- e 获取并设置限用数量-->
                                <span class="add"><a href="javascript:addNum('<%=goods.stock_id;%>');">&nbsp;</a></span>
                            </div>
                        </div>
                    </div>
                </dl>
            </div>
        </li>
        <%}%><%}%>
    </script>
    <script type="text/javascript" src="../../js/libs/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/libs/template.js"></script>
    <script type="text/javascript" src="../../js/libs/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/tmpl/member/member_stock_check.js"></script>
    <!--    <script type="text/javascript" src="../js/tmpl/footer.js"></script>-->
    </html>
<?php
include __DIR__ . '/../../includes/footer.php';
?>