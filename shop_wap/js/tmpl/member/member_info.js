$(function ()
{
    var e = getCookie("key");
    if (!e)
    {
        login();
        return;
    }
    $.ajax({
        type: "get",  url:ApiUrl+"/index.php?ctl=Buyer_User&met=getUserInfo&typ=json", data: {k:e,u:getCookie('id'),}, dataType: "json", success: function (result)
        {
            if (result.status == 200)
            {
                $('#user_avatar').attr('src',result.data.user_logo==""?"../../images/defulat_user.png":result.data.user_logo);
                $('#user_name').html(result.data.user_nickname);
                $('#user_birthday').html(result.data.user_birthday);
            }
            else
            {
            }
        }
    });
});