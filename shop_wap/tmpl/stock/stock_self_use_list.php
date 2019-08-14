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
        <title>商品自用记录</title>
        <link rel="stylesheet" type="text/css" href="../../css/base.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
        <link rel="stylesheet" type="text/css" href="../../css/member_profit.css">
    </head>

    <body>
    <header id="header" class="fixed">
        <div class="header-wrap">
            <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>
            <div class="header-title">
                <h1>商品自用记录</h1>
            </div>
            <div class="header-r">
                <a href="member_stock_self_use.html" class="fr text">商品自用</a>
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout">
        <ul id="stock-list" class="nctouch-log-list">
        </ul>
    </div>
    <footer id="footer" class="bottom"></footer>
    <script type="text/html" id="list_model">
        <% if(items.length >0){%>
        <% for (var k in items) { var v = items[k]; %>
        <li data-out_id="<%=v.out_id;%>" class="borb1">
            <a href="stock_self_use_details.html?out_order_id=<%=v.out_order_id;%>">
                <dl>
                    <dt>
                        <h4>自用时间：<%=v.out_time;%></h4>
                    </dt>
                </dl>
                <div class="money">
                    <h4>自用商品数量:<%=v.good_count;%></h4>
                </div>
            </a>
        </li>
        <%}%>
        <%} else{%>
        <div class="nctouch-norecord signin" style="top: 50%;">
            <div class="norecord-ico"><i></i></div>
            <dl>
                <dt>您还没有商品自用记录</dt>
            </dl>
        </div>
        <%}%>
    </script>

    <script type="text/javascript" src="../../js/libs/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/libs/template.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/libs/ncscroll-load.js"></script>
    <script type="text/javascript" src="../../js/tmpl/member/stock_self_use_list.js"></script>
    <script>

    </script>
    </body>

    </html>
<?php
include __DIR__ . '/../../includes/footer.php';
?>