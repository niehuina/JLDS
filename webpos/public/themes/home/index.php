
<?php $this->extend('header'); ?>
<style>
    [v-cloak]{ display: none; }
</style>
<div class="container-fluid hp100">
    <!-- 头部 -->
    <div class="header clearfix">
        <div class="">
            <div class="pd0 clearfix">
                <div class="fl btn-nav"><i class="iconfont icon-menu"></i><span class="f-16"><?php echo __('功能菜单')?></span></div>
                <div class="fr" id="search">
							<span>
								<i class="iconfont icon-search"></i>
								<input v-model="message" @keyup="keydown(message)"  type="text" id="message"  placeholder="<?php echo __('输入条形码、商品名称')?>" >
                                <i class="iconfont icon-search-cancel fr" @click="del()"></i>
							</span>
                </div>
            </div>
        </div>
    </div>
    <!-- 头部功能菜单下拉侧弹出 -->
    <ul class="w-slide-nav" id="slide">
        <li @click="slide(1)" :class="{active:active==1}"><a href="javascript:;"><i class="iconfont icon-huiyuanguanli"></i><span><?php echo __('会员管理')?></span></a></li>
        <li @click="slide(2)" :class="{active:active==2}"><a href="javascript:;"><i class="iconfont icon-jiaojiebanjilu"></i><span><?php echo __('交接班记录')?></span></a></li>

        <li @click="slide(4)" :class="{active:active==4}"><a href="javascript:;"><i class="iconfont icon-tuihuoguanli"></i><span><?php echo __('退货管理')?></span></a></li>
        <li @click="slide(5)" :class="{active:active==5}"><a href="javascript:;"><i class="iconfont icon-xiaopiaosaomadingdan"></i><span><?php echo __('小票扫码订单')?></span></a></li>
