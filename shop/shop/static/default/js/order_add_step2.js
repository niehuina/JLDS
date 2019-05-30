var select_goods_list = {};

function initEvent() {
    // $(window).resize(function () {
    //         Public.resizeGrid()
    //     }
    // );
    // $("#button_next_step").submit(function () {
    //     var params = $("#form").serialize();
    //     return false;
    //     $.ajax({
    //         type: 'POST',
    //         url: SITE_URL + "?ctl=Seller_Stock_Order&met=addSendOrder",
    //         data: params,
    //         success: function(result){
    //             if (result.status == "200"){
    //                 parent.Public.tips({
    //                     content: '提交成功',
    //                     type: 3
    //                 });
    //                 window.location.href = SITE_URL + "?ctl=Seller_Stock_Order&met=physical";
    //             } else {
    //                 parent.Public.tips({
    //                     type: 1,
    //                     content: result.msg
    //                 });
    //             }
    //         }
    //     });
    //     return false;
    // })
}

function setGrid(adjustH, adjustW) {
    var defaultPage = Public.getDefaultPage();
    if (defaultPage.SYSTEM.skin === 'green') {
        var adjustH = adjustH || 70;
    } else {
        var adjustH = adjustH || 65;
    }
    var adjustW = adjustW || 20;
    var gridW = $(window).width() - adjustW, gridH = $(window).height() - $(".grid-wrap").offset().top - adjustH;
    return {
        w: gridW,
        h: gridH
    }
};

function cbkCheck(obj) {
    var goods_id = $(obj).data("goods_id");
    if(obj.checked == true){
        var order_goods_num = $("#"+goods_id).find("input[name='order_goods_num']").val();
        select_goods_list[goods_id] = order_goods_num;
        computeTotalAmountPlus(goods_id);
    }else if(obj.checked == false){
        computeTotalAmountMinus(goods_id);
        delete select_goods_list[goods_id];
    }
    disabledButton();
}

function selectAllCheck(isCompute) {
    var flag = true;
    $("#goods_grid tbody").find(":checkbox[name='check']:checked").each(function(){
        var goods_id = $(this).data('goods_id');
        var row_num = $(this).data('row_num');
        var order_goods_num = $("#"+goods_id).find("input[name='order_goods_num']").val();
        if(order_goods_num){
            select_goods_list[goods_id] = order_goods_num;
            if(isCompute) {
                computeTotalAmountPlus(goods_id);
            }
        }else{
            Public.tips.error('选中的第【'+row_num+'】行商品未填写发货数量！');
            $("#"+goods_id).find("input[name='order_goods_num']").focus();
            flag = false;
        }
    });
    if(flag){
        $("#select_goods_list").val(JSON.stringify(select_goods_list));
    }
    return flag;
}

function unSelectAllCheck() {
    var flag = true;
    $("#goods_grid tbody").find(":checkbox[name='check']").each(function(){
        var goods_id = $(this).data('goods_id');
        if(select_goods_list.hasOwnProperty(goods_id)){
            computeTotalAmountMinus(goods_id);
            delete select_goods_list[goods_id];
        }
    });
    return flag;
}

function computeTotalAmountPlus(goods_id, new_goods_amount, old_goods_amount) {
    new_goods_amount = new_goods_amount ? new_goods_amount : $("#"+goods_id).children().last().text();
    old_goods_amount = old_goods_amount ? old_goods_amount : 0;
    var curChk = $("#"+goods_id+"").find(":checkbox");
    if(curChk.prop("checked")){
        var total_amount = $("#total_amount").text();
        total_amount = total_amount*1 - old_goods_amount*1 + new_goods_amount*1;
        $("#total_amount_show").text("￥"+total_amount.toFixed(2));
        $("#total_amount").text(total_amount);
    }
}
function computeTotalAmountMinus(goods_id, goods_amount) {
    goods_amount = goods_amount ? goods_amount : $("#"+goods_id).children().last().text();
    var total_amount = $("#total_amount").text();
    total_amount = total_amount*1 - goods_amount*1;
    $("#total_amount_show").text("￥"+total_amount.toFixed(2));
    $("#total_amount").text(total_amount);
}

