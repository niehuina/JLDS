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

    </head>

    <body>
    <header id="header" class="fixed">
        <div class="header-wrap">
            <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>
            <div class="header-title">
                <h1>账单明细</h1>
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout">
        <div class="nctouch-asset-info">
            <div class="container pre">
                <i class="icon"></i>
                <dl>
                    <dt>我的余额</dt>
                    <dd><em id="moneyCount"></em></dd>
                </dl>
            </div>
        </div>
        <div id="fixed_nav" class="nctouch-single-nav">
            <ul id="filtrate_ul" class="w50h">
                <li class="selected"><a href="javascript:void(0);" data-type="1">收入</a></li>
                <li><a href="javascript:void(0);" data-type="2">支出</a></li>
            </ul>
        </div>
        <ul id="moneyloglist" class="nctouch-log-list">
        </ul>
    </div>
    <footer id="footer" class="bottom"></footer>
    <script type="text/html" id="list_model">
        <% var records=items; %>
        <% if(records.length >0){%>
        <% for (var k in records) { var v = records[k];
            var url = getPayUrl(v.order_id);
        %>
        <li data-order-id="<%=v.order_id;%>" <% if(v.trade_type_id == 3 && v.act == "pay"){ %> class="deposit"<%}%>>
            <% if(type_id == 1){%>
            <img src="../../images/new/member_monelist_add.png">
            <dl>
                <dt>
                    <%=v.record_title;%>
                </dt>
                <dd><%=v.record_status_con;%>
                    <% if(v.trade_type_id == 3 && v.act == "pay"){ %>
                        <a href="<%=url;%>">支付</a>
                    <%}%>
                </dd>
            </dl>
            <div class="money add">+
                <%=v.record_money;%>
            </div>
            <%}else if(type_id == 2){%>
            <img src="../../images/new/member_monelist_reduce.png">
            <dl>
                <dt>
                    <%=v.record_title;%>
                </dt>
                <dd><%=v.record_status_con;%></dd>
            </dl>
            <div class="money reduce"><%if(v.record_money > 0){%>-<%}%>
                <%=v.record_money;%>
            </div>
            <%}%>
            <time class="date">
                <%=v.record_time;%>
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
                <dt>您还没有账单记录</dt>
            </dl>
        </div>
        <%}%>
    </script>

    <script type="text/javascript" src="../../js/libs/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/libs/template.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/libs/ncscroll-load.js"></script>
    <script type="text/javascript" src="../../js/tmpl/member/moneylog_list.js"></script>
    </body>

    </html>
<?php
include __DIR__ . '/../../includes/footer.php';
?>