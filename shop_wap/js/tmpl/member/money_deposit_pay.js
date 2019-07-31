$(function(){

    var card_payway = false;
    var money_payway = false;
    var bt_payway = false;

    $("#deposit_btn").click(function () {
        var online_payway = $(".checked > .checkbox").val();
        var uorder_id = $("#uorder").text();
        var data = {
            k:getCookie('key'),u:getCookie('id'),
            card_payway:card_payway,
            money_payway:money_payway,
            bt_payway:bt_payway,
            online_payway:online_payway,
            uorder_id:uorder_id,
        };
        $.post(PayCenterWapUrl + "?ctl=Info&met=checkPayWay&typ=json", data,
            function (result) {
                if (result.status = '200') {
                    if(online_payway == "wx_native"){
                        window.location.href = PayCenterWapUrl + "?ctl=Pay&met=" + online_payway + "&trade_id=" + uorder_id+"&trade_type=H5";
                    }else{
                        window.location.href = PayCenterWapUrl + "?ctl=Pay&met=" + online_payway + "&trade_id=" + uorder_id;
                    }
                }
            }
        );
    });
});