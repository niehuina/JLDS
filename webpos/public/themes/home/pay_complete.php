
<body class="bgf2">

<div class="container-fluid hp100" id="ticket">
    <div class="main-header">
        <h2><?php echo __('完成收款')?></h2>
    </div>

    <div class="hp100 main-content">
        <div class="main-left bore5 pd-none">
            <div class="cpay-left-top">
                <ul>
                    <li><?php echo __('关联单号：')?><span><?php echo $goods_info['order_id']?></span></li>
                    <li><?php echo __('交易时间：')?><span><?php echo $goods_info['created']?></span></li>
                    <li><?php echo __('收银员：')?><span><?php echo $goods_info['shop_users_name']?></span></li>
                </ul>
            </div>
            <div class="cpay-goods cpay-goods-reset">
                <div class="tit">
                    <span class="tit-head ellipsis"><?php echo __('商品名称')?></span>
                    <span><?php echo __('数量')?></span>
                    <span><?php echo __('单价')?></span>
                    <span><?php echo __('金额')?></span>
                </div>
                <ul class="hp90">
                    <?php foreach($goods_info['goods'] as $goods){?>
                        <li>
                            <div class="clearfix">
                                <span class="spanW ellipsis"> <?php
                                    echo $goods['name'];
                                    ?></span>
                                <span><?php echo $goods['nums']?></span>
                                <span><?php echo $goods['goods_price']?></span>
                                <span><?php echo $goods['goods_price']*$goods['nums']?></span>
                            </div>
                        </li>
                    <?php }?>
                </ul>

            </div>
            <div class="cpay-total">
                <ul>
                    <li><?php echo __('累计消费数量')?><span><?php echo $goods_info['num'] ?></span></li>
                    <li><?php echo __('会员编号')?><span><?php echo $goods_info['numbers'] ?></span></li>
                    <li><?php echo __('优惠方式')?><span><?php echo $goods_info['yh']?></span></li>
                    <li>
                        <div class="total-area"><?php echo __('合计')?><span><?php echo $goods_info['price'] ?></span>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="cpay-way cpay-total">
                <ul>
                    <li><?php echo __('收款方式')?><span><?php echo $goods_info['payid']; ?></span></li>
                    <li><?php echo __('收据编号')?><span><?php echo $goods_info['sjbh'];?></span></li>
                </ul>
            </div>

            <div class="cpay-bottom">
                <?php echo $goods_info['shop_name']?>
            </div>
        </div>

        <!--打印区域-->
        <div class="bore5 pd-none" id="sy" style="display: none">
            <div class="cpay-left-top">
                <ul>
                    <li><?php echo __('关联单号：');?><span><?php echo $goods_info['order_id'];?></span></li>
                    <li><?php echo __('交易时间：');?><span><?php echo $goods_info['created'];?></span></li>
                    <li><?php echo __('收银员：');?><span><?php echo $goods_info['shop_users_name'];?></span></li>
                </ul>
            </div>
            <div class="cpay-goods">
                <div class="tit">
                    <span class="tit-head ellipsis"><?php echo __('商品名称');?></span>
                    <span><?php echo __('数量');?></span>
                    <span><?php echo __('单价');?></span>
                    <span><?php echo __('金额');?></span>
                </div>
                <ul class="borUl">
                    <?php foreach($goods_info['goods'] as $goods){?>
                        <li>
                            <div class="clearfix">
                                <span class="spanW ellipsis"> <?php
                                    echo $goods['name'];
                                    ?></span>
                                <span><?php echo $goods['nums'];?></span>
                                <span><?php echo $goods['goods_price'];?></span>
                                <span><?php echo $goods['goods_price']*$goods['nums'];?></span>
                            </div>
                        </li>
                    <?php }?>
                </ul>

            </div>
            <div class="cpay-total">
                <ul>
                    <li><?php echo __('累计消费数量')?><span><?php echo $goods_info['num']; ?></span></li>
                    <li><?php echo __('会员编号')?><span><?php echo $goods_info['numbers']; ?></span></li>
                    <li><?php echo __('优惠方式')?><span><?php echo $goods_info['yh'];?></span></li>
                    <li>
                        <div class="total-area"><?php echo __('合计')?><span><?php echo $goods_info['price']; ?></span>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="cpay-way cpay-total">
                <ul>
                    <li><?php echo __('收款方式')?><span><?php echo $goods_info['payid']; ?></span></li>
                    <li><?php echo __('收据编号')?><span><?php echo $goods_info['sjbh'];?></span></li>
                </ul>
            </div>

            <div class="cpay-bottom">
                <?php echo $goods_info['shop_name'];?>
            </div>
        </div>

        <!--
        扫码订单小票
        -->
        <div class="bore5 pd-none" style="display: none" id="small_ticket_sweets">
            <div class="cpay-left-top ">
                <ul>
                    <li><?php echo __('关联单号：');?><span><?php echo $goods_info['order_id'];?></span></li>
                    <li><?php echo __('交易时间：');?><span><?php echo $goods_info['created'];?></span></li>
                    <li><?php echo __('收银员：');?><span><?php echo $goods_info['shop_users_name'];?></span></li>
                </ul>
            </div>
            <div class="cpay-goods">
                <div class="tit">
                    <span class="tit-head ellipsis"><?php echo __('商品名称');?></span>
                    <span><?php echo __('数量');?></span>
                    <span><?php echo __('单价');?></span>
                    <span><?php echo __('金额');?></span>
                </div>
                <ul class="borUl">
                    <?php foreach($goods_info['goods'] as $goods){?>
                        <li>
                            <div class="clearfix">
                                <span class="spanW ellipsis"> <?php
                                    echo $goods['name'];
                                    ?></span>
                                <span><?php echo $goods['nums']?></span>
                                <span><?php echo $goods['goods_price']?></span>
                                <span><?php echo $goods['goods_price']*$goods['nums']?></span>
                            </div>
                        </li>
                    <?php }?>
                </ul>
            </div>
            <div class="cpay-total">
                <ul>
                    <li><?php echo __('累计消费数量');?><span><?php echo $goods_info['num'];?></span></li>
                    <li><?php echo __('会员编号');?><span><?php echo $goods_info['numbers'];?></span></li>
                    <li><?php echo __('优惠方式');?><span><?php echo $goods_info['yh'];?></span></li>
                    <li>
                        <div class="total-area"><?php echo __('合计');?><span><?php echo $goods_info['price'];?></span>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="cpay-way cpay-total">
                <ul>
                    <li><?php echo __('收款方式');?><span><?php echo $goods_info['payid'];?></span></li>
                    <li><?php echo __('收据编号');?><span><?php echo $goods_info['sjbh'];?></span></li>
                </ul>
                <div class="tc tic-code">
                    <div id="code" style="display: none"></div>
                    <img id="image" src="" />
                    <p><?php echo __('支付宝、微信扫码立即支付');?></p>
                </div>
            </div>
            <p class="tic-store title"><?php echo __('webpos收银系统');?><?php echo $goods_info['shop_name'];?></p>
        </div>
        <!--
        end
        -->

        <div class="main-right bore5">
            <div class="cpay-right-top">
                <i class="icon icon-complete"></i>
                <span>￥<?php echo $goods_info['price']?></span>
                <em><?php if($goods_info['status'] == 6){echo __('完成收款');}else{echo __('等待付款');}?></em>
            </div>
            <div class="cpay-right-bottom">
                <?php if($goods_info['payment_id'] != config('payment.xpsm')){?>
                    <div class="cpay-print" @click="printOrder()"><a href="javascript:;"><i class="iconfont icon-print"></i><?php echo __('打印小票')?></a></div>
                <?php }?>
                <div class="op-cbtn">
                    <button type="button" class="style-btn" @click="con()"><?php echo __('继续收银')?></button>
                </div>


            </div>
        </div>
    </div>
