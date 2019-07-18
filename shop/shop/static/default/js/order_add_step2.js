var select_goods_list = {};

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
    //disabledButton();
}

function selectAllCheck(isCompute) {
    var flag = true;
    $("#goods_grid tbody").find(":checkbox[name='check']:checked").each(function(){
        var goods_id = $(this).data('goods_id');
        var goods_stock = $(this).data('goods_stock');
        var row_num = $(this).data('row_num');
        var order_goods_num = $("#"+goods_id).find("input[name='order_goods_num']").val();
        if(order_goods_num && order_goods_num > 0 && order_goods_num <= goods_stock){
            select_goods_list[goods_id] = order_goods_num;
            if(isCompute) {
                computeTotalAmountPlus(goods_id);
            }
        }else{
            if(order_goods_num && order_goods_num > goods_stock){
                Public.tips.error('选中的第【'+row_num+'】行商品发货数量不能大于商品库存！');
            }else if(!order_goods_num){
                Public.tips.error('选中的第【'+row_num+'】行商品未填写发货数量！');
            }
            $("#"+goods_id).find("input[name='order_goods_num']").focus();
            flag = false;
        }
    });

    if(flag){
        $("#select_goods_list").val(JSON.stringify(select_goods_list));
    }
    return flag;
}

function getShippingFee() {
    var flag = true;
    var order_price = $("#total_amount").val();
    var city_id = $("#id_2").val();
    if(!city_id){
        Public.tips.error('收货人地址未设置！');
        flag = false;
        return flag;
    }
    if(Object.keys(select_goods_list).length == 0){
        Public.tips.error('未选择商品！');
        flag = false;
        return flag;
    }

    var user_stocks = $("#user_stocks").val();
    if (order_price*1 > user_stocks*1) {
        Public.tips.error('商品总金额不能超过合伙人备货金！');
        flag = false;
        return flag;
    }

    var url = "index.php?ctl=Seller_Stock_Order&met=getTransport&typ=json";
    var form_ser = {
        'select_goods_list': JSON.stringify(select_goods_list),
        'order_price': order_price,
        'city_id':city_id
    };
    $.getJSON(url, form_ser, function (result) {
        if (result.status == 200) {
            var cost = result.data.cost;
            if(cost===cost+''){
                $("#shipping_fee_show").text("￥"+cost);
            }else{
                $("#shipping_fee_show").text("￥"+cost.toFixed(2));
            }
            $("#shipping_fee").val(cost);

            if (order_price*1 + cost*1 > user_stocks*1) {
                Public.tips.error('订单总金额不能超过合伙人备货金！');
                flag = false;
                return flag;
            }
        } else {
            Public.tips.error('计算配送费失败！');
            flag = false;
        }
    })
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
        var total_amount = $("#total_amount").val();
        total_amount = total_amount*1 - old_goods_amount*1 + new_goods_amount*1;
        $("#total_amount_show").text("￥"+total_amount.toFixed(2));
        $("#total_amount").val(total_amount);
    }
}
function computeTotalAmountMinus(goods_id, goods_amount) {
    goods_amount = goods_amount ? goods_amount : $("#"+goods_id).children().last().text();
    var total_amount = $("#total_amount").val();
    total_amount = total_amount*1 - goods_amount*1;
    $("#total_amount_show").text("￥"+total_amount.toFixed(2));
    $("#total_amount").val(total_amount);
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
                curChk.attr('data-goods_stock', curRowData['goods_stock']);
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
        beforeSelectRow: function (rowid, e) {
            var $myGrid = $(this),
                i = $.jgrid.getCellIndex($(e.target).closest('td')[0]),
                cm = $myGrid.jqGrid('getGridParam', 'colModel');
            return (cm[i].name === 'cb');
        },
        beforeRequest: function() {
            var flag = selectAllCheck(false);
            //disabledButton();
            return flag;
        },
    });

    $("#goods_grid").jqGrid('setLabel','rn', '序号', {'text-align':'left'},'');
}

$(function () {
    initGrid();

    $("#button_get_shipping_fee").click(function () {
        var flag = getShippingFee();
        if(flag){
            disabledButton();
        }
    })
    
    $("#button_next_step").click(function () {
        var flag = selectAllCheck(false);
        if(flag) {
            var url = "index.php?ctl=Seller_Stock_Order&met=addSendOrder&typ=json";
            var form_ser = $("#form").serialize();
            $.post(url, form_ser, function (data) {
                if (data.status == 200) {
                    Public.tips.success('创建备货订单成功！');
                    window.location.href = SITE_URL + "?ctl=Seller_Stock_Order&met=physical";
                    return true;
                } else {
                    Public.tips.error('创建备货订单失败！');
                    return false;
                }
            })
        }
    })
});

function disabledButton(){
    var user_stocks = $("#user_stocks").val();
    var total_amount = $("#total_amount").val();

    if(Object.keys(select_goods_list).length > 0 && total_amount <= user_stocks){
        $('#button_next_step').attr('disabled',false).addClass('bbc_seller_submit_btns').removeClass('bbc_sellerGray_submit_btns');
    }else {
        $('#button_next_step').attr('disabled',true).removeClass('bbc_seller_submit_btns').addClass('bbc_sellerGray_submit_btns');
    }
}

//选择发货地址
$('a[dialog_id="edit_send_address"]').on('click', function () {

    var user_id = $("#user_id").val(),
        url = SITE_URL + '?ctl=Seller_Stock_Order&met=chooseBuyerAddress&typ=';

    $.dialog({
        title: '选择发货地址',
        content: 'url: ' + url + 'e&user_id=' + user_id,
        height: 400,
        width: 640,
        lock: true,
        drag: false,
        data: { callback: function ( send_address, win ) {
                $("#order_address_name").val(send_address.order_seller_name);
                $("#d_2").addClass("hidden");
                $("#d_1").html(send_address.seller_address_area + '&nbsp;&nbsp;<a href="javascript:sd();">编辑</a>');
                $("#order_address_address").val(send_address.order_seller_address);
                $("#order_address_phone").val(send_address.order_seller_contact);
                $("#t").val(send_address.seller_address_area);
                $("#seller_address_span").text(send_address.seller_address_span);
                win.api.close();
            }
        }
    })
})