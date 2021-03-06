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
        <title>订单详情</title>
        <link rel="stylesheet" type="text/css" href="../../css/base.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
    </head>
    <body>
    <header id="header" class="fixed">
        <div class="header-wrap">
            <div class="header-l"> <a href="order_list.html"> <i class="back"></i> </a> </div>
            <div class="header-title">
                <h1>订单详情</h1>
            </div>
            <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>
        </div>
        <div class="nctouch-nav-layout">
            <div class="nctouch-nav-menu"> <span class="arrow"></span>
                <ul>
                    <li><a href="../../index.html"><i class="home"></i>首页</a></li>
                    <li><a href="../search.html"><i class="search"></i>搜索</a></li>
                    <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
                </ul>
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout mb20">
        <div class="nctouch-order-list" id="order-info-container">
            <ul>
            </ul>
        </div>
    </div>
    <footer id="footer"></footer>
    <script type="text/html" id="order-info-tmpl">
        <div class="nctouch-oredr-detail-block order-status-bg">
            <h3>交易状态</h3>
            <div class="order-state"><%=order_state_con%></div>
            <%if (order_cancel_reason != ''){%><div class="info"><%=order_cancel_reason%></div><%}%>
            <%if(order_status == 4){%>
            <div class="time fnTimeCountDown" data-end="<%=order_receiver_date%>" style="font-size: 12px">
                <i class="icon-time"></i>
                <span class="ts">剩余
             <span class="day" >00</span><strong>天</strong>
             <span class="hour">00</span><strong>小时</strong>
             <span class="mini">00</span><strong>分</strong>
             <span class="sec" >00</span><strong>秒</strong>
                 自动确认收货
             </span>
            </div>
            <% }%>
            <%if(order_status == 1){%>
            <div class="time fnTimeCountDown" data-end="<%=cancel_time%>" style="font-size: 12px">
                <i class="icon-time"></i>
                <span class="ts">剩余
             <span class="day" >00</span><strong>天</strong>
             <span class="hour">00</span><strong>小时</strong>
             <span class="mini">00</span><strong>分</strong>
             <span class="sec" >00</span><strong>秒</strong>
                 自动关闭订单
             </span>
            </div>
            <% }%>
        </div>
        <%if(order_status == 4){%>
        <div class="nctouch-oredr-detail-delivery">
            <a href="<%=WapSiteUrl%>/tmpl/member/order_delivery.html?order_id=<%=order_id%>">
			<span class="time-line">
				<i></i>
			</span>
            <div class="info">
                <p id="delivery_content"><%=order_shipping_message%></p>
                <time id="delivery_time"><%=order_shipping_time%></time>
            </div>
            <span class="arrow-r"></span>
            </a>
        </div>
        <%}%>
        <div class="nctouch-oredr-detail-block">
            <div class="nctouch-oredr-detail-add">
                <i class="icon-add"></i>
                <dl>
                    <dt>收货人：<span><%=order_receiver_name%></span><span style="float: right"><%=order_receiver_contact%></span></dt>
                    <dd>收货地址：<span class="addr-detail"><%=order_receiver_address%></span></dd>
                </dl>
            </div>
        </div>
        <%if (order_message != ''){%>
        <div class="nctouch-oredr-detail-block">
            <h3><i class="msg"></i>买家留言</h3>
            <div class="info"><%=order_message%></div>
        </div>
        <%}%>
        <div class="nctouch-oredr-detail-block ">
            <%if (order_invoice != ''){%>
            <div class="order-det-overview clearfix">
                <h3 class="fl">发票信息</h3>
                <div class="info fr"><%=order_invoice%></div>
            </div>
            <%}%>
            <%if (payment_name != ''){%>
            <div class="order-det-overview clearfix">
                <h3 class="fl">付款方式</h3>
                <div class="info fr"><%=payment_name%></div>
            </div>
            <%}%>
        </div>
        <div class="nctouch-order-item mt5">
            <div class="nctouch-order-item-head">
                <%if (shop_self_support){%>
                <a class="store"><i class="icon"></i><%=shop_name%></a>
                <%}else{%>
                <a href="<%=WapSiteUrl%>/tmpl/store.html?shop_id=<%=shop_id%>" class="store"><i class="icon"></i><%=shop_name%><i class="arrow-r"></i></a>
                <%}%>
            </div>
            <div class="nctouch-order-item-con">
                <%for(i=0; i<goods_list.length; i++){%>

                <%
                var goods_returns_setting = goods_list[i].goods_returns_setting;
                var refund_status = goods_list[i].goods_refund_status;//退货
                var return_status = goods_list[i].goods_return_status;//退货
                var goods_status = goods_list[i].order_goods_status;//状态
                var return_url1 = '';
                var return_status_con1 = '';
                //退货
                if((goods_status == 4 && refund_status > 0 && refund_status != 3) || (refund_status > 0 && goods_status == 6)){
                    return_url1 = WapSiteUrl + '/tmpl/order/member_return_info.html?refund_id='+ goods_list[i].order_refund_id;
                    return_status_con1 = goods_list[i].goods_refund_status_con;
                }
                var return_url2 = '';
                var return_status_con2 = '';
                //退款
                if((return_status > 0 && return_status != 3) || (return_status > 0 && goods_status == 6)){
                    return_url2 = WapSiteUrl + '/tmpl/order/member_refund_info.html?refund_id='+ goods_list[i].order_return_id;
                    return_status_con2 = goods_list[i].goods_return_status_con;
                }
                var returnClass = '';
                if(goods_returns_setting != 1 && goods_list[i].goods_price !=0){
                    if(refund_status == 0 && goods_status == 4){
                        returnClass = "goods-refund";
                    }
                    if(return_status == 0 && goods_status == 2){
                        returnClass = "goods-refund";
                    }
                }
                %>
                <div class="goods-block detail">
                    <a href="<%=WapSiteUrl%>/tmpl/product_detail.html?goods_id=<%=goods_list[i].goods_id%>">
                        <div class="goods-pic">
                            <img src="<%=goods_list[i].goods_image%>">
                        </div>
                        <dl class="goods-info">
                            <dt class="goods-name"><%=goods_list[i].goods_name%></dt>
                            <%
                            var order_spec_info = '';
                            if(goods_list[i].order_spec_info && goods_list[i].order_spec_info.length > 0){
                            for(var j in goods_list[i].order_spec_info){
                            order_spec_info += goods_list[i].order_spec_info[j] + '; ';
                            }
                            %>
                            <dd class="goods-type"><%=order_spec_info%></dd>
                            <%  } %>
                        </dl>
                        <div class="goods-subtotal">
                            <span class="goods-price">￥<em><%=goods_list[i].goods_price%></em></span>
                            <span class="goods-num">x<%=goods_list[i].order_goods_num%></span>
                            <div>
                            <% if(return_url1) {%>
                            <a href="<%=return_url1%>" class="ml4"><span class="goods-price"><%=return_status_con1%></span></a>
                            <% } %>
                            <% if(return_url2) {%>
                            <a href="<%=return_url2%>" class="ml4"><span class="goods-price"><%=return_status_con2%></span></a>
                            <% } %>
                            <% if(returnClass) {%>
                            <a href="javascript:void(0);" order_id="<%=order_id%>" order_goods_id="<%=goods_list[i].order_goods_id%>" class="<%=returnClass%>"><span class="goods-price">退款/退货</span></a>
                            <%}%>
                            </div>
                        </div>
                    </a>
                </div>
                <%}%>

                <div class="goods-subtotle">
                    <dl>
                        <dt>运费</dt>
                        <% if(order_shipping_fee>0) {%>
                        <dd>￥<em><%=order_shipping_fee%></em></dd>
                        <%}else{%>
                        <dd><em>免运费</em></dd>
                        <%}%>
                    </dl>
                    <dl class="t">
                        <dt>实付款<em class="col8 fz4">（含运费）</em></dt>
                        <dd>￥<em><%=order_payment_amount%></em></dd>
                    </dl>
                </div>
            </div>

        </div>
