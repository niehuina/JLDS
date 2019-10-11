var page = pagesize;
var curpage = 0;
var hasMore = true;
var footer = false;
var reset = true;
$(function ()
{
    var e = getCookie("key");
    if (!e)
    {
        location.href = "login.html"
    }
    template.helper("isEmpty", function (e)
    {
        for (var t in e)
        {
            return false
        }
        return true
    });
    function t() {
        if (reset) {
            curpage = 0;
            hasMore = true
        }
        $(".loading").remove();
        if (!hasMore) {
            return false
        }
        hasMore = false;
        // var t = $("#filtrate_ul").find(".selected").find("a").attr("data-state");
        // var r = $("#order_key").val();

        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?ctl=Buyer_Message&met=message&typ=json&firstRow=" + curpage+"&listRows=10",
            data: {k: e, u: getCookie('id'), op:""},
            dataType: "json",
            success: function (e) {
                curpage = e.data.page * pagesize;
                if(page < e.data.totalsize)
                {
                    hasMore = true;
                }

                if (!hasMore) {
                    get_footer()
                }
                if (e.data.items.length <= 0) {
                    $("#footer").addClass("posa")
                } else {
                    $("#footer").removeClass("posa")
                }
                var t = e;
                t.WapSiteUrl = WapSiteUrl;
                t.ApiUrl = ApiUrl;
                t.key = getCookie("key");
                template.helper("$getLocalTime", function (e) {
                    var t = new Date(parseInt(e) * 1e3);
                    var r = "";
                    r += t.getFullYear() + "年";
                    r += t.getMonth() + 1 + "月";
                    r += t.getDate() + "日 ";
                    r += t.getHours() + ":";
                    r += t.getMinutes();
                    return r
                });
                template.helper("p2f", function (e) {
                    return (parseFloat(e) || 0).toFixed(2)
                });
                template.helper("parseInt", function (e) {
                    return parseInt(e)
                });
                var r = template.render("messageListScript", t);
                if (reset) {
                    reset = false;
                    $("#messageList").html(r)
                } else {
                    $("#messageList").append(r)
                }
                $('.to_chat').click(function(){
                    var id=$(this).attr("t_id");
                    addCookie('message_id',id);
                    var url = WapSiteUrl+'/tmpl/member/member_chatdetail.html';
                    $(this).attr('href', url).find('div').click();
                })

                $(".msg-list-del").click(function () {
                    var ids = new Array();
                    ids[0]=$(this).attr("t_id");
                    $.ajax({
                        type: "post",
                        url: ApiUrl + "/index.php?ctl=Buyer_Message&met=delAllMessage&typ=json",
                        data: {k: getCookie("key"), u: getCookie('id'), id: ids},
                        dataType: "json",
                        success: function (e) {
                            if (e.status == 200) {
                                location.reload()
                            }
                            else {
                                $.sDialog({skin: "red", content:"删除失败", okBtn: false, cancelBtn: false});
                                return false
                            }
                        }
                    })
                });
            }
        })
    }
    t();
    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1) {
            t()
        }
    })
    function get_footer() {
        if (!footer) {
            footer = true;
            $.ajax({url: "../../js/tmpl/footer.js", dataType: "script"})
        }
    }

    // $.ajax({
    //     type: "post", url: ApiUrl + "/index.php?ctl=Buyer_Message&met=message&typ=json", data: {k: e, u: getCookie('id'), recent: 1, op:'get_user_list', from:'wap'}, dataType: "json", success: function (t)
    //     {
    //         checkLogin(t.login);
    //         var a = t.data;
    //         $("#messageList").html(template.render("messageListScript", a));
    //         $(".msg-list-del").click(function ()
    //         {
    //             var t = $(this).attr("t_id");
    //             $.ajax({
    //                 type: "post", url: ApiUrl + "/index.php?ctl=Buyer_Message&met=delUserMessage&typ=json", data: {k: e, u: getCookie('id'), t_id: t}, dataType: "json", success: function (e)
    //                 {
    //                     if (e.status == 200)
    //                     {
    //                         location.reload()
    //                     }
    //                     else
    //                     {
    //                         $.sDialog({skin: "red", content: e.data.error, okBtn: false, cancelBtn: false});
    //                         return false
    //                     }
    //                 }
    //             })
    //         })
    //     }
    // })

    // $.ajax({
    //     type: "post", url: ImApiUrl + "/index.php?ctl=Api_Chatlog&met=getMessage&typ=json", data: {u: getCookie('user_account')}, dataType: "json", success: function (t)
    //     {
    //         console.log(checkLogin(t.login));
    //         $("#messageList").html(template.render("messageListScript", t));
    //         $('.to_chat').click(function(){
    //             var receiver_name = $(this).attr('receiver_name');
    //             var send_name = $(this).attr('send_name');
    //             var url = WapSiteUrl+'/tmpl/im-chatinterface.html?contact_type=C&contact_you=' + send_name + '&uname='+ receiver_name;
    //             $(this).attr('href', url).find('div').click();
    //         })
    //         $(".msg-list-del").click(function ()
    //         {
    //             var t = $(this).attr("t_id");
    //             $.ajax({
    //                 type: "post", url: ImApiUrl + "/index.php?ctl=Api_Chatlog&met=delMessage&typ=json", data: {k: e, u: getCookie('id'), t_id: t}, dataType: "json", success: function (e)
    //                 {
    //                     if (e.status == 200)
    //                     {
    //                         location.reload()
    //                     }
    //                     else
    //                     {
    //                         $.sDialog({skin: "red", content: e.data.error, okBtn: false, cancelBtn: false});
    //                         return false
    //                     }
    //                 }
    //             })
    //         })
    //     }
    // })
});