</div>

<script>

    $(function(){


        var ticketVue = new Vue({
            el: '#ticket',
            created: function() {
                var payment_id = <?php echo $goods_info['payment_id']; ?>;
                if(payment_id == <?php echo config('payment.xpsm');?>)
                {
                    $('#small_ticket_sweets').css('display','block');
                }
            },
            mounted: function(){
                var payment_id = <?php echo $goods_info['payment_id']; ?>;
                if(payment_id == <?php echo config('payment.xpsm');?>)
                {
                    var url = "<?php echo 'http://'."$_SERVER[HTTP_HOST]".url('home/welcome/ticket_code_pay',['order_id'=>$goods_info['order_id']]);?>";
                    console.log(url);
                    $('#code').qrcode(url);
                    var img = document.getElementById("image");
                    var canvas  = document.getElementsByTagName("canvas")[0];
                    img.src = canvas.toDataURL();
                    $('#small_ticket_sweets').jqprint();
                    $('#small_ticket_sweets').css('display','none');
                }

            },
            methods: {

                printOrder:function(){
                    $('#sy').css('display','block');
                    $('#sy').jqprint();
                    $('#sy').css('display','none');

                },

                con: function() {
                    set_cache('user', 't_' + new Date().getTime());

                    $.ajax({
                        type: "GET",
                        url: base_url+"/home/welcome/user_index",
                        data: {},
                        dataType: "json",
                        success: function(data) {
                            if(data.status) {
                                console.log(data);
                                $(" body").html(data.html);

                            }
                        }
                    });

                }

            }

        });

    })


</script>
</body>