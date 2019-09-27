var queryConditions = {

};

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

function initGrid(check_id) {
    var a = ['商品名称', '商品自用数量']
        , b = [{
        name: "goods_name",
        index: "goods_name",
        width: 400
    }, {
        name: "out_num",
        index: "out_num",
        align: "center",
        width: 200
    }];
    $("#checks_grid").jqGrid({
        url: SITE_URL + "?ctl=Seller_Stock_Order&met=self_use_goods&typ=json",
        postData: {
            out_order_id : $("#out_order_id").val()
        },
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
            id: "out_id"
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

    initGrid($("#check_id").val());

    //查询
    $('.btn_search_check').click(function () {
        queryConditions.out_order_id = $("#out_order_id").val();
        queryConditions.goods_key = $("#goods_key").val();
        reloadGrid(queryConditions);
    });
})
