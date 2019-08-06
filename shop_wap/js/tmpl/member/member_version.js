$(function ()
{
    var e = getCookie("key");
    if (e === null)
    {
        login();
        return;
    }

    $.ajax({
        url: ApiUrl + "/index.php?ctl=Api_Wap&met=versionImage&typ=json", type: "post", dataType: "json", data: {k: e, u: getCookie('id')}, success: function (data)
        {
            if (checkLogin(e.login))
            {
                $('#img').attr('src', data.data.shop_logo);
                $('.shop_version').html('V'+data.data.version);
            }
        }
    })

});