<!--        <li @click="slide(6)" :class="{active:active==6}"><a href="javascript:;"><i class="iconfont icon-guanyuwomen"></i><span>--><?php //echo __('关于我们')?><!--</span></a></li>-->
        <div class="tc btn-signOut"><a rel="<?php echo url('home/login/loginout');?>" id="loginout"><?php echo __('退出')?></a></div>
    </ul>

    <div class="bgf2 hp100">
        <div class="hp100">
            <!-- left -->
            <div class="main hp100">
                <div class="bgf bore5 pd20 radius10 hp100 relative" id="leftProduct"   >
                    <div class="auto swiper-container swiper-container1" id="category"  >
                        <ul class="w-nav clearfix swiper-wrapper" >
                            <li class="swiper-slide all_cat " @click="all_category()"><a href="javascript:;"  ><?php echo __('全部分类')?></a></li>
                            <li class="swiper-slide" v-cloak v-for = "v in category"  :class="{active: activeId == v.id}"
                                @click="category_change(v.id,$event)"  >
                                <a href="javascript:;">
                                    {{v.cat_name}}
                                </a>
                            </li>



                        </ul>

                    </div>
                    <div  id="products"   >
                        <div class="w-menu-items">
                            <ul class="clearfix row">

                                <li v-cloak v-for = "v in products"    class="col-lg-3 col-md-4 col-sm-6">
                                    <a href="javascript:;" @click="click_goods(v)" >
                                        <img :src="v.file" alt="" style="width: 80px;height: 110px;">
                                        <div class="goods-desc">
                                            <div class="">
                                                <h3 class=""> {{v.common_name}}</h3>

                                                <span> <?php echo __('库存:');?>{{v.common_stock}} </span>
                                                <span> <?php echo __('规格:');?>{{v.common_spec_name}} </span>
                                                <span> <?php echo __('低保价:');?>{{v.common_dibao_price}} </span>
                                                <p class="mt4">￥ {{v.common_price}}  </p>
                                            </div>
                                        </div>
                                        <b v-if="v.common_discounts == 1" class="icon-discount"></b>
                                    </a>
                                </li>
                            </ul>
                            <p v-if="active == 1"  class="nodata tc"><b></b><span><?php echo __("查无此商品") ?></span></p>
                        </div>


                        <div  class="drap-down tc">

                            <a href="javascript:;"  v-if="prev"    @click="loadPrev()">
                                <i class="iconfont icon-prev" ></i>
                            </a>

                            <a href="javascript:;"  v-if="loading" @click="loadMore(1)" >
                                <i class="iconfont icon-next" ></i>
                            </a>

                        </div>



                    </div>
                </div>

                <?php $this->extend('user_search'); ?>
                <div class="bgf bore5 pd20 radius10 hp100 relative" id="orders" :class="{hide:show!=1}">

                    <div class="cont-head" @click="hide()">
                        <a href="javascript:;"><i class="iconfont icon-arrow-left"></i><span><?php echo __('挂单列表信息')?></span></a>
                    </div>

                    <div class="swiper-container swiper-container5 hp90">
                        <ul class="guadan-list  swiper-wrapper">
                            <li v-cloak v-for = "(v,k) in orderList" class="swiper-slide">
                                <div class="clearfix top">
                                    <div class="fl" @click="cart(v)" style="cursor:pointer">
                                        <i class="iconfont icon-rili"></i><span>{{messageList[k]}}|{{v}}</span>
                                    </div>
                                    <div class="fr"><span><?php echo __('合计')?>：{{priceList[k]|fixed}}</span><i class="icon" data-off="0" ></i> </div>
                                </div>
                                <!-- 下拉 -->
                                <div class="guadan-list-con" style="display:none;">
                                    <dl class="clearfix" v-cloak v-for="val in goodsList[k]">
                                        <dt class="fl">
                                            <img :src="val.file" alt="" style="width: 80px;height: 100px;">
                                        <div>
                                            <h4 class="">{{val.common_name}}</h4>
                                            <span><?php echo __('购买数量')?>：{{val.number}}</span>
                                        </div>
                                        </dt>
                                        <dd class="fr">￥{{val.common_price}}</dd>
                                    </dl>
                                </div>
                            </li>
                        </ul>
                        <div class="swiper-scrollbar swiper-scrollbar5"></div>
                    </div>

                </div>

            </div>
            <!-- right -->
            <div class="right-settle">
                <div class="settlement pd0">
                    <div class="settle-head clearfix" id="shopinfo">
                        <img src="<?php echo theme_url(); ?>/img/default-user.png" alt="">
                        <div class="inline cashier-infor">
                            <h3><?php echo cookie('home_nickname');?> </h3>
                            <b><?php echo cookie('home_num');?></b>
                        </div>
                        <div class="fr" id="times">
                            <span id="timess"><?php echo  date('H:i:s' ,time()); ?></span>
                            <em><?php echo date('Y/m/d',time()); ?></em>
                        </div>

                    </div>
                    <div class="right-center">
                        <div class="right-nav" >
                            <ul class="clearfix fff">
                                <li class="borr0" id="gdShow" >
                                    <a href="javascript:;" ><i class="iconfont icon-guadan-lists"></i><?php echo __('挂单列表')?> <b class="iconfont  red"></b> </a>
                                </li>
                                <li class="borl0" id="control"><a href="javascript:;" @click="searchUser"><i class="iconfont icon-user"></i><?php echo __('点击搜索会员')?> </a></li>
                            </ul>
                        </div>


                        <div class="w-menu-sel swiper-container swiper-container2">
                            <ul class="list swiper-wrapper" id="carts">
                                <li v-cloak v-for = "v in carts" class="swiper-slide">
                                    <div :class="{'animated  zoomIn': v.fadeIn , 'goods-cont':1 } " >
                                        <img :src="v.file" alt="" style="width: 80px;height: 100px;">
                                        <div class="goods-text">
                                            <h5 class="">{{v.common_name}}</h5>
                                            <div class="clearfix nums">
                                                <em class="fl"><?php echo __('购买数量')?></em>
                                                <p class="fr num-module">
                                                    <a href="javascript:;" class="num-btn left" @click="reduce(v)">
                                                        <span class="reduce">-</span>
                                                    </a>

                                                    <input  type="text"  step=1 v-model="v.number" @input="change(v)" >
                                                    <a href="javascript:;" class="num-btn right" @click="add(v)">
                                                        <span class="add">+</span>
                                                    </a>
                                                </p>
                                            </div>

                                            <div v-if="isDiBao == 1 &&  v.common_dibao_price > 0 " class="price">
                                                <em class="unit-pri fl">￥{{v.common_dibao_price}}</em>
                                                <em class="indiv-pri fr">￥{{v.common_dibao_price*v.number|fixed}}</em>
                                            </div>
                                            <div v-else class="price">
                                                <em class="unit-pri fl">￥{{v.common_price}}</em>
                                                <em class="indiv-pri fr">￥{{v.common_price*v.number|fixed}}</em>
                                            </div>

                                        </div>
                                    </div>
                                    <a href="javascript:;" class="btn-del-sel"><span class="w-table"><i class="iconfont icon-delete w-table-cell"></i></span></a>
                                </li>
                            </ul>
                            <div class="swiper-scrollbar swiper-scrollbar2"></div>
                        </div>
                        <div class="clearfix emlinate">
                            <a href="javascript:;" class="fl btn-emlinate" id="inputMember"><i class="iconfont icon-user"></i></a>
                            <a href="javascript:;" class="fr btn-emlinate"><i class="iconfont icon-eliminate"></i></a>
                        </div>
                        <!-- 未添加任何商品 -->
                        <p id="xianshi" :class="{active:active==0}" class="no-data " ><?php echo __('未添加任何商品')?></p><!-- 若显示则添加active -->
                    </div>
                    <div class="right-bottom" >
                        <a style="display: none;" href="javascript:;" id="less" @click="less(disc)"  class="discount-icon less">
                            <input id="discount" value="{{disc}}"  v-model="disc" readonly /><?php echo __('折');?>
                        </a>

                        <div class="clearfix menu-all"  id="all">
                            <div class="fl">
                                <span v-cloak><?php echo __('总数')?>：{{totalNum}}</span>
                            </div>
                            <div class="fr">
                                <span v-cloak><?php echo __('合计金额')?>：</span><em v-cloak>￥{{totalPrice|fixed}}</em>
                            </div>
                        </div>
                        <div class="clearfix">
                            <div class="fl member-tips">
                                <span id="memberTips"></span>
                            </div>
                            <div class="fr" >
                                <a href="javascript:;" class="btn-guadan btn-active" id="guadan"><?php echo __('挂单')?></a>

                            </div>
                        </div>
                        <a href="javascript:;" class="btn-settlement" id="count" @click="total()"><span><?php echo __('结算')?></span>
                            <i class="iconfont icon-bg-arrow-r"></i></a>
                        <input type="hidden" id="only_to_member" value="<?php echo config('config.only_to_member');?>">
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<!-- 弹框提示 -->

