var queryConditions = {

    },
    hiddenAmount = false,
    SYSTEM = system = parent.SYSTEM;
var THISPAGE = {
    init: function(data){
        if (SYSTEM.isAdmin === false && !SYSTEM.rights.AMOUNT_COSTAMOUNT) {
            hiddenAmount = true;
        };
        this.mod_PageConfig = Public.mod_PageConfig.init('log_list');//页面配置初始化
        this.initDom();
        this.loadGrid();
        this.addEvent();
    },
    initDom: function(){
        this.$_searchYear = $('#searchYear');
        this.$_searchYear.placeholder();
    },
    loadGrid: function(){
        var gridWH = Public.setGrid(), _self = this;
        var colModel = [
            {name:'dividend_year', label:'分红年份', width:150,align:'center'},
            {name:'dividend_amount', label:'分红总金额', width:200, align:"center"},
            {name:'shares_member', label:'分红明细', width:200, align:"center", "formatter":function (val, opt, row) {
                    return '<span class="view_shares ui-label ui-label-success" data-id="' + row.id+ '">分红明细</span>';
                }},
            {name:'shares_dividend', label:'股份分红比例(%)', width:250, align:"center"},
            {name:'dividend_datetime', label:'分红时间', width:250, align:"center"},
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url:SITE_URL +  '?ctl=User_Shares&met=getList&typ=json',
            postData: queryConditions,
            datatype: "json",
            autowidth: true,//如果为ture时，则当表格在首次被创建时会根据父元素比例重新调整表格宽度。如果父元素宽度改变，为了使表格宽度能够自动调整则需要实现函数：setGridWidth
            height: gridWH.h,
            altRows: true, //设置隔行显示
            gridview: true,
            multiselect: false,
            multiboxonly: true,
            colModel:colModel,
            cmTemplate: {sortable: false, title: false},
            page: 1,
            sortname: 'number',
            sortorder: "desc",
            pager: "#page",
            rowNum: 20,
            rowList:[20,50,100],
            viewrecords: true,
            shrinkToFit: false,
            forceFit: true,
            jsonReader: {
                root: "data.items",
                records: "data.records",
                repeatitems : false,
                total : "data.total",
                id: "dividend_id"
            },
            loadError : function(xhr,st,err) {

            },
            ondblClickRow : function(rowid, iRow, iCol, e){
                $('#' + rowid).find('.ui-icon-pencil').trigger('click');
            },
            resizeStop: function(newwidth, index){
                THISPAGE.mod_PageConfig.setGridWidthByIndex(newwidth, index, 'grid');
            }
        }).navGrid('#page',{edit:false,add:false,del:false,search:false,refresh:false}).navButtonAdd('#page',{
            caption:"",
            buttonicon:"ui-icon-config",
            onClickButton: function(){
                THISPAGE.mod_PageConfig.config();
            },
            position:"last"
        });

//        function operFmatter (val, opt, row) {
//            var html_con = '<div class="operating" data-id="' + row.id+ '">--</div>';
//            return html_con;
//        };

    },
    reloadData: function(data){
        $("#grid").jqGrid('setGridParam',{postData: data}).trigger("reloadGrid");
    },
    addEvent: function(){
        var _self = this;
        //添加
        $("#btn-add").click(function (t)
        {
            t.preventDefault();
            Business.verifyRight("INVLOCTION_ADD") && handle.add("id")
        });
        //查询
        $('#search').click(function(){

            queryConditions.page = 1;
            queryConditions.year = _self.$_searchYear.val() === '请输入分红年份...' ? '' : _self.$_searchYear.val();
            THISPAGE.reloadData(queryConditions);
        });

        $("#btn-refresh").click(function ()
        {
            THISPAGE.reloadData('');
            _self.$_searchYear.val('请输入分红年份...');
        });

        $(window).resize(function(){
            Public.resizeGrid();
        });
    }
};
var handle = {
    add: function (t, e)
    {
        f = 'complain-progress';
        parent.tab.addTabItem({
            tabid: f,
            text: '新增年度分红',
            url: SITE_URL + '?ctl=User_Shares&met=addSharesDividend'
        })
    }

};
$(function(){
    THISPAGE.init();

    $('#grid').on('click', '.view_shares', function (e) {
        var devidend_id = $(this).data('id');
        var data = {
            rowId: devidend_id,
            callback: this.callback
        };
        $.dialog({
            title: '分红明细',
            content: 'url:' + SITE_URL + '?ctl=User_Shares&met=details&typ=e&id=' + devidend_id,
            data: data,
            width: 550,
            height: 480,
        });
    })
});
