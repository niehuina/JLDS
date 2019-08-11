<?php
include __DIR__ . '/../../includes/header.php';
?>
    <!doctype html>
    <html>

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="apple-touch-fullscreen" content="yes"/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
        <meta name="format-detection" content="telephone=no"/>
        <meta name="msapplication-tap-highlight" content="no"/>
        <meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1"/>
        <title>账单明细</title>
        <link rel="stylesheet" type="text/css" href="../../css/base.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
        <link rel="stylesheet" type="text/css" href="../../css/member_profit.css">
    </head>

    <body>
    <header id="header-member" class="fixed write">
        <div class="header-wrap">
            <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>
            <div class="header-title">
                <h1>股金分红</h1>
            </div>
        </div>
        <div class="nctouch-asset-info">
            <div class="container pre">
                <i class="icon"></i>
                <dl>
                    <dt>已分红金额</dt>
                    <dd>¥<em id="shares_profit"></em></dd>
                </dl>
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout mt50">
        <ul id="profit-list" class="nctouch-log-list">
        </ul>
    </div>
    <footer id="footer" class="bottom"></footer>
    <script type="text/html" id="list_model">
        <% if(items.length >0){%>
            <% for (var k in items) { var v = items[k]; %>
            <li data-order-id="<%=v.order_id;%>" class="deposit">
            <dl>
                <dt>
                    <%=v.record_title;%>
                </dt>
                <dd>分红时间：<%=v.record_time;%></dd>
            </dl>
            <div class="money add">
                <em>¥</em><%=v.record_money;%>
            </div>
            <%}%>
        <%} else{%>
        <div class="nctouch-norecord signin" style="top: 50%;">
            <div class="norecord-ico"><i></i></div>
            <dl>
                <dt>您还没有股金分红记录</dt>
            </dl>
        </div>
        <%}%>
    </script>

    <script type="text/javascript" src="../../js/libs/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/libs/template.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/libs/ncscroll-load.js"></script>
    <script type="text/javascript" src="../../js/tmpl/member/profit_shares.js"></script>
    <script>

    </script>
    </body>

    </html>
<?php
include __DIR__ . '/../../includes/footer.php';
?>