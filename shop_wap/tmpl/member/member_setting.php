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
        <meta name="format-detection" content="telephone=no"/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
        <meta name="format-detection" content="telephone=no"/>
        <meta name="msapplication-tap-highlight" content="no"/>
        <meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1"/>
        <title>设置</title>
        <link rel="stylesheet" type="text/css" href="../../css/base.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
    </head>

    <body>
    <header id="header">
        <div class="header-wrap">
            <div class="header-l">
                <?php
                if ($_REQUEST["from"] == "deliver") {
                    ?>
                    <a href="../deliver/order.html"> <i class="back"></i> </a>
                    <?php
                } else {
                    ?>
                    <a href="member.html"> <i class="back"></i> </a>
                <?php } ?>
            </div>
            <div class="header-title">
                <h1>设置</h1>
            </div>
        </div>
        <div class="header-r"><a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a></div>
        <div class="nctouch-nav-layout">
            <div class="nctouch-nav-menu"><span class="arrow"></span>
                <ul>
                    <li><a href="../../index.html"><i class="home"></i>首页</a></li>
                    <li><a href="../search.html"><i class="search"></i>搜索</a></li>
                    <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
                </ul>
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout">
        <ul class="nctouch-default-list mt5">
            <li>
                <a href="member_info.html">
                    <h4>个人信息</h4>
                    <span class="arrow-r"></span></a>
            </li>
            <li>
                <a href="member_update_password.html">
                    <h4>登录密码</h4>
                    <span class="arrow-r"></span></a>
            </li>
            <li>
                <a href="member_certification.html">
                    <h4>实名认证</h4>
                    <span style="float: right; margin-right: 50px" id="certificationStatus"></span>
                    <span class="arrow-r"></span></a>
            </li>
            <li>
                <a href="pay_password_edit.html">
                    <h4>支付密码</h4>
                    <span style="float: right; margin-right: 50px" id="certificationStatus"></span>
                    <span class="arrow-r"></span></a>
            </li>
        </ul>
<!--        <ul class="nctouch-default-list mt5">-->
<!--            <li>-->
<!--                <a href="member_account.html">-->
<!--                    <h4>通用</h4>-->
<!--                    <span class="arrow-r"></span></a>-->
<!--            </li>-->
<!--        </ul>-->
        <ul class="nctouch-default-list mt5">
            <li>
                <a href="member_version.php">
                    <h4>关于我们</h4>
                    <span class="arrow-r"></span></a>
            </li>
        </ul>
        <ul class="nctouch-default-list mt5">
            <li>
                <a id="logoutbtn" href="javascript:void(0);">
                    <h4>安全退出</h4>
                </a>
            </li>
        </ul>
    </div>

    <script type="text/javascript" src="../../js/libs/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/tmpl/member/member_setting.js"></script>
    <script type="text/javascript" src="../../js/tmpl/footer.js"></script>
    </body>

    </html>
<?php
include __DIR__ . '/../../includes/footer.php';
?>