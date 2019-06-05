var select_goods_list = {};
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
    $("#goods_grid tbody").find("input[name='real_goods_stock']").each(function () {
        var real_goods_stock = $(this).val();
        var stock_id = $(this).data('stock_id');
        var row_num = $(this).data('row_num');
        if (!real_goods_stock) {
            Public.tips.error('选中的第【' + row_num + '】行商品未填写实际库存！');
            $("#" + stock_id).find("input[name='real_goods_stock']").focus();
            flag = false;
        }else{
            //select_goods_list[stock_id] = real_goods_stock;
        }
    });
    if (flag) {
        $("#real_stock_list").val(JSON.stringify(select_goods_list));
    }
    return flag;
}

function setSelect(obj) {
    var real_goods_stock = $(obj).val();
    var stock_id = $(obj).data('stock_id');
    var row_num = $(obj).data('row_num');
    if (real_goods_stock) {
        select_goods_list[stock_id] = real_goods_stock;
    } else {
        Public.tips.error('选中的第【' + row_num + '】行商品未填写实际库存！');
        $("#" + stock_id).find("input[name='real_goods_stock']").focus();
    }
}

function initGrid() {
    var a = ['', "商品名称", "商品库存", "实际库存"]
        , b = [{
        name: "goods_id",
        index: "goods_id",
        hidden: true
    }, {
        name: "goods_name",
        index: "goods_name",
        width: 400
    }, {
        name: "goods_stock",
        index: "goods_stock",
        align: "right",
        width: 100
    }, {
        name: "real_goods_stock",
        width: 100,
        formatter: function (val, opt, row) {
            var html_con = '<input type="number" name="real_goods_stock" role="textbox" class="textbox" autocomplete="false" onchange="setSelect(this);" ' +
                'value="' + row.goods_stock + '"data-goods_id="' + row.goods_id + '" data-stock_id="' + row.stock_id + '" /></div>';
            return html_con;
        }
    }];
    $("#goods_grid").jqGrid({
        url: SITE_URL + "?ctl=Seller_Stock_Order&met=stock_goods&typ=json",
        postdata: queryConditions,
        datatype: "json",
        width: 958,
        height: 500,
        altRows: !0,
        rownumbers: true,
        multiboxonly: false,
        gridview: !0,
        rowNum: 5,
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
            id: "stock_id"
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
                var stock_id = rowIds[k];
                var curRowData = $("#goods_grid").jqGrid('getRowData', stock_id);
                var input = $("#" + stock_id + "").find("input[name='real_goods_stock']");
                input.attr('data-goods_id', stock_id);
                input.attr('data-row_num', k + 1);

                var real_goods_stock_cell = $("#" + stock_id).find("input[name='real_goods_stock']");
                if (select_goods_list.hasOwnProperty(stock_id)) {
                    $(real_goods_stock_cell).val(select_goods_list[stock_id]);
                }
                // else{
                //     select_goods_list[stock_id] = curRowData['goods_stock'];
                // }
            }

        },
        beforeRequest: function () {
            var flag = recordPageAll();
            return flag;
        },
    });

    $("#goods_grid").jqGrid('setLabel', 'rn', '序号', {'text-align': 'left'}, '');
}

function reloadGrid(data) {
    select_goods_list = {};
    $("#goods_grid").jqGrid('setGridParam', {postData: data}).trigger("reloadGrid");
}

$(function () {
    initGrid();

    //查询
    $('#search_form .btn_search_goods').click(function () {
        queryConditions.goods_key = $("#goods_key").val();
        reloadGrid(queryConditions);
    });
})