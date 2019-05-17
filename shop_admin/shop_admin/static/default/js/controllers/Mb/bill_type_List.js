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
    var gridWH = Public.setGrid(), _self = this;
    var t = ["操作", "type_id", "编号", "名称"], e = [{
        name: "operate",
        width: 60,
        fixed: !0,
        align: "center",
        formatter: operFmatterCIMG
    },
        {name: "type_id", index: "type_id", hidden: true},
        {name: "type_no", index: "type_no", width: 100},
        {name: "type_name", index: "type_name",  width: 150}
    ];
    $("#grid").jqGrid({
        url: SITE_URL + '?ctl=Mb_BillType&met=billTypeList&typ=json',
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
            var i = "新增账单类型", a = {oper: t, callback: this.callback};
        }
        else
        {
            var i = "修改账单类型", a = {oper: t, rowData: $("#grid").data("gridData")[e], callback: this.callback};

        }
        console.info(a);
        $.dialog({
            title: i,
            content: "url:"+ SITE_URL + "?ctl=Mb_BillType&met=manage&typ=e",
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
            $("#grid").jqGrid("setRowData", t.type_id, t);
            i && i.api.close();
        }
        else
        {
            $("#grid").jqGrid("addRowData", t.type_id, t, "last");
            i && i.api.close();
        }
    }, del: function (t)
    {
        $.dialog.confirm("删除的数据将不能恢复，请确认是否删除？", function ()
        {
            Public.ajaxPost(SITE_URL + "?ctl=Mb_BillType&met=removeBillType&typ=json", {type_id: t}, function (e)
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
    var html_con = '<div class="operating" data-id="' + row.type_id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
    return html_con;
};


initEvent();
initGrid();
