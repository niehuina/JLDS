$(function () {

    //是否显示已关闭订单
    $('#skip_off').on('click', function () {

        URL = createQuery();
        window.location = URL;
    });

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

        var url = SITE_URL + '?' + location.href.match(/ctl=\w+&met=\w+&typ=\w+/) + '&';

        $('#query_start_date').val() && (url += 'query_start_date=' + $('#query_start_date').val() + '&');
        $('#query_end_date').val() && (url += 'query_end_date=' + $('#query_end_date').val() + '&');
        $('#query_buyer_name').val() && (url += 'query_buyer_name=' + $('#query_buyer_name').val() + '&');
        $('#order_sn').val() && (url += 'query_order_sn=' + $('#order_sn').val() + '&');
        $('#skip_off').prop('checked') && (url += 'skip_off=1&');

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
                            content: '取消成功',
                            type: 3
                        }), window.location.reload();
                        return true;
                    } else {
                        parent.Public.tips({
                            content: '取消失败',
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
//隐藏订单
window.hideOrder = function (e)
{
    $.dialog({
        title: '删除订单',
        content: '您确定要删除吗？删除后的订单可在回收站找回，或彻底删除! ',
        height: 100,
        width: 405,
        lock: true,
        drag: false,
        ok: function () {

            $.post(SITE_URL  + '?ctl=Seller_Stock_Order&met=hideOrder&typ=json',{order_id:e,user:'seller'},function(data)
            {
                if(data && 200 == data.status) {
                    Public.tips.success('删除成功！');
                    window.location.reload();
                } else {
                    Public.tips.error('删除失败！');
                    window.location.reload();
                }
            })
        }
    })

}

//删除订单
window.delOrder = function (e)
{
    $.dialog({
        title: '删除订单',
        content: '您确定要永久删除吗？永久删除后您将无法再查看该订单，也无法进行投诉维权，请谨慎操作！',
        height: 100,
        width: 610,
        lock: true,
        drag: false,
        ok: function () {
            $.post(SITE_URL  + '?ctl=Seller_Stock_Order&met=hideOrder&typ=json',{order_id:e,user:'seller',op:'del'},function(data)
                {
                    if(data && 200 == data.status) {
                        Public.tips.success('删除成功！');
                        window.location.reload();
                    } else {
                        Public.tips.error('删除失败！');
                        window.location.reload();
                    }
                }
            );
        }
    })
}
//还原订单
window.restoreOrder = function (e)
{
    $.dialog({
        title: '还原删除订单',
        content: '您确定要还原吗？',
        height: 100,
        width: 'auto',
        lock: true,
        drag: false,
        ok: function () {
            $.post(SITE_URL  + '?ctl=Seller_Stock_Order&met=restoreOrder&typ=json',{order_id:e,user:'seller'},function(data)
                {
                    if(data && 200 == data.status) {
                        Public.tips.success('还原成功！');
                        window.location.reload();
                        //$.dialog.alert('还原成功'), window.location.reload();
                    } else {
                        Public.tips.error('还原失败！');
                        window.location.reload();
                        //$.dialog.alert('还原成功'), window.location.reload();
                    }
                }
            );
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

function refreshPage() {
    var url = SITE_URL + '?' + location.href.match(/ctl=\w+&met=\w+/);
    window.location = url;
}
