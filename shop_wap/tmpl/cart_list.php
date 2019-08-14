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
        <title>购物车</title>
        <link rel="stylesheet" type="text/css" href="../css/base.css">
        <link rel="stylesheet" type="text/css" href="../css/nctouch_common.css">
        <link rel="stylesheet" type="text/css" href="../css/nctouch_cart.css">
        <style type="text/css">
            .selected-all {
                font-size: 0.6rem;
                vertical-align:middle;
            }
            .nctouch-cart-item li .edit-area {
                display: block;
                position: absolute;
                right: 0;
                top: 0.5rem;
                width: 12rem;
                height:73%;
            }
            #batchRemove {
                float: right;
                display: none;
                
                text-align: center;
                color:#FFF ;
                font-size: 0.7rem;
                line-height: 2rem;
                width:20%;
                background: -webkit-linear-gradient(to right, #06c1ae, #1aafa0);
                background: linear-gradient(to right,#06c1ae, #1aafa0);
            }

            .JS-header-edit {
                display: none;
            }
            .nctouch-cart-bottom { 
                bottom: 2.5rem;

            }


            .nctouch-cart-item li .goods-info dt.goods-name a{
                height: 1rem;
                max-height: 2.0rem;
                line-height: 1rem;
            }

            .selected-all {
                font-size: 0.6rem;
                vertical-align:middle;
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
    <header id="header" class="fixed">
        <div class="header-wrap">
            <div class="header-l">
                <a href="javascript:history.go(-1)"> <i class="back"></i> </a>
            </div>
            <div class="header-title">
                <h1>购物车</h1>
            </div>
            <div class="header-r">
                <a class="JS-header-edit fr text">管理</a>
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout">
        <div id="cart-list-wp" class="mb50 mallCart"></div>
    </div>
    <footer id="footer" class="bottom"></footer>
    <div class="pre-loading hide">
        <div class="pre-block">
            <div class="spinner"><i></i></div>
            购物车数据读取中...
        </div>
    </div>

    <script id="cart-list" type="text/html">
        <% if(cart_list.length >0){%><% for (var i = 0;i
        <cart_list.length;i++){%>
        <div class="nctouch-cart-container">
            <dl class="nctouch-cart-store">
<!--                <dl class="nctouch-cart-store">-->
<!--                    <dt>-->
<!--                        <span class="store-check">-->
<!--                            <input class="store_checkbox" type="checkbox" checked>-->
<!--                        </span>-->
<!--                        <i class="icon-store"></i> <%=cart_list[i].shop_name%>-->
<!--                        <span class="JS-edit fr">编辑</span>-->
<!--                    </dt>-->
<!--                </dl>-->

                <% if (cart_list[i].free_freight) { %>
                <dd class="store-activity">
                    <em>免运费</em> <span><%=cart_list[i].free_freight%></span>
                </dd>
                <% } %> <% if (cart_list[i].mansong_info && !isEmpty(cart_list[i].mansong_info)) { %>
                <dd class="store-activity">
                    <em>满即送</em> <%var mansong = cart_list[i].mansong_info%>
                    <span><%if(mansong.rule_discount){%>店铺优惠<%=mansong.rule_discount%>。<%}%>
                        <%if(mansong.goods_name){%>赠品：<%=mansong.goods_name%><%if(!isEmpty(mansong.goods_image)){%>
                        <img src="<%=mansong.goods_image%>" /><%}%><%}%></span>
                    <i class="arrow-down"></i>
                </dd>
                <% } %>
            </dl>
            <ul class="nctouch-cart-item">
                <% if (cart_list[i].goods) { %> <% for (var j=0; j < cart_list[i].goods.length; j++) {var goods = cart_list[i].goods[j];%>
                <li cart_id="<%=goods.cart_id%>" class="cart-litemw-cnt">
                    <div class="buy-li">
                        <div class="goods-check">
                            <input type="checkbox" data-num="<%= goods.goods_num %>" checked name="cart_id" value="<%=goods.cart_id%>" <% if(goods.IsHaveBuy){ %> disabled="" title="您已达限购数量" <% } %> />
                        </div>
                        <div class="goods-pic">
                            <a href="<%=WapSiteUrl%>/tmpl/product_detail.html?goods_id=<%=goods.goods_id%>">
                                <img src="<%=goods.common_base.common_image%>" /> </a>
                        </div>
                        <dl class="goods-info">
                            <dt class="goods-name">
                                <a class="word-ellipsis"
                                   href="<%=WapSiteUrl%>/tmpl/product_detail.html?goods_id=<%=goods.goods_id%>">
                                    <%=goods.common_base.common_name%>
                                </a>
                            </dt>
                            <dd class="goods-type"><%=goods.goods_base.spec_val_str%></dd>
                            <span class="goods-price">￥<em><%=goods.now_price%></em></span>
                            <!--  <% if(goods.old_price > 0){ %>
                             <span class="old-price">￥<s><%=goods.old_price%></s></span>
                             <% } %> -->
                            <div class="edit-area">
                                <div class="goods-subtotal">
                                    <div class="value-box">
                                        <span class="minus"><a href="javascript:void(0);">&nbsp;</a></span>
                                        <!-- s 获取并设置限用数量 -->
                                        <span>
                                            <% if(goods.buy_limit > 0 && !goodsIsHaveBuy)
                                            {
                                                data_max = goods.buy_limit;
                                            }
                                            else
                                            {
                                                data_max = goods.goods_base.goods_stock;
                                            }
                                            if(goods.goods_base.lower_limit)
                                            {
                                                data_min = goods.goods_base.lower_limit;
                                                promotion = 1;
                                            }
                                            else
                                            {
                                                data_min = 1;
                                                promotion = 0;
                                            }
                                            %>
                                            <input type="text" min="0" class="buy-num buynum" name="out_num"
                                               data_max="<%=data_max%>" data_min="<%=data_min%>" value="<%=goods.goods_num%>" />
                                        </span>
                                        <!-- e 获取并设置限用数量-->
                                        <span class="add"><a href="javascript:void(0);">&nbsp;</a></span>
                                    </div>
                                </div>
                            </div>
                        </dl>

                    </div>
                </li>
                <% } %> <% } %>
            </ul>
            <% if (cart_list[i].shop_voucher) { %>
            <div class="nctouch-bottom-mask nctouch-bottom-mask<%=i%>">
                <div class="nctouch-bottom-mask-bg"></div>
                <div class="nctouch-bottom-mask-block">
                    <div class="nctouch-bottom-mask-tip"><i></i>点击此处返回</div>
                    <div class="nctouch-bottom-mask-top store-voucher">
                        <i class="icon-store"></i> <%=cart_list[i].shop_name%>&nbsp;&nbsp;领取店铺代金券
                        <a href="javascript:void(0);" class="nctouch-bottom-mask-close"><i></i></a>
                    </div>
                    <div class="nctouch-bottom-mask-rolling nctouch-bottom-mask-rolling<%=i%>">
                        <div class="nctouch-bottom-mask-con">
                            <ul class="nctouch-voucher-list">
                                <% for (var j=0; j < cart_list[i].shop_voucher.length; j++) { var voucher = cart_list[i].shop_voucher[j];%>
                                <li>
                                    <dl>
                                        <dt class="money">面额<em><%=voucher.voucher_t_price%></em>元</dt>
                                        <dd class="need">需消费<%=voucher.voucher_t_limit%>使用</dd>
                                        <dd class="time">至<%=voucher.voucher_t_end_date%>前使用</dd>
                                    </dl>
                                    <a href="javascript:void(0);" class="btn" data-tid=<%=voucher.voucher_t_id%>>领取</a>
                                </li>
                                <% } %>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <% } %>
        </div><%}%><% if (check_out === true) {%>
        <div class="nctouch-cart-bottom">
            <div class="all-check">
                <input class="all_checkbox" type="checkbox" checked> <span class="selected-all">全选</span>
            </div>
            <div class="total">
                <dl class="total-money">
                    <dt>合计总金额：</dt>
                    <dd>￥<em><%=sum%></em></dd>
                </dl>
            </div>
            <div id="batchRemove">删除</div>
            <div class="check-out ok">
                <a href="javascript:void(0)" id="productNumber">去付款(<%=number%>)</a>
            </div>
        </div><% } else { %>
        <div class="nctouch-cart-bottom no-login">
            <div class="cart-nologin-tip">结算购物车中的商品，需先登录商城</div>
            <div class="cart-nologin-btn"><a href="../tmpl/member/login.html" class="btn">登录</a>
                <a href="../tmpl/member/register.html" class="btn">注册</a>
            </div>
        </div><% } %><%}else{%>
        <div class="nctouch-norecord cart">
            <div class="norecord-ico"><i></i></div>
            <dl>
                <dt>您的购物车还是空的</dt>
                <dd>去挑一些中意的商品吧</dd>
            </dl>
            <a href="<%=WapSiteUrl%>" class="btn">随便逛逛</a>
        </div><%}%>
    </script>

    <script id="cart-list1" type="text/html">
        <% if(cart_list.length >0){%><%
        for (var i = 0;i<cart_list.length;i++){%>
        <div class="nctouch-cart-container">
<!--            <dl class="nctouch-cart-store">-->
<!--                <dt>-->
<!--                    <span class="store-check">-->
<!--					    <input class="store_checkbox" type="checkbox" checked>-->
<!--					</span>-->
<!--                    <i class="icon-store"></i> <%=cart_list[i].shop_name%>-->
<!--                    <span class="JS-edit fr">编辑</span>-->
<!--                </dt>-->
<!--            </dl>-->
            <ul class="nctouch-cart-item">
                <% if (cart_list[i].goods) { %>
                <% for (var j=0; j<cart_list[i].goods.length; j++) {
                    var goods = cart_list[i].goods[j];%>
                <li cart_id="<%=goods.cart_id%>" class="cart-litemw-cnt">
                    <div class="buy-li">
                        <div class="goods-check">
                            <input type="checkbox" checked name="cart_id" data-num="<%= goods.goods_num %>" value="<%=goods.cart_id%>" />
                        </div>
                        <div class="goods-pic">
                            <a href="<%=WapSiteUrl%>/tmpl/product_detail.html?goods_id=<%=goods.goods_id%>">
                                <img src="<%=goods.goods_image_url%>" /> </a>
                        </div>
                        <dl class="goods-info">
                            <dt class="goods-name">
                                <a class="dpm" href="<%=WapSiteUrl%>/tmpl/product_detail.html?goods_id=<%=goods.goods_id%>">
                                    <%=goods.goods_name%>
                                </a>
                            </dt>
                            <dd class="goods-type"><%=goods.goods_spec%></dd>
                            <span class="goods-price">￥<em><%=goods.goods_price%></em></span>
                            <!--  <% if(goods.old_price > 0){ %>
                             <span class="old-price">￥<s><%=goods.old_price%></s></span>
                             <% } %> -->
                            <div class="edit-area">
                                <div class="goods-subtotal">
                                    <div class="value-box">
                                        <span class="minus"><a href="javascript:void(0);">&nbsp;</a></span>
                                        <!-- s 获取并设置限用数量 -->
                                        <span>
                                            <% if(goods.buy_limit > 0 && !goodsIsHaveBuy)
                                            {
                                                data_max = goods.buy_limit;
                                            }
                                            else
                                            {
                                                data_max = goods.goods_stock;
                                            }
                                            if(goods.lower_limit)
                                            {
                                                data_min = goods.lower_limit;
                                                promotion = 1;
                                            }
                                            else
                                            {
                                                data_min = 1;
                                                promotion = 0;
                                            }
                                            %>
                                            <input type="text" min="0" class="buy-num buynum" name="out_num"
                                                   data_max="<%=data_max%>" data_min="<%=data_min%>" value="<%=goods.goods_num%>" />
                                        </span>
                                        <!-- e 获取并设置限用数量-->
                                        <span class="add"><a href="javascript:void(0);">&nbsp;</a></span>
                                    </div>
                                </div>
                            </div>
                        </dl>
                    </div>
                </li>
                <% } %> <% } %>
            </ul>
        </div><%}%><% if (check_out === true) {%>
        <div class="nctouch-cart-bottom">
            <div class="all-check">
                <input class="all_checkbox" type="checkbox" checked> <span class="selected-all">全选</span>
            </div>
            <div class="total">
                <dl class="total-money">
                    <dt>合计总金额：</dt>
                    <dd>￥<em><%=sum%></em></dd>
                </dl>
            </div>
            <div class="check-out ok">
                <a href="javascript:void(0)">去付款(<%=number%>)</a>
            </div>
        </div><% } else { %>
        <div class="nctouch-cart-bottom no-login">
            <div class="cart-nologin-tip">结算购物车中的商品，需先登录商城</div>
            <div class="cart-nologin-btn"><a href="../tmpl/member/login.html" class="btn">登录</a>
                <a href="../tmpl/member/register.html" class="btn">注册</a>
            </div>
        </div><% } %><%}else{%>
        <div class="nctouch-norecord cart">
            <div class="norecord-ico"><i></i></div>
            <dl>
                <dt>您的购物车还是空的</dt>
                <dd>去挑一些中意的商品吧</dd>
            </dl>
            <a href="<%=WapSiteUrl%>" class="btn">随便逛逛</a>
        </div><%}%>
    </script>

    <!-- 底部 -->
    <?php 
            include __DIR__.'/../includes/footer_menu.php';
    ?>

    <script type="text/javascript" src="../js/libs/zepto.min.js"></script>

    <script type="text/javascript" src="../js/libs/template.js"></script>
    <script type="text/javascript" src="../js/common.js"></script>
    <script type="text/javascript" src="../js/libs/simple-plugin.js"></script>
    <script type="text/javascript" src="../js/cart-list.js"></script>

    </body>
    </html>
<?php
include __DIR__ . '/../includes/footer.php';
?>