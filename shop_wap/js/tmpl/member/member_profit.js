$(function () {
    var key = getCookie('key');
    if (!key) {
        window.location.href = WapSiteUrl + '/tmpl/member/login.html';
        return;
    }

    $(".nctouch-default-list").hide();
    //获取返利金额
    var url = SiteUrl + '/index.php?ctl=Buyer_User&met=getRecordAmountByUserId&typ=json';
    var data = {k:key, u:getCookie('id'), user_id: getCookie('id'), user_type: 1, type: [13,14,15,16]};
    $.getJSON(url, data, function (result){
        $("#profit").text(sprintf('%0.2f',result.data.amount));
        var user_grade = result.data.user_grade;
        $(".v"+user_grade).show();
        if(result.data.self_user_id == getCookie('id')){
            $(".stock").hide();
        }
    });
});