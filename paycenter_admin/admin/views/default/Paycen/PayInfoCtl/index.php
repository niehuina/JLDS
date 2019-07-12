<?php if (!defined('ROOT_PATH')) exit('No Permission'); ?>
<?php
include TPL_PATH . '/' . 'header.php';
?>

    </head>

    <div class="wrapper page">

        <p class="warn_xiaoma"><span></span><em></em></p>
        <div class="explanation" id="explanation">
            <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
                <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
                <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em>
            </div>
            <ul>
                <li></li>
                <li></li>
            </ul>
        </div>

        <div class="fixed-bar">
            <div class="item-title">
                <div class="subject">
                    <h3>实名认证</h3>
                    <h5>相关实名认证信息总览</h5>
                </div>
                <ul class="tab-base nc-row">
                    <li><a class="current" href="index.php?ctl=Paycen_PayInfo&met=index"><span>实名认证</span></a></li>
                    <!--           <li><a class="" href="index.php?ctl=Paycen_PayInfo&met=minimumLiving"><span>低保认证</span></a></li>-->
                </ul>
            </div>
        </div>
        <div class="ncap-form-default">

            <div class="mod-search cf">
                <div class="fl">
                    <ul class="ul-inline">
                        <li>
                            <span id="source"></span>
                            <input type="text" id="userName" class="ui-input ui-input-ph con"
                                   value="请输入用户账号/用户姓名/用户手机号">
                        </li>
                        <li><a class="ui-btn" id="search">查询<i class="iconfont icon-btn02"></i></a></li>
                    </ul>
                </div>
                <div class="fr">
                    <!--        <a class="ui-btn ui-btn-sp mrb" id="add">新增<i class="iconfont icon-btn03"></i></a>-->
                    <!--        <a href="javascript:void(0)" class="ui-btn" id="btn-batchDel">删除<i class="iconfont icon-bin"></i></a>-->
                </div>
            </div>
            <div class="grid-wrap">
                <table id="grid">
                </table>
                <div id="page"></div>
            </div>
        </div>

    </div>
    <script src="./admin/static/default/js/controllers/payinfo/payinfo_list.js"></script>
<?php
include TPL_PATH . '/' . 'footer.php';
?>