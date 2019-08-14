$(function () {
    var key = getCookie('key');
    if (!key) {
        login();
        return;
    }

    //渲染list
    function t() {
        var load_class = new ncScrollLoad();
        load_class.loadInit({
            'url': ApiUrl + '/index.php?ctl=Buyer_User&met=check_details_goods&typ=json',
            'getparam': {
                k: key,
                u: getCookie('id'),
                user_id: getCookie('id'),
                check_id: getQueryString('check_id'),
            },
            'tmplid': 'list_model',
            'containerobj': $("#stock-list"),
            'iIntervalId': true,
            'callback': function (data) {
                $(".goods_count").html('数量：'+data.goods_count);
            }
        });
    }

    t();
});