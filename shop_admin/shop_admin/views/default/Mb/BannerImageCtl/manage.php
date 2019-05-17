<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>

<?php
include TPL_PATH . '/' . 'header.php';
?>
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<form method="post" id="app-banner-setting-form" name="settingForm" class="nice-validator n-yellow" novalidate="novalidate">
    <div class="ncap-form-default">
        <dl class="row">
            <dt class="tit">
                <label for="banner_type">Banner图位置</label>
            </dt>
            <dd class="opt">
                <input type="hidden" id="banner_type">
                <span id="banner_type_text"><?=$menuName?></span>
                <p class="notic"></p>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">
                <label for="banner_image">展示图片</label>
            </dt>
            <dd class="opt">
                <img id="textfield1" name="textfield1" alt="选择图片" src="http://127.0.0.1/yf_shop_admin/shop_admin/static/default/images/default_user_portrait.gif" width="90px" height="90px">

                <div class="image-line upload-image" id="button1">上传图片</div>

                <input id="banner_image" name="" value="" class="ui-input w400" type="hidden">
                <div class="notic">展示图片，建议大小90x90像素PNG图片。</div>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">
                <label for="banner_url">图片跳转链接</label>
            </dt>
            <dd class="opt">
                <textarea value="" name="banner_url" id="banner_url" class="ui-input ui-input-ph" /></textarea>
                <span class="err"></span>
                <p class="notic">图片点击后的跳转链接</p>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">
                <label for="banner_order">显示顺序</label>
            </dt>
            <dd class="opt">
                <input type="text" value="0" name="banner_order" id="banner_order" class="ui-input ui-input-ph">
                <span class="err"></span>
                <p class="notic">数字范围为0~255，数字越小越靠前</p>
            </dd>
        </dl>
    </div>
</form>

<?php
include TPL_PATH . '/' . 'footer.php';
?>
<script type="text/javascript" src="<?=$this->view->js_com?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js?>/models/upload_image.js" charset="utf-8"></script>
<script>
    $(function() {
        api = frameElement.api, data = api.data, oper = data.oper, callback = data.callback;

        if ( oper == 'edit' ) {
            //init
            var rowData = data.rowData;

            $('#banner_type').val(rowData.banner_type);
            $('#textfield1').prop('src', rowData.banner_image);
            $('#banner_image').val(rowData.banner_image);

        }

        api.button({
            id: "confirm", name: '确定', focus: !0, callback: function () {
                postData();
                return false;
            }
        }, {id: "cancel", name: '取消'});

        function postData() {

            var param = {
                banner_type: $("#banner_type").val(),
                banner_image: $('#banner_image').val(),
                banner_url: $("#banner_url").val(),
                banner_order: $("#banner_order").val(),
            };

            if ( oper == 'edit' ) {
                param.mb_banner_image_id = data.rowData.mb_banner_image_id;
            }

            debugger;
            Public.ajaxPost(SITE_URL + '?ctl=Mb_BannerImage&met=' + oper + 'BannerImage&typ=json', {
                param: param
            }, function (data) {
                if (data.status == 200) {
                    typeof callback == 'function' && callback(data.data, oper, window);
                    return true;
                } else {
                    Public.tips({type: 1, content: data.msg});
                }
            })
        }

        new UploadImage({
            thumbnailWidth: 90,
            thumbnailHeight: 90,
            imageContainer: '#textfield1',
            uploadButton: '#button1',
            inputHidden: '#banner_image'
        });

    });
</script>
