<body class="bgf2">
    <div class="container-fluid hp100" id="logo">
        <div class="main-header">
            <div class="fl white pd-left f-14"  @click="back()"><i class="iconfont icon-back"></i><span class="middle"><?php echo __('返回');?></span> </div>

            <h2><?php echo __('关于我们');?></h2>
        </div>
        <div class="hp100 main-content">
            <div class="bgf hp100 radius10">

                <div class="w-table tc">
                    <div class="w-table-cell">
                        <div class="about-me tc">
                            <img src="<?php echo theme_url(); ?>/img/logo.png" alt="">
                            <h3 class=common-col><?php echo __('WebPos收银系统');?></h3>
<!--                            <span>--><?php //echo __('当前版本号V').config('app.version');?><!--</span>-->
<!--                            <span>--><?php //echo __('客服热线：400-8581598');?><!--</span>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
    </div>
    
 
<script>
    $(function(){
        var logoVue = new Vue({
            el: '#logo',
            data:{

            },
            created: function(){

            },
            methods:{
                back: function() {

                    $.ajax({
                        type: "GET",
                        url: base_url+"/home/welcome/user_index",
                        data: {},
                        dataType: "json",
                        success: function(data) {
                            console.log(data);
                            if(data.status) {
                                $(" body").html(data.html);

                            }
                        }
                    });

                }

            }



        });

    });


</script>
</body>