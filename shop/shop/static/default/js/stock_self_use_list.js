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

function initGrid() {
    var a = ['商品自用时间', "自用商品数", '操作']
        , b = [{
        name: "out_time",
        index: "out_time",
        width: 200
    }, {
        name: "good_count",
        index: "good_count",
        align: "center",
        width: 100
    }, {
        name: "",
        width: 100,
        align: "center",
        formatter: function (val, opt, row) {
            var html_con = '<a href="' + SITE_URL + '?ctl=Seller_Stock_Order&met=stock_self_use_detail&typ=e&out_order_id=' + row.out_order_id + '" target="_blank" name="viewDetail">查看详情</a>';
            return html_con;
        }
    }];
    $("#checks_grid").jqGrid({
        url: SITE_URL + "?ctl=Seller_Stock_Order&met=stock_self_use_list&typ=json",
        postdata: queryConditions,
        datatype: "json",
        width: 958,
        height: 500,
        altRows: !0,
        rownumbers: true,
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
        pager: "#checks_page",
        jsonReader: {
            root: "data.items",
            records: "data.records",
            total: "data.total",
            repeatitems: !1,
            id: "out_order_id"
        },
        loadComplete: function (a) {
            if (a && 200 == a.status) {
                var b = {};
                a = a.data;
                for (var c = 0; c < a.items.length; c++) {
                    var d = a.items[c];
                    b[d.Name] = d
                }
                $("#checks_grid").data("gridData", b)
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
    });

    $("#checks_grid").jqGrid('setLabel', 'rn', '序号', {'text-align': 'left'}, '');
}

function reloadGrid(data) {
    $("#checks_grid").jqGrid('setGridParam', {postData: data}).trigger("reloadGrid");
}

$(function () {
    //时间
    $('#query_start_date').datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        onShow: function (ct) {
            this.setOptions({
                maxDate: $('#query_end_date').val() ? $('#query_end_date').val() : false
            })
        }
    });
    $('#query_end_date').datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        onShow: function (ct) {
            this.setOptions({
                minDate: $('#query_start_date').val() ? $('#query_start_date').val() : false
            })
        },
    });

    initGrid();

    //查询
    $('.btn_search_check').click(function () {
        queryConditions.query_start_date = $("#query_start_date").val();
        queryConditions.query_end_date = $("#query_end_date").val();
        reloadGrid(queryConditions);
    });
})
