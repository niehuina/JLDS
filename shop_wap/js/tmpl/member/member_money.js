$(function ()
{
    var e = getCookie("key");
    if (!e)
    {
        login();
        return;
    }

    //获取余额
    var url = PayCenterWapUrl + '/index.php?ctl=Info&met=getUserResourceInfo&typ=json';
    var data = {k:e, u:getCookie('id')};
    $.getJSON(url, data, function (result){
        $("#member_money").text(result.data.user_money);
        $("#member_card").text(result.data.user_recharge_card);
    });

    //判断是否实名认证
    $("#depositBtn,#withdrawBtn").click(function () {
        var btnName = this.value;
        $.post(PayCenterWapUrl + '/index.php?ctl=Info&met=getUserRealName&typ=json', data,
            function (result){
                if(result.status == 200){
                    window.location.href = WapSiteUrl + "/tmpl/member/money_" + btnName + ".html";
                }else{
                    errorTipsShow("<p>" + result.msg + "</p>");
                }
            }
        );
    });
});