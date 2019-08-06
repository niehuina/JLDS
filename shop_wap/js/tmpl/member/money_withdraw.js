$(function ()
{
    var a = getCookie("key");
    if (!a)
    {
        login();
        return;
    }
    $.ajax({
        type: "get",
        url: PayCenterWapUrl + "/index.php?ctl=Info&met=getWithDrawInfoForWap&typ=json",
        data: {k:a,u:getCookie('id'),},
        dataType: "json",
        success: function (result)
        {
            if (result.status == 200)
            {
                var userInfo = result.data.user_info;
                var userResource = result.data.user_resource;
                var cashamount_min = result.data.cashamount_min;
                var service_fee_list = result.data.service_fee_list;

                var html = '';
                $.each(service_fee_list, function (n, service) {
                    var serht = '<label>'
                        +'<input type="hidden" class="service_fee_id" value="' + service.id +'">'
                        +'<input type="hidden" class="service_fee_rates" value="' + service.fee_rates/100 +'">'
                        +'<input type="hidden" class="fee_min" value="' + service.fee_min +'">'
                        +'<input type="hidden" class="fee_max" value="' + service.fee_max +'">'
                        +'<input type="radio" name="service_select">'
                        + service.name + '(' + service.fee_rates + '%服务费)' + '</label>';
                    html += serht;
                });

                $("#service_type").html(html);
                $("#service_type>label").first().addClass("checked");
                $("#service_type>label").first().find("input[name='service_select']").attr("checked", "checked");

                var sphone = getPhoneStr(userInfo.user_mobile);
                $('#sphone').html(sphone);
                $('#phone').val(userInfo.user_mobile);
                $('#account_money').val(userResource.money);
                $('#cashamount_min').val(cashamount_min);
            }
        }
    });

    $.sValid.init({
        rules: {bank_name: "required", bank: "required", cardno: "required",
            withdraw_money: "required", yzm: "required", password: "required"},
        messages: {bank_name: "银行账号不能为空", bank: "银行不能为空", cardno: "银行卡号不能为空",
            withdraw_money: "提现金额不能为空", yzm: "验证码不能为空！", password: "密码不能为空！"},
        callback: function (a, e, r)
        {
            if (a.length > 0)
            {
                var i = "";
                $.map(e, function (a, e)
                {
                    i += "<p>" + a + "</p>";
                });
                errorTipsShow(i);
            }
            else
            {
                if($('#cardno').val().length<16 || $('#cardno').val().length >19) {
                    var i =  "<p>银行卡号格式错误</p>";
                    errorTipsShow(i);
                }else{
                    errorTipsHide();
                }
            }
        }
    });
    $("#header-nav").click(function ()
    {
        $(".btn").click()
    });
    $("#withdraw_btn").click(function ()
    {
        if ($.sValid())
        {
            var user_id=getCookie('id');
            var k=getCookie('key');

            var id        = $("#service_type>label.checked").find(".service_fee_id").val();
            var bank_name = $("#bank_name").val();
            var cardno    = $("#cardno").val();
            var bank      = $("#bank").val();
            var withdraw_money = $("#withdraw_money").val();
            var con       = $("#con").val();
            var password = $("#password").val();
            var mobile = $("#phone").val();
            var yzm = $.trim($("#yzm").val());

            $.ajax({
                type: "post",
                url: PayCenterWapUrl + "/index.php?ctl=Info&met=addWithdraw&typ=json",
                data: {
                    k: k, u: user_id,
                    id: id,
                    bank_name: bank_name,
                    cardno: cardno,
                    bank: bank,
                    withdraw_money: withdraw_money,
                    con: con,
                    paypasswd: password,
                    yzm: yzm,
                    mobile: mobile,
                },
                dataType: "json",
                success: function (a) {
                    if (a.status == 200) {
                        location.href = WapSiteUrl + "/tmpl/member/member_money.html"
                    } else if (a.status == 260) {
                        errorTipsShow("<p>验证码错误</p>");
                        $("#yzm").focus;
                    } else if (a.status == 230) {
                        errorTipsShow("<p>支付密码错误</p>");
                        $("#password").focus;
                    } else if (a.status == 240) {
                        errorTipsShow("<p>余额不足</p>");
                        $("#withdraw_money").focus;
                    }
                    else {
                        if (a.data) {
                            errorTipsShow("<p>"+a.data[0]+"</p>");
                        }
                        else {
                            errorTipsShow("<p>操作失败</p>");
                        }
                    }
                }
            });
        }
    });
});

$(".btn-mobile").click(function(){
    $(".btn-mobile").attr("disabled", "disabled");
    $(".btn-mobile").attr("readonly", "readonly");
    var mobile = $('#phone').val();

    var url = UCenterApiUrl +'/index.php?ctl=User&met=getYzmforMobile&typ=json';
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

function count()
{
    //计算服务费与付款总额
    var money = $("#withdraw_money").val();
    if(!money)
    {
        money = 0;
    }

    var service_checked = $("#service_type>label.checked");
    var seriver_fee = Number(money * (service_checked.find(".service_fee_rates").val()*1)).toFixed(2);
    var fee_min = service_checked.find(".fee_min").val();
    var fee_max = service_checked.find(".fee_max").val();
    if(seriver_fee*1 > fee_max*1){
        seriver_fee = fee_max;
    }else if(seriver_fee*1 < fee_min*1){
        seriver_fee = fee_min;
    }else{
        seriver_fee = seriver_fee;
    }

    var	acount_total = Number(seriver_fee*1+money*1).toFixed(2);
    $("#service_total").html(seriver_fee);
    $("#acount_total").html(acount_total);
    $("#con").focus();
}

//验证提现金额是否大于当前账户的余额
function checkMoney(e)
{
    var min_amount = $('#cashamount_min').val();
    var user_resource = $('#account_money').val();
    if(Number(user_resource) < Number($(e).val()))
    {
        str = '您的余额只有' + user_resource + '元。';
        errorTipsShow("<p>"+str+"</p>");
        $("#withdraw_money").val("");
        return false;
    }
    else if(Number($(e).val())<Number(min_amount)){
        str = '提现金额不能小于' + min_amount + '元。';
        errorTipsShow("<p>"+str+"</p>");
        $("#withdraw_money").val("");
        return false;
    }

    count();
}

/**
 * 实时动态强制更改用户录入
 * arg1 inputObject
 **/
function amount(th){
    var regStrs = [
        ['^0(\\d+)$', '$1'], //禁止录入整数部分两位以上，但首位为0
        ['[^\\d\\.]+$', ''], //禁止录入任何非数字和点
        ['\\.(\\d?)\\.+', '.$1'], //禁止录入两个以上的点
        ['^(\\d+\\.\\d{2}).+', '$1'] //禁止录入小数点后两位以上
    ];
    for(i=0; i<regStrs.length; i++){
        var reg = new RegExp(regStrs[i][0]);
        th.value = th.value.replace(reg, regStrs[i][1]);
    }
}
