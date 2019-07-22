$(function () {
    $(window).on("click", ".again-order", function(){
        againOrder($(this).attr("order_id"),$(this).attr("shop_id"),$(this).attr("promotion_type"),$(this).attr("promotion_id"));
    });
});
function againOrder(orderid,shopid,promotion_type,promotion_id) {
    if(promotion_type == "combine"){
        window.location.href = WapSiteUrl + "/tmpl/product_confirm_order.html?group_id=" + promotion_id+"&goods_num=1";
    }else {
        $.ajax({
            url: ApiUrl + '/index.php?ctl=Buyer_Cart&met=againOrderToAddCart&typ=json',
            data: {k: getCookie('key'), u: getCookie('id'), order_id: orderid},
            type: "post",
            dataType: "json",
            async: false,
            success: function (a) {
                if (a.status == 200) {
                    if (a.data && a.data.isVirtual == "1") {
                        window.location.href = WapSiteUrl + '/tmpl/service_confirm_order.html?goods_id=' + a.data.goodids[0] + '&buyer_limit=' + a.data.buy_limit;
                    } else {
                        window.location.href = WapSiteUrl + "/tmpl/cart_list.html?cartids=" + a.data.cart_ids + "&shop_id=" + shopid;
                    }
                }
                else {
                    if (a.msg != 'failure') {
                        $.sDialog({skin: "red", content: a.msg, okBtn: false, cancelBtn: false})
                    } else {
                        $.sDialog({skin: "red", content: '再来一单失败！', okBtn: false, cancelBtn: false})
                    }
                }
            },
            failure: function (a) {
                $.sDialog({skin: "red", content: '操作失败！', okBtn: false, cancelBtn: false})
            }
        });
    }
}
