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
        template.helper("getPhoneStr", function (e) {
            return e ? getPhoneStr(e) : '';
        });
        if(status == 0){
            load_class.loadInit({
                'url': ApiUrl + '/index.php?ctl=Seller_Stock_Order&met=get_order_profit&typ=json',
                'getparam': {
                    k: key,
                    u: getCookie('id'),
                    status: status,
                },
                'tmplid': 'list_model',
                'containerobj': $("#profit-list"),
                'iIntervalId': true,
                'callback': function (data) {
                    $("#profit_money").text(sprintf('%0.2f',data.amount));
                }
            });
        }else{
            $("#profit-list").html('');
            load_class.loadInit({
                'url': ApiUrl + '/index.php?ctl=Buyer_User&met=getProfit&typ=json',
                'getparam': {
                    k: key,
                    u: getCookie('id'),
                    status: status,
                    type: 13,
                },
                'tmplid': 'record_model',
                'containerobj': $("#profit-list"),
                'iIntervalId': true,
                'callback': function (data) {
                    //$("#profit_money").text(sprintf('%0.2f',data.amount));
                }
            });
        }
    }

    $("#filtrate_ul").find("a").click(function () {
        $("#filtrate_ul").find("li").removeClass("selected");
        $(this).parent().addClass("selected").siblings().removeClass("selected");
        window.scrollTo(0, 0);
        t();
    });

    t();
});