$(function () {
    /* var e = getCookie("key");
     if (e)
     {
         window.location.href = WapSiteUrl + "/tmpl/member/member.html";
         return
     }*/


    /*显示注册协议 开始 */
    $("#protocol").hide();
    $(".reg-cms").click(function () {
        $("#register_main").hide();
        $("#protocol").show();
    });
    $("#protocol .close").click(function () {
        $("#protocol").hide();
        $("#register_main").show();
    });

    $.ajax({
        type: 'post',
        url: UCenterApiUrl + "/index.php?ctl=Login&met=getRegisterDocument&typ=json",
        data: {app_id: app_id},
        dataType: 'json',
        success: function (result) {
            if (result.status == 200) {
                $("#reg_protocol").html(result.data.config_value);
                errorTipsHide();
            } else {
                errorTipsShow('<p>' + result.msg + '</p>');
            }
        }
    });
    /*显示注册协议 结束 */

    // $.getJSON(ApiUrl + "/index.php?act=connect&op=get_state&t=connect_sms_reg", function (e) {
    //     if (e.datas != "0") {
    //         $(".register-tab").show()
    //     }
    // });
    $.sValid.init({
        rules: {
            username: "required",
            userpwd: "required",
            password_confirm: "required",
            usermobile: {required: true, mobile: true},
            captcha: "required"
        },
        messages: {
            username: "用户名必须填写！",
            userpwd: "密码必填!",
            password_confirm: "确认密码必填!",
            usermobile: {required: "请填写手机号！", mobile: "手机号码不正确"},
            captcha: "验证码必须填写！"
        },
        callback: function (e, r, a) {
            if (e.length > 0) {
                var i = "";
                $.map(r, function (e, r) {
                    i += "<p>" + e + "</p>"
                });
                errorTipsShow(i)
            } else {
                errorTipsHide()
            }
        }
    });
    $("#registerbtn").click(function () {
        // if (!$(this).parent().hasClass("ok")) {
        //     return false
        // }
        var e = $("input[name=username]").val();
        var r = $("input[name=pwd]").val();
        var a = $("input[name=password_confirm]").val();
        var m = $("input[name=usermobile]").val();
        var c = $('input[name=captcha]').val();
        var d = $('input[name=parent_id]').val();
        var t = "wap";

        if ($.sValid()) {

            if (r !== a) {
                $.sDialog({
                    skin: "red",
                    content: '两次输入的密码不一致！',
                    okBtn: false,
                    cancelBtn: false
                });
                return false
            }

            $.ajax({
                type: "post",
                url: UCenterApiUrl + "/index.php?ctl=Login&met=register&typ=json",
                data: {"user_account": e, "user_password": r, "user_code": c, "mobile": m, "parent_id": d},
                dataType: "json",
                success: function (data) {
                    console.info(data);
                    if (data.status == 200) {
                        $.post(ApiUrl + "/index.php?ctl=Login&met=doLogin&typ=json", {
                            parent_id: data.data.parent_id,
                            user_account: e,
                            user_password: r,
                            client: t,
                        }, function (result) {
                            allow_submit = true;
                            if (result.status == 200) {
                                // 更新cookie购物车
                                updateCookieCart(result.data.key);
                                addCookie('id', result.data.user_id);
                                addCookie('user_account', result.data.user_account);
                                addCookie('key', result.data.key);

                                location.href = WapSiteUrl + "/tmpl/member/member.html";
                                errorTipsHide();
                            } else {
                                errorTipsShow('<p>' + result.msg + '</p>');
                            }
                        });

                    } else {
                        errorTipsShow("<p>" + data.msg + "</p>")
                    }
                }

            });
        }
    })


    $("#refister_mobile_btn").click(function () {
        if (!window.randStatus) {
            return;
        }

        if (isNaN($("#usermobile").val()) || $("#usermobile").val().length !== 11) {
            $.sDialog({
                skin: "red",
                content: '请填写手机号',
                okBtn: false,
                cancelBtn: false
            });
            return false
        }

        var mobile = $("#usermobile").val();

        if (!isNaN(mobile) && mobile.length == 11) {
            var ajaxurl = UCenterApiUrl + '/index.php?ctl=Login&met=regCode&typ=json&from=wap&mobile=' + mobile;
            $.ajax({
                type: "POST",
                url: ajaxurl,
                dataType: "json",
                async: false,
                success: function (respone) {
                    if (respone.status != 20) {
                        $.sDialog({
                            skin: "red",
                            content: respone.msg,
                            okBtn: false,
                            cancelBtn: false
                        });
                        return false
                    } else {
                        window.countDown();
                        $.sDialog({
                            skin: "red",
                            content: '请查看手机短信获取验证码！',
                            okBtn: false,
                            cancelBtn: false
                        });
                        return false
                    }

                    console.info(respone);
                }
            });

            $('.randfuc').html('重新获取验证码');
        }
    })


    msg = "获取验证码";
    var delayTime = 60;
    window.randStatus = true;
    window.countDown = function () {
        window.randStatus = false;
        delayTime--;
        $('.id_get_de').html(delayTime + " 秒后重新获取");
        if (delayTime == 0) {
            delayTime = 60;
            $('.id_get_de').html(msg);
            clearTimeout(t);

            window.randStatus = true;
        } else {
            t = setTimeout(countDown, 1000);
        }
    }
});


function getIntroducer(obj){
    if($(obj).html() == "清除"){
        $("#parent_id").val("");
        $("#intro_keys").val("");
        $("#intro_list").html("");
        $("#intro_list").parent().parent().hide();
        $(obj).html("查询");
        return;
    }

    var intro_keys = $("#intro_keys").val();
    if(!intro_keys) return;
    var ajaxurl = UCenterApiUrl + '/index.php?ctl=Login&met=getIntroducer&typ=json&intro_keys='+$("#intro_keys").val();
    $.ajax({
        type: "POST",
        url: ajaxurl,
        dataType: "json",
        async: false,
        success: function (respone) {
            if (respone.status == 200) {
                var data = respone.data;
                var html = '';
                $.each(data, function (i, item) {
                    if(i == 0) {
                        $("#parent_id").val(item.user_id);
                        html += item.user_truename + '/' + item.user_mobile;
                    }
                });

                if(html == ""){
                    html = '请准确填写推荐人姓名或手机号';
                    $("#intro_list").addClass("error");
                    $("#intro_list").parent().parent().show();
                }else{
                    $("#intro_list").removeClass("error");
                    $("#intro_list").parent().parent().show();
                }
                $("#intro_list").html(html);
                $($("#intro_list").parent()).removeClass("hidden");
                $(obj).html("清除");
            }
        }
    });
}