var queryConditions = {};

function setGrid(adjustH, adjustW) {
    var defaultPage = Public.getDefaultPage();
    if (defaultPage.SYSTEM.skin === 'green') {
        var adjustH = adjustH || 70;
    } else {
        var adjustH = adjustH || 65;
    }
    var adjustW = adjustW || 20;
    var gridW = $(window).width() - adjustW,
        gridH = $(window).height() - $(".grid-wrap").offset().top - adjustH;
    return {
        w: gridW,
        h: gridH
    }
};

function recordPageAll() {
    var flag = true;
    $("#goods_grid tbody").find(":checkbox[name='check']:checked").each(function () {
        var stock_order_goods_id = $(this).data('stock_order_goods_id');
        select_goods_list[stock_order_goods_id] = stock_order_goods_id;
    });
    return flag;
}

function unSelectAllCheck() {
    var flag = true;
    $("#goods_grid tbody").find(":checkbox[name='check']").each(function(){
        var stock_order_goods_id = $(this).data('stock_order_goods_id');
        if(select_goods_list.hasOwnProperty(stock_order_goods_id)){
            delete select_goods_list[stock_order_goods_id];
        }
    });
    return flag;
}

function initGrid() {
    var a = ['', "商品名称", "商品价格", "购买件数", "购买金额"]
        , b = [{
        name: "goods_id",
        index: "goods_id",
        hidden: true
    }, {
        name: "goods_name",
        index: "goods_name",
        width: 400
    }, {
        name: "goods_price_vip",
        index: "goods_price_vip",
        width: 150
    }, {
        name: "goods_num",
        index: "goods_num",
        width: 100
    }, {
        name: "order_goods_amount_vip",
        index: "order_goods_amount_vip",
        align: "right",
        width: 100
    }];
    $("#goods_grid").jqGrid({
        url: "index.php?ctl=Seller_Stock_Order&met=stock_orderGoods&typ=json&order_id="+$('input[name="order_id"]').val(),
        postData: queryConditions,
        datatype: "json",
        width: 958,
        height: 500,
        altRows: !0,
        rownumbers: true,
        multiselect: true,
        multiboxonly: false,
        gridview: !0,
        rowNum: 15,
        colNames: a,
        colModel: b,
        shrinkToFit: false,
        forceFit: true,
        autowidth: false,
        viewrecords: !0,
        cmTemplate: {
            sortable: !1,
            title: !1
        },
        page: 1,
        pager: "#goods_page",
        jsonReader: {
            root: "data.items",
            records: "data.records",
            total: "data.total",
            repeatitems: !1,
            id: "stock_order_goods_id"
        },
        loadComplete: function (a) {
            if (a && 200 == a.status) {
                var b = {};
                a = a.data;
                for (var c = 0; c < a.items.length; c++) {
                    var d = a.items[c];
                    b[d.Name] = d
                }
                $("#goods_grid").data("gridData", b)
            } else {
                var e = 250 == a.status ? "没有数据！" : "获取数据失败！" + a.msg;
                parent.Public.tips({
                    type: 2,
                    content: e
                })
            }
        },
        loadError: function (a, b, c) {
            parent.Public.tips({
                type: 1,
                content: "操作失败了哦，请检查您的网络链接！"
            })
        },
        gridComplete: function () {
            var rowIds = $("#goods_grid").jqGrid('getDataIDs');
            for (var k = 0; k < rowIds.length; k++) {
                var stock_order_goods_id = rowIds[k];
                var curChk = $("#"+stock_order_goods_id+"").find(":checkbox");
                curChk.attr('name', 'check');
                curChk.attr('data-stock_order_goods_id', stock_order_goods_id);

                if (select_goods_list.hasOwnProperty(stock_order_goods_id)) {
                    $(curChk).prop("checked","true");
                }
            }

            //下面的代码顺序不能变(这是页面上所有行被真选中[所有行被黄色])
            $("#cb_goods_grid").click(function () {
                if(this.checked){
                    $("#goods_grid tbody").find(":checkbox").prop("checked", "true");
                    recordPageAll();
                }else{
                    unSelectAllCheck();
                }
            });   //input框
            $("#jqgh_goods_grid_cb").click();   //div标签
            $("#goods_grid_cb").click();   //th标签
        },
        beforeRequest: function () {
            var flag = recordPageAll();
            return flag;
        },
    });

    $("#goods_grid").jqGrid('setLabel', 'rn', '序号', {'text-align': 'left'}, '');
}

function reloadGrid(data) {
    select_goods_list = api.data.select_goods_list;
    $("#goods_grid").jqGrid('setGridParam', {postData: data}).trigger("reloadGrid");
}

$(function () {

    initGrid();

    //查询
    $('#search_form .btn_search_goods').click(function () {
        queryConditions.goods_key = $("#goods_key").val();
        queryConditions.order_id = $('input[name="order_id"]').val();
        reloadGrid(queryConditions);
    });
})