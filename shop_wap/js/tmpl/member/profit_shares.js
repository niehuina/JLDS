$(function () {
    var key = getCookie('key');
    if (!key) {
        window.location.href = WapSiteUrl + '/tmpl/member/login.html';
        return;
    }

    //渲染list
    function t() {
        var load_class = new ncScrollLoad();
        load_class.loadInit({
            'url': ApiUrl + '/index.php?ctl=Buyer_User&met=getProfit&typ=json',
            'getparam': {
                k: key,
                u: getCookie('id'),
                type: 16,
            },
            'tmplid': 'list_model',
            'containerobj': $("#profit-list"),
            'iIntervalId': true,
            'callback': function (data) {
                $("#shares_profit").text(sprintf('%0.2f',data.amount));
            }
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