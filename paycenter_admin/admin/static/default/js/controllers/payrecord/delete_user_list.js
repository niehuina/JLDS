var queryConditions = {
        user_keys: '',
        status:0
    },  
    hiddenAmount = false, 
    SYSTEM = system = parent.SYSTEM;
var THISPAGE = {
    init: function(data){
        if (SYSTEM.isAdmin === false && !SYSTEM.rights.AMOUNT_COSTAMOUNT) {
            hiddenAmount = true;
        };
        this.mod_PageConfig = Public.mod_PageConfig.init('other-income-list');//页面配置初始化
        this.initDom();
        this.loadGrid();            
        this.addEvent();
    },
    initDom: function(){
        this.$_userName = $('#userName');
        this.$_userName.placeholder();
    },
    loadGrid: function(){
        var gridWH = Public.setGrid(), _self = this;
        var colModel = [
            {name:'operating', label:'操作', width:50, fixed:true, formatter:operFmatter, align:"center"},
            // {name:'user_id', label:'用户编号', width:100, align:"center"},
            {name:'user_account', label:'用户帐号', width:150,align:'center'},
            {name:'user_realname', label:'用户姓名', width:150,align:'center'},
            {name:'user_mobile', label:'用户手机号', width:150,align:'center'},
            {name:'user_money_pending_settlement', label:'待结算余额', width:110, align:"center"},
            {name:'user_money', label:'用户资金', width:110, align:"center"},
            {name:'user_money_frozen', label:'冻结资金', width:110, align:"center"},
            {name:'user_shares', label:'用户股金', width:110, align:"center"},
            {name:'user_stocks', label:'用户备货金', width:110, align:"center"},
            {name:'user_login_time', label:'最后登录时间', width:150, align:"center"},
            {name:'user_delete',label:'状态',  width:100,align:'center'},
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url: SITE_URL +'?ctl=Paycen_PayRecord&met=getUserDeleteResourceInfo&typ=json',
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
            rowNum: 100,
            rowList:[100,200,500], 
            viewrecords: true,
            shrinkToFit: false,
            forceFit: true,
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
        
    
        function operFmatter (val, opt, row) {
            var html_con = '<div class="operating" data-id="' + row.user_id + '">' +
                '<span class="ui-icon ui-icon-pencil" title="已结算"></span>' +
                '</div>';
            return html_con;
        };

    },
    reloadData: function(data){
        data.status = $('.tab-base>li>a.current').data('status');
        $("#grid").jqGrid('setGridParam',{postData: data}).trigger("reloadGrid");
    },
    addEvent: function(){
        var _self = this;
        //确定已结算
        $('.grid-wrap').on('click', '.ui-icon-pencil', function(e){
            e.preventDefault();
            var e = $(this).parent().data("id");
            handle.operate("ok", e)
        });
  
        $('#search').click(function(){
            queryConditions.page = 1;
            queryConditions.user_keys = _self.$_userName.val() === '请输入用户账号/用户姓名/用户手机号' ? '' : _self.$_userName.val();
            THISPAGE.reloadData(queryConditions);
        });
        
        $(window).resize(function(){
            Public.resizeGrid();
        });
    }
};
var handle = {
    operate: function (t, e)
    {
        if ("ok" == t)
        {
            $.dialog({
                title: '确定结算',
                content: '您确定是否已结算完成？请谨慎操作！',
                height: 100,
                width: 500,
                lock: true,
                drag: false,
                ok: function () {
                    $.post(SITE_URL  + '?ctl=Paycen_PayRecord&met=editExitUserResource&typ=json',{user_id:e},
                        function(data)
                        {
                            if(data && 200 == data.status) {
                                Public.tips({content: "操作成功！"});
                                queryConditions.page = 1;
                                THISPAGE.reloadData(queryConditions);
                            } else {
                                Public.tips({type: 1, content: "操作失败！"});
                            }
                        }
                    );
                }
            })
        }
    }, callback: function (t, e, i)
    {
        window.location.reload(); 
    }
};
$(function(){
    THISPAGE.init();

    $(".tab-base li").click(function () {
        $(".tab-base li a").removeClass('current');
        $(this).find('a').addClass('current');
        queryConditions.page = 1;
        THISPAGE.reloadData(queryConditions);
    })
});

