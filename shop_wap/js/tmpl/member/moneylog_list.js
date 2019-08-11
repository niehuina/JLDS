var log_type = 1;
$(function () {
    var key = getCookie('key');
    if (!key) {
        login();
        return;
    }

    template.helper("getPayUrl", function (orderid) {
        return WapSiteUrl + "/tmpl/member/money_deposit_pay.html?uorder=" + orderid;
    });

    //渲染list
    function t() {
        var load_class = new ncScrollLoad();
        load_class.loadInit({
            'url': ApiUrl + '/index.php?ctl=Buyer_User&met=getRecordListForWap&typ=json',
            'getparam': {
                k: key,
                u: getCookie('id'),
                type: log_type,
                user_id:getCookie('id'),
            },
            'tmplid': 'list_model',
            'containerobj': $("#moneyloglist"),
            'iIntervalId': true
        });

        $(document).on('click', 'li.deposit', function() {
            var order_id = $(this).data('order-id');
            window.location.href = WapSiteUrl + "/tmpl/member/money_deposit_pay.html?uorder=" + order_id;
        });
    }

    $("#filtrate_ul").find("a").click(function () {
        $("#filtrate_ul").find("li").removeClass("selected");
        $(this).parent().addClass("selected").siblings().removeClass("selected");
        window.scrollTo(0, 0);
        log_type = $("#filtrate_ul").find(".selected").find("a").attr("data-type");
        $("#moneyloglist").html('');
        t();
    });


    //获取余额
    var url = PayCenterWapUrl + '/index.php?ctl=Info&met=getUserResourceInfo&typ=json';
    var data = {k:key, u:getCookie('id')};
    $.post(url, data, function (result){
        var money = result.data;
        $("#moneyCount").text((parseFloat(money.user_money) + parseFloat(money.user_recharge_card)).toFixed(2));
    });

    t();
});