var pagesize = pagesize;
var curpage = 1;
var firstRow = 0;
var hasmore = true;
var footer = false;
var keyword = decodeURIComponent(getQueryString("keyword"));
var key = getQueryString("key");
var myDate = new Date;

var select_goods_list = {};

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

    $(document).on("click", ".JS-edit", function() {
        var flag = getAllGoods();
        if(!flag) return false;
        if(Object.keys(select_goods_list).length == 0){
            return false;
        }else{
            param.real_stock_list = JSON.stringify(select_goods_list);
            param.k = getCookie("key");
            param.u = getCookie('id');

            //本系统登录
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?ctl=Buyer_User&met=stock_check&typ=json",
                data:param,
                dataType: "json",
                success: function(result){
                    if (result.status == 200) {
                        window.location.href = WapSiteUrl + '/tmpl/member/member_stock.html';
                        return true;
                    } else {
                        $.sDialog({
                            skin:"red",
                            content:'库存盘点失败！',
                            okBtn:false,
                            cancelBtn:false
                        });
                        return false;
                    }
                },
                error: function(){
                    errorTipsShow('<p>' + result.msg + '</p>');
                }
            });


        }
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

    $.getJSON(ApiUrl + "/index.php?ctl=Buyer_User&met=stock_goods&typ=json&ua=wap", param, function (e)
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
        $("#product_list .nctouch-cart-item").append(r);
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

function addNum(stock_id) {
    var check_num_obj = $("#"+stock_id).find("input[name='check_num']");
    var check_num = $(check_num_obj).val();
    var num_max = $(check_num_obj).data('max');
    //if(check_num == num_max) return;

    check_num = check_num*1 + 1;
    $(check_num_obj).val(check_num);
}

function reduceNum(stock_id) {
    var check_num_obj = $("#"+stock_id).find("input[name='check_num']");
    var check_num = $(check_num_obj).val();
    var num_min = $(check_num_obj).data('min');
    if(check_num == num_min) return;
    check_num = check_num*1 - 1;
    $(check_num_obj).val(check_num);
}

function formatNum(obj) {
    var check_num = $(obj).val();
    if(check_num == "" || isNaN(check_num)){
        $(obj).val("0");
    }
}

function getAllGoods() {
    var flag = true;
    $("#product_list .nctouch-cart-item").find(".cart-litemw-cnt").each(function(){
        var stock_id = this.id;
        var check_num_obj = $("#"+stock_id).find("input[name='check_num']");
        var check_num = $(check_num_obj).val();
        // var goods_stock = $(check_num_obj).data('max');
        if(check_num && check_num > 0){
            select_goods_list[stock_id] = check_num;
        }else{
            $("#"+stock_id).find("input[name='check_num']").focus();
            flag = false;
        }
    });
    return flag;
}