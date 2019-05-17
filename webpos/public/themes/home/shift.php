
<body class="bgf2">
<div class="container-fluid hp100" id="shift">
    <div class="main-header">
        <div class="fl white pd-left f-14" @click="back()" style="cursor:pointer"><i class="iconfont icon-back"></i><span class="middle"><?php echo __('返回');?></span></div>
        <h2><?php echo __('交接班记录');?></h2>
    </div>
    <div class="main-content hp100">
        <div class="main-left pd0 left_relative">
            <div class="left_box">
                <div class="record-head tc">
                    <img src="<?php echo theme_url(); ?>/img/default-user.png" alt="">
                    <p><span class=""><?php echo cookie('home_nickname');?></span><span class=""><?php echo cookie('home_num');?></span></p>
                </div>
                <div class="record-pro">
                    <dl>
                        <dt><i class="iconfont icon-time"></i><span><?php echo __('本次登录时间');?></span></dt>
                        <dd><?php echo cookie('first_login_time');?>~~{{time}}</dd>
                    </dl>
                    <dl>
                        <dt><i class="iconfont icon-store"></i><span><?php echo __('所属门店');?></span></dt>
                        <dd><?php echo cookie('shop_name');?></dd>
                    </dl>
                    <p class="off"><input type="checkbox" checked id="shift_ticket"><span class="middle"><?php echo __('交接班后打印小票');?></span></p>
                </div>
                <div class="left-bottom-btn" @click="signOuts()"><a href="javascript:;" class="btn-active"><?php echo __('交接班退出');?></a></div>
            </div>
        </div>
        <div class="bore5 main-right">
            <div class="auto">
                <p class="record-mon"><span><?php echo __('收银总额');?>：￥<?php echo $syze;?></span></p>
                <ul class="pay-methods">
                    <li>
                        <p><?php echo __('现金支付');?></p>
                        <span>￥<?php echo $cash_payments;?></span>
                    </li>
                    <li>
                        <p><?php echo __('银联支付');?></p>
                        <span>￥<?php echo $unionpay_pay;?></span>
                    </li>
                     <li>
                        <p><?php echo __('微信支付');?></p>
                        <span>￥<?php echo $weixin_pay;?></span>
                    </li>
                    <li>
                        <p><?php echo __('支付宝支付');?></p>
                        <span>￥<?php echo $alipay_pay;?></span>
                    </li>

                </ul>
                <div class="remarks">
                    <dl class="clearfix">
                        <dt class="fl"><?php echo __('备用金');?></dt>
                        <dd class="fr"><input type="text" v-model="spare" @input="input()" class="inp-set"></dd>
                    </dl>
                    <dl class="clearfix">
                        <dt class="fl"><?php echo __('实缴金额应为');?></dt>
                        <dd class="fr">￥{{total+spare}}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>



    <div class="prompt tc" style="display: none;background-color: white" id='confirm'>
        <div class="small-tic" id="form1">
            <h4 class="title tc"><?php echo __('交接班小票');?></h4>
            <div class="tic-module1 pdlr20">
                <dl>
                    <dt><?php echo __('收银员：');?></dt>
                    <dd><?php echo cookie('home_nickname');?></dd>
                </dl>
                <dl>
                    <dt><?php echo __('登录时间：');?></dt>
                    <dd><?php echo cookie('first_login_time');?>-{{time}}</dd>
                </dl>
                <dl>
                    <dt><?php echo __('票据编号：');?></dt>
                    <dd><?php echo cookie('record_succession_id');?>号</dd>
                </dl>
            </div>
            <div class="tic-module2 total-cash pdlr20">
                <dl class="clearfix">
                    <dt><?php echo __('收银金额');?></dt>
                    <dd><?php echo $syze;?></dd>
                </dl>
            </div>
            <div class="tic-module2 tic-pay-ways pdlr20">
                <dl class="clearfix">
                    <dt><?php echo __('现金支付');?></dt>
                    <dd><?php echo $cash_payments;?></dd>
                </dl>
                <dl class="clearfix">
                    <dt><?php echo __('银联支付');?></dt>
                    <dd><?php echo $unionpay_pay;?></dd>
                </dl>
                <dl class="clearfix">
                    <dt><?php echo __('微信支付');?></dt>
                    <dd><?php echo $weixin_pay;?></dd>
                </dl>
                <dl class="clearfix">
                    <dt><?php echo __('支付宝支付');?></dt>
                    <dd><?php echo $alipay_pay;?></dd>
                </dl>

            </div>
            <div class="tic-module2 tic-reserve-fund pdlr20">
                <dl class="clearfix">
                    <dt><?php echo __('备用金');?></dt>
                    <dd>{{spare}}</dd>
                </dl>
            </div>
            <div class="tic-module2 tic-actual-amount pdlr20">
                <dl class="clearfix">
                    <dt><?php echo __('实缴金额应为');?></dt>
                    <dd>{{total+spare}}</dd>
                </dl>
            </div>
            <p class="tic-store title"><?php echo __('webpos收银系统');?><?php echo cookie('shop_name');?></p>
        </div>
    </div>

</div>

</body>

<script>

    $(function(){
        var shiftVue = new Vue({
            el: '#shift',
            data:{
                spare:'',
                total:'',
                shop_name:'',
                home_nickname:'',
                home_num:'',
                first_login_time:'',
                time:''
            },
            created: function(){
                this.total = parseFloat("<?php echo $cash_payments;?>");
                this.time = "<?php echo date('Y-m-d H:i', time())?>"

            },

            methods:{
                signOuts:function(){
                    if(this.spare == ''){
                        Public.tips.error('请输入备用金');
                        return false;
                    }
                    $('#confirm').css('display','block');
                    $('#form1').jqprint();
                    $('#confirm').css('display','none');
                    shiftVue.signOut();
                },
                signOut: function(){
                    var cash_payments = "<?php echo $cash_payments;?>";
                    var unionpay_pay = "<?php echo $unionpay_pay;?>";
                    var weixin_pay = "<?php echo $weixin_pay;?>";
                    var alipay_pay = "<?php echo $alipay_pay;?>";
                    var that = this;

                    $.ajax({
                        type: "POST",
                        url: base_url+"/home/welcome/shiftPost",
                        data: {cash_payments:cash_payments, unionpay_pay:unionpay_pay, weixin_pay:weixin_pay, alipay_pay:alipay_pay, spare:that.spare},
                        dataType: "json",
                        success: function(data) {
                            if(data.status == 1 && data.msg ){
                                Public.tips.success(data.msg);
                            }else{
                                Public.tips.error(data.msg);
                            }
                            if(data.url){
                                setTimeout(function(){
                                    var storage=window.localStorage;
                                    storage.a=1;
                                    storage.setItem("c",3);
                                    storage.clear();
                                    window.location.href = data.url;
                                },1000);
                            }
                        }
                    });
                },
                input: function(){
                    if(this.spare){
                        this.spare = parseFloat(this.spare);
                    }
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
    })
</script>