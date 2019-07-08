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
        <title>所有商品</title>
        <link rel="stylesheet" type="text/css" href="../../css/base.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_products_list.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_common.css">
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
            .list .goods-secrch-list .goods-sold{
                width: 33%;
            }
        </style>
    </head>
    <body>
    <header id="header" class="nctouch-product-header fixed">
        <div class="header-wrap">
            <div class="header-l"><a href="javascript:history.go(-1)"> <i class="back"></i> </a></div>
            <div class="header-title">
                <h1>个人仓库商品</h1>
            </div>
            <div class="header-r">
                <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a>
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
            <ul class="goods-secrch-list"></ul>
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
        <%for(j=0;j<goods_list.length;j++){%>
        <% var goods = goods_list[j];
           var goods_id = goods.goods_id;
        %>
        <li class="goods-item" goods_id="<%=goods.goods_id;%>">
            <span class="goods-pic">
                <a href="product_detail.html?goods_id=<%=goods.goods_id;%>">
                    <img src="<%=goods.common_info.common_image;%>"/>
                </a>
            </span>
            <dl class="goods-info">
                <dt class="goods-name">
                    <a href="product_detail.html?goods_id=<%=goods.goods_id;%>">
                        <h4><%=goods.common_info.common_name;%></h4>
                        <h6><%=goods.common_info.common_jingle;%></h6>
                    </a>
                </dt>
                <dd class="goods-assist">
                    <a href="product_detail.html?goods_id=<%=goods.goods_id;%>">
                        <span class="goods-sold">销量
                            <em><%=goods.common_info.common_salenum;%></em>
                        </span>
                        <span class="goods-sold">评论
                            <em><%=goods.common_info.common_evaluate;%></em>
                        </span>
                        <span class="goods-sold">库存
                            <em><%=goods.goods_stock;%></em>
                        </span>
                    </a>
                </dd>
            </dl>
        </li>
        <%}%><%}%>

        <% if (hasmore) {%>
        <li class="loading">
            <div class="spinner"><i></i></div>
            商品数据读取中...
        </li><%}else {%>
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
    <script type="text/javascript" src="../../js/tmpl/member/member_stock_goods.js"></script>
    <!--    <script type="text/javascript" src="../js/tmpl/footer.js"></script>-->
    </html>
<?php
include __DIR__ . '/../../includes/footer.php';
?>