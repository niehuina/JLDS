$(function () {
    ucenterLogin();
});
$(function () {
    if (getQueryString('key') != '') {
        var key = getQueryString('key');
        var username = getQueryString('username');
        addCookie('key', key);
        addCookie('username', username);
        updateCookieCart(key);
    } else {
        var key = getCookie('key');
    }

    if (key) {
        $.ajax({
            type: 'post',
            url: ApiUrl + "/index.php?ctl=Buyer_User&met=getUserInfo&typ=json",
            data: {k: key, u: getCookie('id')},
            dataType: 'json',
            //jsonp:'callback',
            success: function (result) {
                if (result.status == 250) {
                    return false;
                }
                checkLogin(result.login);
                // $('#user_money').html("￥"+result.data.money.user_money);
                $('#user_money').html("￥125");
                $('#user_points').html(result.data.points.user_points);
                if(!result.data.user_logo){
                    result.data.user_logo = "/tmpl/img/user_avt_default.png";
                }
                var html = '<div class="mine-head-bg"><div class="member-info">'
                    + '<div class="user-avatar"> <a href="member_info.html"><img src="' + result.data.user_logo + '"/> </a></div>'
                    + '<div class="user-name"> <span><strong>' + result.data.user_name + '</strong><sup>V' + result.data.user_grade + '</sup></span> </div>';
                //渲染页面
                $(".member-top").html(html);


                var html = '<a href="../order/order_list.html">' +
                    '<h3>我的订单</h3>'+
                    '<h5>查看全部订单<i class="arrow-r"></i></h5>'+
                    '</a>';
                $(".order-content").html(html);

                var html = '<li><a href="../order/order_list.html?data-state=wait_pay"><i class="cc-01"></i><p>待付款</p></a>' + (result.data.order_count.wait > 0 ? '<b>' + result.data.order_count.wait + '</b>' : '') + '</li>'
                    + '<li><a href="../order/order_list.html?data-state=order_payed"><i class="cc-02"></i><p>待发货</p></a>' + (result.data.order_count.payed > 0 ? '<b>' + result.data.order_count.payed + '</b>' : '') + '</li>'
                    + '<li><a href="../order/order_list.html?data-state=wait_confirm_goods"><i class="cc-03"></i><p>待收货</p></a>' + (result.data.order_count.confirm > 0 ? '<b>' + result.data.order_count.confirm + '</b>' : '') + '</li>'
                    + '<li><a href="../order/order_list.html?data-state=finish"><i class="cc-04"></i><p>待评价</p></a>' + (result.data.order_count.finish > 0 ? '<b>' + result.data.order_count.finish + '</b>' : '') + '</li>'
                    + '<li><a href="../order/member_refund.html"><i class="cc-05"></i><p>退款/退货</p></a>' + (result.data.order_count.return > 0 ? '<b>' + result.data.order_count.return + '</b>' : '') + '</li>';

                //渲染页面
                $("#order_ul").html(html);

                var html = '<dl class="mt5">'+
                    '<dt>'+
                    '<a href="member_money.html">'+
                    '<h3>我的钱包</h3>'+
                    '<h5>查看明细<i class="arrow-r"></i></h5>'+
                    '</a>'+
                    '</dt>'+
                    '</dl>';
                $(".member-center").append(html);

                var html = '<dl class="mt5">'+
                    '<dt>'+
                    '<a href="../stock/member_stock.html">'+
                    '<h3><i class="mc-08"></i>个人仓储</h3>'+
                    '<h5><i class="arrow-r"></i></h5>'+
                    '</a>'+
                    '</dt>'+
                    '</dl>';
                $(".member-center").append(html);

                if (result.data.directseller_is_open && result.data.user_grade > 1) {
                    var html = '<dl class="mt5">' +
                        '<dt>' +
                        '<a href="member_agent.html">' +
                        '<h3><i class="mc-09"></i>下线人员</h3>' +
                        '<h5><i class="arrow-r"></i></h5>' +
                        '</a>' +
                        '</dt>' +
                        '</dl>';
                    $(".member-center").append(html);
                    var html = '<dl class="mt5">' +
                        '<dt>' +
                        '<a href="../profit/member_profit.html">' +
                        '<h3><i class="mc-02"></i>下线返利</h3>' +
                        '<h5><i class="arrow-r"></i></h5>' +
                        '</a>' +
                        '</dt>' +
                        '</dl>';
                    $(".member-center").append(html);
                }

                $(".header-r").show();

                return false;
            }
        });
    } else {
        var html = '<div class="mine-head-bg"><div class="member-info">'
            + '<a class="default-avatar logbtn" href="javascript:void(0);" style="display:block;"></a>'
            + '<a class="to-login logbtn" href="javascript:void(0);">点击登录</a>'
            + '</div></div>';
        //渲染页面
        $(".member-top").html(html);

        var html = '<a class="logbtn"> ' +
            '<h3>我的订单</h3>'+
            '<h5>查看全部订单<i class="arrow-r"></i></h5>'+
            '</a>';
        $(".order-content").html(html);

        var html = '<li><a class="logbtn"><i class="cc-01"></i><p>待付款</p></a></li>'
            + '<li><a class="logbtn"><i class="cc-02"></i><p>待发货</p></a></li>'
            + '<li><a class="logbtn"><i class="cc-03"></i><p>待收货</p></a></li>'
            + '<li><a class="logbtn"><i class="cc-04"></i><p>待评价</p></a></li>'
            + '<li><a class="logbtn"><i class="cc-05"></i><p>退款/退货</p></a></li>';
        //渲染页面
        $("#order_ul").html(html);

        var html = '<dl class="mt5">'+
            '<dt>'+
            '<a class="logbtn">'+
            '<h3>我的钱包</h3>'+
            '<h5>查看明细<i class="arrow-r"></i></h5>'+
            '</a>'+
            '</dt>'+
            '</dl>';
        $(".member-center").append(html);

        $(".header-r").hide();

        return false;
    }

    //滚动header固定到顶部
    $.scrollTransparent();


    $("#paycenter,.property-overview").click(function () {
        window.location.href = PayCenterWapUrl;
    });
});