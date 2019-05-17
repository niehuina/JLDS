function initEvent() {
    $("#btn-repair").click(function(a) {
            a.preventDefault();
            if($('.cbox:checked').length<1)
            {
                parent.Public.tips({
                    type: 1,
                    content: '没有选择数据表！'
                });
            }
            else
            {
                postData('repair','table','','');
            }
        }
    ),
        $("#btn-optimize").click(function(a) {
                a.preventDefault();
                if($('.cbox:checked').length<1)
                {
                    parent.Public.tips({
                        type: 1,
                        content: '没有选择数据表！'
                    });
                }
                else
                {
                    postData('optimize','table','','');
                }
            }
        ),
        $("#btn-backup").click(function(a) {
            a.preventDefault();
            if($('.cbox:checked').length<1)
            {
                parent.Public.tips({
                    type: 1,
                    content: '没有选择数据表！'
                });
            }
            else
            {
                postData('backup','table','','');
            }
        }
    ),
        $(window).resize(function() {
                Public.resizeGrid()
            }
        )
}
function dbmt_click(a)
{
    var i=$(a).parent().parent().parent().attr('id'),

        e=$(a).parent().parent().next().next().html();

    $("#erp_material_fnumber").val(i);
    $("#erp_material_name").val(e);
    $("#button_next_step").trigger("click");
    //postData(s,'row',i,e);
}
 function setGrid(adjustH, adjustW){
    var defaultPage = Public.getDefaultPage();
    if(defaultPage.SYSTEM.skin === 'green'){
        var adjustH = adjustH || 70;
    } else {
        var adjustH = adjustH || 65;
    };
    var adjustW = adjustW || 20;
    var gridW = $(window).width() - adjustW, gridH = $(window).height() - $(".grid-wrap").offset().top - adjustH;
    return {
        w : gridW,
        h : gridH
    }
};

function initGrid() {
    var a = ["操作","编码","名称","其他信息"]
        , b = [{
            name: "operate",
            width: 60,
            fixed: !0,
            align: "center",
            formatter: function (val, opt, row) {
                var html_con = '<div class="operating" data-id="' + row.id + '"><a href="javascript:void(0)" onclick="dbmt_click(this);" class="dbmt_repair" >选择</a></div>';
                return html_con;
            }
        },{
            name: "FNUMBER",
            index: "FNUMBER",
            width: 200
        },{
            name: "FNAME",
            index: "FNAME",
            align: "center",
            width: 300
        },{
            name: "FSPECIFICATION",
            index: "FSPECIFICATION",
            align: "center",
            width: 200
        }];
    $("#grid").jqGrid({
        url: SITE_URL+"?ctl=Seller_Goods&met=listMaterial&typ=json",
        datatype: "json",
        height: setGrid().h,
        altRows: !0,
        multiselect: false,
        multiboxonly: false,
        gridview: !0,
        rowNum: 20,

        colNames: a,
        colModel: b,
        shrinkToFit: false,
        forceFit: true,
        autowidth: !0,
        viewrecords: !0,
        cmTemplate: {
            sortable: !1,
            title: !1
        },
        page: 1,
        pager: "#page",

        jsonReader: {
            root: "data.items",
            records: "data.records",
            total:"data.total",
            repeatitems: !1,
            id: "FNUMBER"
        },
        loadComplete: function(a) {
            if (a && 200 == a.status) {
                var b = {};
                a = a.data;
                for (var c = 0; c < a.items.length; c++) {
                    var d = a.items[c];
                    b[d.Name] = d
                }
                $("#grid").data("gridData", b)
            } else {
                var e = 250 == a.status ? "没有数据！" : "获取数据失败！" + a.msg;
                parent.Public.tips({
                    type: 2,
                    content: e
                })
            }
        },
        loadError: function(a, b, c) {
            parent.Public.tips({
                type: 1,
                content: "操作失败了哦，请检查您的网络链接！"
            })
        }
    })
}

   initGrid();