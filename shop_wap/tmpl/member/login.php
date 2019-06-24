<?php
include __DIR__ . '/../../includes/header.php';
?>
    <!DOCTYPE html>
    <!-- saved from url=(0059).//login.html -->
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-touch-fullscreen" content="yes">
        <meta name="format-detection" content="telephone=no">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="format-detection" content="telephone=no">
        <meta name="msapplication-tap-highlight" content="no">
        <meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1">
        <title>登录</title>
        <link rel="stylesheet" type="text/css" href="../../css/base.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
        <link rel="stylesheet" type="text/css" href="../../css/member.css">
    </head>
    <body>

    <header id="header">
        <div class="header-wrap">
            <div class="header-l"><a href="../../index.html"><i class="home"></i></a></div>
            <div class="header-title">
                <h1>账号登录</h1>
            </div>
            <div class="header-r"><a id="header-nav" href="./register.html" class="text">注册</a></div>
        </div>
    </header>

    <div class="nctouch-main-layout fixed-Width">
        <div class="nctouch-inp-con">
            <form action="" method="">
                <ul class="form-box">
                    <li class="form-item">
                        <h4>账　 户</h4>
                        <div class="input-box">
                            <input type="text" placeholder="请输入用户名/手机号" class="inp" name="user_account" id="user_account" oninput="writeClear($(this));">
                            <span class="input-del"></span>
                        </div>
                    </li>
                    <li class="form-item">
                        <h4>密　 码</h4>
                        <div class="input-box">
                            <input type="password" placeholder="请输入登录密码" class="inp" name="user_password" id="user_password" oninput="writeClear($(this));">
                            <span class="input-del"></span>
                        </div>
                    </li>
                </ul>
                <div class="remember-form">
                    <input id="checkbox" type="checkbox" checked="" class="checkbox">
                    <label for="checkbox">自动登录</label>
                    <a class="forgot-password" href="./find_password.html">忘记密码？</a> </div>
                <div class="form-btn"><a href="javascript:void(0);" class="btn" id="loginbtn">登录</a></div>
            </form>
        </div>
    </div>

    </body>

    <script type="text/javascript" src="../../js/libs/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/libs/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/tmpl/footer.js"></script>
    <script type="text/javascript" src="../../js/tmpl/member/login.js"></script>

    </html>
<?php
include __DIR__ . '/../../includes/footer.php';
?>