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
        <title>库存盘点明细</title>
        <link rel="stylesheet" type="text/css" href="../../css/base.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
        <link rel="stylesheet" type="text/css" href="../../css/member_profit.css">
    </head>

    <body>
    <header id="header" class="fixed">
        <div class="header-wrap">
            <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>
            <div class="header-title">
                <h1>库存盘点明细</h1>
            </div>
            <div class="header-r">
                <a href="javascript:void(0);" class="fr text goods_count"></a>
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
        <li data-goods-id="<%=v.goods_id;%>">
            <a href="../product_detail.html?goods_id=<%=v.goods_id;%>">
                <dl>
                    <dt>
                        <%=v.goods_name;%>
                    </dt>
                    <dd>商品库存:<%=v.goods_stock;%></dd>
                </dl>
                <%if(v.check_status == 1){%>
                    <div class="money add">
                        <h4>状态：盘盈</h4>
                    </div>
                <%}else if(v.check_status == -1){%>
                    <div class="money reduce">
                        <h4>状态：盘亏</h4>
                    </div>
                <%}else{%>
                    <div class="money">
                        <h4>状态：正常</h4>
                    </div>
                <%}%>
                <time class="date">
                    盘点库存:<%=v.real_goods_stock;%>
                </time>
            </a>
        </li>
        <%}%>
        <%} else{%>
        <div class="nctouch-norecord signin" style="top: 50%;">
            <div class="norecord-ico"><i></i></div>
            <dl>
                <dt>没有商品</dt>
            </dl>
        </div>
        <%}%>
    </script>

    <script type="text/javascript" src="../../js/libs/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/libs/template.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/libs/ncscroll-load.js"></script>
    <script type="text/javascript" src="../../js/tmpl/member/stock_check_details.js"></script>
    <script>

    </script>
    </body>

    </html>
<?php
include __DIR__ . '/../../includes/footer.php';
?>