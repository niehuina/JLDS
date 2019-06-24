var pagesize = pagesize;
var curpage = 1;
var firstRow = 0;
var hasmore = true;
var footer = false;
var keyword = decodeURIComponent(getQueryString("keyword"));
var key = getQueryString("key");
var actgoods = getQueryString("actgoods");
var ci = getQueryString("ci");
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
        var flag = selectAllCheck();
        if(!flag) return false;
        if(Object.keys(select_goods_list).length == 0){
            return false;
        }else{
            param.out_num_list = JSON.stringify(select_goods_list);
            param.k = getCookie("key");
            param.u = getCookie('id');

            //本系统登录
            $.ajax({
                type: "get",
                url: ApiUrl + "/index.php?ctl=Seller_Stock_Order&met=stock_self_use&typ=json",
                data:param,
                dataType: "json",
                success: function(result){
                    if (result.status == 200) {
                        window.location.href = WapSiteUrl + '/tmpl/member/member_stock.html';
                        return true;
                    } else {
                        $.sDialog({
                            skin:"red",
                            content:'自用失败！',
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

    if (keyword != "")
    {
        $("#keyword").html(keyword)
    }
    $("#show_style").click(function ()
    {
        if ($("#product_list").hasClass("grid"))
        {
            $(this).find("span").addClass("browse-grid").removeClass("browse-list");
            $("#product_list").removeClass("grid").addClass("list")
        }
        else
        {
            $(this).find("span").removeClass("browse-grid").addClass("browse-list");
            $("#product_list").addClass("grid").removeClass("list")
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

    $.getJSON(ApiUrl + "/index.php?ctl=Seller_Stock_Order&met=stock_goods&typ=json&ua=wap", param, function (e)
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
    $("#product_list .nctouch-cart-item").html("");
    $("#footer").removeClass("posa");
    get_list();
}
function cbkCheck(obj) {
    var stock_id = $(obj).data("stock_id");
    var goods_stock = $(obj).data('goods_stock');
    var row_num = $(obj).data('row_num');
    if(obj.checked == true){
        var out_num = $("#"+stock_id).find("input[name='out_num']").val();
        if(out_num && out_num > 0 && out_num <= goods_stock){
            select_goods_list[stock_id] = out_num;
        }else{
            if(out_num && out_num > goods_stock){
                $.sDialog({
                    skin:"red",
                    content:'选中的商品自用数量不能大于商品库存！',
                    okBtn:false,
                    cancelBtn:false
                });
            }else if(!out_num || out_num == 0){
                $.sDialog({
                    skin:"red",
                    content:'选中的商品未填写自用数量！',
                    okBtn:false,
                    cancelBtn:false
                });
            }
            $("#"+stock_id).find("input[name='out_num']").focus();
        }
    }else if(obj.checked == false){
        delete select_goods_list[stock_id];
    }
}

function selectAllCheck() {
    var flag = true;
    $("#product_list .nctouch-cart-item").find("input[name='check']:checked").each(function(){
        var stock_id = $(this).data('stock_id');
        var goods_stock = $(this).data('goods_stock');
        var row_num = $(this).data('row_num');
        var out_num = $("#"+stock_id).find("input[name='out_num']").val();
        if(out_num && out_num > 0 && out_num <= goods_stock){
            select_goods_list[stock_id] = out_num;
        }else{
            if(out_num && out_num > goods_stock){
                $.sDialog({
                    skin:"red",
                    content:'选中的第【'+row_num+'】行商品自用数量不能大于商品库存！',
                    okBtn:false,
                    cancelBtn:false
                });
            }else if(!out_num || out_num == 0){
                $.sDialog({
                    skin:"red",
                    content:'选中的第【'+row_num+'】行商品未填写自用数量！',
                    okBtn:false,
                    cancelBtn:false
                });
            }
            $("#"+stock_id).find("input[name='out_num']").focus();
            flag = false;
        }
    });
    return flag;
}