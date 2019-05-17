

$.cookie.json = true;

set_cache('user',  new Date().toLocaleString());

$('.icon-eliminate').click(function(){

    var cart = cookie_get_cart();
    if(cart == null){

       Public.tips.error('没有可删除的商品');
        return false;

    }
    $('#clearCat').show();
});

/*
文本框自动对焦
*/
function puhuo(){
    window.onload = function ()
    {
    var input = document.getElementById("message");
        input.focus();
    };
}


/**
 加载商品
 */
function load_product(this_ , method){
    var query = this_.query;
    if(method == 'push'){
        ++ this_.loadPage;
        this_.prev = 1;
    }
    if(method == 'set'){
        this_.loadPage = 1
    }

    if(method == 'prev'){
        --this_.loadPage;
        if(this_.loadPage == 1){
            this_.prev = 0;
        }
    }


    query.page = this_.loadPage;
    console.log('load products method:'+method+" current page:"+this_.loadPage);
    $.get(base_url+"/home/welcome/products",query,function(rs){
        if(!rs.data ||  rs.total == this_.loadPage ){
            this_.loading = 0;
            this_.total = rs.total
        }else{
            this_.loading = 1
        }

        this_.products =  rs.data;
        /*判断此分类下有没有商品*/
        if(this_.products == null){
            productsVue.active = 1;
        }else{
            productsVue.active = 0;
        }

        //扫码枪直接录入产品
        if(this_.products){

            var i = 0;
            $.each(this_.products, function(j){
                i++
            })
        }
        if(productsVue.is_search != 1){
            if(i==1){
                console.log('bind click');
                var current_obj = this_.products[0];
                this_.click_goods(current_obj);
                searchVue.message = '';
            }
            this_.is_search = 0
        }


    },'json')
}
/*
 商品列表
 */
var productsVue = new Vue({
    el:"#products",
    data:{
        loadPage:1,
        products:[],
        loading: 1,
        query:{},
        total:"",
        show: 1 ,
        active: 0,
        is_search:0, //是否来自扫码枪
        prev:0
    },
    created:function(){
        load_product(this,'set')
        puhuo()
    },
    methods:{
        click_goods: function (obj) {
            /*
             右侧购物车，该商品不存在时添加
             */
            productsVueSet( obj , 'add' )
        },
        loadMore:function(page){
            load_product(this,'push')
        },
        loadPrev: function(){

            load_product(this,'prev');
        }

    }
});
/*
 清空购物车
 */
var clearCatVue = new Vue({
    el:"#clearCat",
    methods:{
        sure:function(){
            var cart = cookie_get_cart();

            if(cart['length']==0){
                Public.tips.error('没有可删除的商品');
                return false;
            }
            clearCat();
            $('#clearCat').fadeOut();
            xianshiVue.active = 0;
        },
        close:function(){
            $('#clearCat').fadeOut()
        }
    }

});
/*
 收银员信息
 */
var shopinfoVue = new Vue({
    el:"#shopinfo",
    data:{
        shop_info:[]
    },
    created:function(){
        var this_ = this;
        $.get(base_url+"/home/welcome/shop_info",function(rs){
            this_.shop_info = rs.data[0];
        },'json')
    }

});

/*购物车 暂无商品字样的 显示隐藏*/
var xianshiVue = new Vue({
    el:"#xianshi",
    data:{
        active:0
    }
});
/*挂单 红色圆点显示隐藏*/
var gdShowVue = new Vue({
    el:"#gdShow",
    data:{

    },
    created:function(){
        var adc = get_cache('guadan');
        
        if(adc.length == 0){
            $('.red').removeClass('icon-dot');
        }else{
            $('.red').addClass('icon-dot');
        }
    }
});
/*
 分类点击，显示对应的商品
 */
var categoryVue = new Vue({
    el:"#category",
    data:{
        category:[] ,
        activeId: '' ,
        show: 1

    },
    /*
     初始化分类数据
     */
    created:function(){
        var this_ = this;
        $.get(base_url+"/home/welcome/index",function(rs){

            this_.category = rs.data;
        },'json')
    },
    methods: {
        /**
         * 点分类时
         */
        category_change: function (id) {
            $(".all_cat").removeClass('active');
            var this_ = this;
            this_.activeId = id;
            productsVue.query.cat_id = id;
            load_product(productsVue,'set')

        },
        all_category:function(){
            this.activeId = " ";
            $(".all_cat").addClass('active');
            var this_ = this;

            productsVue.loadPage = 1;
            productsVue.products = [];
            productsVue.loading = 1;
            productsVue.query = {};
            productsVue.total = "";
            productsVue.show = 1;
            productsVue.is_search = 0;
            load_product(productsVue,'set')
        }
    }
});

