<style>
    .display{
        display:none
    }
</style>
<body class="bgf2">
<div class="container-fluid hp100" id="ticket">
    <div class="main-header">
        <div class="fl white pd-left f-14"  @click="back()"><i class="iconfont icon-back"></i><span class="middle"><?php echo __('返回');?></span></div>
        <h2><?php echo __('小票扫码订单');?></h2>
    </div>
    <div class="hp100 main-content">
        <div class="main-left bore5 pd0">
            <ul class="scan-order-nav clearfix tc">
                <li :class="{active:type==1}" @click="changeType(1)"><a href="javascript:;"><?php echo __('待付款');?></a></li>
                <li :class="{active:type==6}" @click="changeType(6)"><a href="javascript:;"><?php echo __('已完成');?></a></li>
            </ul>
            <!-- 暂无订单信息-->
            <div class="order-nodata tc" :class="{hide:order == 1}">
                <span class="icon-nodata"></span>
                <p><?php echo __('暂无订单信息');?></p>
            </div>
            <!-- 有订单信息 -->
             <div class="swiper-container swiper-order hp90">
                <ul class="order-ziti swiper-wrapper" :class="{hide:order != 1}" >
                    <li class="swiper-slide" v-for="v in order_list" @click="order_detail(v.order_id)" style="cursor:pointer" :class="{active:v.order_id == order_id}"><span><?php echo __('订单号：');?>{{v.order_id}}</span><p class="clearfix"><em class="fl">{{v.order_status|status}}</em><strong class="fr"><?php echo __('合计 ：￥');?>{{v.good_price}}</strong></p></li>
                </ul>
                 <div class="swiper-scrollbar swiper-scrollbar-order"></div>
            </div>
        </div>
        <div class="main-right bore5" :class="{hide:hide !=1}">
            <div class="t_search">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="<?php echo __('输入订单号或者会员手机号');?>" v-model="order_id">
				      <span class="input-group-btn">
				        <button class="btn btn-default" type="button" @click="search_ceshi(order_id)"><span class="iconfont icon-search white"></span></button>
				      </span>
                </div>
            </div>
            <div class="keyarea">
                <ul>
                    <li><a href="javascript:;" @click="calculator('1')">1</a></li>
                    <li><a href="javascript:;" @click="calculator('2')">2</a></li>
                    <li><a href="javascript:;" @click="calculator('3')">3</a></li>
                    <li><a href="javascript:;" @click="calculator('4')">4</a></li>
                    <li><a href="javascript:;" @click="calculator('5')">5</a></li>
                    <li><a href="javascript:;" @click="calculator('6')">6</a></li>
                    <li><a href="javascript:;" @click="calculator('7')">7</a></li>
                    <li><a href="javascript:;" @click="calculator('8')">8</a></li>
                    <li><a href="javascript:;" @click="calculator('9')">9</a></li>
                    <li><a href="javascript:;" @click="calculator('0')">0</a></li>
                    <li><a href="javascript:;" @click="backspace()" class="common-col f-30">←</a></li>
                    <li><a href="javascript:;" class="style-btn" @click="search_ceshi(order_id)"><?php echo __('查询');?></a></li>
                </ul>
            </div>
        </div>

        <div class="main-right bore5" :class="{hide:hide == 1}">
                <p class="right-order-status"><a @click="cancel()" href="javascript:;"><i class="iconfont icon-arrow-left"></i><span><?php echo __('订单信息');?></span></a></p>
                <div class="order-present">
                    <span><i class="iconfont icon-order-num"></i><em>{{detail.order_id}} </em></span>
                    <span class="tc inline">{{detail.order_status|status}}</span>
                    <span><?php echo __('商品数量：');?>{{detail.good_num}}</span>
                    <span><?php echo __('合计总额：￥');?>{{detail.good_price}}</span>
                </div>
                <div class="swiper-container swiper-ticket return-height">
                    <ul class="order-table tc swiper-wrapper">
                        <li v-for="v in detail_list" class="swiper-slide block clearfix">

                            <div class="tc fl w70"><img :src="v.yf_goods_common.file" alt=""> </div>
                            <div width="36%" class="fl">
                                <div class="tl">
                                
                                    <h5 class="ellipsis">{{v.goods_name}}</h5>
                                    <span><?php echo __('商品数量：');?>{{v.num}}</span>
                                </div>
                            </div>

                            <div class="tc fr wp20 mt30">￥{{v.goods_price}}</div>
                        </li>
                    </ul>
                    <div class="swiper-scrollbar swiper-scrollbar-ticket"></div> 
               
        </div>
         <div class="tc btn-area" ><a href="javascript:;" @click="cancel()" class="btn-bottom style" :class="{display:type == 6}"><?php echo __('取消');?></a><a href="javascript:;" @click="account()" class="btn-bottom style-btn" :class="{display:type == 6}"  ><?php echo __('结算');?></a></div>


    </div>


