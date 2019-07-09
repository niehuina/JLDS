<?php
include __DIR__ . '/../../includes/header.php';
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
        <title></title>
        <link rel="stylesheet" type="text/css" href="../../css/base.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
        <style>
            .nctouch-log-list li dl{
                padding-left: 0px;
            }
            .nctouch-log-list li dt{
                font-size: 0.6rem;
            }
            .nctouch-log-list li .money{
                font-size: 0.6rem;
            }
        </style>
    </head>
    <body>
    <header id="header">
        <div class="header-wrap">
            <div class="header-l">
                <a href="member_profit.html"> <i class="back"></i> </a>
            </div>
            <div class="header-title">
                <h1>备货差额返利</h1>
            </div>
        </div>
        <div class="header-r">
        </div>
    </header>
    <div class="nctouch-main-layout">
        <div class="nctouch-asset-info">
            <div class="container pre">
                <i class="icon"></i>
                <dl>
                    <dt>已返还差额</dt>
                    <dd>¥<em id="profit_money"></em></dd>
                </dl>
            </div>
        </div>
        <div id="fixed_nav" class="nctouch-single-nav">
            <ul id="filtrate_ul" class="w20h">
                <li class="selected"><a href="javascript:void(0);" data-status="0">未结算</a></li>
                <li><a href="javascript:void(0);" data-status="1">已结算</a></li>
            </ul>
        </div>
        <ul id="profit-list" class="nctouch-log-list">
        </ul>
    </div>
    <script type="text/html" id="list_model">
        <% var records=items; %>
        <% if(records.length >0){%>
        <% for (var k in records) {
        var profit = records[k];
        %>
        <li data-order-id="<%=profit.stock_order_id;%>">
            <dl>
                <dt>
                    <%=profit.stock_order_id;%>
                </dt>
                <dd><%=profit.order_create_text;%>&nbsp;&nbsp;<%=profit.order_settlement_text;%></dd>
            </dl>
            <div class="money add">
                <em>¥</em><%=profit.order_commission;%>
            </div>
            <time class="date">
                订单金额¥<%=profit.order_payment_amount_vip;%>
            </time>
        </li>
        <%}%>
        <li class="loading">
            <div class="spinner"><i></i></div>
            数据读取中
        </li>
        <%}else{%>
        <div class="nctouch-norecord signin" style="top: 50%;">
            <div class="norecord-ico"><i></i></div>
            <dl>
                <dt>您还没有备货订单返利</dt>
            </dl>
        </div>
        <%}%>
    </script>
    <script type="text/javascript" src="../../js/libs/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/libs/template.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/libs/ncscroll-load.js"></script>
    <script type="text/javascript" src="../../js/tmpl/member/profit_stock_order.js"></script>
    <script type="text/javascript" src="../../js/tmpl/footer.js"></script>

    </body>
    </html>
<?php
include __DIR__ . '/../../includes/footer.php';
?>