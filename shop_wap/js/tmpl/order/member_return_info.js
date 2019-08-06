var e = getCookie("key");
var u = getCookie("id");
var r = getQueryString("refund_id");
$(function () {
    template.helper("isEmpty", function (el) {
        for (var l in el) {
            return false
        }
        return true
    });

    $.getJSON(ApiUrl + "/index.php?ctl=Buyer_Service_Return&met=index&act=detail&typ=json", {k: e,u:u, id: r}, function (result) {
        $("#return-info-div").html(template.render("return-info-script", result.data))
    });
});

function sendReturnGoods() {
    //表单验证通过，提交表单
    $.ajax({
        url: ApiUrl + '?ctl=Buyer_Service_Return&met=sendReturnGoods&typ=json',
        data: {
            k: e,u:u,
            order_return_id : $("#order_return_id").val()
        },
        success: function (res)
        {
            if (res.status == 200)
            {
                window.location.reload();
            }
            else
            {
                $.sDialog({
                    skin:"red",
                    content:"操作失败",
                    okBtn:false,
                    cancelBtn:false
                });
                return false;
            }
        }
    });
}

