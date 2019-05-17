<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
    <link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
    <link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
    </head>
    <body>
    <div class="wrapper page">
        <div class="fixed-bar">
            <div class="item-title">
                <div class="subject">
                    <h3>一般设置&nbsp;</h3>
                    <h5>站点相关基础信息及功能设置选项</h5>
                </div>
                <ul class="tab-base nc-row">
                    <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=site&config_type%5B%5D=site"><span>基础设置</span></a></li>
                    <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=settings&typ=e&config_type%5B%5D=site"><span>站点Logo</span></a></li>
                    <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=msgAccount&config_type%5B%5D=email&config_type%5B%5D=sms">邮件设置</a></li>
                    <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=mobileAccount&config_type%5B%5D=mobile&config_type%5B%5D=sms">短信设置</a></li>
                    <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=seo&typ=e&config_type%5B%5D=seo"><span>SEO配置</span></a></li>
                    <li><a class="current" href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=set&typ=e&config_type%5B%5D=set"><span>一般配置</span></a></li>
                </ul>
            </div>
        </div>
        <!-- 操作说明 -->
        <p class="warn_xiaoma"><span></span><em></em></p>
        <div class="explanation" id="explanation">
            <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
                <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
                <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
            <ul>
                <li>网站全局一般设置。</li>
            </ul>
        </div>

        <form method="post" enctype="multipart/form-data" id="set-setting-form" name="form">
            <input type="hidden" name="config_type[]" value="set"/>

            <div class="ncap-form-default">

                <dl class="row">
                    <dt class="tit">
                        <label for="cashamount_min">最低提现金额</label>
                    </dt>
                    <dd class="opt">
                        <input id="cashamount_min" name="set[cashamount_min]" value="<?=$data['cashamount_min']['config_value']?>" class="ui-input w400" type="text"/>
                        <p class="notic">最低提现金额</p>
                    </dd>
                </dl>

                <div class="bot"> <a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
            </div>
        </form>
    </div>
    <script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>