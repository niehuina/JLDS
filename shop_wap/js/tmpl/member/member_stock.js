$(function () {
    ucenterLogin();
});
$(function(){
    var e = getCookie("key");
    if (!e) {
        login();
        return;
    }

    $.ajax({
        type:'post',
        url: ApiUrl + "/index.php?ctl=Buyer_User&met=getUserInfo&typ=json",
        data: {k: e, u: getCookie('id')},
        dataType:'json',
        //jsonp:'callback',
        success:function(result){
            if (result.status == 250) {
                return false;
            }
            checkLogin(result.login);
            var html = '<div class="member-info">'
                + '<div class="user-avatar"> <img src="' + result.data.user_logo + '"/> </div>'
                + '<div class="user-name"> <span>'+result.data.user_name+'<sup>V' + result.data.user_grade + '</sup></span> </div>'
                // + '<p>确认收货7天以后到账</p>'
                + '</a> </span></div>';
            //渲染页面
            $(".member-top").html(html);

            return false;
        }
    });

    //滚动header固定到顶部
    $.scrollTransparent();

});