<div class="prompt tc" style="display: none;" id='clearCat'>
    <div class="prompt-cont">
        <span class="pro-img"></span>
        <p><?php echo __('确定删除列表中所有商品？')?></p>
        <div>
            <a href="javascript:;" class="prompt-btn sure" @click="sure"><?php echo __('确定')?></a>
            <a href="javascript:;" @click="close" class="prompt-btn cancel"><?php echo __('取消')?></a>
        </div>
        <em class="iconfont prompt-close icon-quxiao"  @click="close"></em>
    </div>
</div>

<div class="prompt tc" style="display: none;" id='confirm'>
    <div class="prompt-cont">
        <!-- <span class="pro-img"></span> -->
        <div>
            <input type="text" v-model="message" @keyup= "keyups(message)" class="" placeholder="<?php echo __('输入挂单备注信息')?>">
            <a href="javascript:;" class="prompt-btn sure" @click="sure"><?php echo __('确认挂单')?></a>
        </div>
        <em class="iconfont prompt-close icon-quxiao"  @click="close"></em>
    </div>
</div>

<div class="prompt-member tc" style="display: none;" id="member">
    <div class="prompt-cont">
        <div>
            <div class="bore5 sousuo"  :class="{hide:show != 'sousuo'}">
                <div class="auto">
                    <p class="right-order-status"><span><?php echo __("搜索会员"); ?></span></p>
                    <div class="t_search">
                        <div class="input-group">
                            <input type="text" name="userse" id="userse" class="form-control"  placeholder="<?php echo __("输入手机号、会员账号名称"); ?>">
                            <span class="input-group-btn">
    						<button class="btn btn-default"  type="button" id="serarchmember" @click="click()"><span class="iconfont icon-search white"></span></button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="swiper-container swiper-memlist ser-list " style="display: none;">
                    <div class="swiper-wrapper">
                        <div id="upuser1" class="member-infor tl swiper-slide" @click="click_url(v.id)" v-for = "v in users">
                            <a href="javascript:;" >
                                <dl class="member-infor-dl1">
                                    <dt><i class="iconfont icon-member-user"></i> </dt>
                                    <dd>{{v.ucenter_name}}</dd>
                                </dl>
                                <dl>
                                    <dt><i class="iconfont icon-member-phone"></i></dt>
                                    <dd>{{v.phone}}</dd>
                                </dl>
                            </a>
                        </div>
                    </div>
                    <div class="swiper-scrollbar swiper-scrollbar-memlist"></div>
                </div>
                <p style="display:none;"  class="nodata tc"><b></b><span><?php echo __("查无此会员") ?></span></p>
            </div>
        </div>
        <em class="iconfont prompt-close icon-quxiao"  @click="close" style="top:-2px;right: -1px;width: 23px;height: 23px;"></em>
    </div>
