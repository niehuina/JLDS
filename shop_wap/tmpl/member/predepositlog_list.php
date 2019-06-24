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
    <meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="msapplication-tap-highlight" content="no" />
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />
    <title>账户余额</title>
    <link rel="stylesheet" type="text/css" href="../../css/base.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
</head>

<body>
    <header id="header" class="fixed">
        <div class="header-wrap">
            <div class="header-l"><a href="member.html"><i class="back"></i></a></div>
            <div class="header-title">
                <h1>预存款账户</h1>
            </div>
            <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>
        </div>
        <div class="nctouch-nav-layout">
            <div class="nctouch-nav-menu"> <span class="arrow"></span>
                <ul>
                    <li><a href="../../index.html"><i class="home"></i>首页</a></li>
                    <li><a href="../search.html"><i class="search"></i>搜索</a></li>
                    <li><a href="member.html"><i class="member"></i>我的商城</a><sup></sup></li>
                    <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
                </ul>
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout">
        <div id="pd_count" class="nctouch-asset-info"></div>
        <div id="fixed_nav" class="nctouch-single-nav">
            <ul id="filtrate_ul" class="w33h">
                <li class="selected"><a href="javascript:void(0);">账户余额</a></li>
                <li><a href="pdrecharge_list.html">充值明细</a></li>
                <li><a href="pdcashlist.html">余额提现</a></li>
            </ul>
        </div>
        <ul id="pointsloglist" class="nctouch-log-list">
        </ul>
    </div>
    <div class="fix-block-r">
        <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>
    </div>
    <footer id="footer" class="bottom"></footer>
    <script type="text/html" id="pd_count_model">
        <div class="container pre">
            <i class="icon"></i>
            <dl>
                <dt>预存款余额</dt>
                <dd><em><%=data[0];%></em></dd>
            </dl>
        </div>
    </script>
    <script type="text/html" id="list_model">
        <% if(list.length >0){%>
            <% for (var k in list) { var v = list[k]; %>
                <li>
                    <div class="detail">
                        <%=v.lg_desc;%>
                    </div>
                    <% if(v.lg_av_amount >0){%>
                        <div class="money add">+
                            <%=v.lg_av_amount;%>
                        </div>
                        <%}else{%>
                            <div class="money reduce">
                                <%=v.lg_av_amount;%>
                            </div>
                            <%}%>
                                <time class="date">
                                    <%=v.lg_add_time_text;%>
                                </time>
                </li>
                <%}%>
                    <li class="loading">
                        <div class="spinner"><i></i></div>数据读取中</li>
                    <%}else {%>
                        <div class="nctouch-norecord pdre">
                            <div class="norecord-ico"><i></i></div>
                            <dl>
                                <dt>您尚无预存款收支信息</dt>
                                <dd>使用商城预存款结算更方便</dd>
                            </dl>
                        </div>
                        <%}%>
    </script>
    
    <script type="text/javascript" src="../../js/libs/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/libs/template.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/zepto.waypoints.js"></script>
    <script type="text/javascript" src="../../js/ncscroll-load.js"></script>
    <script>
        $(function () {
            var key = getCookie('key');
            if (!key) {
                window.location.href = WapSiteUrl + '/tmpl/member/login.html';
                return;
            }

            //渲染list
            var load_class = new ncScrollLoad();
            load_class.loadInit({
                'url': ApiUrl + '/index.php?act=member_fund&op=predepositlog',
                'getparam': {
                    'k': key,
                    'u':getCookie('id')
                },
                'tmplid': 'list_model',
                'containerobj': $("#pointsloglist"),
                'iIntervalId': true
            });

            //获取预存款余额
            $.getJSON(ApiUrl + '/index.php?ctl=Buyer_Index&met=getUserInfoMoney&typ=json', {
                'k': key,
                'u':getCookie('id'),
                'fields': 'predepoit'
            }, function (result) {
                var html = template.render('pd_count_model', result);
                $("#pd_count").html(html);

                $('#fixed_nav').waypoint(function () {
                    $('#fixed_nav').toggleClass('fixed');
                }, {
                    offset: '50'
                });
            });
        });
    </script>
</body>

</html>
<?php 
include __DIR__.'/../../includes/footer.php';
?>