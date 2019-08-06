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
    <div class="grid-wrap" style="padding: 5px;">
        <table id="user_grid">
        </table>
        <div id="user_page"></div>
    </div>
    <script type="text/javascript" src="<?=$this->view->js?>/controllers/user/shares/details.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>