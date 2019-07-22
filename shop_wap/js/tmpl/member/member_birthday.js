$(function ()
{
    var a = getCookie("key");
    if (!a)
    {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html";
        return
    }
    $.ajax({
        type: "get",
        url:ApiUrl+"/index.php?ctl=Buyer_User&met=getUserInfo&typ=json",
        data: {k:a,u:getCookie('id'),},
        dataType: "json",
        success: function (result)
        {
            if (result.status == 200)
            {
                $('#date').val(formatDate(result.data.user_birthday));
            }
        }
    });
    $.sValid.init({
        rules: {date: "required"},
        messages: {date: "生日必填！"},
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
    $(".btn-green").click(function ()
    {
        if ($.sValid())
        {
            var e = $("#date").val();
            var user_id=getCookie('id');
            var k=getCookie('key');

            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?ctl=Buyer_User&met=updateUserInfo&typ=json",
                data: {k:k,u:getCookie('id'),user_id:user_id, user_birthday:e},
                dataType: "json",
                success: function (result)
                {
                    if (a)
                    {
                        location.href = WapSiteUrl + "/tmpl/member/member_info.html"
                    }
                    else
                    {
                        location.href = WapSiteUrl
                    }
                }
            })
        }
    });
});
function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}