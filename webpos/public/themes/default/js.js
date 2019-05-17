$(function(){
    initAll();

});




function initAll(){
    /*
     *同步数据
     **/
    $('.sync').click(function(){
        sync(this);
    });
    $('#bt-store').click(function(){
        sync(this);
    });
    $('#bt-user').click(function(){
        sync(this);
    });
    function sync(obj){
        var url = $(obj).attr('rel');
        var urls = $('.btn_reload');
        $.get(url,function(data){
            Public.tips.success(data.msg)

        },'json');
        return false;

    }
    $('.yes').click(function(){
        var val = $('.yes').val();
        if(val == 1){
            $('.zhikou_0').removeAttr('readonly');
        }
    })
    $('.no').click(function(){
        
            $('.zhikou_0').attr('readonly','readonly');
        

    })
    /*密码显示隐藏*/
    $(".shop_pwd").click(function(){
        var input = document.getElementById("shop_user_pwd");
       if(input.type == 'text'){
        $("#shop_user_pwd").attr('type','password');
    }else{
        $("#shop_user_pwd").attr('type','text');
    }
        
    })

    $('#wq').blur(function(){
        if(!$(this).val()){
            $('#form_search').submit();
        }
    });

    $(".ajax *").focus(function(){
        $(this).parent('div.form-group').removeClass('has-error');
    });

    $('.ajax').ajaxForm({

        dataType:  'json',

        success:   function(data) {
            if(data.status == 1 && data.msg ){
                Public.tips.success(data.msg);
            }else if(data.msg){
                Public.tips.error(data.msg);
            }

            if(data.vali){
                $.each(data.vali,function(i,obj){
                    $("*[name='"+i+"']").parent("div.form-group").addClass('has-error');
                });
            }
            if(data.url){
                setTimeout(function(){
                    window.location.href = data.url;
                },1000);
            }
            if(data.render){
                $("."+data.render).html(data.html);
                adel();
                ajax_load_tabel_ele();

            }
        }
    });

    $('.btn_reload').click(function(){
        reload(this);
    });
    function reload(obj){
        var url = $(obj).attr('rel');
        $.get(url,function(d){
            $('.ajax_load_table').html(d.html);
            Public.tips.success('刷新成功');
            ajax_load_tabel_ele();
        },'json');
    }
    autocomplete();
    adel();
    function autocomplete(){
        $( ".autocomplete" ).autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: autocomplete_url,
                    dataType: "json",
                    data: {
                        top: 10,
                        wq: request.term
                    },
                    success: function(data) {
                        response($.map(data.lists, function(item) {
                            return { label: item.value, value: item.label }
                        }));
                    }
                });
            },
            select: function(event, ui) {

                if($('#form_search')){
                    $('#'+event.target.id).val(ui.item.value);
                    $('#form_search').submit();
                }

            },
            minLength: 2
        });
    }



    function escapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }


    ajax_load_table();
    function ajax_load_table(){
        var t = $('.ajax_load_table');
        $.get(t.attr('rel'),function(d){
            t.html(d.html);
            ajax_load_tabel_ele();
        },'json');

    }

    function ajax_load_tabel_ele(){
        adel();
        $('.ajax_load_table .ajax,.ajax_load_table .pagination a').click(function(){

            $.get($(this).attr('href'),function(d){
                $('.ajax_load_table').html(d.html);
                ajax_load_tabel_ele();
            },'json');
            return false;
        });

    }



    swal.setDefaults({ confirmButtonText: '关闭' });
    function alert_success(msg){
        swal("操作成功", msg, "success");
    }
    function alert_error(msg){
        swal("操作失败", msg, "error");
    }

    function adel(){
        $('.del').click(function(){
            var obj = this;
            swal({
                    title: "确认删除?",
                    text: "该操作将不可恢复",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "确认删除",
                    closeOnConfirm: false,
                    cancelButtonText:"取消"
                } ,

                function(isConfirm){
                    if (isConfirm) {

                        swal.setDefaults({ confirmButtonText: '关闭' });
                        var id=$(obj).data('id');
                        var url = $(obj).attr('rel');
                        var $tr = $(obj).parent().parent();
                        $.get(url,{"id":id},function(data){
                            if(data==1){
                                $tr.remove();
                            }else if(data==2){
                                alert_error("门店角色已被使用，无法进行删除或者修改");
                            }else if(data==3){
                                alert_error("该分类下有子分类，不可以删除");
                            }else if(data==4){
                                alert_error("该分类下有商品，不可删除");
                            }else{
                                alert_error("删除失败.");
                            }

                        });


                        setTimeout(function(){
                            swal.close();
                        },1000);

                    } else {

                    }
                });

            return false;
        });


    }

    loginout();
    function loginout(){
        $('#loginout').click(function(){
            var obj = this;
            swal({
                    title: "确认退出?",
                    text: "退出后将重新登录",
                    showCancelButton: true,
                    confirmButtonText: "确认",
                    closeOnConfirm: false,
                    cancelButtonText:"取消"
                } ,

                function(isConfirm){
                    if (isConfirm) {
                        swal.setDefaults({ confirmButtonText: '关闭' });
                        var url = $(obj).attr('rel');
                        window.location.href = url;

                    } else {

                    }
                });

            return false;
        });


    }



    jQuery.extend({
        browser: function()
        {
            var
                rwebkit = /(webkit)\/([\w.]+)/,
                ropera = /(opera)(?:.*version)?[ \/]([\w.]+)/,
                rmsie = /(msie) ([\w.]+)/,
                rmozilla = /(mozilla)(?:.*? rv:([\w.]+))?/,
                browser = {},
                ua = window.navigator.userAgent,
                browserMatch = uaMatch(ua);

            if (browserMatch.browser) {
                browser[browserMatch.browser] = true;
                browser.version = browserMatch.version;
            }
            return { browser: browser };
        }
    });

    function uaMatch(ua)
    {
        ua = ua.toLowerCase();

        var match = rwebkit.exec(ua)
            || ropera.exec(ua)
            || rmsie.exec(ua)
            || ua.indexOf("compatible") < 0 && rmozilla.exec(ua)
            || [];

        return {
            browser : match[1] || "",
            version : match[2] || "0"
        };
    }


    /*
     * 时间选择框
     */
    $(".select2").select2();

    $.datepicker.regional['zh-CN'] = {
        closeText: '关闭',
        prevText: '<上月',
        nextText: '下月>',
        currentText: '今天',
        monthNames: ['一月','二月','三月','四月','五月','六月',
            '七月','八月','九月','十月','十一月','十二月'],
        monthNamesShort: ['一','二','三','四','五','六',
            '七','八','九','十','十一','十二'],
        dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],
        dayNamesShort: ['周日','周一','周二','周三','周四','周五','周六'],
        dayNamesMin: ['日','一','二','三','四','五','六'],
        weekHeader: '周',
        dateFormat: 'yy-mm-dd',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: true,
        yearSuffix: '年'};
    $.datepicker.setDefaults($.datepicker.regional['zh-CN']);

    $('.datepicker').datepicker({
        autoclose: true
    });


    $('.iCheck').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'iradio_flat-blue'
    });

    $(".checkbox-toggle").click(function () {
        var clicks = $(this).data('clicks');
        if (clicks) {

            $("input[type='checkbox']").iCheck("uncheck");
            $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
        } else {

            $("input[type='checkbox']").iCheck("check");
            $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
        }
        $(this).data("clicks", !clicks);
    });


    $('.open_status').iphoneStyle({
        checkedLabel: "是",
        uncheckedLabel: "否"
    });



    /*
     * 选择是否享受折扣
     */
    $('.zhikou').on('ifChecked', function(event){
        var ory = $('.zhikou:checked').val();
        if(ory == 1){
            $(".zhikou_0").removeAttr("readonly");
        }else{
            $(".zhikou_0").attr("readonly","readonly");
        }
    });
    var ory = $('.zhikou:checked').val();
    if(ory == 1){
        $(".zhikou_0").removeAttr("readonly");
    }else{
        $(".zhikou_0").attr("readonly","readonly");
    }
    /*
     * 修改手机号
     */
    $('#get_phone').click(function(){
        $("#phone").removeAttr("readonly");
        $("#dis").removeAttr("style");
    });
    /*
     * 获取手机验证码   
     */

    $('#get_code').click(function(){
        var url = $(this).attr('rel');
        $("#code").attr('name','code');
        var obj = this;
        $.post(url,{phone:$('#phone').val()},function(dt){
            if(dt.status == false){
                alert_error(dt.msg);
            }else{
                invokeSettime(obj);
                alert_success(dt.msg);
            }
        },'json').error(function(){
            alert_error( '获取验证码失败，请稍后再试！');

        });

        return false;
    });
    function invokeSettime(obj){
        var countdown=60;
        settime(obj);
        function settime(obj) {
            if (countdown == 0) {
                $(obj).attr("disabled",false);
                $(obj).html("获取验证码");
                countdown = 60;
                return;
            } else {
                $(obj).attr("disabled",true);
                $(obj).html("(" + countdown + ") s 重新发送");
                countdown--;
            }
            setTimeout(function() {
                    settime(obj) }
                ,1000)
        }
    }
    /*
     *权限选择
     **/
    $('.iCheck').on('ifChecked', function(event){
        var id = $(this).data('id');
        $('.dj').each(function(){
            var pid = $(this).data('pid');
            if(id == pid){
                $(this).iCheck('check');
            }
        })
    });
    $('.iCheck').on('ifUnchecked', function(event){
        var id = $(this).data('id');
        $('.dj').each(function(){
            var pid = $(this).data('pid');
            if(id == pid){
                $(this).iCheck('uncheck');
            }
        })
    });


    /*
     * 图片上传
     **/
    var urlsz = $('#common_image').attr('rel');
    $("#common_image").AjaxFileUpload({
        onSubmit: function(filename ){
            if($('#img').val()){
                Public.tips.error('上传文件过多');
                return false;
            }
        },

        action: urlsz,
        onComplete: function(filename, response) {
            $('#img').val(response.name);
            $("#uploads").append(
                $("<img /><br /><a >删除</a>").attr("src", response.name).attr("width", 200).attr('id','del-img')
            );

        }
    });



    $(document).on('click','#del-img',function(){

        var url = $('#base_url').val();

        var img =$('#img').val();
        var id = $('#img_id').val();
        $.post(url,{id:id,img:img},function(data){

            $('#uploads').children().remove();
            $('#img').val('');

        });

    });


    /*
     *分类树
     */
    var hiddenNodes = [];

    var ztree = $('#ztree').val();

    var setting = {
        async: {
            enable: true,
            url:ztree,
            autoParam:["id", "name="],
            otherParam:{"otherParam":"zTreeAsyncTest"},
            dataFilter: filter
        }
    };


    function filter(treeId, parentNode, childNodes) {
        if (!childNodes) return null;
        for (var i=0, l=childNodes.length; i<l; i++) {
            childNodes[i].name = childNodes[i].name.replace(/\.n/g, '.');
        }

        return childNodes;
    }



    $.fn.zTree.init($("#tree-obj"), setting);
    /*
     *分类树搜索
     * */


    $("#search-bt").click(function(){
        var v = $('#keyword').val();

        var setting = {
            async: {
                enable: true,
                url:ztree+'?wq='+v,
                autoParam:["id", "name="],
                otherParam:{"otherParam":"zTreeAsyncTest"},
                dataFilter: filter
            }
        };


        $.fn.zTree.init($("#tree-obj"), setting);

    });
        

    /*
     *通过商家账号获取授权店铺数
     **/
    $('#shop_user_id').change(function(){
        ajax_load_select_data();
    });
    var ucenter_id = $('#ucenter_id').val();
    if(ucenter_id){
        ajax_load_select_data();
    }
    function ajax_load_select_data(){
        var v = $('#shop_user_id').val();
        var url=$('#shop_user_id').attr('rel');
        $.post(url,{id:v},function(d){
            $('#max_stores').val(d);
        });
    }

        var shuliang = $("#cont").val();
        var zuida = $("#cont").val();
        var cont = $("#cont");
        var all = $("#all_price");
        $("#jia").click(function(){
            if(shuliang < zuida){
                shuliang++;
                cont.val(shuliang);
                agio = (parseFloat($("#price").val()) + parseFloat($("#all_price").val()));
                all.val(agio);
                $("#one_price").text(agio);
            }else{
                Public.tips.error('退货数量不能超过购买数量!');
                cont.val(zuida);
            }
        });
        $("#jian").click(function(){
            if(shuliang > 0){
                shuliang--;
                cont.val(shuliang);
                agio = ($("#all_price").val() - $("#price").val());
                console.log($("#all_price").val())
                console.log($("#price").val())
                all.val(agio);
                $("#one_price").text(agio);
            }else{
                shuliang = 0;
                cont.val(shuliang);
            }
        })
        $('.order_return').click(function(){

             var goods_id = $('input[name="goods_id"]').val();
                 goods_id = new Array(goods_id);

             var num = $('input[name="num"]').val();
                 num = new Array(num);

             var goods_price = $('input[name="goods_price"]').val();
                 goods_price = new Array(goods_price);

            var price = $('input[name="goods_price"]').val();

            var order_id = $('input[name="order_id"]').val();

           
           
            if(num == 0){
                 Public.tips.error('请选择退货数量');
                 return false;
            }else{

                $.ajax({
                        type: "GET",
                        url: "/home/welcome/order_return",
                        data: {goods_id:goods_id, goods_num:num, order_id:order_id, goods_price:goods_price},
                        dataType: "json",
                        success: function(data) {
                           if(data.status == true){
                                Public.tips.success('退货成功');
                                window.location.href = "/doc/wp_order/index ";
                           }else{
                                Public.tips.error('退货失败');
                           }
                        }
                    });

            }
        })


}
