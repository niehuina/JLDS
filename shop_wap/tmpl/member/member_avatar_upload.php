<?php
include __DIR__ . '/../../includes/header.php';
?>

    <!DOCTYPE html>

    <html>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no"/>
    <link rel="stylesheet" type="text/css" href="../../css/base.css">
    <link rel="stylesheet" type="text/css" href="../../css/cropper/cropper.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/cropper/main.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_common.css">
    <head>
    </head>

    <body>
    <header id="header" class="fixed write">
        <div class="header-wrap">
            <div class="header-l">
                <a href="javascript:history.go(-1);"> <i class="back"></i> </a>
            </div>
            <div class="header-title">
                <h1>头像上传</h1>
            </div>
            <div class="header-r"></div>
        </div>
    </header>
    <div class="nctouch-main-layout">
        <div class="nctouch-inp-con">

            <div class="container" id="crop-avatar">
                <div class="img-container">
                    <img id="certificate_logo" src="<?= $_GET['avatar_link'] ? $_GET['avatar_link'] : "../img/user_avt_default.png" ?>" alt="Picture">
                </div>
                <input type="file" class="inp" id="inputImage" accept="image/*"  style="display: none">
            </div>
            <div class="error-tips"></div>
            <div class="form-btn ok" style="display: block">
                <a class="btn-submit" href="javascript:;" id="submitBtn">保存</a>
            </div>
        </div>

    </div>

    <script type="text/javascript" src="../../js/libs/jquery.min.js"></script>
    <script type="text/javascript" src="../../js/libs/cropper.min.js"></script>
    <script type="text/javascript" src="../../js/libs/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/tmpl/footer.js"></script>
    <script type="text/javascript">
        var upload_flag = false;
        var data_img = '';
        $(function () {
            var a = getCookie("key");
            if (!a) {
                window.location.href = WapSiteUrl + "/tmpl/member/login.html";
                return
            }

            var agent = navigator.userAgent.toLowerCase();
            if(agent.indexOf('iphone') != -1  || agent.indexOf('ipad') != -1 ){
                $('.js_upFile').removeAttr("capture");
            }

            $('#certificate_logo').click(function () {
                $('#inputImage').click();
            });

            var $image = $('#certificate_logo');

            // Import image
            var $inputImage = $('#inputImage'),
                URL = window.URL || window.webkitURL,
                blobURL;

            // window.addEventListener('dragmove', func, { passive: false });

            if (URL) {
                $inputImage.change(function () {
                    var files = this.files,
                        file;

                    if (files && files.length) {
                        file = files[0];
                        if (/^image\/\w+$/.test(file.type)) {
                            //上传图片不能超过2M
                            if (file.size > 0 && file.size >= 2*1024*1000) {
                                errorTipsShow("上传图片不能超过2M");
                                $("#submitBtn").hide();
                                return;
                            }
                            blobURL = URL.createObjectURL(file);
                            $image.one('built.cropper', function () {
                                URL.revokeObjectURL(blobURL); // Revoke when load complete
                            }).cropper({
                                viewMode: 1,
                                dragMode: 'move',//拖拽模式
                                autoCrop: true,
                                autoCropArea: 0.8,
                                dragCrop: false,
                                aspectRatio: 1,
                                resizable: true,
                                width: 350,
                                height: 350
                            }).cropper('reset', true).cropper('replace', blobURL);

                            $inputImage.val('');
                            upload_flag = true;
                            $("#submitBtn").show();
                        } else {
                            errorTipsShow("请上传头像");
                            $("#submitBtn").hide();
                        }
                    }
                });
            } else {
                $inputImage.parent().remove();
            }

            $("#submitBtn").click(function () {
                if($(this).hasClass('disabled')) return;
                if (upload_flag == false) {
                    errorTipsShow("请上传头像");
                    return;
                }

                $(this).addClass('disabled');
                $(".pre-loading").show();
                var cas = $image.cropper('getCroppedCanvas');//获取被裁剪后的canvas
                var data_img = cas.toDataURL('image/jpeg', 0.5); //转换为base64地址形式

                $.ajax({
                    type: "post",
                    url: ApiUrl + "/index.php?ctl=Upload&met=uploadBase64Image&typ=json",
                    data: {k: getCookie('key'), image: data_img},
                    dataType: "json",
                    success: function (a) {
                        if (a.state != 'SUCCESS') {
                            errorTipsShow("上传失败");
                            $(".pre-loading").hide();
                        } else {
                            $.ajax({
                                type: "post",
                                url: ApiUrl + "/index.php?ctl=Buyer_User&met=updateUserInfo&typ=json",
                                data: {
                                    k: getCookie('key'),
                                    u: getCookie('id'),
                                    user_id: getCookie('id'),
                                    user_logo: a.url
                                },
                                dataType: "json",
                                success: function (a) {
                                    location.href = WapSiteUrl + "/tmpl/member/member_info.html";
                                }
                            })
                        }
                    }
                });
            })
        })
    </script>
    </body>

    </html>
<?php
include __DIR__ . '/../../includes/footer.php';
?>