var queryConditions = {
        type: 'all'
    },
    hiddenAmount = false,
    SYSTEM = system = parent.SYSTEM;
var THISPAGE = {
    init: function(data){
        if (SYSTEM.isAdmin === false && !SYSTEM.rights.AMOUNT_COSTAMOUNT) {
            hiddenAmount = true;
        };
        this.mod_PageConfig = Public.mod_PageConfig.init('complain-new-list');//页面配置初始化
        this.initDom();
        this.loadGrid();
        this.addEvent();
    },
    initDom: function(){
    },
    loadGrid: function(){
        var gridWH = Public.setGrid(), _self = this;
        var colModel = [
            {name:'operating', label:'操作', width:90, fixed:true, formatter:operFmattershop, align:"center"},
            {name:'user_name', label:'用户名',  width:200, align:"center"},
            {name:'user_realname', label:'用户姓名', width:200,align:'center'},
            {name:'user_grade', label:'用户等级',  width:100, align:"center"},
            {name:'user_regtime', label:'升级为股东的时间',  width:200, align:"center"},
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#user_grid").jqGrid({
            url:SITE_URL +  "?ctl=User_Shares&met=get_user_list&typ=json",
            postData: queryConditions,
            datatype: "json",
            autowidth: true,//如果为ture时，则当表格在首次被创建时会根据父元素比例重新调整表格宽度。如果父元素宽度改变，为了使表格宽度能够自动调整则需要实现函数：setGridWidth
            height: 600,
            altRows: true, //设置隔行显示
            gridview: true,
            multiboxonly: true,
            colModel:colModel,
            cmTemplate: {sortable: false, title: false},
            page: 1,
            sortname: 'number',
            sortorder: "desc",
            pager: "#user_page",
            rowNum: 20,
            rowList:[20,50,100],
            viewrecords: true,
            shrinkToFit: false,
            forceFit: false,
            jsonReader: {
                root: "data.items",
                records: "data.records",
                repeatitems : false,
                total : "data.total",
                id: "user_id"
            },
            loadError : function(xhr,st,err) {

            },
            ondblClickRow : function(rowid, iRow, iCol, e){
                $('#' + rowid).find('.ui-icon-pencil').trigger('click');
            },
            resizeStop: function(newwidth, index){
                THISPAGE.mod_PageConfig.setGridWidthByIndex(newwidth, index, 'grid');
            }
        }).navGrid('#user_page',{edit:false,add:false,del:false,search:false,refresh:false}).navButtonAdd('#user_page',{
            caption:"",
            buttonicon:"ui-icon-config",
            onClickButton: function(){
                THISPAGE.mod_PageConfig.config();
            },
            position:"last"
        });

        function operFmattershop(val, opt, row) {
            var html_con = '<div class="operating" data-id="' + row.user_id + '"><input type="checkbox" /></div>';
            return html_con;
        };
    },
    reloadData: function(data){
        $("#user_grid").jqGrid('setGridParam',{postData: data}).trigger("reloadGrid");
    },
    addEvent: function(){
        var _self = this;
        $(window).resize(function(){
            Public.resizeGrid();
        });
    }
};

$(function(){
    $source = $("#source").combo({
        data: [{
            id: "all",
            name: "所有股东"
        },{
            id: "all_g_partner",
            name: "所有高级合伙人"
        },{
            id: "all_partner",
            name: "所有合伙人"
        },{
            id: "all_one_year",
            name: "所有满一年的股东"
        },{
            id: "part",
            name: "部分(手动选择)"
        }],
        value: "id",
        text: "name",
        width: 200,
        callback: {
            onChange: function (e) {
                var type = e.id;
                if(type == "part"){
                    $("#select_user").show();
                }else{
                    $("#select_user").hide();
                }
            },
        }
    }).getCombo();

    var date = new Date();
    var year = date.getFullYear();
    var preYear = year - 1;

    var data_year = [{
        id: preYear,
        name: preYear
    }];

    $year = $("#dividend_year").combo({
        data: data_year,
        value: "id",
        text: "name",
        width: 200,
    }).getCombo();

    $(".submit-btn").click(function () {
        Public.ajaxPost(SITE_URL + "?ctl=User_Shares&typ=json&met=save", {
            dividend_year: $year.getValue(),
            type: $source.getValue(),
        }, function (e)
        {
            if (200 == e.status)
            {
                Public.tips({content: _("股份分红成功！")});
            }
            else
            {
                Public.tips({type: 1, content: _("股份分红失败！") + e.msg})
            }
        })
    });

    THISPAGE.init();
});