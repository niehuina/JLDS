
<style>
    .display{
        display:none
    }
    .payments{
        font-size: 18px;
        color: #666666;
        text-align: center;
    }
    .qrcode-cross-explain{display:none;}
</style>
<body class="bgf2">
<div class="container-fluid hp100" id="pay">
    <div class="main-header">
        <div class="fl white pd-left f-14" @click="back()"><i class="iconfont icon-back"></i><span class="middle"><?php echo __('返回')?></span></div>
        <div class="fr white pd-right f-16" @click="abolish()" style="cursor: pointer;"><?php echo __('作废')?></div>
        <h2><?php echo __('收款');?></h2>
    </div>
    <div class="hp100 main-content" >
        <div class="main-left bore5">
            <h3 class="f-18 c6"><?php echo __('请选择支付方式')?></h3>
            <div class="cp-way" >
                <ul>					
                    <li @click="change(<?php echo config('payment.member');?>,$event)" id="memberPay">
                        <a class="cp5" href="javascript:;"><span><?php echo __('账户余额');?></span>
                            <input  class="style-input" type="radio" name="cpway" :checked="show==<?php echo config('payment.member');?>"></a>
                    </li>
                    <li @click="change(<?php echo config('payment.cash');?>,$event)" id="cashPay">
                        <a class="cp1" href="javascript:;"><span><?php echo __('现金');?></span>
                            <input  class="style-input" type="radio" name="cpway" :checked="show==<?php echo config('payment.cash');?>"></a>
                    </li>
                    <li @click="change(<?php echo config('payment.alipay');?>,$event)">
                        <a class="cp2" href="javascript:;"><span><?php echo __('支付宝支付');?></span>
                            <input  class="style-input" type="radio" name="cpway" :checked="show==<?php echo config('payment.alipay');?>"></a>
                    </li>
                    <li @click="change(<?php echo config('payment.wepay');?>,$event)">
                        <a class="cp3" href="javascript:;"><span><?php echo __('微信支付');?></span>
                            <input  class="style-input" type="radio" name="cpway" :checked="show==<?php echo config('payment.wepay');?>"></a>
                    </li>
                    <li @click="change(<?php echo config('payment.unionpay');?>,$event)">
                        <a class="cp4" href="javascript:;"><span><?php echo __('银联POS');?></span>
                            <input  class="style-input" type="radio" name="cpway" :checked="show==<?php echo config('payment.unionpay');?>"></a>
                    </li>

                    <!--                    --><?php //if($type != 'ticket'){?>
                    <!--                        <li  @click="change(--><?php //echo config('payment.xpsm');?><!--,$event)">-->
                    <!--                            <a class="cp6" href="javascript:;"><span>--><?php //echo __('小票扫码支付');?><!--</span>-->
                    <!--                                <input class="style-input" type="radio" name="cpway"></a>-->
                    <!--                        </li>-->
                    <!--                    --><?php //}?>
                </ul>
            </div>
        </div>

        <div class="main-right bore5">
            <div class="top-content" :class="{'top-content-height':phone != 0}">
                <div class="tc-left">
                    <ul>
                        <li><span><?php echo __('商品数量');?></span><em>{{num}}</em></li>
                        <li><span><?php echo __('合计金额');?></span><em><?php echo $all_price;?></em></li>
                        <li :class="{display:phone == 0}">
                            <span><?php echo __('满即减');?></span><em>￥<?php echo $manjian;?></em></li>
                        <li><span><?php echo __('优惠方式');?></span><em><?php echo $discount; ?></em></li>
                        <li><span><?php echo __('优惠金额');?></span><em>￥<?php echo $yhje;?></em></li>
                        <li :class="{display:phone == 0}">
                            <span><?php echo __('平台红包');?></span><em>￥<?php echo $rpt_price;?></em></li>
                        <li :class="{display:phone == 0}">
                            <span><?php echo __('优惠券');?></span><em>￥<?php echo $voucher_price;?></em></li>
                    </ul>
                </div>
                <div class="tc-middle"></div>
                <div class="tc-right" >
                    <!-- 支付方式是现金时  -->
                    <div class="w-table" :class="{display:show!=<?php echo config('payment.cash');?>}">
                        <ul class="w-table-cell">
                            <li>
                                <span><?php echo __('应收金额');?></span>
                                <em>￥{{price}}</em>
                            </li>

                            <li class="common-col">
                                <span class="f-20"><?php echo __('实收金额');?></span>
                                <em class="f-20">
                                    <input maxlength="8" type="text" v-model="actual" onkeyup="value=value.replace(/[^\d]/g,'')">
                                </em>
                            </li>

                            <li>
                                <span><?php echo __('找零');?></span>
                                <em>￥{{give}}</em>
                            </li>
                        </ul>
                        <script>
                            $(function(){
                                $(".top-content input").focus(function(){
                                    $(this).addClass("active");
                                })
                            })
                        </script>
                    </div>

                    <!-- 支付方式不是现金时  -->
                    <div class="w-table" :class="{display:show==<?php echo config('payment.cash');?>}">
                        <ul class="w-table-cell">
                            <li class="common-col">
                                <span class="f-20"><?php echo __('应收金额');?></span>
                                <em class="f-20">￥{{price}}</em>
                            </li>
                            <li class="common-col" :class="{display:show!=<?php echo config('payment.member');?>}">
                                <span class="f-20"><?php echo __('账户余额');?></span>
                                <em class="f-20">￥{{account}}</em>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
            <div class="bottom-content" :class="{'member-top':phone != 0}">
                <div class="acc-content clearfix" :class="{display:show!=<?php echo config('payment.member');?>}">
                    <div class="op-main acc-main-wh input-group" :class="{display:!isPay}">
                        <input class="form-control" type="password" id="password" placeholder="<?php echo __('请输入交易密码');?>"
                               v-model="password" onkeyup="value=value.replace(/[^\d]/g,'')" autocomplete="off">
                        <button class="op-btn lr-linear" type="button" @click="settlement(<?php echo config('payment.member');?>)" ><?php echo __('确定');?></button>
                    </div>
                    <div :class="{display:isPay}">
                        <span class="payments">
                            <?php echo __('账户余额不足，请更换支付方式!') ?>
                        </span>
                    </div>
                </div>

                <div class="xj-content clearfix" :class="{display:show!=<?php echo config('payment.cash');?>}" >

                    <a href="javascript:;" @click="changeActual('1')">1</a>
                    <a href="javascript:;" @click="changeActual('2')">2</a>
                    <a href="javascript:;" @click="changeActual('3')">3</a>
                    <a class="a-right" href="javascript:;" @click="actualPrice()">￥{{price}}</a>
                    <a href="javascript:;" @click="changeActual('4')">4</a>
                    <a href="javascript:;" @click="changeActual('5')">5</a>
                    <a href="javascript:;" @click="changeActual('6')">6</a>
                    <a class="a-right" href="javascript:;" @click="clear()">C</a>
                    <a href="javascript:;" @click="changeActual('7')">7</a>
                    <a href="javascript:;" @click="changeActual('8')">8</a>
                    <a href="javascript:;" @click="changeActual('9')">9</a>
                    <a class="a-right a-arrow" href="javascript:;" @click="backspace()">←</a>
                    <a href="javascript:;" @click="changeActual('00')">00</a>
                    <a href="javascript:;" @click="changeActual('0')">0</a>
                    <a href="javascript:;" @click="changeActual('.')" >·</a>
                    <a class="a-right a-btn" href="javascript:;" @click="settlement(<?php echo config('payment.cash');?>)"><?php echo __('完成结算');?></a>
                </div>

                <div class="zfb-content" :class="{display:show!=<?php echo config('payment.alipay');?>}" id="alipay">
                    <iframe  id="myFrame" style="border: none;"  width="300" height="300"  >

                    </iframe>

                </div>
                <div class="zfb-content"  :class="{display:show!=<?php echo config('payment.wepay');?>}" id="wx">

                </div>


                <span class="payments" v-if="payments == 2">
                <?php echo __('请打开手机支付宝,扫一扫本二维码,完成支付!') ?>
                </span>

                <span class="payments" v-if="payments == 1">
                <?php echo __('请打开手机微信,扫一扫本二维码,完成支付!') ?>
                </span>

                <div class="pos-content xpsm-content" :class="{display:show!=<?php echo config('payment.xpsm');?>}">
                    <div class="op-main pos-main-wh" @click="settlement(<?php echo config('payment.xpsm');?>)"><i class="iconfont icon-print"></i><?php echo __('打印支付小票');?></div>
                    <button class="op-btn lr-linear" type="button" @click="back()"><?php echo __('返回收银');?></button>
                </div>

                <div class="pos-content" :class="{display:show!=<?php echo config('payment.unionpay');?>}">
                    <div class="op-main pos-main-wh" ><i class="iconfont icon-print"></i><?php echo __('刷卡支付');?></div>
                    <button class="op-btn lr-linear" type="button" @click="settlement(<?php echo config('payment.unionpay');?>)" ><?php echo __('完成结算');?></button>
                </div>


            </div>
        </div>


        <div class="prompt tc"  :class="{hide:cancel!=1}">
            <div class="prompt-cont">
                <span class="void-img"></span>
                <p><?php echo __('确定作废当前订单？');?></p>
                <div style="cursor:pointer">
                    <a href="javascript:;" class="prompt-btn sure" @click="sure()"><?php echo __('确定');?></a><a href="javascript:;" class="prompt-btn cancel" @click="not_sure()"><?php echo __('取消');?></a>
                </div>
                <em class="iconfont prompt-close icon-quxiao" @click="not_sure()"></em>
            </div>
        </div>



    </div>