/*
 加入购物车
 */
var cartVue = new Vue({
    el:"#carts",
    data:{
        carts:[],
        totalPrice:0,
        isShow: true,
        isDiBao: 0
    },
    created:function(){
        var obj = cookie_get_cart();
        if(obj){
            this.carts = obj
        }
    },

    watch: {
        /*
         监听carts内容变化
         有变化写COOKIE
         */
        carts:function(obj){
            cookie_set_cart(obj)
        }
    },
    methods:{
        add:function(obj){
            console.log(obj)
            productsVueSet( obj , 'add' )
        },
        reduce:function(obj){
            productsVueSet( obj , 'reduce')
        },
        change: function(obj){


            if(obj.number>0){

                if(parseInt(obj.number)>=parseInt(obj.common_stock)){

                    obj.number = obj.common_stock;
                    Public.tips.error('购买数量不能超过库存');

                }

                productsVueSet(obj,obj.number)

            }


        }
    }
});
/*
 搜索
 */
var searchVue = new Vue({
    el:"#search",
    data:{
        message:''
    },
    methods: {
        keydown: function (ev) {
            if(!orderVue.show){
                productsVue.is_search = 0;
            }
        },
        del: function(){
            this.message='';
        }
    },
    watch: {
        /*
         监听serach内容变化
         */
        message:function(wq){
            if(!orderVue.show){
                productsVue.query.wq = wq;
                productsVue.is_search = 1;
                load_product(productsVue,'set')
            }else{
                gdSearch(this.message);
            }
        }
    }
});


function gdSearch(obj){

    var message = get_cache('message');
    var guadan = get_cache('guadan');

    orderVue.messageList = [];
    orderVue.orderList = [];

    var array = orderV(guadan);

    var g = [];
    var m = [];

    /*
    *  排除刚好过期的挂单
    */
    for(var j in array.priceList){
        if(array.priceList[j]){
            g.push(guadan[j]);
            m.push(message[j]);
        }
    }

    /*
    * 查询是否有挂单信息
    */
    if(obj){
        for(var i in m){
            if(m[i].indexOf(obj) != -1){
                orderVue.messageList .push(m[i]);
                orderVue.orderList.push(g[i]);
            }
        }
    }else{

        orderVue.orderList = g;
        orderVue.messageList = m;

    }

    var order = orderV(orderVue.orderList);
    orderVue.goodsList = order.goodsList;
    orderVue.priceList = order.priceList;
}

/*
 购物车总数
 */
var allVue = new Vue({
    el:"#all",
    data:{
        totalNum:0,
        totalPrice:0
    },
    created:function(){
        var obj = cartVue.carts;
        var isDiBao= cartVue.isDiBao;
        if(obj){
            this.totalNum = totalNum(obj);
            this.totalPrice = totalPrice(obj,isDiBao);

        }

    }

});
/*
 删除折扣
 */
var lessVue = new Vue({
    el:"#less",
    data:{
        disc:0
    },
    methods:{
        less:function(val){
            
            this.disc = 0;
            $('.less').css('display','none')
            
        }
    }
});


Vue.filter('fixed', function (value) {

    if(value){
        value = value.toFixed(2);
    }
    return value;

});

/*
 * 购物车总价
 */
function totalPrice(obj,dibauser) {

    var price = 0;
    for (var i=0;i<obj.length;i++){
        if(dibauser && obj[i].common_dibao_price > 0){
            price += parseFloat(obj[i].common_dibao_price*obj[i].number);
        }else {
            price += parseFloat(obj[i].common_price*obj[i].number);
        }

    }
    return price;

}

/**
 * 购物车总数量
 */

function totalNum(obj) {

    var num = 0;
    for (var i = 0; i < obj.length; i++) {
        num += parseInt(obj[i].number);
    }
    return num;

}



/*
 * 挂单列表
 */
