
$(function() {

    var isIntegral = getQueryString("isIntegral"),
        sumPoints = getQueryString("sumPoints"),
        point_cart_id = getQueryString("point_cart_id") ? getQueryString("point_cart_id").split(",") : [];

    if ( isIntegral ) {

        if ( point_cart_id.length > 0 ) {
            $(".check-out").addClass("ok");
        }

        $(".nctouch-cart-block.mt5").remove();

        $(".nctouch-cart-bottom").find("dt").html("支付总积分：");
        $(".nctouch-cart-bottom").find("dd").html("<em id='totalPayPrice'>" + sumPoints + "</em>");

        $('#ToBuyStep2').unbind("click");

        ~function initPointProductList() {
            $.ajax({
                type:'POST',
                url: ApiUrl  + '?ctl=Buyer_Points&met=getPointGoodsByCart&typ=json',
                data: {
                    point_cart_ids: point_cart_id,
                    k: key,
                    u: getCookie('id')
                },
                dataType: "json",
                success: function( data ) {

                    if ( data.status == 200 ) {

                    } else {
                        $.sDialog({
                            content: data.msg,
                            okBtn:false,
                            cancelBtnText:'确定'
                        });
                    }
                }
            })
        }();

        $('#ToBuyStep2').on("click", function() {

            var address_id = $("#address_id").val();

            if( !address_id ) {
                return $.sDialog({
                    skin:"red",
                    content:'请选择收货地址！',
                    okBtn:false,
                    cancelBtn:false
                });
            }

            //1.获取收货地址
            var param = {
                k: key,
                u: getCookie('id'),
                receiver_name: $("#true_name").html(),
                receiver_address: $("#address").html(),
                receiver_phone: $("#mob_phone").html(),
                point_cart_id: point_cart_id
            };

            $.ajax({
                type:'POST',
                url: ApiUrl  + '?ctl=Points&met=addPointsOrder&typ=json',
                data: param,
                dataType: "json",
                success: function( data ) {

                    if ( data.status == 200 ) {
                        $.sDialog({
                            content: "订单提交成功",
                            okBtn: false,
                            cancelBtn: false
                        });
                        setTimeout("window.location.href = WapSiteUrl", 2000);
                    } else {
                        $.sDialog({
                            content: data.msg,
                            okBtn:false,
                            cancelBtnText:'返回',
                            cancelFn: function() { history.back(); }
                        });
                    }
                }
            });
        });
    }
});
