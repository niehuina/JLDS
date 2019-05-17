<body class="bgf2">
<div class="container-fluid hp100 bgf2" id="return">
    <div class="main-header">
        <div class="fl white pd-left f-14" style="cursor:pointer" @click="back()"><i class="iconfont icon-back"></i><span class="middle"><?php echo __('返回');?></span></div>
        <h2><?php echo __('退货处理');?></h2>
    </div>
    <div class="hp100 main-content">
        <div class="main-left bore5 pd0">
            <h3 class="f-18 c6 pd20"><?php echo __('订单列表');?></h3>
            <!-- 暂无退货信息-->
            <div class="order-nodata tc" style="display:none;">
                <span class="icon-nodata back-goods"></span>
                <p><?php echo __('暂无退货信息');?></p>
            </div>
            <!-- 有退货订单 -->
            <div class="swiper-container swiper-order-back hp90">
                <ul class="order-ziti swiper-wrapper" >
                    <li v-for="v in reproducts" @click="order(v)" :class="{active:v.order_id == id}" class="swiper-slide">
                        <span><?php echo __('订单号：');?>{{v.order_id}}</span>
                        <p class="tr"><strong><?php echo __('合计 ：￥');?>{{v.good_price}}</strong></p>
                    </li>
                </ul>
                 <div class="swiper-scrollbar swiper-scrollbar-order-return"></div> 
            </div>
           
        </div>

        <div class="main-right bore5 list" style="display:block;">
            <div class="t_search">
                <div class="input-group">
                    <input type="text" v-model="actual" class="form-control" placeholder="<?php echo __('输入订单号或者会员手机号');?>">
                    <i class="iconfont icon-search-cancel " @click="del()"></i>
                      <span class="input-group-btn">
                        <button class="btn btn-default"  @click="inquire(actual)"  type="button"><i class="iconfont icon-search white"></i></button>
                      </span>
                </div>
            </div>
            <div class="keyarea">
                <ul>
                    <li><a href="javascript:;"  @click="changeActual('1')">1</a></li>
                    <li><a href="javascript:;"  @click="changeActual('2')">2</a></li>
                    <li><a href="javascript:;"  @click="changeActual('3')">3</a></li>
                    <li><a href="javascript:;"  @click="changeActual('4')">4</a></li>
                    <li><a href="javascript:;"  @click="changeActual('5')">5</a></li>
                    <li><a href="javascript:;"  @click="changeActual('6')">6</a></li>
                    <li><a href="javascript:;"  @click="changeActual('7')">7</a></li>
                    <li><a href="javascript:;"  @click="changeActual('8')">8</a></li>
                    <li><a href="javascript:;"  @click="changeActual('9')">9</a></li>
                    <li><a href="javascript:;"  @click="changeActual('0')">0</a></li>
                    <li><a href="javascript:;"  @click="backspace()" class="common-col f-30">←</a></li>
                    <li><a href="javascript:;" @click="inquire(actual)" class="style-btn"><?php echo __('查询');?></a></li>
                </ul>
            </div>
        </div>
        <!-- 退货信息 -->
        <div class="main-right bore5 info"   style="display:none;">
            <p class="right-order-status" @click="rnnedo()">
                <a href="javascript:;"><i class="iconfont icon-arrow-left"></i><span><?php echo __('订单信息');?></span></a>
            </p>
            <!-- <td width="22px"><input type="checkbox"  @click="quanxuan(infogood)"  class="style-input "></td> -->
            <div class="order-present">

                <span><i class="iconfont icon-order-num"></i><em>{{number}} </em></span>
                <span><?php echo __('订单支付方式：');?>{{payid}}  </span>
                <span><?php echo __('可退货数量');?>：{{return_num}}</span>
                <span><?php echo __('合计退款：￥');?>{{return_price}}</span>
            </div>
            <div class="swiper-container swiper-return return-height">
                <!-- <div class="swiper-wrapper"> -->
                    <ul class="order-table tc swiper-wrapper">
                        <li v-for="v in infogood" class="swiper-slide block clearfix">
                            <div class="fl w22"><input type="checkbox" @click="checkbox(v)" class="style-input" v-model="v.state" ></div>
                            <div class="fl tc w70"><img :src="v.file" alt=""> </div>
                            <div class="fl">
                                <div class="tl">

                                    <h5 class="ellipsis">

                                        {{v.goods_name}}
                                        <input type="hidden" name="goods_name" v-model="v.goods_name">
                                        <input type="hidden" name="goods_id" v-model="v.goods_id">
                                        <input type="hidden" name="shop_id" v-model="v.shop_id">
                                    </h5>
                                        <span >
                                        <?php echo __('购买数量： ');?>{{v.order_num}}
                                        </span>
                                </div>
                            </div>
                            <div class="fr order-right-pad">
                                <em class="fl" style="width:90px;"><?php echo __('已退数量：');?>{{v.return_num}}</em>
                                <div class="clearfix nums fl" style="width:110px;">
                                    <span class="reduce" @click="reduce(v)" >-</span>
                                    <input type="text"  v-model="v.num" readonly>
                                    <span  class="add"  @click="add(v)">+</span>
                                </div>
                                <em class="fl" style="width:80px;">￥{{v.price*v.num}}
                                <input type="hidden" name="goods_price" v-model="v.goods_price"></em>
                            </div>
                        </li>
                    </ul> 
                <!-- </div> -->
              
                <div class="swiper-scrollbar swiper-scrollbar-return"></div> 
            </div>
            



            <div class="tc btn-area">
                <a href="javascript:;"  @click="rnnedo()" class="btn-bottom style"><?php echo __('取消');?></a>
                <a type="submit" id="button" @click="tanchu(order_id)"  class="btn-bottom style-btn"><?php echo __('退货');?></a>
            </div>




        </div>


    </div>

    <div class="prompt tc tanchu" style="display:none;">
        <div class="prompt-cont">
            <span class="pro-img"></span>
            <p><?php echo __("确定退选中的所有商品？") ?></p>
            <div>
                <a type="submit" id="button" @click="btn(order_id)"  class="prompt-btn sure"><?php echo __('确定');?></a>
                <a type="submit"  @click="quxiao()" class="prompt-btn cancel"><?php echo __('取消');?></a>
            </div>
        </div>
    </div>