function initGrid() {
    var a = ['',"商品名称", "商品价格", "商品VIP价格", "商品股东价格","商品库存","发货数量","发货总价"]
        , b = [{
        name: "common_id",
        index: "common_id",
        hidden: true
    },{
        name: "goods_name",
        index: "goods_name",
        width: 380
    }, {
        name: "goods_price",
        index: "goods_price",
        align: "right",
        width: 70
    }, {
        name: "goods_price_vip",
        index: "goods_price_vip",
        align: "right",
        width: 80
    }, {
        name: "goods_price_partner",
        index: "goods_price_partner",
        align: "right",
        width: 80
    }, {
        name: "goods_stock",
        index: "goods_stock",
        align: "right",
        width: 70
    }, {
        name: "order_goods_num",
        width: 80,
        formatter: function (val, opt, row) {
            var html_con = '<input type="number" name="order_goods_num" role="textbox" class="textbox" autocomplete="false" ' +
                'data-goods_id="'+row.goods_id+'" data-common_id="'+row.common_id+'" /></div>';
            return html_con;
        }
    }, {
        name: "order_goods_amount",
        align: "right",
        width: 80,
    }];
    $("#goods_grid").jqGrid({
        url: SITE_URL + "?ctl=Seller_Stock_Order&met=listGoods&typ=json",
        datatype: "json",
        width: 958,
        height: 500,
        altRows: !0,
        rownumbers: true,
        multiselect: true,
        multiboxonly: false,
        gridview: !0,
        rowNum: 20,
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
            id: "goods_id"
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
        gridComplete: function() {
            var rowIds = $("#goods_grid").jqGrid('getDataIDs');
            for(var k=0; k<rowIds.length; k++) {
                var goods_id = rowIds[k];
                var curRowData = $("#goods_grid").jqGrid('getRowData', goods_id);
                var curChk = $("#"+goods_id+"").find(":checkbox");
                curChk.attr('name', 'check');
                curChk.attr('data-goods_id', goods_id);
                curChk.attr('data-goods_name', curRowData['goods_name']);
                curChk.attr('data-row_num', k+1);

                var order_goods_num_cell = $("#"+goods_id).find("input[name='order_goods_num']");
                if(select_goods_list.hasOwnProperty(goods_id)){
                    $(curChk).prop("checked","true");
                    $(order_goods_num_cell).val(select_goods_list[goods_id]);
                }

                $(curChk).click(function () {
                    cbkCheck(this);
                });
            }

            $("input[name='order_goods_num']").blur(function () {
                var goods_id = $(this).data("goods_id");
                var curRowData = $("#goods_grid").jqGrid('getRowData', goods_id);
                var goods_price = curRowData['goods_price'];
                var goods_price_vip = curRowData['goods_price_vip'];
                var goods_price_partner = curRowData['goods_price_partner'];
                var num = $(this).val();
                var new_goods_amount = (num*1) * (goods_price_vip*1);
                var old_goods_amount = $("#"+goods_id).children().last().text();
                $("#"+goods_id).children().last().text(new_goods_amount.toFixed(2));
                computeTotalAmountPlus(goods_id, new_goods_amount, old_goods_amount);
            });

            $("input[name='order_goods_num']").keypress(function (event) {
                var keyCode = event.keyCode;
                if ((keyCode >= 48 && keyCode <= 57)) {
                    event.returnValue = true;
                } else {
                    event.returnValue = false;
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
        beforeRequest: function() {
            var flag = selectAllCheck(false);
            disabledButton();
            return flag;
        },
    });

    $("#goods_grid").jqGrid('setLabel','rn', '序号', {'text-align':'left'},'');
}

initGrid();
initEvent();

function disabledButton(){
    if(Object.keys(select_goods_list).length > 0){
        $('#button_next_step').attr('disabled',false).addClass('bbc_seller_submit_btns').removeClass('bbc_sellerGray_submit_btns');
    }else {
        $('#button_next_step').attr('disabled',true).removeClass('bbc_seller_submit_btns').addClass('bbc_sellerGray_submit_btns');
    }
}

//选择发货地址
$('a[dialog_id="edit_send_address"]').on('click', function () {

    var user_id = $("#user_id").val(),
        url = SITE_URL + '?ctl=Seller_Stock_Order&met=chooseSendAddress&typ=';

    $.dialog({
        title: '选择发货地址',
        content: 'url: ' + url + 'e&user_id=' + user_id,
        height: 400,
        width: 640,
        lock: true,
        drag: false,
        data: { callback: function ( send_address, win ) {
                $("#order_address_name").val(send_address.user_address_name);
                $("#d_1").html(send_address.user_address_area + '&nbsp;&nbsp;<a href="javascript:sd();">编辑</a>');
                $("#order_address_address").val(send_address.user_address_address);
                $("#order_address_phone").val(send_address.user_address_phone);
                $("#t").val(send_address.user_address_span);
                $("#seller_address_span").text(send_address.user_address_span);
                win.api.close();
            }
        }
    })
})