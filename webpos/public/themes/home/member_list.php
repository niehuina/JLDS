
<div class="container-fluid hp100 bgf2" id="usersearch" >
    <div class="main-header">
        <div class="fl white pd-left f-14" style="cursor:pointer" @click="back()" ><i class="iconfont icon-back"></i><span class="middle"><?php echo __("返回"); ?></span> </div>
        <h2><?php echo __("会员"); ?></h2>
    </div>
    <div class="main-content hp100">
        <div class="main-left pd0 left_relative zuoce">
            <div class="left_box">
                <h3><?php echo __("会员信息列表"); ?></h3>
                <div class="swiper-container swiper-container3 list-hei">
                    <ul class="mem_list swiper-wrapper">
                        <li v-for="v in ulist" class="mem_row c_both clearfix swiper-slide hauto" @click="click_info(v.id)" :class="{active:v.id == id}">
                            <div class="fl"><img src="<?php echo theme_url(); ?>/img/viptx.png"></div>
                            <div class="tel fl ellipsis">{{v.ucenter_name}}</div>
                            <div class="tel fl">{{v.phone}}</div>
                            <div class="mem_bg"></div>
                        </li>
                    </ul>
                    <div class="swiper-scrollbar swiper-scrollbar3"></div>
                </div>

                <div class="left-bottom-btn"><a href="javascript:;" @click="click_new()"  class="btn-active"><?php echo __("新增会员"); ?></a></div>
            </div>
        </div>
        <div class="bore5 main-right sousuo"  :class="{hide:show != 'sousuo'}">
            <div class="auto">
                <p class="right-order-status"><span><?php echo __("搜索会员"); ?></span></p>
                <div class="t_search">
                    <div class="input-group">
                        <input type="text" name="userse" id="userse" class="form-control"  placeholder="<?php echo __("输入手机号、会员账号名称"); ?>">
                        <span class="input-group-btn">
    						<button class="btn btn-default"  type="button" @click="click()" id="btnserarch"><span class="iconfont icon-search white"></span></button>
                            </span>
                    </div>
                </div>
            </div>
            <div class="swiper-container swiper-memlist ser-list " style="display: none;">
                <div class="swiper-wrapper">
                    <div id="upuser" class="member-infor tl swiper-slide" @click="click_url(v.id)" v-for = "v in users">
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

        <!-- 会员详情页面 -->
        <div class="bore5 main-right infos" :class="{hide:show != 'infos'}">
            <div class="auto">
                <p class="right-order-status"><a href="javascript:;" @click="fanhuiss()" class="head-close"><?php echo __("返回"); ?></a><span><?php echo __("会员信息"); ?></span><a href="javascript:;" @click="chongzhi()" class="fr"><?php echo __("会员充值"); ?></a><a href="javascript:;" @click="bianji()" class="fr"><?php echo __("编辑"); ?></a></p>
                <div class="member-informations clearfix">
                    <div class="clearfix">
                        <div class="fl">
                            <img src="<?php echo theme_url(); ?>/img/viptx.png">
                            <div class="member-text inline">
                                <h5>{{items.ucenter_name}}</h5>
                                <p class="inline fff"><span>1</span><em><?php echo __("会员等级"); ?></em></p>
                            </div>
                        </div>
                    </div>
                    <div class="member-infor-det tc">
                        <dl>
                            <dt><?php echo __("积分"); ?></dt>
                            <dd>0<?php echo __("分") ?></dd>
                        </dl>
                        <dl>
                            <dt><?php echo __("会员折扣") ?></dt>
                            <dd>{{uinfos.discount}}<?php echo __("折") ?></dd>
                        </dl>
                        <dl>
                            <dt><?php echo __("代金券") ?></dt>
                            <dd>0<?php echo __("张") ?></dd>
                        </dl>
                        <dl>
                            <dt><?php echo __("余额") ?></dt>
                            <dd>{{items.account_balance}}</dd>
                        </dl>
                    </div>
                </div>
                <div class="member-edit-area">
                    <span class="triangle"></span>
                    <dl>
                        <dt><?php echo __("卡号：") ?></dt>
                        <dd>{{items.cardno}}</dd>
                    </dl>
                    <dl>
                        <dt><?php echo __("姓名：") ?></dt>
                        <dd>{{items.realname}}</dd>
                    </dl>
                    <dl>
                        <dt><?php echo __("手机：") ?></dt>
                        <dd>{{items.phone}}</dd>
                    </dl>
                    <dl>
                        <dt><?php echo __("生日：") ?></dt>
                        <dd><span>{{items.bron}}</span></dd>
                    </dl>
                    <dl>
                        <dt><?php echo __("性别：") ?></dt>
                        <dd><span>{{items.sex}}</span></dd>
                    </dl>
                    <dl>
                        <dt><?php echo __("注册时间：") ?></dt>
                        <dd>{{items.created}}</dd>
                    </dl>
                </div>
                <a class="member-card fff zhekouka" style="display: block;">
                    <div class="clearfix"><strong class="fr">{{uinfos.discount}}<?php echo __("折") ?></strong></div>
                    <p>NO:{{uinfos.numbers}}</p>
                    <time><?php echo __("有效日期：") ?> {{uinfos.started}} <?php echo __("至") ?> {{uinfos.ended}}</time>
                </a>

            </div>
        </div>

        <!-- 编辑会员页面 -->
        <div class="bore5 main-right update form-group" :class="{hide:show != 'update'}">
            <?php echo form::open('form1',['class'=>'ajax','action'=>$this->action('save')]); ?>
            <div class="auto"    >
                <p class="right-order-status"><a href="javascript:;" @click="fanhui()" class="head-close"><?php echo __("关闭") ?></a><span><?php echo __("编辑会员信息") ?></span> </p>
                <div class="member-informations clearfix">
                    <div class="clearfix">
                        <div class="fl">
                            <img src="<?php echo theme_url(); ?>/img/viptx.png">
                            <div class="member-text inline">
                                <h5>
                                    <input type="hidden" name="id" v-model="uinfos.id">
                                    <input type="hidden" name="ucenter_name" v-model="items.ucenter_name">
                                    <input type="hidden" name="phone" v-model="items.phone">
                                    <input type="text" readonly name="ucenter_name" onkeyup= "if(!/^[A-Za-z0-9_-]{0,20}$/.test(this.value)){this.value='';}" v-model="items.ucenter_name">
                                </h5>
                                <p class="inline fff"><span>1</span><em><?php echo __("会员等级") ?></em></p>
                            </div>
                        </div>
                    </div>

                    <div class="member-infor-det tc">
                        <dl>
                            <dt><?php echo __("会员编号") ?></dt>
                            <dd>{{items.id}}</dd>
                        </dl>
                        <dl>
                            <dt><?php echo __("会员积分") ?></dt>
                            <dd>0分</dd>
                        </dl>
                        <dl>
                            <dt><?php echo __("会员折扣") ?></dt>
                            <dd><input class="diib" type="text" name="discount" v-model="uinfos.discount" ><?php echo __("折") ?></dd>
                        </dl>
                        <dl>
                            <dt><?php echo __("余额") ?></dt>
                            <dd>{{items.account_balance}}</dd>
                        </dl>
                    </div>
                </div>
                <div class="member-edit-area">
                    <span class="triangle"></span>
                    <dl>
                        <dt><?php echo __("卡号：") ?></dt>
                        <dd><input type="text" name="cardno" v-model="items.cardno" placeholder="<?php echo __("读取卡号") ?>" class="tr"  > <i class="iconfont icon-edit" ></i></dd>
                    </dl>
                    <dl>
                        <dt><?php echo __("姓名：") ?></dt>
                        <dd><input type="text"  name="realname" v-model="items.realname" placeholder="<?php echo __("设置姓名") ?>" class="tr" > <i class="iconfont icon-edit" ></i></dd>
                    </dl>
                    <dl>
                        <dt><?php echo __("手机：") ?></dt>
                        <dd>{{items.phone}}</dd>
                    </dl>
                    <dl>
                        <dt><?php echo __("生日：") ?></dt>
                        <dd ><span><input class="datepicker tr mr4 brons" @click="bron()" type="text" name="bron" v-model="items.bron" ><i class="iconfont icon-edit" ></i></span></dd>
                    </dl>
                    <dl>
                        <dt><?php echo __("性别：") ?></dt>
                        <dd >
                            <span >
                                <input type="text" @click="sex()"  name="sex" readonly v-model="items.sex" class="tr">
                                <i class="iconfont icon-edit" ></i>
                            </span>
                        </dd>
                    </dl>
                    <dl>
                        <dt><?php echo __("注册时间：") ?></dt>
                        <dd>{{items.created}} </dd>
                    </dl>

                </div>
                <div class="tc btn-area"><button type="submit" class="btn-bottom style-btn" @click="update()" ><?php echo __("确认修改") ?></button></div>
            </div>
            </form>
        </div>
        <!-- 新增会员页面 -->
        <div class="bore5 main-right insert form-group" :class="{hide:show != 'insert'}">
            <?php echo form::open('form1',['class'=>'ajax','action'=>$this->action('save')]); ?>
            <div class="auto">
                <p class="right-order-status"><a href="javascript:;" @click="guanbi()" class="head-close"><?php echo __("关闭") ?></a><span><?php echo __("新增会员") ?></span> </p>
                <div class="member-informations clearfix">
                    <div class="clearfix">
                        <div class="fl">
                            <img src="<?php echo theme_url(); ?>/img/viptx.png">
                            <div class="member-text inline">
                                <h5>
                                    <input type="text" id="ucenter_name" name="ucenter_name" onkeyup= "if(!/^[A-Za-z0-9_-]{0,20}$/.test(this.value)){this.value='';}" placeholder="<?php echo __("设置会员名") ?>" style="color: black; font-weight: 500;background-color: #fff;">
                                    <input type="hidden" id="status" name="status" value=0>
                                </h5>
                                <p class="inline fff"><span>1</span><em><?php echo __("会员等级") ?></em></p>
                            </div>
                        </div>
                    </div>
                    <div class="member-infor-det tc">
                        <dl>
                            <dt><?php echo __("会员编号") ?></dt>
                            <dd></dd>
                        </dl>
                        <dl>
                            <dt><?php echo __("会员积分") ?></dt>
                            <dd>0分</dd>
                        </dl>
                        <dl>
                            <dt><?php echo __("会员折扣") ?></dt>
                            <dd><input type="text" id="discount" name="discount"  ><?php echo __("折") ?></dd>
                        </dl>
                        <dl>
                            <dt><?php echo __("余额") ?></dt>
                            <dd>0</dd>
                        </dl>
                    </div>
                </div>
                <div class="member-edit-area">
                    <span class="triangle"></span>
                    <dl>
                        <dt><?php echo __("卡号：") ?></dt>
                        <dd><input type="text" id="cardno" name="cardno" placeholder="<?php echo __("读取卡号") ?>" class="tr"> </dd>
                    </dl>
                    <dl>
                        <dt><?php echo __("姓名：") ?></dt>
                        <dd><input type="text" id="realname" name="realname" placeholder="<?php echo __("设置姓名") ?>" class="tr"> </dd>
                    </dl>
                    <dl>
                        <dt><?php echo __("手机号：") ?></dt>
                        <dd><input type="text" id="phone" name="phone" placeholder="<?php echo __("输入手机号码") ?>" @blur="checkPhone()" class="tr"> </dd>
                    </dl>
                    <!--                    <dl>-->
                    <!--                        <dt>--><?php //echo __("邮箱：") ?><!--</dt>-->
                    <!--                        <dd><input type="text" id="email" name="email" placeholder="--><?php //echo __("设置邮箱") ?><!--" class="tr"> </dd>-->
                    <!--                    </dl>-->
                    <dl>
                        <dt><?php echo __("生日：") ?></dt>
                        <dd >
                        <span>

                        <input class="datepicker tr" id="bron" type="text" placeholder="<?php echo __("点击设置生日") ?>" name="bron" class="tr" >
                        </span>
                        </dd>
                    </dl>
                    <dl>
                        <dt><?php echo __("性别：") ?></dt>
                        <dd>
                            <span >
                                <input id="sex" type="text" @click="sex()" placeholder="<?php echo __("点击设置性别") ?>" name="sex" readonly v-model="ins" class="tr">
                            </span>
                        </dd>
                    </dl>
                    <dl>
                        <dt><?php echo __("注册时间：") ?></dt>
                        <dd >
                        <span>

                        <input class="datepicker tr" id="created" type="text" placeholder="<?php echo __("点击设置注册时间") ?>" name="created" class="tr" >
                        </span>
                        </dd>
                    </dl>
                    <dl>
                        <dt><?php echo __("支付密码：") ?></dt>
                        <dd >
                        <span>
                        <input type="password" id="password" name="password" placeholder="<?php echo __("输入支付密码") ?>" @blur="checkPassword()" class="tr">
                        </span>
                        </dd>
                    </dl>
                    <dl>
                        <dt><?php echo __("确认支付密码：") ?></dt>
                        <dd >
                        <span>
                        <input type="password" id="spassword" name="spassword" placeholder="<?php echo __("输入支付密码") ?>" @blur="checksPassword()" class="tr">
                        </span>
                        </dd>
                    </dl>


                </div>
                <div class="tc btn-area"></div>
                <div class="tc btn-area">
                    <div class="tc btn-area">
                        <a href="javascript:;" @click="guanbi()" class="btn-bottom style"><?php echo __("取消") ?></a>
                        <button type="submit" class="btn-bottom style-btn"   ><?php echo __("保存") ?></button>
                    </div>

                </div>
            </div>
            </form>
        </div>
        <!-- 会员充值 -->
        <div class="bore5 main-right amount form-group" :class="{hide:show != 'amount'}" style="height: 95%">
            <?php echo form::open('form2',['class'=>'ajax','action'=>$this->action('saveRecord')]); ?>
            <div class="auto"    >
                <p class="right-order-status"><a href="javascript:;" @click="fanhui()" class="head-close"><?php echo __("关闭") ?></a><span><?php echo __("会员充值") ?></span> </p>
                <div class="member-informations clearfix">
                    <div class="clearfix">
                        <div class="fl">
                            <img src="<?php echo theme_url(); ?>/img/viptx.png">
                            <div class="member-text inline">
                                <h5>
                                    <input type="hidden" name="id" v-model="uinfos.id">
                                    <input type="hidden" name="ucenter_id" v-model="items.ucenter_id">
                                    <input type="hidden" name="ucenter_name" v-model="items.ucenter_name">
                                    <input type="hidden" name="phone" v-model="items.phone">
                                    <input type="hidden"  name="cardno" v-model="items.cardno">

                                    {{items.ucenter_name}}
                                </h5>
                                <p class="inline fff"><span>1</span><em><?php echo __("会员等级") ?></em></p>
                            </div>
                        </div>
                    </div>

                    <div class="member-infor-det tc">
                        <dl>
                            <dt><?php echo __("会员编号") ?></dt>
                            <dd>{{items.id}}</dd>
                        </dl>
                        <dl>
                            <dt><?php echo __("会员积分") ?></dt>
                            <dd>0分</dd>
                        </dl>
                        <dl>
                            <dt><?php echo __("会员折扣") ?></dt>
                            <dd><input class="diib" type="text" name="discount" v-model="uinfos.discount" ><?php echo __("折") ?></dd>
                        </dl>
                        <dl>
                            <dt><?php echo __("余额") ?></dt>
                            <dd>{{items.account_balance}}</dd>
                        </dl>
                    </div>
                </div>
                <div class="member-edit-area"  style="height: 95%">
                    <h3><i class="iconfont icon-money"></i><span>会员充值</span> </h3>
                    <div class="tc">
                        <input type="text" id="amount" name="amount" placeholder="输入充值金额" class="input-write">
                    </div>

                    <input type="hidden" id="pay_way" name="pay_way" value="wepay">
                    <input type="hidden"  id="order_id" name="order_id" value="">
                    <ul class="payment-method">
                        <li class="active">
                            <input type="hidden" value="cash">
                            <i class="iconfont icon-cosh"></i>
                            <h4>现金支付</h4>
                        </li>
                        <li>
                            <input type="hidden" value="alipay">
                            <i class="iconfont icon-zhifubao" ></i>
                            <h4 >支付宝支付</h4>
                        </li>
                        <li>
                            <input type="hidden" value="wepay">
                            <i class="iconfont icon-weixin"></i>
                            <h4>微信支付</h4>
                        </li>

                        <li>
                            <input type="hidden" value="unionpay">
                            <i class="iconfont icon-pos"></i>
                            <h4>银联POS刷卡</h4>
                        </li>
                    </ul>

                </div>
                <div class="bottom-content" style="top:450px">

                    <div class="xj-content clearfix"  style="display: none" id="cash">

                    </div>

                    <div class="zfb-content"  style="display: none" id="alipay">
                        <iframe  id="myFrame" style="border: none;"  width="300" height="300"  >

                        </iframe>

                    </div>
                    <div class="zfb-content" style="display: none" id="wx">
                    </div>

                    <span class="payments" id="alipaytext" style="display: none">
                        <?php echo __('请打开手机支付宝,扫一扫本二维码,完成支付!') ?>
                     </span>

                    <span class="payments" id="wxpaytext" style="display: none">
                        <?php echo __('请打开手机微信,扫一扫本二维码,完成支付!') ?>
                    </span>

                    <div class="pos-content" style="display: none" id="unionpay">
                        <div class="op-main pos-main-wh" ><i class="iconfont icon-print"></i><?php echo __('刷卡支付');?></div>
