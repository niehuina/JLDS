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
            .nctouch-full-mask.left {
                left: 25%;
            }

            .nctouch-main-layout-a {
                top: 0;
            }

            .secreen-layout .bottom {
                padding: 0.5rem 0;
            }

            #reset {
                background: #70696a;
            }
            .nctouch-cart-item li .goods-info dt.goods-name a{
                height: 2.0rem;
            }

            .selected-all {
                font-size: 0.6rem;
                vertical-align:middle;
            }
            .nctouch-cart-item li .edit-area {
                display: block;
                position: relative;
                width: 8rem;
                float: right;
                top:-0.5rem;
            }
            .JS-header-edit {
                position: absolute;
                z-index: 1;
                top: 0;
                right: 50px;
                text-align: right;
                line-height:1.95rem;
                font-size:0.7rem;
            }
            #batchRemove {
                float: right;
                display: none;

                text-align: center;
                color:#FFF ;
                font-size: 0.7rem;
                line-height: 2rem;
                width:20%;
                background: -webkit-linear-gradient(to right, #d92e66, #ec5a6f);
                background: linear-gradient(to right,#d92e66, #ec5a6f);
            }

            .JS-header-edit {
                display: none;
            }
            .nctouch-cart-bottom {
                bottom: 2.5rem;
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
            <div class="header-l"><a href="javascript:history.go(-1)"> <i class="back"></i> </a></div>
            <div class="header-title">
                <h1>仓库商品自用</h1>
            </div>
            <div class="header-r">
                <a href="javascript:void(0);" class="JS-edit fr text">完成</a>
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
        <a href="./views_list.html" class="browse-btn"><i></i></a>
        <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>
    </div>
    </body>
    <script type="text/html" id="home_body">
        <% var goods_list = data.items; %>
        <% if(goods_list.length >0){%>
        <% for(j=0;j<goods_list.length;j++){ %>
        <% var goods = goods_list[j]; var goods_id = goods.goods_id; %>
        <li class="cart-litemw-cnt" id="<%=goods.stock_id;%>">
            <div class="buy-li">
                <div class="goods-check">
                    <input type="checkbox" name="check" value="<%=goods.goods_id%>" onclick="cbkCheck(this)"
                           data-stock_id="<%=goods.stock_id %>" data-goods_stock="<%=goods.goods_stock %>" />
                </div>
                <div class="goods-pic">
                    <a href="<%=WapSiteUrl%>/tmpl/product_detail.html?goods_id=<%=goods.goods_id%>">
                        <img src="<%=goods.common_info.common_image%>" /> </a>
                </div>
                <dl class="goods-info">
                    <dt class="goods-name">
                        <a href="<%=WapSiteUrl%>/tmpl/product_detail.html?goods_id=<%=goods.goods_id%>"> <%=goods.common_info.common_name%> </a>
                    </dt>
                    <dd class="goods-type"></dd>
                    <span class="goods-price">库存：<em><%=goods.goods_stock%></em></span>
                    <div class="edit-area">
                        <div class="goods-subtotal">
                            <div class="value-box">
                            <span class="minus"><a href="javascript:void(0);">&nbsp;</a></span>
                            <!-- s 获取并设置限用数量 -->
                            <span>
                                <input type="number" min="0" class="buy-num buynum" name="out_num"
                                       data_max="<%=goods.goods_stock%>" data_min="0" value="0" />
                            </span>
                            <!-- e 获取并设置限用数量-->
                            <span class="add"><a href="javascript:void(0);">&nbsp;</a></span>
                            </div>
                        </div>
                    </div>
                </dl>
            </div>
        </li>
        <%}%>

        <% if (hasmore) {%>
        <li class="loading">
            <div class="spinner"><i></i></div>
            商品数据读取中...
        </li><% } %><%}else {%>
        <div class="nctouch-norecord search">
            <div class="norecord-ico"><i></i></div>
            <dl>
                <dt>没有找到任何相关信息</dt>
                <dd>选择或搜索其它商品分类/名称...</dd>
            </dl>
            <a href="javascript:history.go(-1)" class="btn">重新选择</a>
        </div><%}%>
    </script>
    <script type="text/javascript" src="../../js/libs/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/libs/template.js"></script>
    <script type="text/javascript" src="../../js/libs/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/tmpl/member/member_stock_self_use.js"></script>
    <!--    <script type="text/javascript" src="../js/tmpl/footer.js"></script>-->
    </html>
<?php
include __DIR__ . '/../../includes/footer.php';
?>