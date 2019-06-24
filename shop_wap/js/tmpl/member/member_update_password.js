$(function ()
{
    var a = getCookie("key");
    if (!a)
    {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html";
        return
    }
    $.ajax({
        type: "get",  url:ApiUrl+"/index.php?ctl=Buyer_User&met=getUserInfo&typ=json", data: {k:a,u:getCookie('id'),}, dataType: "json", success: function (result)
        {
            if (result.status == 200)
            {
                $('#phone').val(result.data.user_mobile);
                $('#sphone').html(getPhoneStr(result.data.user_mobile));
            }
        }
    });

    $.sValid.init({
        rules: {yzm: "required", password: "required", spassword: "required"},
        messages: {yzm: "验证码必填！", password: "密码必填！", spassword: "确认密码必填！"},
        callback: function (a, e, r)
        {
            if (a.length > 0)
            {
                var i = "";
                $.map(e, function (a, e)
                {
                    i += "<p>" + a + "</p>"
                });
                errorTipsShow(i)
            }
            else
            {
                errorTipsHide()
            }
        }
    });
    $("#submit_btn").click(function ()
    {
        if ($.sValid())
        {
            var mobile = $("#phone").val();
            var password=$('#password').val();
            var spassword=$('#spassword').val();
            var user_id=getCookie('id');
            var k=getCookie('key');
            var yzm = $.trim($("#yzm").val());
            if(password!=spassword){
                errorTipsShow("<p>确认密码与密码不一致</p>");
                return;
            }else {
                if(yzm == ''){
                    errorTipsShow("<p>验证码不能为空</p>");
                    return false;
                }
                var url = UCenterApiUrl+'/index.php?ctl=User&met=checkMobileYzm&typ=json';
                $.post(url, {k: k, u: getCookie('id'),'yzm':yzm,'mobile':mobile}, function(a){
                    if (a.status == 200){
                        $.ajax({
                            type: "post",
                            url: UCenterApiUrl + "/index.php?ctl=User&met=updatepassword&typ=json",
                            data: {k: k, u: getCookie('id'), user_id: user_id, password: password},

                            dataType: "json",
                            success: function (a) {
                                if (a) {
                                    location.href = WapSiteUrl + "/tmpl/member/member.html"
                                }
                                else {
                                    location.href = WapSiteUrl
                                }
                            }
                        })
                    }
                    else{
                        errorTipsShow("<p>验证码错误</p>");
                        flag=false;
                        return;
                    }
                });
            }
        }
    });
});

$(".btn-mobile").click(function(){
    $(".btn-mobile").attr("disabled", "disabled");
    $(".btn-mobile").attr("readonly", "readonly");
    var mobile = $('#phone').val();

    var url = UCenterApiUrl +'index.php?ctl=User&met=getYzmforMobile&typ=json';
    var sj = new Date();
    var pars= {k:getCookie('key'),u:getCookie('id'),shuiji:sj, val:mobile};
    $.post(url, pars, function (data){
        if(data.status == 200){
            t = setTimeout(countDown,1000);
        }else{
            $(".btn-mobile").attr("disabled", false);
            $(".btn-mobile").attr("readonly", false);
            errorTipsShow("<p>"+data.msg+"</p>");
        }
    },'json');
});
var delayTime = 60;
function countDown()
{
    delayTime--;
    $(".btn-mobile").val(delayTime + '秒后重新获取');
    if (delayTime == 0) {
        delayTime = 60;
        $(".btn-mobile").val("获取手机验证码");
        $(".btn-mobile").removeAttr("disabled");
        $(".btn-mobile").removeAttr("readonly");
        clearTimeout(t);
    }
    else
    {
        t=setTimeout(countDown,1000);
    }
}