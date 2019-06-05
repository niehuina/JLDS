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

    //搜索
    var URL;
    $('input[type="submit"]').on('click', function (e) {

        e.preventDefault();

        URL = createQuery();
        window.location = URL;
    });

    function createQuery() {

        var url = SITE_URL + '?' + location.href.match(/ctl=\w+&met=\w+/) + '&';

        $('#query_start_date').val() && (url += 'query_start_date=' + $('#query_start_date').val() + '&');
        $('#query_end_date').val() && (url += 'query_end_date=' + $('#query_end_date').val() + '&');
        $('#buyer_name').val() && (url += 'query_buyer_name=' + $('#buyer_name').val() + '&');
        $('#order_sn').val() && (url += 'query_order_sn=' + $('#order_sn').val() + '&');

        return url;
    }

    //取消订单
    $('a[dialog_id="seller_order_cancel_order"]').on('click', function () {

        var order_id = $(this).data('order_id'),
            url = SITE_URL + '?ctl=Seller_Stock_Order&met=cancelOrder&typ=';

        $.dialog({
            title: '取消订单',
            content: 'url: ' + url + 'e',
            data: {order_id: order_id},
            height: 250,
            width: 400,
            lock: true,
            drag: false,
            ok: function () {

                var form_ser = $(this.content.order_cancel_form).serialize();

                $.post(url + 'json', form_ser, function (data) {
                    if (data.status == 200) {
                        parent.Public.tips({
                            content: '修改成功',
                            type: 3
                        }), window.location.reload();
                        return true;
                    } else {
                        parent.Public.tips({
                            content: '修改失败',
                            type: 1
                        });
                        return false;
                    }
                })
            }
        })
    });

});

//确认收货
window.confirmOrder = function (e) {
    var url = SITE_URL + '?ctl=Seller_Stock_Order&met=confirmOrder&typ=';

    $.dialog({
        title: '确认收货',
        content: 'url: ' + url + 'e&user=buyer',
        data: {order_id: e},
        height: 200,
        width: 400,
        lock: true,
        drag: false,
        ok: function () {

            var form_ser = $(this.content.order_confirm_form).serialize();

            $.post(url + 'json', form_ser, function (data) {
                if (data.status == 200) {
                    Public.tips.success('确认收货成功！');
                    window.location.reload();
                    //$.dialog.alert('确认收货成功'), window.location.reload();
                    return true;
                } else {
                    Public.tips.error('确认收货失败！');
                    //$.dialog.alert('确认订单失败');
                    return false;
                }
            })
        }
    })
}
function formSub() {
    $('.search-form').parents('form').submit();
}

window.hide_logistic = function (order_id) {
    $("#info_" + order_id).hide();
    $("#info_" + order_id).html("");
}

window.show_logistic = function (order_id, express_id, shipping_code) {
    $("#info_" + order_id).show();
    $.post(BASE_URL + "/shop/api/logistic.php", {
        "order_id": order_id,
        "express_id": express_id,
        "shipping_code": shipping_code
    }, function (da) {

        if (da) {
            $("#info_" + order_id).html(da);
        } else {
            $("#info_" + order_id).html('<div class="error_msg">接口出现异常</div>');
        }

    })
}