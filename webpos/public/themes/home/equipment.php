<body class="bgf2">
<div class="container-fluid hp100" id="equipement">
    <div class="main-header">
        <div class="fl white pd-left f-14" style="cursor:pointer" @click="back()"><i class="iconfont icon-back"></i><span class="middle">返回</span></div>
        <h2>设备管理</h2>
    </div>
    <div class="main-content hp100">
        <div class="main-left pd0 left_relative">
            <div class="left_box">
                <h3>设备信息列表</h3>
                <ul class="equipment_list">
                    <li class="clearfix">
                        <div class="fl">打印机</div>
                        <div class="fr">型号：SPRT58</div>
                    </li>

                </ul>
                <div class="left-bottom-btn" @click="addE()"><a href="javascript:;" class="btn-active">添加设备</a></div>
            </div>
        </div>
        <div class="bore5 main-right">
            <div class="auto">
                <ul class="equipment-ul">
                    <li class="clearfix">
                        <strong class="fl">打印机</strong>
                        <em class="fl">型号：SPRT58</em>
                        <a href="javascript:;">自动检测</a>
                        <span class="active fr"><i class="iconfont icon-connect"></i><b>已连接</b></span>
                    </li>
                    <li class="clearfix">
                        <strong class="fl">小票机</strong>
                        <em class="fl">型号：SPRT20</em>
                        <a href="javascript:;">自动检测</a>
                        <span class="fr"><i class="iconfont icon-inconnect"></i><b>未连接</b></span>
                    </li>
                </ul>
            </div>
            <!-- 暂无设备 -->
            <!--   <div class="no-equip w-table tc">
                <div class="w-table-cell">
                  <span></span>
                  <p>暂无设备</p>
                </div>
              </div> -->


        </div>
    </div>

    <!-- 弹框提示 -->
    <div class="prompt tc" :class="{hide:show!=1}">
        <div class="prompt-cont">
            <div class="add-input">
                <dl>
                    <dt>设备名称</dt>
                    <dd><input type="text" placeholder="请输入设备名称"></dd>
                </dl>
                <dl>
                    <dt>设备型号</dt>
                    <dd><input type="text" placeholder="请输入设备型号"></dd>
                </dl>
            </div>
            <div>
                <a href="javascript:;" class="prompt-btn sure" @click="equSure()">确定</a><a href="javascript:;" class="prompt-btn cancel" @click="equCancel()">取消</a>
            </div>
        </div>
    </div>
</div>


</body>

<script>

    var equipementVue = new Vue({
        el: '#equipement',
        data:{
            show:0
        },
        methods:{
            /**
             * 添加设备弹框
             */
            addE:function(){
                this.show = 1;

            },
            equSure: function(){

            },
            equCancel: function(){
                this.show = 0;
            },
            back: function(){

                $.ajax({
                    type: "GET",
                    url: base_url+"/home/welcome/user_index",
                    data: {},
                    dataType: "json",
                    success: function(data) {

                        if(data.status) {
                            $(" body").html(data.html);

                        }

                    }
                });
            }


        }


    });


</script>