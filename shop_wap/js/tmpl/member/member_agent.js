$(function () {
    var key = getCookie('key');
    if (!key) {
        window.location.href = WapSiteUrl + '/tmpl/member/login.html';
        return;
    }

    //渲染list
    function t() {
        var level = $("#filtrate_ul").find(".selected").find("a").data("level");
        var load_class = new ncScrollLoad();
        template.helper("getPhoneStr", function (e) {
            return e ? getPhoneStr(e) : '';
        });
        load_class.loadInit({
            'url': ApiUrl + '/index.php?ctl=Buyer_User&met=getUserChildren&typ=json',
            'getparam': {
                k: key,
                u: getCookie('id'),
                level: level,
            },
            'tmplid': 'list_model',
            'containerobj': $("#user-list"),
            'iIntervalId': true
        });
    }

    $("#filtrate_ul").find("a").click(function () {
        $("#filtrate_ul").find("li").removeClass("selected");
        $(this).parent().addClass("selected").siblings().removeClass("selected");
        window.scrollTo(0, 0);
        t();
    });

    t();
});