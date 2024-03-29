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
    <title>我的商城</title>
    <link rel="stylesheet" type="text/css" href="../../css/base.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
</head>

<body>
    <header id="header">
        <div class="header-wrap">
            <div class="header-l">
                <a href="javascript:history.go(-1)"> <i class="back"></i> </a>
            </div>
            <div class="header-title">
                <h1>用户反馈</h1>
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout feedback">
        <form>
            <textarea id="feedback" class="textarea" placeholder="请描述一下您遇到的问题"></textarea>
            <input type="text" class="feed_url" name="url" value="" placeholder="请输入您要反馈的地址链接" style="width:" />
            <div class="error-tips"></div>
            <a href="javascript:void(0);" class="btn-l" id="feedbackbtn">提交</a>
        </form>
    </div>
    <footer id="footer" class="bottom"></footer>
    
    <script type="text/javascript" src="../../js/libs/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/libs/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/tmpl/footer.js"></script>
    <script type="text/javascript" src="../../js/tmpl/member/member_feedback.js"></script>
</body>

</html>
<?php 
include __DIR__.'/../../includes/footer.php';
?>