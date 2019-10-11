$(function ()
{
    var a = getCookie("key");
    var message_id=getCookie("message_id");
    if (!a)
    {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html";
        return
    }
    $.ajax({
        type: "get",   url: ApiUrl + "/index.php?ctl=Buyer_Message&met=getMessage&typ=json",data: {k: a, u: getCookie('id'), id:message_id}, dataType: "json", success: function (result)
        {
            if (result.status == 200)
            {
                $('.header-title').find('h1').html(result.data.message_title);
                $('#content').html(result.data.message_content);
            }
        }
    });
});