</div>

</body>

<script>
    $(function(){
        var ticketVue = new Vue({
            el: '#ticket',
            data:{
                type:1,
                order_id:'',
                order_list:[],
                hide:1,
                detail_list:[],
                detail:[],
                order:1,
                price: 0
            },
            filters:{
                status: function(obj){
                    if(obj == '1'){
                        return '待付款'
                    } else if(obj == '6') {
                        return '已完成'
                    }

                }

            },
            created: function(){

                var that = this
                $.ajax({
                    type: "POST",
                    url: base_url+"/home/welcome/order_ticket",
                    data: {type:1},
                    dataType: "json",
                    success: function(data) {

                        that.order_list = data;
                        var Swiperorder = new Swiper('.swiper-order', {
                        scrollbar: '.swiper-scrollbar-order',
                        direction: 'vertical',
                        slidesPerView: 'auto',
                        slidesPerView:6,
                        mousewheelControl: true,
                        freeMode: true,
                        initialSlide :0,
                        observer:true,//修改swiper自己或子元素时，自动初始化
                        observeParents:true//修改swiper的父元素时，自动初始化swiper
                     }); 

                        if(data.length == 0){
                            that.order = 0;
                        } else{
                            that.order = 1;
                        }

                    }
                });

            },
            methods:{
                back: function() {

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

                },
                /**
                 *
                 * 切换订单状态
                 */
                changeType: function(obj){

                    this.type = obj;
                    this.hide = 1;
                    var that = this

                    $.ajax({
                        type: "POST",
                        url: base_url+"/home/welcome/order_ticket",
                        data: {type:obj},
                        dataType: "json",
                        success: function(data) {
                            that.order_list = data;

                            if(data.length == 0){
                                that.order = 0;
                            } else{
                                that.order = 1;
                            }
                        }
                    });



                },
                /**
                 *
                 * 搜索订单
                 */
                /*search: function(){
                    if(!this.order_id){
                        $.notify('请输入订单号',"error");
                        return false;
                    }

                    var that = this

                    $.ajax({
                        type: "POST",
                        url: base_url+"/home/welcome/order_ticket",
                        data: {order_id: that.order_id, mold: 'order'},
                        dataType: "json",
                        success: function(data) {

                            if(data == null){
                                that.order = 0;
                                $.notify('该订单不存在',"error");
                            }else{
                                that.order = 1;
                                that.order_list[0] = data
                            }

                        }
                    });

                },*/
                /**
                 *
                 * 通过会员手机号和订单号搜索
                 */
                search_ceshi: function(val){
                    if(!val){
                        Public.tips.error('请输入订单号');
                        return false;
                    }

                    var that = this

                     $.get(base_url+"/home/welcome/order_serach",{wq:val},function(rs){
                        
                        if(data == null){
                                that.order = 0;
                                Public.tips.error('该订单不存在');
                            }else{
                                that.order = 1;
                                that.order_list[0] = data
                            }

                    },'json')

                },

                /**
                 *
                 * 键盘输入
                 */
                calculator: function(v) {
                    this.order_id += v;
                },

                /**
                 * 退格
                 */

                backspace: function() {

                    if(this.order_id) {
                        this.order_id = this.order_id.replace(/.$/,'');
                    }

                },

                /**
                 *
                 * 订单详情
                 */
                order_detail: function(obj) {
                    this.hide = 0;
                    this.order_id = obj

                    var that = this
                    $.ajax({
                        type: "POST",
                        url: base_url+"/home/welcome/order_ticket",
                        data: {order_id: obj, mold: 'detail'},
                        dataType: "json",
                        success: function(data) {

                            that.detail_list = data.detail
                            that.detail = data.order
                            var swiperticket= new Swiper('.swiper-ticket', {
                            scrollbar: '.swiper-scrollbar-ticket',
                            direction: 'vertical',
                            slidesPerView: 'auto',
                            slidesPerView:5,
                            mousewheelControl: true,
                            freeMode: true,
                            initialSlide :0,
                            observer:true,//修改swiper自己或子元素时，自动初始化
                            observeParents:true//修改swiper的父元素时，自动初始化swiper
                         });

                        }
                    });

                },
                cancel: function() {

                    this.hide = 1;
                    this.order_id = '';
                },

                account: function() {

                    var that = this;

                    $.ajax({
                        type: "POST",
                        url: base_url+"/home/welcome/pay",
                        data: {order_id:that.order_id, type:'ticket'},
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





    });


</script>