var orderVue = new Vue({
    el:"#orders",
    data:{
        orderList: [],
        priceList: [],
        goodsList: [],
        messageList: [],
        show: 0
    },
    created: function() {


    },

    methods: {

        hide: function(){
            this.show = 0;

            $('#message').attr('placeholder','输入条形码、商品名称');
            $('#leftProduct').css('display','block');
        },
        cart: function(obj) {
            cartVue.carts = get_cache(obj+'_carts');

            set_cache('user', obj);

            allVue.totalNum = totalNum(cartVue.carts);
            allVue.totalPrice = totalPrice(cartVue.carts,cartVue.isDiBao);
            xianshiVue.active = 1;
        }
    }


});

/*
 * 挂单
 */

var gdVue = new Vue({
    el:"#confirm",
    data:{
        message : ''
    },
    methods:{
        keyups: function(){
            if(!/^.{0,20}$/.test(this.message)){
                Public.tips.error('挂单备注限制在20个字符以内');
                this.message='';

            }
        },
        close: function() {

            $('#confirm').hide();
        },
        sure: function(){
            var cart = cookie_get_cart();

            if(cart==''){
                Public.tips.error('请选择商品');
                return false;
            }else{
                $('.red').addClass('icon-dot');
            }
            if(this.message == ''){

               Public.tips.error('请输入挂单信息');
                return false;
            }

            var guadan = get_cache('guadan');

            var message = get_cache('message');

            var user = get_cache('user');

            var carts = get_cache(user+"_carts");
            var isDiBao = get_cache(user+"_dibao");

            var price = totalPrice(carts,isDiBao);

            allVue.totalNum = totalNum(carts);
            allVue.totalPrice = price;

            if(!guadan) guadan = [];
            if(!message)message = [];

            if(guadan.toString().indexOf(user) == -1) {

                if(!carts)carts = [];

                message.unshift(this.message);
                guadan.unshift(user);

                if(!orderVue.orderList){
                    orderVue.orderList = [];
                    orderVue.priceList = [];
                    orderVue.goodsList = [];
                }

                orderVue.orderList.unshift(user);
                orderVue.priceList.unshift(price);
                orderVue.goodsList.unshift(carts);
                orderVue.messageList = message;
            } else {

                for(var j in guadan) {
                    if(guadan[j] == user ) {

                        Vue.set(orderVue.messageList, j, this.message);
                        Vue.set(orderVue.goodsList, j, carts);
                        Vue.set(orderVue.priceList, j, price);


                    }

                }

            }


            set_cache('guadan', guadan);

            set_cache('message', orderVue.messageList);

            clearCat('gd');

            set_cache('user',  new Date().toLocaleString());

            $('#confirm').hide();
            xianshiVue.active = 0;
            this.message = '';
        }

    }

});

/*
 * 结算
 */