</div>




</body>
<script type="text/javascript">
    $(function(){
        var returnVue = new Vue({
            el:"#return",
            data:{
                actual:'',
                reproducts:[],
                infogood:[],
                goods:[],
                order_id:[],
                id:'',
                number:'',      /*订单号*/
                payid:'',
                re_money:0,
                goods_num:0,
                return_num:0,  /*退货商品数量*/
                return_price:0, /*退货总价*/
                return_order_id:[],
                return_order_num:[],
                return_goods_price:[]
            },
            methods:{
                changeActual: function(v) {
                    this.actual += v;

                },
                inquire: function(actual) {
                    var this_ = this;

                    if(!actual){
                        Public.tips.error( "请输入正确的查询信息");
                    }

                    $.get(base_url+"/home/user_search/order_list",{wq:actual},function(rs){
                        
                        if(!rs.data){
                            Public.tips.error( "暂无此订单信息");
                        }else{
                            this_.reproducts = rs.data;
                        }

                        

                        var Swiperorder = new Swiper('.swiper-order-back', {
                            scrollbar: '.swiper-scrollbar-order-return',
                            direction: 'vertical',
                            slidesPerView: 'auto',
                            slidesPerView:6,
                            mousewheelControl: true,
                            freeMode: true,
                            initialSlide :0,
                            observer:true,//修改swiper自己或子元素时，自动初始化
                            observeParents:true//修改swiper的父元素时，自动初始化swiper
                         });


                    },'json')

                },
                del: function(){
                    this.actual = '';
                },
                backspace: function() {
                    if(this.actual) {
                        this.actual = this.actual.replace(/.$/,'');
                    }
                },
                order:function(obj){

                    var this_ = this;
                    $('.list').css('display','none');

                    $('.info').css('display','block');
                    this_.id = obj.order_id;


                    this_.return_order_id = [];
                    this_.return_order_num = [];
                    this_.return_goods_price = [];


                    $.get(base_url+"/home/user_search/info_good",{order_id:obj.order_id},function(rs){

                        this_.infogood = rs.data['0'];
                        this_.number = rs.data['order_id'];
                        var swiperss= new Swiper('.swiper-return', {
                            scrollbar: '.swiper-scrollbar-return',
                            direction: 'vertical',
                            slidesPerView: 'auto',
                            slidesPerView:5,
                            mousewheelControl: true,
                            freeMode: true,
                            initialSlide :0,
                            observer:true,//修改swiper自己或子元素时，自动初始化
                            observeParents:true//修改swiper的父元素时，自动初始化swiper
                         });
                        var url = " " ;
                        switch(rs.data['payid'])
                                {
                                    case 'cash':
                                            url = '现金';
                                        break;

                                    case 'xpsm':
                                            url = '小票扫码';
                                        break;

                                    case 'wepay':
                                            url = '微信';
                                        break;

                                    case 'unionpay':
                                            url = '银行卡';
                                        break;
                                        
                                    case 'alipay':
                                            url = '支付宝';
                                        break;

                                    case 'member':
                                            url = '余额';
                                        break;    

                                }

                        this_.payid = url;
                        this_.return_num = rs.data['return_num'];
                        this_.return_price = rs.data['return_price'];



                        for(var i in rs.data['0']){

                            this_.return_order_id.push(rs.data['0'][i]['goods_id']);
                            this_.return_order_num.push(rs.data['0'][i]['num']);
                            this_.return_goods_price.push(rs.data['0'][i]['num'] * rs.data['0'][i]['price']);


                        }

                    },'json')
                },
                rnnedo:function(){

                    $('.list').css('display','block');

                    $('.info').css('display','none');

                },
                tanchu:function(obj){
                    if(!this.return_order_id.length){

                        Public.tips.error( "请选择要退货的商品");

                    }else{
                        $('.tanchu').css('display','block')
                    }
                    
                },

                quxiao:function(){
                    $('.tanchu').css('display','none')
                },
                add:function(obj){

                    if(obj.num<obj.tmp_num){

                        obj.num++;

                        if(obj.state){
                            this.return_num ++ ;
                            this.return_price = parseFloat(this.return_price) + parseFloat(obj.price);

                            var u = this.return_order_id.indexOf(obj.goods_id);
                            this.return_order_num[u] = obj.num;
                            this.return_goods_price[u] = obj.num*obj.price;

                        }

                    }else{

                        obj.num = obj.tmp_num;
                        Public.tips.error( "退货数量超过限度");

                    }

                },
                reduce:function(obj){
                    
                    /*商品数量减少 价钱随之改变 */
                    if(obj.num > 1){

                        obj.num--;

                         if(obj.state){

                             this.return_num -- ;
                             this.return_price = parseFloat(this.return_price) - parseFloat(obj.price);

                             var u = this.return_order_id.indexOf(obj.goods_id);
                             this.return_order_num[u] = obj.num;
                             this.return_goods_price[u] = obj.num*obj.price;

                         }

                    }else if(obj.num == 0){

                        obj.num = 0;
                        Public.tips.error( "此商品无退货数量可选择");

                    }else{

                        obj.num = 1;
                        Public.tips.error( "退货数量不能为空");

                    }
                },
                checkbox:function(obj){

                    if(obj.state){

                        this.return_num += parseInt(obj.num) ;
                        this.return_price += parseFloat(obj.price*obj.num);


                        this.return_order_id.push(obj.goods_id);
                        this.return_order_num.push(obj.num);
                        this.return_goods_price.push(obj.num*obj.price);


                    }else{

                        this.return_num -= parseInt(obj.num) ;
                        this.return_price -= parseFloat(obj.price*obj.num);

                        var u = this.return_order_id.indexOf(obj.goods_id);

                        this.return_order_id.splice(u, 1);
                        this.return_order_num.splice(u, 1);
                        this.return_goods_price.splice(u, 1);

                    }

                },
                btn:function(){

                    var that = this;
                    
                    $('.tanchu').css('display','none');

                    $.ajax({
                        type: "GET",
                        url: base_url+"/home/welcome/order_return",
                        data: {goods_id:that.return_order_id, goods_num:that.return_order_num, order_id:that.number, price:that.return_price, goods_price:that.return_goods_price},
                        dataType: "json",
                        success: function(data) {

                            if(data.status == true) {
                                Public.tips.success( data.msg);
                               $('.list').css('display','block');

                               $('.info').css('display','none');
                                
                            }else{
                                Public.tips.error(data.msg);
                            }
                        }
                    });


                    
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
                            }else if(data.msg){
                                Public.tips.error(data.msg);
                            }
                        }
                    });

                }
            }
        })
    });
    
    
</script>
