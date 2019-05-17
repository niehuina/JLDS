<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
    <link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
    <script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
    </head>
    <style>
        body{min-width:200px;}
        .manage-wrap{margin: 0px auto 10px;width:100%;}
    </style>
    </head>
    <body>
    <div id="manage-wrap" class="manage-wrap">

        <form method="post" enctype="multipart/form-data" id="manage-form" name="manage-form">
            <input id="shipping_method_id" name="shipping_method_id" value="" class="ui-input w200" type="hidden"/>
            <div class="ncap-form-default">

                <dl class="row">
                    <dt class="tit">
                        <label for="shipping_method_Name"><em>*</em>名称: </label>
                    </dt>
                    <dd class="opt">
                        <input id="shipping_method_Name" name="shipping_method_Name" value="" class="ui-input w200" type="text"/>
                        <p class="notic"> </p>
                    </dd>
                </dl>
            </div>
        </form>
    </div>
    <script type="text/javascript" src="<?=$this->view->js?>/controllers/shippingMethod/shippingMethod_manage.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>