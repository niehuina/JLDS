$(function(){
    var key = getCookie('key');
    if (key) {
        window.location.href = WapSiteUrl+'/tmpl/member/member.html';
        return;
    }else
    {
        callback = WapSiteUrl + '/tmpl/member/member.html';

        login_url   = UCenterApiUrl + '?ctl=Login&met=index&typ=e';


        callback = ApiUrl + '?ctl=Login&met=check&typ=e&redirect=' + encodeURIComponent(callback);


        login_url = login_url + '&from=wap&callback=' + encodeURIComponent(callback);

        window.location.href = login_url;
    }

    // $.getJSON(ApiUrl + '/index.php?act=connect&op=get_state', function(result){
    //     var ua = navigator.userAgent.toLowerCase();
    //     var allow_login = 0;
    //     if (result.data.pc_qq == '1') {
    //         allow_login = 1;
    //         $('.qq').parent().show();
    //     }
    //     if (result.data.pc_sn == '1') {
    //         allow_login = 1;
    //         $('.weibo').parent().show();
    //     }
    //     if ((ua.indexOf('micromessenger') > -1) && result.data.connect_wap_wx == '1') {
    //         allow_login = 1;
    //         $('.wx').parent().show();
    //     }
    //     if (allow_login) {
    //         $('.joint-login').show();
    //     }
    // });

    $(".tab1").bind("click",function(){
        $(".tab1").addClass("on");
        $(".tab2").removeClass("on");
        $(".content1").show();
        $(".content2").hide();
        $("#captchaimgChange").click();
        $("#captchaimg1").show();
    });
    $(".tab2").bind("click",function(){
        $(".tab2").addClass("on");
        $(".tab1").removeClass("on");
        $(".content2").show();
        $(".content1").hide();
        $("#captchaimgChange_mobile").click();
    });

    var referurl = document.referrer;//上级网址
    $.sValid.init({
        rules:{
            user_account:"required",
            user_password:"required"
        },
        messages:{
            user_account:"用户名必须填写！",
            user_password:"密码必填!"
        },
        callback:function (eId,eMsg,eRules){
            if(eId.length >0){
                var errorHtml = "";
                $.map(eMsg,function (idx,item){
                    errorHtml += "<p>"+idx+"</p>";
                });
            }
        }
    });
    var allow_submit = true;
    $('#loginbtn').click(function(){//会员登陆
        // if (!$(this).parent().hasClass('ok')) {
        //     return false;
        // }
        if (allow_submit) {
            allow_submit = false;
        } else {
            return false;
        }
        var user_account = $('#user_account').val();
        var pwd = $('#user_password').val();
        var client = 'wap';
        if($.sValid()){
            $.ajax({
                type:'post',
                url:UCenterApiUrl+"/index.php?ctl=Login&met=login&from=wap&typ=json",
                data:{user_account:user_account,user_password:pwd,client:client},
                dataType:'json',
                success:function(result){
                    allow_submit = true;
                    if(result.status == 200){
                        var key = result.data.k;
                        if(typeof(key)=='undefined'){
                            return false;
                        }else{
                            //本系统登录API
                            var expireHours = 0;
                            if ($('#checkbox').prop('checked')) {
                                expireHours = 188;
                            }

                            addCookie('id',result.data.user_id, expireHours);
                            addCookie('user_account',result.data.user_name, expireHours);
                            addCookie('key',key, expireHours);

                            // 更新cookie购物车
                            updateCookieCart(key);

                            var u = result.data.user_id;
                            var k = result.data.user_name;
                            var callback = ApiUrl + '?ctl=Login&met=check&typ=e&redirect=' + encodeURIComponent(referurl);
                            window.location.href = decodeURIComponent(callback) + '&us=' + encodeURIComponent(u) + '&ks=' + encodeURIComponent(k);
                        }
                        errorTipsHide();
                    }else{
                        errorTipsShow('<p>' + result.msg + '</p>');
                    }
                }
            });
        }
    });

    $('.weibo').click(function(){
        location.href = ApiUrl+'/index.php?act=connect&op=get_sina_oauth2';
    })
    $('.qq').click(function(){
        location.href = ApiUrl+'/index.php?act=connect&op=get_qq_oauth2';
    })
    $('.wx').click(function(){
        location.href = ApiUrl+'/index.php?act=connect&op=index';
    })
});