var api = frameElement.api;
var devidend_id = api.data.rowId;
var callback = api.data.callback;

var queryConditions = {
        id: devidend_id
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
            {name:'user_nickname', label:'用户姓名', width:200,align:'center'},
            {name:'record_money', label:'用户分红',  width:100, align:"center"}
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#user_grid").jqGrid({
            url:SITE_URL +  "?ctl=User_Shares&met=getDiviendDetails&typ=json",
            postData: queryConditions,
            datatype: "json",
            autowidth: true,//如果为ture时，则当表格在首次被创建时会根据父元素比例重新调整表格宽度。如果父元素宽度改变，为了使表格宽度能够自动调整则需要实现函数：setGridWidth
            //width: 400,
            height: 380,
            altRows: true, //设置隔行显示
            rownumbers: true,
            gridview: true,
            multiboxonly: true,
            colModel:colModel,
            cmTemplate: {sortable: false, title: false},
            page: 1,
            sortname: 'number',
            sortorder: "desc",
            pager: "#user_page",
            rowNum: 10,
            rowList:[10,20],
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
            resizeStop: function(newwidth, index){
                //THISPAGE.mod_PageConfig.setGridWidthByIndex(newwidth, index, 'grid');
            }
        }).navGrid('#user_page',{edit:false,add:false,del:false,search:false,refresh:false}).navButtonAdd('#user_page',{
            caption:"",
            buttonicon:"ui-icon-config",
            onClickButton: function(){
                THISPAGE.mod_PageConfig.config();
            },
            position:"last"
        });
    },
    reloadDatareloadData: function(data){
        data.id = devidend_id;
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
    select_user_list = {};
    THISPAGE.init();
});