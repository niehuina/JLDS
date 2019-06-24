$(function ()
{
    var e = getCookie("key");
    if (!e)
    {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html";
        return;
    }

    //获取余额
    var url = PayCenterWapUrl + '/index.php?ctl=Info&met=getUserResourceInfo&typ=json';
    var data = {k:e, u:getCookie('id')};
    $.post(url, data, function (result){
        $("#member_money").text(result.data.user_money);
        $("#card_money").text(result.data.user_recharge_card);
    });
});