<!--                        <button class="op-btn lr-linear" type="button" >--><?php //echo __('完成结算');?><!--</button>-->
                    </div>


                </div>
                <div class="tc btn-area" id="btndiv" style="display: block"><button type="submit" class="btn-bottom style-btn" @click="amount()"><?php echo __("充值") ?></button></div>

            </div>
            </form>

        </div>


    </div>

    <div class="prompt tc sex" style="display:none;">
        <div class="prompt-cont">
            <span class="pro-img"></span>
            <p><?php echo __("选择性别") ?></p>
            <div>
                <a href="javascript:;"  @click="man(1)"  class="btn-bottom style-btn"><?php echo __('男');?></a>
                <a href="javascript:;"  @click="man(2)" style="display: block;margin-top: 10px; margin-left: 18px;"  class="btn-bottom style-btn"><?php echo __('女');?></a>
                <a href="javascript:;"  @click="man(3)" style="display: block;margin-top: 10px; margin-left: 18px;"  class="btn-bottom style-btn"><?php echo __('保密');?></a>
            </div>
        </div>
    </div>
    <!--    <div class="prompt tc pay">-->
    <!--        <div class="prompt-cont">-->
    <!--            <span class="got-img"></span>-->
    <!--            <p>完成支付，确认充值？</p>-->
    <!--            <div>-->
    <!--                <a href="javascript:;" class="prompt-btn sure">确定</a><a href="javascript:;" class="prompt-btn cancel">取消</a>-->
    <!--            </div>-->
    <!--            <em class="iconfont prompt-close icon-quxiao"></em>-->
    <!--        </div>-->
    <!--    </div>-->




