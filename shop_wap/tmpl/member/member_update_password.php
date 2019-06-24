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
        <title></title>
        <link rel="stylesheet" type="text/css" href="../../css/base.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_common.css">
        <link rel="stylesheet" type="text/css" href="../../css/member.css">
    </head>

    <body>
    <header id="header">
        <div class="header-wrap">
            <div class="header-l">
                <a href="javascript:history.go(-1);"> <i class="back"></i> </a>
            </div>
            <div class="header-title">
                <h1>修改登录密码</h1>
            </div>
            <div class="header-r"></div>
        </div>
    </header>
    <div class="nctouch-main-layout">
        <form>
            <div class="nctouch-inp-con">
                <ul class="form-box">
                    <li class="form-item">
                        <h4>手机号</h4>
                        <div class="input-box">
                            <input type="hidden"  id="phone" value=""/>
                            <span  class="inp" id="sphone"></span>
                            <input type="button" class="btn-mobile"  value="获取手机验证码" />
                            <span class="input-del"></span> </div>
                    </li>
                    <li class="form-item">
                        <h4>手机验证码</h4>
                        <div class="input-box">
                            <input type="text" class="inp" name="yzm" id="yzm" autocomplete="off" oninput="writeClear($(this));" />
                            <span class="input-del"></span> </div>
                    </li>
                    <li class="form-item">
                        <h4>新密码</h4>
                        <div class="input-box">
                            <input type="password" class="inp" name="password" id="password" autocomplete="off" oninput="writeClear($(this));" />
                            <span class="input-del"></span> </div>
                    </li>
                    <li class="form-item">
                        <h4>确认密码</h4>
                        <div class="input-box">
                            <input type="password" class="inp" name="spassword" id="spassword" autocomplete="off" oninput="writeClear($(this));" />
                            <span class="input-del"></span> </div>
                    </li>
                </ul>
                <div class="error-tips"></div>
                <div class="form-btn"><a href="javascript:void(0);" class="btn" id="submit_btn">保存</a></div>
            </div>
        </form>
    </div>
    <footer id="footer" class="bottom"></footer>
    <script type="text/javascript" src="../../js/libs/zepto.min.js"></script>

    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/libs/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/tmpl/footer.js"></script>
    <script type="text/javascript" src="../../js/tmpl/member/member_update_password.js"></script>
    </body>

    </html>
<?php
include __DIR__.'/../../includes/footer.php';
?>