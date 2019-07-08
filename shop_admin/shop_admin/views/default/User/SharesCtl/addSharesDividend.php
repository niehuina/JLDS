<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
    <link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
    <link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>

    <style>

    </style>
    </head>
    <body>
    <div class="">
        <form method="post" id="dividend-edit-form" name="form">
            <div class="ncap-form-default">
                <dl class="row">
                    <dt class="tit">
                        <label><em>*</em>分红年度</label>
                    </dt>
                    <dd class="opt">
                        <span id="dividend_year"></span>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label><em>*</em>参与分红人员</label>
                    </dt>
                    <dd class="opt">
                        <span id="source"></span>
                    </dd>
                </dl>
                <dl class="row" id="select_user" style="display: none;">
                    <dt class="tit">
                        <label><em>*</em>选择人员</label>
                    </dt>
                    <dd class="opt">
                        <div class="wrapper page" style="padding: 0 0 1% 0;">
                            <div class="grid-wrap">
                                <table id="user_grid">
                                </table>
                                <div id="user_page"></div>
                            </div>
                        </div>
                    </dd>
                </dl>
            </div>
            <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
        </form>
    </div>
    <script type="text/javascript" src="<?=$this->view->js?>/controllers/user/shares/manage.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>