$(function(){
		// 首页右侧商品滑动显示删除效果
			//侧滑显示删除按钮
			var expansion = null; //是否存在展开的list
			var container = document.querySelectorAll('.list li');
			for(var i = 0; i < container.length; i++){    
			    var x, y, X, Y, swipeX, swipeY;

			    container[i].addEventListener('touchstart', function(event) {
			        x = event.changedTouches[0].pageX;
			        y = event.changedTouches[0].pageY;
			        swipeX = true;
			        swipeY = true ;
			        if(expansion){   //判断是否展开，如果展开则收起
			            expansion.className = "";
			        }        
			    });
			    container[i].addEventListener('touchmove', function(event){
			        X = event.changedTouches[0].pageX;
			        Y = event.changedTouches[0].pageY;        
			        // 左右滑动
			        if(swipeX && Math.abs(X - x) - Math.abs(Y - y) > 0){
			            // 阻止事件冒泡
			            event.stopPropagation();
			            if(X - x > 10){   /*右滑*/
			                event.preventDefault();
			                this.className = "";    /*右滑收起*/
			            }
			            if(x - X > 10){   /*左滑*/
			                event.preventDefault();
			                this.className = "swipeleft";   /*左滑展开*/
			                expansion = this;
			            }
			            swipeY = false;
			        }
			        // 上下滑动
			        if(swipeY && Math.abs(X - x) - Math.abs(Y - y) < 0) {
			            swipeX = false;
			        }        
			    });
			}
		// 弹框提示关闭
			$(".prompt-close").click(function(){
				$(this).parents(".prompt").hide();
			})
		//首页头部分类超出滑动
		 	var width = 0;  
		  $('.w-nav li').each(function () {  
		    width += $(this).outerWidth(true);  
		  });
		  var W=$(".auto").width();
		  var w=width/2;
		  if(w>W){
		  	$(".w-nav").css("width",w+100);
		  }
		  var onoff=true;
		  $(".header .btn-nav").click(function(){
		  		if(onoff){
		  			$(".w-slide-nav").animate({left:"0px"});
		  			$(this).find("span").text("收起菜单");
		  			onoff=false;
		  		}else{
					$(".w-slide-nav").animate({left:"-230px"});
					$(this).find("span").text("功能菜单");
		  			onoff=true;
		  		}
		  	
		  })
})