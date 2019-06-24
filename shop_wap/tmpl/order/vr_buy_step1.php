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
    <meta name="format-detection" content="telephone=no"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="msapplication-tap-highlight" content="no" />
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />
    <title>确认订单</title>
    <link rel="stylesheet" type="text/css" href="../../css/base.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_common.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_cart.css">
</head>
<body>
<header id="header">
    <div class="header-wrap">
        <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>
        <div class="header-title">
            <h1>确认订单</h1>
        </div>
        <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>
    </div>
    <div class="nctouch-nav-layout">
        <div class="nctouch-nav-menu"> <span class="arrow"></span>
            <ul>
                <li><a href="../../index.html"><i class="home"></i>首页</a></li>
                <li><a href="../../tmpl/search.html"><i class="search"></i>搜索</a></li>
                <li><a href="../../tmpl/member/member.html"><i class="member"></i>我的商城</a></li>
                <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
            </ul>
        </div>
    </div>
</header>
<div class="nctouch-main-layout mb20">
    <div class="nctouch-cart-block posr pb5">
        <h3><i class="mobile"></i>接收方式</h3>
        <div class="tip-con">服务订单兑换码将以短信形式发送至买家手机</div>
        <input type="tel" class="inp-tel" id="buyerPhone" placeholder="请输入接收手机号码" maxlength="11"/>
    </div>
    <!--商品列表Begin-->
    <div id="goodslist_before" class="mt5">
        <div id="deposit"> </div>
    </div>
    <!--商品列表End-->

    <!--底部总金额固定层Begin-->
    <div class="nctouch-cart-bottom">
        <div class="total"><span id="online-total-wrapper"></span>
            <dl class="total-money">
                <dt>合计总金额：</dt>
                <dd>￥<em id="totalPrice"></em></dd>
            </dl>
        </div>
        <div class="check-out ok"><a href="javascript:void(0);" id="ToBuyStep2">提交订单</a></div>
    </div>
    <!--底部总金额固定层End-->
    <div class="nctouch-bottom-mask">
        <div class="nctouch-bottom-mask-bg"></div>
        <div class="nctouch-bottom-mask-block">
            <div class="nctouch-bottom-mask-tip"><i></i>点击此处返回</div>
            <div class="nctouch-bottom-mask-top">
                <p class="nctouch-cart-num">本次交易需在线支付<em id="onlineTotal">0.00</em>元</p>
                <p style="display:none" id="isPayed"></p>
                <a href="javascript:void(0);" class="nctouch-bottom-mask-close"><i></i></a> </div>
            <div class="nctouch-inp-con nctouch-inp-cart">
                <ul class="form-box" id="internalPay">
                    <p class="rpt_error_tip" style="display:none;color:red;"></p>
                    <li class="form-item" id="wrapperUseRCBpay">
                        <div class="input-box pl5">
                            <label>
                                <input type="checkbox" class="checkbox" id="useRCBpay" autocomplete="off" />
                                使用充值卡支付 <span class="power"><i></i></span> </label>
                            <p>可用余额 ￥<em id="availableRcBalance"></em></p>
                        </div>
                    </li>
                    <li class="form-item" id="wrapperUsePDpy">
                        <div class="input-box pl5">
                            <label>
                                <input type="checkbox" class="checkbox" id="usePDpy" autocomplete="off" />
                                使用预存款支付 <span class="power"><i></i></span> </label>
                            <p>可用余额 ￥<em id="availablePredeposit"></em></p>
                        </div>
                    </li>
                    <li class="form-item" id="wrapperPaymentPassword" style="display:none">
                        <div class="input-box"> <span class="txt">输入支付密码</span>
                            <input type="password" class="inp" id="paymentPassword" autocomplete="off" />
                            <span class="input-del"></span> </div>
                        <a href="../member/member_paypwd_step1.html" class="input-box-help" style="display:none"><i>i</i>尚未设置</a> </li>
                </ul>
                <div class="nctouch-pay">
                    <div class="spacing-div"><span>在线支付方式</span></div>
                    <div class="pay-sel">
                        <label style="display:none">
                            <input type="radio" name="payment_code" class="checkbox" id="alipay" autocomplete="off" />
                            <span class="alipay">支付宝</span></label>
                        <label style="display:none">
                            <input type="radio" name="payment_code" class="checkbox" id="wxpay_jsapi" autocomplete="off" />
                            <span class="wxpay">微信</span></label>
                    </div>
                </div>
                <div class="pay-btn"> <a href="javascript:void(0);" id="toPay" class="btn-l">确认支付</a> </div>
            </div>
        </div>
    </div>
</div>
<script type="text/html" id="goods_list">
    <div class="nctouch-cart-container">
        <dl class="nctouch-cart-store">
            <dt><i class="icon-store"></i><%=shop_base.shop_name%></dt>
        </dl>
        <ul class="nctouch-cart-item">
            <li class="buy-item">
                <div class="goods-pic">
                    <a href="<%=WapSiteUrl%>/tmpl/product_detail.html?goods_id=<%=goods_base.goods_id%>">
                        <img src="<%=goods_base.goods_image%>"/>
                    </a>
                </div>
                <dl class="goods-info">
                    <dt class="goods-name"><a href="<%=WapSiteUrl%>/tmpl/product_detail.html?goods_id=<%=goods_base.goods_id%>"><%=goods_base.goods_name%></a></dt>
                    <dd class="goods-type"><%=goods_base.spec%></dd>
                </dl>
                <div class="goods-subtotal">
                    <span class="goods-price">￥<em><%=goods_base.goods_price%></em></span>
                </div>
                <div class="goods-num">
                    x<em><%=goods_base.goods_num%></em>
                </div>
            </li>
        </ul>

        <div class="nctouch-cart-subtotal">
            <%if(mansong_info){%>
            <dl>
                <dt>店铺活动</dt>
                <dd>
                <%if(mansong_info.rule_discount){%>
                优惠<%=mansong_info.rule_discount%>元
                <%}%>
                <%if(mansong_info.gift_goods_id){%>
                赠品<img title="<%=mansong_info.goods_name%>" alt="<%=mansong_info.goods_name%>" src="<%=mansong_info.goods_image%>" style="width: 2rem;height: 2rem;">
                <%}%>
                </dd>
            </dl>
            <%}%>
            <div class="message">
                <input type="hidden" id="has_physical" name="has_physical" value="<%=has_physical%>" />
                <% if(has_physical == 1){ %>
                <input type="text" placeholder="店铺订单留言：请填写收货人信息" id="storeMessage">
                <% }else{ %>
                <input type="text" placeholder="店铺订单留言：" id="storeMessage">
                <% } %>
            </div>
            <div class="store-total">
                合计<span><em id="storeTotal"><%=goods_base.sumprice%></em></span>元
            </div>
        </div>
</script>

<script type="text/javascript" src="../../js/libs/zepto.min.js"></script>
<script type="text/javascript" src="../../js/libs/template.js"></script>
<script type="text/javascript" src="../../js/common.js"></script>
<script type="text/javascript" src="../../js/libs/simple-plugin.js"></script>
<script type="text/javascript" src="../../js/tmpl/order_payment_common.js"></script>
<script type="text/javascript" src="../../js/tmpl/vr_buy_step1.js"></script>
</body>
</html>
<?php 
include __DIR__.'/../../includes/footer.php';
?>