$(function () {
    var key = getCookie('key');
    if (!key) {
        window.location.href = WapSiteUrl + '/tmpl/member/login.html';
        return;
    }

    //渲染list
    function t() {
        var status = $("#filtrate_ul").find(".selected").find("a").data("status");
        var load_class = new ncScrollLoad();
        load_class.loadInit({
            'url': ApiUrl + '/index.php?ctl=Buyer_Order&met=getProfit&typ=json',
            'getparam': {
                k: key,
                u: getCookie('id'),
                status: status,
            },
            'tmplid': 'list_model',
            'containerobj': $("#profit-list"),
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