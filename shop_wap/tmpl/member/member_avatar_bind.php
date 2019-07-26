<?php
include __DIR__ . '/../../includes/header.php';
?>

    <!DOCTYPE html>

    <html>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no"/>
    <link rel="stylesheet" type="text/css" href="../../css/base.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_common.css">
    <head>
    </head>

    <body>
    <header id="header" class="fixed">
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
        <form>
            <div class="nctouch-inp-con">
                <ul class="form-box">
                    <li>

                        <div style="text-align: center;">
                            <br>
                            <br>
                            <img id="certificate_logo" style="width: 6rem;height: 6rem;margin-top: 3rem auto;"/>
                            <br>
                            <br>
                            <br>
                            <a href="javascript:void(0);">
                                <input type="button" id="btnUpload" class="btn-green"
                                       style="width: 50%; line-height: 1.5rem;margin-left: 0rem;margin-top: 0.1rem"
                                       value="上传头像">
                                <input type="file" class="inp" name="upfile" style="display: none">
                                <p><i class="icon-upload"></i></p>
                            </a>
                            <input type="hidden" id="cer_img" value=""/>
                            <br>
                        </div>
                    </li>
                </ul>
                <div class="error-tips"></div>
                <div class="form-btn ok" style="display: block"><a class="btn-green" href="javascript:;" id="submitBtn">保存</a>
                </div>
            </div>
        </form>

    </div>
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
            $.ajax({
                type: "get",
                url: ApiUrl + "/index.php?ctl=Buyer_User&met=getUserInfo&typ=json",
                data: {k: a, u: getCookie('id'),},
                dataType: "json",
                success: function (result) {
                    if (result.status == 200) {
                        $('#certificate_logo').attr('src', result.data.user_logo == "" ? "../../images/defulat_user.png" : result.data.user_logo);
                    }
                }
            });


            $('#btnUpload').click(function () {
                $('input[name="upfile"]').click();
            });
            $('input[name="upfile"]').change(function () {
                var file = this.files[0];
                if (!/image\/\w+/.test(file.type)) {
                    errorTipsShow("请上传图片文件")
                    return false;
                }
                upload_flag = true;
                var reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = function (e) {
                    var img = new Image,
                        width = 200, //image resize
                        quality = 0.8, //image quality
                        canvas = document.createElement("canvas"),
                        drawer = canvas.getContext("2d");
                    img.src = this.result;
                    img.onload = function () {
                        canvas.width = width;
                        canvas.height = width * (img.height / img.width);
                        drawer.drawImage(img, 0, 0, canvas.width, canvas.height);
                        data_img = canvas.toDataURL("image/jpeg", quality);

                        $("#certificate_logo").attr("src", data_img);

                    }
                }
            });


            // $('input[name="upfile"]').ajaxUploadImage({
            //     url: ApiUrl + "/index.php?ctl=Upload&action=uploadImage",
            //     data: {key:getCookie('id')},
            //     start: function (e) {
            //     },
            //     success: function (e, a) {
            //         checkLogin(a.login);
            //         if (a.state != 'SUCCESS') {
            //             e.parent().siblings(".upload-loading").remove();
            //             $.sDialog({skin: "red", content: "图片尺寸过大！", okBtn: false, cancelBtn: false});
            //             return false
            //         }
            //         $('#certificate_logo').attr('src',a.url);
            //         e.parents("a").next().val(a.url)
            //     }
            // });
            $("#submitBtn").click(function () {
                if (upload_flag == false) {
                    errorTipsShow("请上传头像");
                    return;
                }
                $(".pre-loading").show();

                $.ajax({
                    type: "post",
                    url: ApiUrl + "/index.php?ctl=Upload&met=uploadBase64Image&typ=json",
                    data: {k: getCookie('key'), image: data_img},
                    dataType: "json",
                    success: function (a) {
                        if (a.state != 'SUCCESS') {
                            errorTipsShow("上传失败");
                            $(".pre-loading").hide();
                        }
                        else {
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

            });
        })
    </script>
    </body>

    </html>
<?php
include __DIR__ . '/../../includes/footer.php';
?>