<!--        <span class="im-contact"><a href="javascript:void(0);" class="kefu"><i class="im"></i>联系客服</a></span>-->
        <% if(shop_phone){ %>
        <span class="to-call"><a href="tel:<%=shop_phone%>" tel="<%=shop_phone%>"><i class="tel"></i>拨打电话</a></span>
        <% } %>
        <div class="nctouch-oredr-detail-block mt5 bort1">
            <ul class="order-log">
                <li>订单编号：<%=order_id%></li>
                <li>创建时间：<%=order_create_time%></li>
                <% if(payment_time !== '0000-00-00 00:00:00'){%>
                <li>付款时间：<%=payment_time%></li>
                <%}%>
                <% if(order_shipping_time  !== '0000-00-00 00:00:00'){%>
                <li>发货时间：<%=order_shipping_time %></li>
                <%}%>
                <% if(order_finished_time !== '0000-00-00 00:00:00'){%>
                <li>完成时间：<%=order_finished_time%></li>
                <%}%>
            </ul>
        </div>
        <div class="nctouch-oredr-detail-bottom">
            <a href="javascript:void(0)" order_id="<%=order_id%>" shop_id="<%=shop_id%>"  class="btn againOrder" onclick="againOrderClick(this)">再来一单</a>
            <% if (order_return_status == 1 || order_refund_status == 1) {%>
            <p>退款/退货中...</p>
            <% } %>
            <% if (order_status == 1) {%>
            <a href="javascript:void(0)" order_id="<%=order_id%>" class="btn cancel-order">取消订单</a>
            <% } %>
            <% if (order_status == 4) { %>
            <a href="javascript:void(0)" order_id="<%=order_id%>" class="btn viewdelivery-order">查看物流</a>
            <%}%>
            <% if (order_status == 4 && can_confirm_order){ %>
            <a href="javascript:void(0)" order_id="<%=order_id%>" class="btn sure-order">确认收货</a>
            <% } %>
            <% if (order_status == 7) {%>
            <a href="javascript:void(0)" order_id="<%=order_id%>" class="btn delete-order">删除订单</a>
            <% } %>
            <% if (order_status == 6 && order_buyer_evaluation_status == 0) {%>
            <a href="javascript:void(0)" order_id="<%=order_id%>" class="btn evaluation-order">评价订单</a>
            <% } %>
            <% if (order_buyer_evaluation_status == 1){ %>
            <a href="javascript:void(0)" order_id="<%=order_id%>" class="btn evaluation-again-order">追加评价</a>
            <% } %>
            <%if(order_status == 1 && order_payment_amount > 0){%>
            <a href="javascript:;" onclick="payOrder('<%= payment_number %>','<%=order_id %>')" data-paySn="<%=order_id %>" class="btn check-payment">订单支付</a>
            <% } %>
        </div>
    </script>
    <script type="text/javascript" src="../../js/libs/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/libs/template.js"></script>

    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/libs/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/tmpl/order/order_detail.js"></script>
    <script type="text/javascript" src="../../js/tmpl/order/order_common.js"></script>
    <script type="text/javascript" src="../../js/libs/jquery.timeCountDown.js" ></script>
    </body>
    </html>
<?php
include __DIR__.'/../../includes/footer.php';
?>