</div>


<?php $this->extend('footer'); ?>

<script>
    $(function() {
        time1();
    });

    function time1(){
        var today = new Date();
        var hour = today.getHours(),
            minute = today.getMinutes(),
            second = today.getSeconds();

        minute = checkTime(minute);
        second = checkTime(second);

        $('timess').text(hour+":"+minute+":"+second);
        t = setTimeout('time1()',1000);
    }

    function checkTime(i){
        if (i<10) {
            i="0" + i;
        }
        return i;
    }

    /*
     搜索会员
     */
    var memberVue = new Vue({
        el: '#member',
        data: {
            ulist:[],
            users:[],
            id:'',
            uinfos:[],
            items:[],
            jiesuan:0,
            ins:'保密',
            account_balance:'',
            show:'sousuo',
            able:1,
            sousuo: 0
        },
        methods: {
            click:function(){

                var this_ = this ;

                var wq = $('#userse').val();

                if(!wq){
                    Public.tips.error('请输入正确的会员信息进行查询');
                    return false;
                }

                $.get(base_url+"/home/user_search/index",{wq:wq},function(rs){

                    if(!rs.data ){

                        $('.nodata').css('display','block');

                        $('.ser-list').css('display','none');

                    }else{

                        arad = rs.data;

                        this_.users = arad.reverse();

                        $('.nodata').css('display','none');

                        $('.ser-list').css('display','block');

                    }
                    var Swipermem = new Swiper('.swiper-memlist', {
                        scrollbar: '.swiper-scrollbar-memlist',
                        direction: 'vertical',
                        slidesPerView: 'auto',
                        mousewheelControl: true,
                        freeMode: true,
                        initialSlide :0,
                        observer:true,//修改swiper自己或子元素时，自动初始化
                        observeParents:true//修改swiper的父元素时，自动初始化swiper
                    });

                },'json')

            },
            click_url:function(id){
                var this_ = this;

                $.get(base_url+"/home/user_search/plural",{id:id},function(rs){

                    if(rs.data){

                        this_.uinfos = rs.data[0];
                        this_.id = rs.data[0]['id'];
                        $("#memberTips").text('会员卡：' + this_.uinfos.phone);
                        cookie_set_member(rs.data[0]);
                    }

                },'json');

                $(this_.$el).hide();
            },
            close: function() {
                $('.prompt-member').hide();
            },
        }
    });
</script>
