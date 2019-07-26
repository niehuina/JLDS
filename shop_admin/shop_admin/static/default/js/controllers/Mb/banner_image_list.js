function initEvent()
{
    $_matchCon = $("#matchCon"),
        $_matchCon.placeholder(),

    $("#btn-add").click(function (t)
    {
        t.preventDefault();
        Business.verifyRight("INVLOCTION_ADD") && handle.operate("add")
    });

    $("#btn-refresh").click(function (t)
    {
        t.preventDefault();
        $("#grid").trigger("reloadGrid")
    });

    //删除
    $("#grid").on("click", ".operating .ui-icon-trash", function (t)
    {
        t.preventDefault();
        if (Business.verifyRight("INVLOCTION_DELETE"))
        {
            var e = $(this).parent().data("id");
            handle.del(e)
        }
    });
    //修改
    $("#grid").on("click", ".operating .ui-icon-pencil", function (t)
    {
        t.preventDefault();
        if (Business.verifyRight("INVLOCTION_UPDATE"))
        {
            var e = $(this).parent().data("id");

            handle.operate("edit", e)
        }
    });

    $(window).resize(function ()
    {
        Public.resizeGrid()
    })
}

function initGrid()
{
    var banner_type = $("#banner_type").val();

    var gridWH = Public.setGrid(), _self = this;
    var t = ["操作", "mb_banner_image_id", "banner_type", "图片", "点击跳转链接", "显示顺序"], e = [{
        name: "operate",
        width: 60,
        fixed: !0,
        align: "center",
        formatter: operFmatterCIMG
    },
        {name: "mb_banner_image_id", index: "mb_banner_image_id", hidden: true},
        {name: "banner_type", index: "banner_type", hidden: true},
        {name: "banner_image", index: "banner_image", width: 150},
        {name: "banner_url", index: "banner_url",  width: 150},
        {name: "banner_order", index: "banner_order",  width: 100},
    ];
    $("#grid").jqGrid({
        url: SITE_URL + '?ctl=Mb_BannerImage&met=bannerImageList&bannerType=' + banner_type + '&typ=json',
        datatype: "json",
        height: Public.setGrid().h,
        colNames: t,
        colModel: e,
        autowidth: !0,
        pager: "#page",
        viewrecords: !0,
        cmTemplate: {sortable: !1, title: !1},
        page: 1,
        rowNum: 100,
        rowList: [100, 200, 500],
        shrinkToFit: !1,
        jsonReader: {root: "data.items", records: "data.records", total: "data.total", repeatitems: !1, id: "id"},
        loadComplete: function (t)
        {
            if (t && 200 == t.status)
            {
                var e = {};
                t = t.data;
                for (var i = 0; i < t.items.length; i++)
                {
                    var a = t.items[i];
                    e[a.id] = a;
                }
                $("#grid").data("gridData", e);
                0 == t.items.length && parent.Public.tips({type: 2, content: "没有数据！"})
            }
            else
            {
                parent.Public.tips({type: 2, content: "获取数据失败！" + t.msg})
            }
        },
        loadError: function ()
        {
            parent.Public.tips({type: 1, content: "操作失败了哦，请检查您的网络链接！"})
        }
    })
}

var handle = {
    operate: function (t, e)
    {
        if ("add" == t)
        {
            var i = "新增Banner图片", a = {oper: t, callback: this.callback};
        }
        else
        {
            var i = "修改Banner图片", a = {oper: t, rowData: $("#grid").data("gridData")[e], callback: this.callback};

        }
        console.info(a);
        $.dialog({
            title: i,
            content: "url:"+ SITE_URL + "?ctl=Mb_BannerImage&met=manage&menuName=" + $(".current")[0].innerText +"&typ=e",
            data: a,
            width: 635,
            height: 520,
            max: !1,
            min: !1,
            cache: !1,
            lock: !0
        })
    }, callback: function (t, e, i)
    {
        var a = $("#grid").data("gridData");

        if (!a)
        {
            a = {};
            $("#grid").data("gridData", a)
        }
        a[t.id] = t;
        if ("edit" == e)
        {
            $("#grid").jqGrid("setRowData", t.mb_banner_image_id, t);
            i && i.api.close();
            $("#grid").trigger("reloadGrid");
        }
        else
        {
            $("#grid").jqGrid("addRowData", t.mb_banner_image_id, t, "last");
            i && i.api.close();
            $("#grid").trigger("reloadGrid");
        }
    },
    del: function (t)
    {
        $.dialog.confirm("删除的数据将不能恢复，请确认是否删除？", function ()
        {
            Public.ajaxPost(SITE_URL + "?ctl=Mb_BannerImage&met=removeBannerImage&typ=json", {mb_banner_image_id: t}, function (e)
            {
                if (e && 200 == e.status)
                {
                    parent.Public.tips({content: "删除成功！"});
                    $("#grid").jqGrid("delRowData", t)
                }
                else
                {
                    parent.Public.tips({type: 1, content: "删除失败！" + e.msg})
                }
            })
        })
    },

};

function operFmatterCIMG(val, opt, row) {
    var html_con = '<div class="operating" data-id="' + row.mb_banner_image_id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span></div>';
    // var html_con = '<div class="operating" data-id="' + row.mb_banner_image_id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
    return html_con;
};


initEvent();
initGrid();
