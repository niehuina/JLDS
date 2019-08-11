<?php
include __DIR__.'/../../includes/header.php';
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
            padding-left: 60px;
        }
    </style>
</head>
<body>
<header id="header" class="fixed">
    <div class="header-wrap">
        <div class="header-l">
            <a href="member.html"> <i class="back"></i> </a>
        </div>
        <div class="header-title">
            <h1>下线用户</h1>
        </div>
    </div>
    <div class="header-r">
    </div>
    <div id="fixed_nav" class="nctouch-single-nav">
        <ul id="filtrate_ul" class="w20h">
            <li class="selected"><a href="javascript:void(0);" data-level="1">普通消费者</a></li>
            <li><a href="javascript:void(0);" data-level="2">会员</a></li>
            <li><a href="javascript:void(0);" data-level="3">合伙人</a></li>
        </ul>
    </div>
</header>
<div class="nctouch-main-layout mt40">
    <ul id="user-list" class="nctouch-log-list">
    </ul>
</div>
<script type="text/html" id="list_model">
    <% var records=items; %>
    <% if(records.length >0){%>
    <% for (var k in records) {
    var user = records[k];
    var user_mobile = getPhoneStr(user.user_mobile);
    %>
    <li data-user-id="<%=user.id;%>">
        <img src="<%=user.user_logo;%>">
        <dl>
            <dt>
                <%=user.user_name;%>
                <em><%=user.user_grade;%></em>
                <% if(user.user_statu == 1){%>
                <span class="fr">已退出</span>
                <%}%>
            </dt>
            <dd><%=user_mobile;%>&nbsp;&nbsp;<%=user.user_regtime;%></dd>
        </dl>
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
            <dt>您还没有当前等级的下线人员</dt>
        </dl>
    </div>
    <%}%>
</script>
<script type="text/javascript" src="../../js/libs/zepto.min.js"></script>
<script type="text/javascript" src="../../js/libs/template.js"></script>
<script type="text/javascript" src="../../js/common.js"></script>
<script type="text/javascript" src="../../js/libs/ncscroll-load.js"></script>
<script type="text/javascript" src="../../js/tmpl/member/member_agent.js"></script>
<script type="text/javascript" src="../../js/tmpl/footer.js"></script>

</body>
</html>
<?php
include __DIR__.'/../../includes/footer.php';
?>