</div>
</body>

<script>

    $(function(){

        function check_order(order_id){
            $.ajax({
                type: "POST",
                url: base_url+"/home/welcome/order_status",
                data: {order_id:order_id},
                dataType: "json",
                async: false,
                success: function(data) {
                    if(data.status == 200 && data.data.order_state_id==2){

                        payVue.order_status = true;

                    }
                }
            });
            return payVue.order_status;
        }

        function common(obj){
            $.ajax({
                type: "POST",
                url: base_url+"/home/welcome/pay_complete",
                data: {order_id:obj },
                dataType: "json",
                success: function(res) {
                    if(res.status) {

                        $(" body").html(res.html);
                    }
                }
            });
        }

        var payVue = new Vue({
            el: '#pay',
            data: {
                show:<?php echo config('payment.cash');?>,
                num:0,                      /*总数量*/
                totalprice:0,               /*总价*/
                price:0,                    /*实际总价*/
                cancel:0,
                actual:'',                  /*实际支付*/
                phone:0,                    /*手机号*/
                type:'common',
                order_id:'',
                order_status:'',
                give:0  ,/*找零*/
                way:'' ,
                payments:0,
                account:0,   /*账户余额*/
                password:'',  /*支付密码*/
				isPay:false
            },
            created: function() {
                this.way = '';
                this.password = '';
                this.type = "<?php echo $type;?>";
                this.totalprice = "<?php echo $all_price;?>";
                this.price = "<?php echo $money;?>";
                this.order_id = "<?php echo $order_id;?>";
                if(this.type == 'common'){
                    this.num = "<?php echo $good_num;?>";
                } else {
                    this.num = "<?php echo $good_num;?>";
                }

                var member = cookie_get_member();
                if(member){
					this.show = <?php echo config('payment.member');?>;
                    this.phone = member.phone;
                    var last_money = member.account_balance;
                    if(parseFloat(last_money) > parseFloat(this.price)){
                        this.able_check = 1;
                        $('#verify').removeClass('not-complete');
                        $('#verify').addClass('style-btn');
                        this.isPay=true;
                    }
                    this.account = last_money;
					$("#memberPay").show();
                }else{
					$("#memberPay").hide();
				}
            },

            watch: {
                /*
                 监听serach内容变化
                 */
                actual:function(wq){
                    if(wq){

                        this.give = parseFloat(wq - this.price).toFixed(2);
                        if(this.give < 0){
                            this.give = 0;
                        }
                    }else{
                        this.give = 0;
                    }
                }
            },
            methods: {


                actualPrice: function(){
                    this.actual = this.price;
                },

                change: function(obj,event) {

                    this.show = obj;

                    if(this.show == <?php echo config('payment.wepay');?>){
                        this.payments = 1;
                    }else if(this.show == <?php echo config('payment.alipay');?>){
                        this.payments = 2;
                    }else{
                        this.payments = 0;
                    }

                    var that = this;
                    var payment_way = '';
                    var shop_name = "<?php echo $shop_name.'订单支付';?>";
                    if(this.show == <?php echo config('payment.wepay');?>)
                    {
                        payment_way = 'wx';
                        $.ajax({
                            type: "POST",
                            url: base_url+"/home/welcome/show_code",
                            data: {order_id:that.order_id,amount:that.price,payment_way:payment_way,title:shop_name},
                            dataType: "json",
                            success: function(data) {
                                console.log(data);
                                $("#wx").html(data);
                                $('img').addClass('imgw');
                                $('.imgw').css('width','300px');
                                $('.imgw').css('height','300px');

                            }
                        });

                    }else if(obj == <?php echo config('payment.alipay');?>){
                        payment_way = 'alipay';
                        $('#myFrame').attr('src',"<?php echo url('home/welcome/show_code',['order_id'=>$order_id,'amount'=>$money,'payment_way'=>'alipay','title'=>$shop_name.'订单支付']);?>");

                    }

                    $(".cp-way li input").prop("checked",false);
                    var ev=event.currentTarget.getElementsByTagName("input")[0];
                    ev.checked=true;
                },

                /**
                 * 完成结算
                 */
                settlement: function() {

                    var obj =  this.show;

                    this.way = 'ok';

                    if(obj == <?php echo config('payment.member');?>){
                        if(this.password){
//                            var member = cookie_get_member();
//                            if(member.password != this.password){
//                                Public.tips.error('交易密码不正确，请重新输入！');
//                                return false;
//                            }
                        }
                        else{
                            Public.tips.error('请输入交易密码');
                            return false;
                        }
                    }

                    if(!this.actual && obj == <?php echo config('payment.cash');?>) {
                        Public.tips.error('请输入实收金额');
                        return false;
                    }

                    if(parseFloat(this.actual) < parseFloat(this.price) && obj == "<?php echo config('payment.cash');?>") {
                        Public.tips.error('实收金额需大于等于订单金额');
                        return false;
                    }

                    var that = this;
                    if(obj != <?php echo config('payment.cash');?> ){
                        that.actual = '';
                    }
                    if(this.type == 'common'){

                        var user = get_cache('user');
                        var member = cookie_get_member();
                        var discount_status = get_cache('discount_status');
                        var goodlist = <?php echo json_encode($goods);?>;

                        if(member){
                            var redpacket_id = <?php echo $redpacket_id;?>;
                            var rpt_price = <?php echo $rpt_price;?>;
                            var voucher_id = <?php echo $voucher_id;?>;
                            var voucher_price = <?php echo $voucher_price;?>;
                        }

                        $.ajax({
                            type: "POST",
                            url: base_url+"/home/welcome/settlement",
                            data: {
                                user:member?member.id:user,
                                discount_status:discount_status,
                                type:obj,
                                goods:goodlist,
                                totalprice:that.totalprice,
                                price:that.price,
                                return_price:this.give,
                                actual:that.actual,
                                num:that.num,
                                kind:'common',
                                order_id:that.order_id,
                                redpacket_id:redpacket_id,
                                voucher_id:voucher_id,
                                password:this.password
                            },
                            dataType: "json",
                            success: function(data) {

                                if(data.status==true) {
                                    cookie_clear();
                                    common(that.order_id);
                                }else{
                                    Public.tips.error('请确认密码输入是否正确');
                                }

                            },
                            error:function(){
                                Public.tips.error('交易失败');
                            }
                        });


                    } else {

                        $.ajax({
                            type: "POST",
                            url: base_url+"/home/welcome/settlement",
                            data: {type:obj, price:that.price, actual:that.actual, num:that.num,order_id:that.order_id, kind:'ticket'},
                            dataType: "json",
                            success: function(data)
                            {
                                if (data.status)
                                {
                                    common(that.order_id);

                                }
                            }
                        });


                    }


                },

                /**
                 * 返回上一页
                 */
                back: function() {
                    this.way = 'ok';
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

                },
                abolish: function() {
                    this.cancel = 1;
                },
                /**
                 *  作废订单
                 */
                sure: function() {

                    if(this.type == 'ticket'){
                        Public.tips.error('小票订单无法作废');
                        return false;
                    }

                    var user = get_cache('user');

                    cookie_clear();

                    payVue.back();

                },

                /**
                 *  取消作废
                 */
                not_sure: function() {
                    this.cancel = 0;
                },

                changeActual: function(v) {
                    this.actual += v;
                },

                clear: function() {
                    this.actual = '';
                },

                backspace: function() {
                    if(this.actual) {

                        this.actual = this.actual.replace(/.$/,'');

                    }

                }

            }

        })



        var interval = setInterval( function()
        {
            if(payVue.way == 'ok'){
                clearInterval(interval);

            }
            if (check_order("<?php echo $order_id;?>"))
            {

                clearInterval(interval);

                payVue.settlement();
            }
        }, 1500);



        function cookie_clear(){

            var user = get_cache('user');

            var guadan = get_cache('guadan');
            var message = get_cache('message');

            if(guadan) {
                for (var i in guadan) {
                    if(guadan[i] == user) {

                        guadan.splice(i, 1);
                        message.splice(i, 1);

                    }
                }

                set_cache('guadan', guadan);
                set_cache('message', message);
            }

            cookie_clear_cart();
            cookie_clear_member();

        }

    });



</script>
