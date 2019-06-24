$(function ()
{
    var e = getCookie("key");
    if (!e)
    {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html";
        return
    }
    $.ajax({
        type: "get",  url:ApiUrl+"/index.php?ctl=Buyer_User&met=certificationForWap&typ=json", data: {k:e,u:getCookie('id'),user_id:getCookie('id')}, dataType: "json", success: function (result)
        {
            if (result.status == 200)
            {
                if(result.data.user_identity_statu=="0"){
                    $('#certificationStatus').html("未认证");
                }else if(result.data.user_identity_statu=="1"){
                    $('#certificationStatus').html("待审核");
                }else if(result.data.user_identity_statu=="2"){
                    $('#certificationStatus').html("已认证");
                }else if(result.data.user_identity_statu=="3"){
                    $('#certificationStatus').html("认证失败");
                }else{
                    $('#certificationStatus').html("未认证");
                }
            }
            else
            {
                $('#certificationStatus').html("未认证");
            }
        }
    });
});