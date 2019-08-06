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
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <meta name="format-detection" content="telephone=no" />
        <meta name="msapplication-tap-highlight" content="no" />
        <meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />
        <title>账户余额提现</title>
        <link rel="stylesheet" type="text/css" href="../../css/base.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_cart.css">
    </head>

    <body>
    <header id="header">
        <div class="header-wrap">
            <div class="header-l">
                <a href="member_money.html"> <i class="back"></i> </a>
            </div>
            <div class="header-title">
                <h1>账户余额提现</h1>
            </div>
        </div>
        <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>
        <div class="nctouch-nav-layout">
            <div class="nctouch-nav-menu"> <span class="arrow"></span>
                <ul>
                    <li><a href="../../index.html"><i class="home"></i>首页</a></li>
                    <li><a href="../search.html"><i class="search"></i>搜索</a></li>
                    <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
                </ul>
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout">
        <div class="nctouch-inp-con">
            <ul class="form-box">
                <li class="form-item">
                    <div class="input-box btn-style" id="service_type">
                    </div>
                </li>
                <li class="form-item">
                    <h4>收款方</h4>
                    <div class="input-box">
                        <input type="text" class="inp bgf" name="bank_name" id="bank_name"
                               autocomplete="off" oninput="writeClear($(this));" placeholder="开户人姓名"/>
                        <span class="input-del"></span>
                    </div>
                    <div class="input-box">
                        <input type="text" class="inp bgf" name="bank" id="bank"
                               autocomplete="off" oninput="writeClear($(this));" placeholder="输入银行"/>
                        <span class="input-del"></span>
                    </div>
                    <div class="input-box">
                        <input type="text" class="inp bgf" name="cardno" id="cardno"
                               autocomplete="off" oninput="writeClear($(this));" placeholder="输入银行卡号"/>
                        <span class="input-del"></span>
                    </div>
                </li>
                <li class="form-item">
                    <h4>提取金额</h4>
                    <div class="input-box">
                        <input type="text" class="inp bgf" name="withdraw_money" id="withdraw_money"
                               autocomplete="off" oninput="writeClear($(this));" placeholder="￥"
                               onKeyUp="amount(this)" onblur="checkMoney(this)" />
                        <span class="input-del"></span>
                        <div class="tips">服务费：
                            <em>￥</em><span id="service_total">0.00</span>
                            （付款总额：<span id="acount_total">0.00</span>）
                        </div>
                    </div>
                </li>
                <li class="form-item">
                    <h4>提现说明</h4>
                    <div class="input-box">
                        <input type="text" class="inp bgf" name="con" id="con"
                               autocomplete="off" oninput="writeClear($(this));"/>
                        <span class="input-del"></span>
                    </div>
                </li>
                <li class="form-item">
                    <h4>手机号</h4>
                    <div class="input-box">
                        <label id="sphone"></label>
                        <input type="hidden" id="phone" value=""/>
                        <input type="button" class="btn-mobile" value="获取手机验证码" />
                    </div>
                </li>
                <li class="form-item">
                    <h4>验证码</h4>
                    <div class="input-box">
                        <input type="text" class="inp bgf" name="yzm" id="yzm" autocomplete="off" oninput="writeClear($(this));" />
                        <span class="input-del"></span>
                    </div>
                </li>
                <li class="form-item">
                    <h4>支付密码</h4>
                    <div class="input-box">
                        <input type="password" class="inp bgf" name="password" id="password" autocomplete="off" oninput="writeClear($(this));" />
                        <span class="input-del"></span>
                    </div>
                </li>
            </ul>
            <div class="error-tips"></div>
            <input type="hidden" id="account_money">
            <input type="hidden" id="cashamount_min">
            <input type="button" class="btn-submit mt5" id="withdraw_btn" value="提交">
        </div>
    </div>
    <footer id="footer"></footer>

    <script type="text/javascript" src="../../js/libs/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/libs/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/tmpl/member/money_withdraw.js"></script>
    <script type="text/javascript" src="../../js/tmpl/footer.js"></script>
    </body>

    </html>
<?php
include __DIR__.'/../../includes/footer.php';
?>