</div>
<style type="text/css">
    .bottom-content{top:450px;margin-top: 0px}
    .zfb-content{margin-top:0px;text-align:center}
    .pos-content{margin-top:0px;width:100%;text-align:center}

</style>

<?php $this->extend('footer');?>
<script>

    $(function(){
        /*
         搜索
         */
        var usersearchVue = new Vue({
            el: '#usersearch',
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
                sousuo: 0,



            },

            created:function(){

                var this_ = this;

                $.get(base_url+"/home/user_search/index",function(rs){
                    this_.ulist = rs.data;
                    var Swiperlist = new Swiper('.swiper-container3', {
                        scrollbar: '.swiper-scrollbar3',
                        direction: 'vertical',
                        slidesPerView: 'auto',
                        slidesPerView:8,
                        mousewheelControl: true,
                        freeMode: true,
                        initialSlide :0,
                        observer:true,//修改swiper自己或子元素时，自动初始化
                        observeParents:true//修改swiper的父元素时，自动初始化swiper
                    });
                },'json')
            },
            methods: {
                checkPhone: function(){

                    if(!(/^1(3|4|5|7|8)\d{9}$/.test($('#phone').val())))
                    {
                        this.able = 0;
                        Public.tips.error('手机号有误');
                    }
                },
                checkPassword: function(){
                    var reg = new RegExp(/^\d{6}$/);
                    if(!(reg.test($('#password').val())))
                    {
                        this.able = 0;
                        Public.tips.error('密码请输入6位数字密码');
                    }
                },
                checksPassword: function(){
                    var reg = new RegExp(/^\d{6}$/);
                    if(!(reg.test($('#spassword').val())))
                    {
                        this.able = 0;
                        Public.tips.error('确认支付密码请输入6位数字密码');
                    }
                    else {
                        var password=$('#password').val();
                        var spassword=$('#spassword').val();
                        if(spassword!=password){
                            this.able = 0;
                            Public.tips.error('确认支付密码与密码不一致');
                        }
                    }
                },
                update:function(val){
                    var bbb = $('.brons').val();
                    this.items.bron = bbb;
                },
                amount:function(val){

                },
                bron:function(){
                    var bbb = $('.brons').val();
                },
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
                            slidesPerView:5,
                            mousewheelControl: true,
                            freeMode: true,
                            initialSlide :0,
                            observer:true,//修改swiper自己或子元素时，自动初始化
                            observeParents:true//修改swiper的父元素时，自动初始化swiper
                        });


                    },'json')

                },

                fanhuiss:function(){
                    $('.sousuo').css('display','block');
                    $('.infos').css('display','none');
                    $('.amount').css('display','none');
                },
                click_url:function(id){

                    $('.sousuo').css('display','none');

                    $('.infos').css('display','block');

                    var this_ = this;

                    $.get(base_url+"/home/user_search/infos",{id:id},function(rs){

                        if(rs.data){

                            this_.uinfos = rs.data[0];

                            this_.id = rs.data[0]['id'];
                        }


                    },'json');

                    $.get(base_url+"/home/user_search/plural",{id:id},function(rs){
                        this_.items = rs.data[0];
                    },'json')
                },
                /*编辑会员选择性别*/
                sex:function(){
                    $(".sex").css('display','block');

                },

                man:function(obj){

                    if(obj == 1){
                        this.items.sex = this.ins = "男";
                    }else if(obj == 2){
                        this.items.sex = this.ins = "女";
                    }else{
                        this.items.sex = this.ins = "保密";
                    }

                    $(".sex").css('display','none');
                },


                fanhui:function(){

                    this.show = 'infos';
                    $('.update').css('display','none');
                    $(".infos").css('display','block');
                },
                czfanhui:function(){
                    this.show = 'infos';
                },
                click_info:function(id){

                    this.show = 'infos';
                    $(".sousuo").css('display','none');
                    $(".infos").css('display','block');
                    var this_ = this;

                    $.get(base_url+"/home/user_search/infos",{id:id},function(rs){

                        if(rs.data){
                            this_.uinfos = rs.data[0];
                            this_.id = rs.data[0]['id'];
                        }

                    },'json');

                    $.get(base_url+"/home/user_search/plural",{id:id},function(rs){
                        this_.items = rs.data[0];

                    },'json')
                },

                click_new:function(){
                    this.ins = "";
                    this.show = 'insert';
                    //清空控件信息
                    $('#ucenter_name').val('');
                    $('#discount').val('');
                    $('#cardno').val('');
                    $('#realname').val('');
                    $('#phone').val('');
                    $('#bron').val('');
                    $('#created').val('');
                    $('#password').val('');
                    $('#spassword').val('');
                },


                guanbi:function(){

                    this.show = 'sousuo';
                },

                click_update:function(){

                    this.show = 'sousuo';
                },
                bianji:function(){
                    var diib = $('.diib').val();
                    if(diib != 0){
                        this.uinfos.discount = diib * 10;

                    }

                    this.show = 'update';
                    $(".infos").css('display','none');
                    $('.update').css('display','block');
                },
                chongzhi:function(){
                    this.show = 'amount';
                    $('.update').css('display','none');
                    $(".amount").css('display','block');
                    $.get(base_url+"/home/user_search/getOrder_id",function(rs){
                        if(rs.data){
                            var  order_id=rs.data['order_id'];
                            $("#order_id").val(order_id);
                        }

                    },'json');

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



        /*
         * 时间选择框
         */

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


        /*
         * 获取手机验证码
         */

        $('#get_code').click(function(){

            if(!usersearchVue.able){
                return false;
            }
            var phone = $("#phone").val();
            if(!phone){
                Public.tips.error('请输入手机号');
                return false;
            }
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

                    usersearchVue.able = 1;

                    countdown = 60;
                    return;
                } else {
                    $(obj).attr("disabled",true);
                    $(obj).html("(" + countdown + ") s 重新发送");
                    usersearchVue.able = 0;

                    countdown--;
                }
                setTimeout(function() {
                        settime(obj) }
                    ,1000)
            }
        }


        $(".ajax *").focus(function(){
            $(this).parent('div.form-group').removeClass('has-error');
        });

        $('.ajax').ajaxForm({

            dataType:  'json',

            success:   function(data) {
                usersearchVue.uinfos.discount = usersearchVue.uinfos.discount/10;
                if(data.status == 1 && data.msg ){
                    Public.tips.success(data.msg);

                    usersearchVue.show = 'sousuo';

                    $.get(base_url+"/home/user_search/index",function(rs){
                        usersearchVue.ulist = rs.data;
                    },'json');
                    document.getElementById('ucenter_name').value = '';
                    document.getElementById('discount').value = '';
                    document.getElementById('phone').value = '';
                    document.getElementById('email').value = '';
                    document.getElementById('code').value = '';
                    document.getElementById('bron').value = '';




                }else if(data.msg){
                    Public.tips.error(data.msg);
                }

                if(data.url){
                    setTimeout(function(){
                        window.location.href = data.url;
                    },1000);
                }
                if(data.render){
                    $("."+data.render).html(data.html);
                }
            }
        });
        $('.payment-method').on('click', 'li', function(e){
            var price=$("#amount").val();
            if(price=="")
            {
                Public.tips.error("请输入充值金额");
                return;
            }
            $('.payment-method li').removeClass('active');
            $(e.target).addClass('active');
            var selected_way=$(e.target).find('input').val();
            $("#pay_way").val($(e.target).find('input').val());


            var payment_way = '';
            if(selected_way == 'wepay'){
                payment_way = 'wx';
            }else if(selected_way == 'alipay'){
                payment_way = 'alipay';
            }else if(selected_way == 'unionpay'){
                payment_way = 'unionpay';
            }else{
                payment_way = 'cash';
            }

            var shop_name = "门店充值";
            debugger;

            var order_id=$("#order_id").val();
            if(payment_way=='wx')
            {
                $.ajax({
                    type: "POST",
                    url: base_url+"/home/welcome/show_code",
                    data: {order_id:order_id,amount:price,payment_way:payment_way,title:shop_name},
                    dataType: "json",
                    success: function(data) {
                        console.log(data);

                        if(payment_way=='wx') {
                            $("#wx").html(data);
                            $("#wx").css('display', 'block');
                            $("#wxpaytext").css('display', 'block');
                            $("#alipaytext").css('display', 'none');
                            $("#alipay").css('display', 'none');
                            $("#btndiv").css('display', 'none');
                            $("#unionpay").css('display', 'none');
                            $("#wx").find('img').addClass('imgw');
                            $('.imgw').css('width','300px');
                            $('.imgw').css('height','300px');
                        }
                    }
                });
            }
            else if(payment_way == 'alipay'){
                payment_way = 'alipay';
                $.get(base_url+"/home/user_search/getPara",{order_id:order_id,amount:price,payment_way:payment_way,title:shop_name},function(rs){

                    if(rs.data){
                        debugger;
                        console.log(rs.data);
                        $('#myFrame').attr('src',rs.data['url']);
                        $("#alipay").css('display', 'block');
                        $("#wx").css('display', 'none');
                        $("#btndiv").css('display', 'none');
                        $("#unionpay").css('display', 'none');
                        $("#wxpaytext").css('display', 'none');
                        $("#alipaytext").css('display', 'block');
                        $("#alipay").find('img').addClass('imgw');
                        $('.imgw').css('width','300px');
                        $('.imgw').css('height','300px');
                    }

                },'json');
            } else if(payment_way=='unionpay'){
                $("#alipay").css('display', 'none');
                $("#wx").css('display', 'none');
                $("#btndiv").css('display', 'block');
                $("#wxpaytext").css('display', 'none');
                $("#alipaytext").css('display', 'none');
                $("#unionpay").css('display', 'block');
            }else{
                $("#wxpaytext").css('display', 'none');
                $("#alipaytext").css('display', 'none');
                $("#btndiv").css('display', 'block');
                $("#alipay").css('display', 'none');
                $("#wx").css('display', 'none');
                $("#unionpay").css('display', 'none');
            }

        });
    })

</script>

