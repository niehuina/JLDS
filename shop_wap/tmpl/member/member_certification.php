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
                <a href="javascript:history.go(-1);"> <i class="back"></i></a>
            </div>
            <div class="header-title">
                <h1>实名认证</h1>
            </div>
            <div class="header-r"></div>
        </div>
    </header>
    <div class="nctouch-main-layout">
        <form>
            <div class="nctouch-inp-con">
                <ul class="form-box">
                    <li class="form-item">
                        <h4>用户名</h4>
                        <div class="input-box">
                            <label id="user_name" ></label>
                            <span class="input-del"></span> </div>
                    </li>
                    <li class="form-item">
                        <h4>真实姓名</h4>
                        <div class="input-box">
                            <input type="text" class="inp" name="user_realname" id="user_realname" autocomplete="off"  />
                            <span class="input-del"></span> </div>
                    </li>
                    <li class="form-item">
                        <h4>证件类型</h4>
                        <div class="input-box">
                            <select id="type" class="" placeholder="选择证件类型">
                                <option data-icon="&spades;" value="1">身份证</option>
                                <option data-icon="&clubs;" value="2">护照</option>
                                <option data-icon="&hearts;" value="3">军官证</option>
                            </select>
                        </div>
                    </li>
                    <li class="form-item">
                        <h4>证件号码</h4>
                        <div class="input-box">
                            <input type="text" class="inp" id="idcardno" maxlength="18" autocomplete="off"  />
                            <span class="input-del"></span> </div>
                    </li>
                    <li class="form-item">
                        <h4>有效起始日</h4>
                        <div class="input-box">
                            <input type="date" class="inp" id="startDate"  autocomplete="off"   />
                            <span class="input-del"></span> </div>
                    </li>
                    <li class="form-item">
                        <h4>有效结束日</h4>
                        <div class="input-box">
                            <input type="date" class="inp"  id="endDate"  autocomplete="off"  />
                            <span class="input-del"></span> </div>
                    </li>
                    <li class="form-item IDImg">
                        <h4>正面照预览</h4>
                        <div class="input-box">
                            <img  height="120"  id="face_logo"/>
                            <span class="input-del"></span> </div>
                    </li>
                    <li class="form-item">
                        <h4>证件正面照</h4>
                        <div class="input-box">
                           <input type="file" name="upfile" id="face" style="display: none" />
                            <input type="button" id="btnFace" value="上传正面照" class="btn-green" style="width: auto;line-height: 1rem;margin-left: 0rem">
                            <input type="hidden" id="face_logo_url" value=""  />
                            <span class="input-del"></span> </div>
                    </li>
                    <li class="form-item IDImg">
                        <h4>背面照预览</h4>
                        <div class="input-box">
                            <img  height="120" id="font_logo" />
                            <span class="input-del"></span> </div>
                    </li>
                    <li class="form-item">
                        <h4>证件背面照</h4>
                        <div class="input-box">
                            <input type="file" name="upfile" id="font"  style="display: none" />
                            <input type="button" id="btnFont" value="上传背面照" class="btn-green" style="width: auto;line-height: 1rem;margin-left: 0rem">
                            <input type="hidden" id="font_logo_url" value="" />
                            <span class="input-del"></span> </div>
                    </li>
                </ul>
                <div class="form-btn ok"><a href="javascript:void(0);" class="btn" id="submit_btn">保存</a></div>
            </div>
        </form>
    </div>
    <footer id="footer" class="bottom"></footer>
    <script type="text/javascript" src="../../js/libs/zepto.min.js"></script>

    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/libs/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/tmpl/footer.js"></script>
    <script type="text/javascript" src="../../js/tmpl/member/certification.js"></script>
    </body>

    </html>
<?php
include __DIR__.'/../../includes/footer.php';
?>