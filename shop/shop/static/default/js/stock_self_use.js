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
                Public.tips.error('选中的商品自用数量不能大于商品库存！');
            }else if(!out_num){
                Public.tips.error('选中的商品未填写自用数量！');
            }
            $("#"+stock_id).find("input[name='out_num']").focus();
        }
    }else if(obj.checked == false){
        delete select_goods_list[stock_id];
    }
    disabledButton();
}

function selectAllCheck() {
    var flag = true;
    $("#goods_grid tbody").find(":checkbox[name='check']:checked").each(function(){
        var stock_id = $(this).data('stock_id');
        var goods_stock = $(this).data('goods_stock');
        var row_num = $(this).data('row_num');
        var out_num = $("#"+stock_id).find("input[name='out_num']").val();
        if(out_num && out_num > 0 && out_num <= goods_stock){
            select_goods_list[stock_id] = out_num;
        }else{
            if(out_num && out_num > goods_stock){
                Public.tips.error('选中的第【'+row_num+'】行商品自用数量不能大于商品库存！');
            }else if(!out_num){
                Public.tips.error('选中的第【'+row_num+'】行商品未填写自用数量！');
            }
            $("#"+stock_id).find("input[name='out_num']").focus();
            flag = false;
        }
    });
    if(flag){
        $("#out_num_list").val(JSON.stringify(select_goods_list));
    }
    return flag;
}

function unSelectAllCheck() {
    var flag = true;
    $("#goods_grid tbody").find(":checkbox[name='check']").each(function(){
        var stock_id = $(this).data('stock_id');
        if(select_goods_list.hasOwnProperty(stock_id)){
            delete select_goods_list[stock_id];
        }
    });
    return flag;
}

function disabledButton(){
    if(Object.keys(select_goods_list).length > 0){
        $('#button_submit').attr('disabled',false).addClass('bbc_seller_submit_btns').removeClass('bbc_sellerGray_submit_btns');
    }else {
        $('#button_submit').attr('disabled',true).removeClass('bbc_seller_submit_btns').addClass('bbc_sellerGray_submit_btns');
    }
}

function initGrid() {
    var a = ['', "商品名称", "商品库存", "自用数量"]
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
        name: "out_num",
        width: 100,
        formatter: function (val, opt, row) {
            var html_con = '<input type="number" name="out_num" role="textbox" class="textbox" autocomplete="false" ' +
                '"data-goods_id="' + row.goods_id + '" data-stock_id="' + row.stock_id + '" data-goods_stock="'+ row.goods_stock +'" /></div>';
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
                var curChk = $("#"+stock_id+"").find(":checkbox");
                curChk.attr('name', 'check');
                curChk.attr('data-stock_id', stock_id);
                curChk.attr('data-goods_stock', curRowData['goods_stock']);
                curChk.attr('data-row_num', k+1);

                var out_num_cell = $("#" + stock_id).find("input[name='out_num']");
                if (select_goods_list.hasOwnProperty(stock_id)) {
                    $(curChk).prop("checked","true");
                    $(out_num_cell).val(select_goods_list[stock_id]);
                }

                $(curChk).click(function () {
                    cbkCheck(this);
                });
            }

            $("input[name='out_num']").blur(function () {
                var stock_id = $(this).data('stock_id');
                var curChk = $("#"+stock_id+"").find(":checkbox");
                var checked = $(curChk).attr("checked");
                if(checked == "checked"){
                    var goods_stock = $(this).data('goods_stock');
                    var out_num = $(this).val();
                    if(out_num && out_num > 0 && out_num <= goods_stock){
                        select_goods_list[stock_id] = out_num;
                    }else{
                        delete select_goods_list[stock_id];
                    }
                    disabledButton();
                }
            });

            //下面的代码顺序不能变(这是页面上所有行被真选中[所有行被黄色])
            $("#cb_goods_grid").click(function () {
                if(this.checked){
                    $("#goods_grid tbody").find(":checkbox").prop("checked", "true");
                    selectAllCheck(true);
                }else{
                    unSelectAllCheck();
                }
            });   //input框
            $("#jqgh_goods_grid_cb").click();   //div标签
            $("#goods_grid_cb").click();   //th标签

        },
        beforeSelectRow: function (rowid, e) {
            var $myGrid = $(this),
                i = $.jgrid.getCellIndex($(e.target).closest('td')[0]),
                cm = $myGrid.jqGrid('getGridParam', 'colModel');
            return (cm[i].name === 'cb');
        },
        beforeRequest: function () {
            var flag = selectAllCheck();
            disabledButton();
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

    $("#button_submit").click(function () {
        var flag = selectAllCheck();
        if(flag) {
            var url = "index.php?ctl=Seller_Stock_Order&met=stock_self_use&typ=json";
            var form_ser = {
                out_num_list: $("#out_num_list").val()
            };
            $.post(url, form_ser, function (data) {
                if (data.status == 200) {
                    parent.Public.tips({
                        content: '自用成功',
                        type: 3
                    });
                    window.location.href = "index.php?ctl=Seller_Stock_Order&met=stock_self_use_list";
                    return true;
                } else {
                    parent.Public.tips({
                        content: '自用失败',
                        type: 1
                    });
                    return false;
                }
            })
        }
    })
})