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
            'url': ApiUrl + '/index.php?ctl=Buyer_User&met=stock_self_user_list&typ=json',
            'getparam': {
                k: key,
                u: getCookie('id'),
                user_id: getCookie('id')
            },
            'tmplid': 'list_model',
            'containerobj': $("#stock-list"),
            'iIntervalId': true,
        });
    }

    t();
});