$(function(){
	/*
    搜索会员
    */
    var searchUserVue = new Vue({
    	el:"#searchUser",
    	data:{
             search_user:'',
    		 show: 0,
    		 users:{ },
             infos:{ },
             ulist:{ },
             zkname:' ',
    	} ,
    	methods: { 
            click: function(){

                var this_ = this;

                $('.zhekou').css('display','none');

                var wq = $('#code').val();

                if(!wq){

                     Public.tips.error('请输入正确的会员信息进行查询');

                    return false;
                }

                
                $.get(base_url+"/home/user_search/index",{wq:wq},function(rs){

                    if(!rs.data){
                        Public.tips.error( "搜索的会员不存在");
                    }

                    this_.users = rs.data;
                    var Swipersearch = new Swiper('.swiper-container4', {
                        scrollbar: '.swiper-scrollbar4',
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

            confirm: function(userId,ucname,user_minimum_living_status){


                this.zkname = ucname;

                $('.zhekou').css('display','block');

                var this_ = this;

                $.get(base_url+"/home/user_search/infos",{userId:userId},function(rs){

                    if(!rs.data){
                        Public.tips.error( "此会员暂无折扣");
                    }

                this_.infos = rs.data; 
                },'json')

                var user = get_cache('user');

                var cart = get_cache(user + '_carts');

                set_cache(user + '_carts', null);

                set_cache('user' , "user_"+userId);

                set_cache('user_' + userId +'_dibao', user_minimum_living_status);

                set_cache('user_' + userId +'_carts', cart);


            },

            zhekou:function(discount,status){

                if(status == 0){
                    
                    lessVue.disc = 0 ;

                    set_cache('discount_status',0);

                }else{
                    if(discount != 0){
                       set_cache('discount_status',1);

                        lessVue.disc = discount; 
                        $('.less').css('display','block')
                    }
                    

                    
                }   

                controlVue.show = 0;

                this.show = 0;

                $('#leftProduct').show();
                $('#searchUser').hide();
            },

            retu:function(){

                controlVue.show = 0;

                this.show = 0;

                
                $('#leftProduct').show();
                $('#searchUser').hide();

            }
        }

    });            
	/**
	搜索 挂单点击
	*/
	var controlVue = new Vue({
    	el:"#control",
    	data:{
    		 show:0
    	},
    	methods:{
            searchUser: function () {  

                 this.show = 0;

            	 searchUserVue.show = 2;

                 orderVue.show = 0;
                 $('#searchUser').show();
            	 $('#leftProduct').hide();
            } 
        } 
    });

    document.onkeydown = function(e){
        var ev = document.all ? window.event : e;
        if(ev.keyCode==13) {
            if(e.target.id=="userse"){
                $("#serarchmember").click();//处理事件
            }else {
                $("#btnserarch").click();//处理事件
            }


        }
    }

})
    
 

