var pagesize = pagesize;
var curpage = 1;
var firstRow = 0;
var hasmore = true;
var footer = false;
var myDate = new Date;
if(!getCookie('sub_site_id')){
    addCookie('sub_site_id',0,0);
}
var sub_site_id = getCookie('sub_site_id');
$(function ()
{
    get_list();
    $("#search_btn").click(function () {
        init_get_list();
    });

    $(window).scroll(function ()
    {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1)
        {
            get_list();
        }
    });
});
function get_list()
{
    $(".loading").remove();
    if (!hasmore)
    {
        return false
    }
    hasmore = false;
    param = {};
    param.goods_key = $("#goods_key").val();
    param.rows = pagesize;
    param.page = curpage;
    param.firstRow = firstRow;

    param.k = getCookie("key");
    param.u = getCookie('id');
    param.user_id = getCookie('id');

    // var load_class = new ncScrollLoad();
    // load_class.loadInit({
    //     'url': ApiUrl + '/index.php?ctl=Buyer_User&met=stock_goods&typ=json&ua=wap',
    //     'getparam': {
    //         k: getCookie("key"),
    //         u: getCookie('id'),
    //         user_id: getCookie('id'),
    //         goods_key: $("#goods_key").val(),
    //     },
    //     'tmplid': 'home_body',
    //     'containerobj': $("#product_list .goods-secrch-list"),
    //     'iIntervalId': true
    // });


    $.getJSON(SiteUrl + "/index.php?ctl=Buyer_User&met=stock_goods&typ=json&ua=wap", param, function (e)
    {
        if (!e)
        {
            e = [];
            e.data = [];
            e.data.items = []
        }
        $(".loading").remove();

        curpage++;
        var r = template.render("home_body", e);
        $("#product_list .goods-secrch-list").append(r);
        //hasmore = e.hasmore
        if(e.data.page < e.data.total)
        {
            firstRow = e.data.records;
            hasmore = true;
        }
        else
        {
            hasmore = false;
        }
    })
}
function init_get_list(e, r)
{
    order = e;
    key = r;
    curpage = 1;
    firstRow = 0;
    hasmore = true;
    $("#product_list .goods-secrch-list").html("");
    $("#footer").removeClass("posa");
    get_list();
}