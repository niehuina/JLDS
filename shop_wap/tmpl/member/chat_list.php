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
    <title>消息列表</title>
    <link rel="stylesheet" type="text/css" href="../../css/base.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_chat.css">
</head>

<body>
    <header id="header">
        <div class="header-wrap">
            <div class="header-l">
                <a href="javascript:history.go(-1)"> <i class="back"></i> </a>
            </div>
            <div class="header-title">
                <h1>消息列表</h1>
            </div>
            <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more bgc-t"></i><sup></sup></a> </div>
        </div>
        <div class="nctouch-nav-layout">
            <div class="nctouch-nav-menu"> <span class="arrow"></span>
                <ul>
                    <li><a href="../../index.html"><i class="home"></i>首页</a></li>
                    <li><a href="../search.html"><i class="search"></i>搜索</a></li>
                    <li><a href="../cart_list.html"><i class="cart"></i>购物车<sup></sup></a></li>
                    <li><a href="../member/member.html"><i class="member"></i>我的商城</a></li>
                </ul>
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout">
        <ul class="nctouch-message-list" id="messageList">
        </ul>
    </div>
    <script type="text/html" id="messageListScript">
        <% if (data.items.length > 0) { %>
        <% for (var i=0;i< data.items.length;i++) { %>
        <li>
            <a class="to_chat" t_id="<%=data.items[i].message_id%>" >
                <div class="avatar">
                    <img src="../../images/message_b.png" width="120" height="120" />
                </div>
                <dl>
                    <dt><%=data.items[i].message_title%></dt>
                    <dd>
                        <%=data.items[i].message_content%>
                    </dd>
                </dl>
                <time>
                    <%=data.items[i].message_create_time%>
                </time>
            </a>
            <a href="javascript:void(0)" t_id="<%=data.items[i].message_id%>" class="msg-list-del"></a>
        </li>
        <% } %>
        <% } else { %>
        <div class="nctouch-norecord talk">
            <div class="norecord-ico"><i></i></div>
            <dl>
                <dt>没有系统消息</dt>
                <!--                                <dd>对话后可在此找到最近联系的客服</dd>-->
            </dl>
        </div>
        <% } %>
    </script>
    <script type="text/javascript" src="../../js/libs/zepto.min.js"></script>
    
    <script type="text/javascript" src="../../js/libs/template.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/libs/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/tmpl/chat_list.js"></script>
</body>

</html>
<?php 
include __DIR__.'/../../includes/footer.php';
?>