var countVue = new Vue({
    el:"#count",
    data:{
        num:0,
        price:0
    },
    methods: {
        total: function(){
            var carts = cookie_get_cart();
            if(!carts) {
                Public.tips.error('请选择商品');
                return false;
            }

            var member = cookie_get_member();
            var only_to_member = $("#only_to_member").val();
            if(only_to_member == "true" && !member){
                Public.tips.error('非会员不能结算，请添加会员信息！');
                return false;
            }

            var user = get_cache('user');
            var isDibao = get_cache(user + '_dibao');
            this.num = totalNum(carts);
            this.price = totalPrice(carts,isDibao);

            var that = this;

            var discount_status = get_cache('discount_status');

            var goods = [];

            for (var i in carts) {

                var good = [];
                good.push(carts[i].id);
                good.push(carts[i].number);
                if(isDibao && carts[i].common_dibao_price > 0){
                    good.push(carts[i].common_dibao_price);
                }
                good.push(carts[i].common_price);

                goods[i] = good;

            }
            var prices = that.price.toFixed(2);
            var userInfo = member?member.id:user;
            xianshiVue.active = 1;
            $.ajax({
                type: "POST",
                url: base_url+"/home/welcome/pay",
                data: {user:userInfo,discount_status:discount_status, goods:goods, price: prices, type:'common'},
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

/**
 * 导航栏
 */

var slideVue = new Vue({
    el: '#slide',
    data:{
        active:0
    },
    methods: {
        slide: function(obj){

            this.active = obj;
            var url = '';

            switch(obj)
            {
                case 1:
                    url = '/home/welcome/member';
                    break;
                case 2:
                    url = '/home/welcome/shift';
                    break;
                case 3:
                    url = '/home/welcome/equipment';
                    break;

                case 4:
                    url = '/home/welcome/return_products';
                    break;

                case 5:
                    url = '/home/welcome/ticket';
                    break;

                case 6:
                    url = '/home/welcome/logo';
                    break;

            }

            $.ajax({
                type: 'GET',
                url: base_url+ url,
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



/**
 * 清空购物车
 */

function clearCat(action){

    cartVue.carts = new Array;

    if(action != 'gd'){

        cookie_clear_member();
        cookie_clear_cart();

        var u = get_cache('user');
        var guadan = get_cache('guadan');
        var message = get_cache('message');

        if(!guadan){
            guadan = [];
            message = [];
        }

        var g = [];
        var m = [];

        for (var i in guadan){
            if(guadan[i] != u){
                g.push(guadan[i]);
                m.push(message[i]);
            }
        }

        set_cache('guadan', g);
        set_cache('message', m);

        var array = orderV(g);

        orderVue.orderList = g;
        orderVue.messageList = m;
        orderVue.goodsList = array.goodsList;
        orderVue.priceList = array.priceList;
        /*判断挂单是否还有数据 无数据则去掉红色标识*/
        if(orderVue.goodsList['length'] == 0){
            $('.red').removeClass('icon-dot');
        }
    }


    allVue.totalNum = 0;
    allVue.totalPrice = 0;
}


function orderV(obj){

    var goodsList = [];
    var priceList = [];

    if(!obj)obj = [];

    for (var i=0;i< obj.length;i++) {

        var tmp = get_cache(obj[i]+"_carts");
        if(!tmp) tmp=[];

        goodsList[i] = tmp;
        priceList[i] = 0;

        for(var j=0;j <tmp.length; j++) {
            priceList[i] +=  parseFloat(tmp[j].common_price*tmp[j].number)
        }

    }

    var array = [];

    array['goodsList'] = goodsList;
    array['priceList'] = priceList;

    return array;
}


/**
 * 点击商品，数组处理，并更新VUE视图
 * @param   {[type]}     array
 * @param   {[type]}     obj
 * @return  {[type]}         option add为自增加，reduce为自减少
 * @weichat sunkangchina
 * @date    2017-08-10
 */
function productsVueSet( obj , option){
    var array = cartVue.carts;
    var arr = [];

    if(array){
        xianshiVue.active = 1;
    }
    console.log('number:'+option);
    var u = get_cache('user');
    var isDiBao = get_cache(u +'_dibao');
    cartVue.isDiBao = isDiBao;

    for (var i = 0; i < array.length; i++) {
        var newValue =  array[i];
        if (newValue.id === obj.id) {
            switch(option)
            {
                case 'add':

                    if(!(newValue.number<obj.common_stock)){

                        Public.tips.error('购买数量不能大于库存');
                        return false;
                    }

                    newValue.number++;
                    allVue.totalNum++;
                    if(isDiBao && newValue.common_dibao_price > 0){
                        allVue.totalPrice += parseFloat(newValue.common_dibao_price);
                    }else{
                        allVue.totalPrice += parseFloat(newValue.common_price);
                    }
                    break;
                case 'reduce':
                    newValue.number--;
                    if(newValue.number<=0){

                        for(var j in array){
                            if(array[j].id != obj.id){
                                arr.push(array[j]);
                            }
                        }

                        cartVue.carts = arr;

                    }else{
                        Vue.set(array, i, newValue);
                    }

                    allVue.totalNum--;
                    if(isDiBao && newValue.common_dibao_price > 0){
                        allVue.totalPrice -= parseFloat(newValue.common_dibao_price);
                    }else{
                        allVue.totalPrice -= parseFloat(newValue.common_price);
                    }
                    break;
                default:
                    /**
                     * 大于0的时候单独处理option这时是个数字，增加的不是一个，而是这个值的数量
                     * @param   {[type]}     parseInt(option)>0
                     */

                    var addNum = parseInt(option);

                    var pre_arr = cookie_get_cart();
                    var pre_number = parseInt(pre_arr[i].number);

                    if(addNum>0){
                        newValue.number = addNum;
                        allVue.totalNum = parseInt(allVue.totalNum) - pre_number + addNum;
                        if(isDiBao && newValue.common_dibao_price > 0){
                            allVue.totalPrice += parseFloat((addNum - pre_number)*newValue.common_dibao_price);
                        }else{
                            allVue.totalPrice += parseFloat((addNum - pre_number)*newValue.common_price);
                        }


                    }

            }
            newValue.fadeIn = 0 ;
            /*
             因JS数组ES5限制，更新Vue视图请使用以下方法
             */

            if(option != 'reduce' ){
                Vue.set(array, i, newValue);
            }

            return false;
        }
    }

    obj.number = 1;
    allVue.totalNum++ ;
    if(isDiBao && obj.common_dibao_price > 0){
        allVue.totalPrice += parseFloat(obj.common_dibao_price);
    }else{
        allVue.totalPrice += parseFloat(obj.common_price);
    }


    array.push(obj);
    array.reverse();

}




/**
 * 设置购物车的COOKIE
 * @param   {[type]}     obj
 * @return  {[type]}
 * @weichat sunkangchina
 * @date    2017-08-10
 */
function set_cache(name, value){

    var curTime = new Date().getTime();

    if(name == 'guadan' || name == 'message' || name=='user'){

        localStorage.setItem(name, JSON.stringify( value ));
    }else{

        localStorage.setItem(name,JSON.stringify({data:value,time:curTime}));

    }


}

/**
 *
 * 取缓存
 */
function get_cache(name){
    var exp='10800000';
    //localStorage.clear();

    var array = [];

    if(localStorage.getItem(name)){

        array = JSON.parse(localStorage.getItem(name));
    }


    if(name == 'guadan' || name == 'message' || name=='user'){

        return array;

    }else{

        if (new Date().getTime() - array.time>exp) {

            var guadan = JSON.parse(localStorage.getItem('guadan'));
            var message = JSON.parse(localStorage.getItem('message'));

            if(guadan){

                var user = name.substr(0, name.indexOf('_carts'));

                var gua = [];
                var m =[];
                for(var i in guadan){
                    if(guadan[i] != user){
                        gua.push(guadan[i]);
                        m.push(message[i]);
                    }
                }

                set_cache('guadan', gua);
                set_cache('message', m);

            }

            localStorage.removeItem(name);
            array.data = [];
        }

        return array.data;

    }


}


function cookie_get_cart(){
    var u = get_cache('user');

    var array =  get_cache(u+'_carts');

    return array
}

function cookie_clear_cart(){

    var u = get_cache('user');

    set_cache(u+'_carts',null)
}


function cookie_set_cart(obj){
    var u = get_cache('user');

    set_cache(u+"_carts", obj)
}


/**
 * 挂单页列表展开收缩
 */

$(document).on('click', '.guadan-list .icon', function(){

    if($(this).attr("data-off")==0){

        $(".guadan-list .icon").attr("data-off",0).removeClass("active");
        $(".guadan-list .guadan-list-con").slideUp(0);
        $(this).parents("li").find(".guadan-list-con").slideDown(300);
        $(this).attr("data-off",1);
        $(this).addClass("active");
    }else{
        $(this).removeClass('active');
        $(this).attr("data-off",0);
        $(this).parents("li").find(".guadan-list-con").slideUp(300);
    }

});


/**
 *  挂单显示
 */
$(document).on('click', '#gdShow', function(){

    orderVue.show = 1;

    $('#searchUser').hide();

    $('#leftProduct').hide();

    gdSearch(searchVue.message);
    $("#message").attr('placeholder',"请输入挂单备注");
});


/**
 *  挂单
 */
$(document).on('click', '#guadan', function(){

    var cart = cookie_get_cart();
    if(cart == null) {
        Public.tips.error('请选择商品');
        return false;

    }

    $('#confirm').show();

});

/**
 *  会员卡
 */
$(document).on('click', '#inputMember', function(){
    $("#member").show();
});

function cookie_set_member(obj){
    var u = get_cache('user');
    set_cache(u+"_member", obj);
    set_cache(u+"_dibao", obj.user_minimum_living_status);
}

function cookie_get_member(){
    var u = get_cache('user');
    var array =  get_cache(u+"_member");
    return array;
}

function cookie_clear_member(){
    var u = get_cache('user');
    set_cache(u+"_member",null);
    $("#memberTips").text('');
}