/**
 * @author     朱羽婷
 */
$(document).ready(function(){

	window.get = function (e)
	{
		$(e).parent().parent().parent().find(".sale_detail").show();
	}

	window.showVoucher = function(e)
	{
		$(e).parent().parent().parent().find(".voucher_detail").show();
	}

	$(".bk").click(function(){
		$(this).parent().parent().hide();
	})

	function getTransport()
	{
		var address = $(".add_choose").find('p').html();
		var cart_id =[];//定义一个数组
		$("input[name='cart_id']").each(function(){
			cart_id.push($(this).val());//将值添加到数组中
		});

		$.post(SITE_URL  + '?ctl=Seller_Transport&met=getTransportCost&typ=json',{address:address,cart_id:cart_id},function(data)
			{
				console.info(data);
				if(data && 200 == data.status) {
					$.each(data.data ,function(key,val){
						$(".trancon"+key).html(val.con);
						$(".trancost"+key).html(val.cost.toFixed(2));

						//计算店铺合计
						$(".sprice"+key).html(($(".price"+key).html()*1 + val.cost*1).toFixed(2));
					})

					//计算订单中金额
					var total = 0;
					$(".dian_total i").each(function(){
						total += $(this).html()*1;
					});
					$(".total").html(total.toFixed(2));
					//$(".rate_total").html((total*rate/100).toFixed(2));

				}
			}
		);

	}
	var c=$(".goods_num");
	var e=null;
	c.each(function(){
		var g=$(this).find("a");	  //添加减少按钮
		var h=$(this).find("input#nums");  //当前商品数量
		var o=this;
		var f=h.attr("data-max");  //最大值 - 库存
		var i=h.attr("data-min");
		var id=h.attr("data-id");  //购物车id
		if(h.val() <= i)
		{
			$(this).find('input#nums').prev().attr('class', 'no_reduce');
			$(this).find('input#nums').val(i);
		}
		h.bind("input propertychange",function(){
			var j=this;
			var k=$(j).val();
			e&&clearTimeout(e);
			e=setTimeout(function(){
				var l=Math.max(Math.min(f,k.replace(/\D/gi,"").replace(/(^0*)/,"")||1),i);
				$(j).val(l);
				edit_num(id,l,o);
				if(l==f){
					g.eq(1).attr("class","no_add");
					if(l==i)
						g.eq(0).attr("class","no_reduce")
					else
						g.eq(0).attr("class","reduce")
				}else{
					if(l<=i){
						g.eq(0).attr("class","no_reduce");
						g.eq(1).attr("class","add")
					}else{
						g.eq(0).attr("class","reduce");
						g.eq(1).attr("class","add")
					}
				}
			},50)
		}).trigger("input propertychange").blur(function(){$(this).trigger("input propertychange")}).keydown(function(l){
			if(l.keyCode==38||l.keyCode==40)
			{
				var j=0;
				l.keyCode==40&&(j=1);g.eq(j).trigger("click")
			}
		});
		g.bind("click",function(l){
			if(!$(this).hasClass("no_reduce")){
				var j=parseInt(h.val(),10)||1;
				if($(this).hasClass("add")&&!$(this).hasClass("no_add")){
					$(this).prev().prev().attr("class","reduce");
					if(f>i&&j>=f){
						$(this).attr("class","no_add")
					}
					else
					{
						j++;
						edit_num(id,j,o);
					}
				}else{
					if($(this).hasClass("reduce")&&!$(this).hasClass("no_reduce")){
						j--;
						edit_num(id,j,o);
						$(this).next().next().attr("class","add");
						j<=i&&$(this).attr("class","no_reduce")
					}
				}
				h.val(j)
			}
		})
	})

	function edit_num(id,num,obj){
		gprice = $("#goods_price").val();
		price = gprice * num;
		$('.cell' + id + ' span').html((Number(price).toFixed(2)));
		$(".subtotal_all").html(Number(price).toFixed(2));
	}


	//付款按钮
	$('.submit-btn').click(function(){
		$('#form').submit();
	});



	//加价购的商品
	var increase_goods_id = [];
	$(".increase_list").each(function(){
		if($(this).is('.checked'))
		{
			increase_goods_id.push($(this).find("#redemp_goods_id").val());
		}
	})

	//去付款按钮（生成订单）
	$("#pay_btn").click(function(){

		var has_physical = $('#has_physical').val();
		if(typeof(has_physical) != 'undefined' && has_physical == 1){
			if($('#goodsremarks').val() == ''){
				$('#goodsremarks').focus();
				Public.tips.error('请在备注中填写收货信息');
				return false;
			}
		}

		//2.获取商品留言
			remarks = $("#goodsremarks").val();
		//3.获取商品信息（商品id，商品备注）
			goods_id = $("#goods_id").val();
			goods_num = $("#goods_num").val();

        //获取收货地址
        var address_id   = $(".add_choose").find("#address_id").val();

        if(!address_id)
        {
            $('.add_address').click();
            Public.tips.error('请填写收货地址！');
            return false;
        }

        var  appointment_date =  $("#g_appointment_date").val();
        if(!appointment_date){
            $('.g_appointment_date').click();
            Public.tips.error('请选择上门时间！');

            return false;
		}


        //TO DO 获取预约服务时间

		//加价购的商品信息，包括商品id，商品数量，规则id，店铺id
		// var increase_goods_id = [];
		var increase_goods_num = [];
		var increase_shop_id = [];
		var increase_arr = [];
		// var increase_price = [];//店铺加价购商品总金额
		var increase_max_num = [];//店铺加价购商品可购买的最大数
		var increase_rule_id = [];//加价购规则id，一个店铺只能选择一个规则下面的多个商品
		var status = 0;
		$(".select_increase").each(function(){
			if($(this).is(':checked'))
			{
				increase_shop_id.push($(this).attr("shop_id"));
				var inc_shop_id = $(this).attr("shop_id");
				// var inc_shop_ids = [];
				var inc_rule_ids = [];
				$(".clearfix.status."+inc_shop_id).each(function () {
					inc_rule_ids.push($(this).find(".select_increase").attr('rule_id'));
				});
				//
				// inc_shop_ids = inc_shop_ids.join(',');
				inc_rule_ids = inc_rule_ids.join(',');
				// increase_shop_id.push(inc_shop_ids);
				increase_rule_id.push(inc_rule_ids);
				// increase_goods_id.push($(this).parents('tr:eq(0)').find(".increase_num").attr('goods_id'));
				// increase_goods_num.push($(this).parents('tr:eq(0)').find(".increase_num").val());
				// increase_max_num.push($(this).parents('tr:eq(0)').find(".increase_num").attr('data-max'));
				// increase_rule_id.push($(this).find(".select_increase").attr('rule_id'));

				// increase_price.push($(this).parents('tr:eq(0)').find(".w150.tc").attr('data_price'));
				var goods_num = $(this).parents('tr:eq(0)').find(".increase_num").val();
				if(! /^[1-9]\d*$/.test(goods_num))
				{
					status = 1;
				}
				console.info($(this).attr('rule_id'));
				var str = {
					increase_goods_id:$(this).parents('tr:eq(0)').find(".increase_num").attr('goods_id'),
					increase_rule_id:$(this).attr('rule_id'),
					increase_shop_id:$(this).attr("shop_id"),
					increase_goods_num:goods_num,
					increase_max_num:$(this).parents('tr:eq(0)').find(".increase_num").attr('data-max'),
					increase_price:$(this).parents('tr:eq(0)').find(".w150.tc").attr('data_price')
				};
				increase_arr.push(str);
			}
		})

		console.info(increase_shop_id);
		console.info(increase_rule_id);
		console.info(increase_arr);
		// return false;
		//加价购的商品
		// var increase_goods_id = [];
		// $(".increase_list").each(function(){
		// 	if($(this).is('.checked'))
		// 	{
		// 		increase_goods_id.push($(this).find("#redemp_goods_id").val());
		// 	}
		// })

		//判断同个店铺加价购商品是否是一个规则下的
		$.unique(increase_rule_id);
		var rule_id_arr = [];
		for(var k=0;k<increase_rule_id.length;k++)
		{
			//如果当前店铺选择了多个加价购商品，判断它们规则id是否相同
			if(increase_rule_id[k].indexOf(',') > 0)
			{
				rule_id_arr = increase_rule_id[k].split(',');
				$.unique(rule_id_arr);
				if(rule_id_arr.length > 1)
				{
					Public.tips.error('请选择同一种规则的加价购商品');
					return false;
				}
			}
			else
			{
				continue;
			}
		}

		//判断店铺加价购商品购买数量是否超过加价购规则限购数
		$.unique(increase_shop_id);//获得去重后的店铺id
		for(var i=0;i<increase_shop_id.length;i++)
		{
			var goods_num_total = 0;
			var max_num = 0;
			for(var j=0;j<increase_arr.length;j++)
			{
				if(increase_shop_id[i] === increase_arr[j].increase_shop_id)
				{
					max_num = parseInt(increase_arr[j].increase_max_num);
					goods_num_total += parseInt(increase_arr[j].increase_goods_num);
					console.info(increase_arr[j].increase_shop_id +'---------'+increase_arr[j].increase_goods_num);
				}
				else
				{
					continue;
				}
			}
			if(goods_num_total > max_num)
			{
				Public.tips.error('加价购商品数量不能大于店铺限购数！');
				return false;
			}
		}

		// return false;
		if(status === 1)
		{
			Public.tips.error('加价购商品数量有误！');
			return false;
		}
		
		//代金券信息
		var voucher_id = [];
		$(".select").each(function(){
			if($(this).find('option').is(":selected"))
			{
				voucher_id.push($(this).find("option:selected").attr('voucher_id'));
			}
		});

		//优惠券信息
		var rpt_info = '';
		var rpt   	= 0;
		if($('#rpt').length > 0)
		{
			rpt_info = $('#rpt').val().split('|');
		}
		if(rpt_info)
		{
			rpt = rpt_info[0];
		}


				$("#mask_box").show();
            
				$.ajax({
					url: SITE_URL  + '?ctl=Buyer_Order&met=addVirtualOrder&typ=json',
					data:{goods_id:goods_id,goods_num:goods_num,remarks:remarks,increase_arr:increase_arr,voucher_id:voucher_id,pay_way_id:1,rpt:rpt,address_id:address_id,appointment_date:appointment_date,from:'pc'},
					dataType: "json",
					contentType: "application/json;charset=utf-8",
					async:false,
					success:function(a){
						console.info(a);
            
						if(a.status == 200)
						{
							window.location.href = PAYCENTER_URL + "?ctl=Info&met=pay&uorder=" + a.data.uorder+'&order_g_type=virtual';
						}
						else
						{
							Public.tips.error('订单提交失败！');
							//alert('订单提交失败');
						}
            
					},
					failure:function(a)
					{
						Public.tips.error('操作失败！');
					}
				});

	});

	window.jiabuy = function(e)
	{
		limit = $(e).parents('.increase').find('#exc_goods_limit').val();
		shop_id = $(e).parents('.increase').find('#shop_id').val();

		if($(e).is('.checked'))
		{
			clanRpt();

			$(e).removeClass('checked');
			$(e).parents('.increase_list').removeClass('checked');

			good_price = $(e).parents('.increase_list').find(".redemp_price").val();
			good_price_rate = $(e).parents('.increase_list').find(".redemp_price_rate").val();
			good_price_arate = good_price - good_price_rate;

			//总会员折扣减价
			total_rate = Number(Number($('.rate_total').html()) - good_price_rate*1).toFixed(2);
			$('.rate_total').html(total_rate);

			//总价减价
			total_price = Number(Number($('.total').html())*1-good_price*1).toFixed(2);
			after_total = Number($('.after_total').html());

			$('.total').html(total_price);
			$(".after_total").html((after_total - good_price_arate*1).toFixed(2));


			//修改订单金额后需要修改平台红包
			iniRpt(after_total.toFixed(2));
			$('#orderRpt').html('-0.00');
		}
		else
		{
			//计算已经选择了加价购商品
			num = $(e).parents('.increase').children(".increase_list").find('.checked').length;

			if(limit <= 0 || (limit > 0 && num < limit))
			{
				clanRpt();

				$(e).addClass('checked');
				$(e).parents('.increase_list').addClass('checked');

				good_price = $(e).parents('.increase_list').find(".redemp_price").val();
				good_price_rate = $(e).parents('.increase_list').find(".redemp_price_rate").val();
				good_price_arate = good_price - good_price_rate;

				//总会员折扣加价
				total_rate = Number(Number($('.rate_total').html()) + good_price_rate*1).toFixed(2);
				$('.rate_total').html(total_rate);

				//总价加价
				total_price = Number(Number($('.total').html())*1+good_price*1).toFixed(2);
				after_total = Number($('.after_total').html());

				$('.total').html(total_price);
				$(".after_total").html((after_total + good_price_arate*1).toFixed(2));


				//修改订单金额后需要修改平台红包
				iniRpt(after_total.toFixed(2));
				$('#orderRpt').html('-0.00');
			}


		}

	}

	window.useVoucher = function (e)
	{
		shop_id = $(e).parent().find('#shop_id').val();

		//获取本代金券的价值
		voucher_price = $(e).parent().find("#voucher_price").val();

		if($(e).is('.checked'))
		{
			clanRpt();

			$(e).removeClass("checked");
			$(e).removeClass("bgred");
			$(e).parents('.voucher_list').removeClass('checked');

			//删除代金券信息
			$(".shop_voucher").html("");

			//总价加价
			total_price = Number(Number($('.total').html())*1+voucher_price*1).toFixed(2);
			after_total = Number($('.after_total').html());

			$('.total').html(total_price);
			$(".after_total").html((after_total + voucher_price*1).toFixed(2));

			//修改订单金额后需要修改平台红包
			iniRpt(after_total.toFixed(2));
			$('#orderRpt').html('-0.00');
		}else
		{
			clanRpt();

			$(e).parents(".voucher").find(".checked").removeClass("checked");
			$(e).parents(".voucher").find(".bgred").removeClass("bgred");
			$(e).addClass("checked");
			$(e).addClass("bgred");
			$(e).parents('.voucher_list').addClass('checked');

			//显示代金券信息
			$(".shop_voucher").html("使用" + voucher_price + "元代金券");

			//总价减价
			total_price = Number(Number($('.total').html())*1-voucher_price*1).toFixed(2);
			after_total = Number($('.after_total').html());

			$('.total').html(total_price);
			$(".after_total").html((after_total - voucher_price*1).toFixed(2));

			//修改订单金额后需要修改平台红包
			iniRpt(after_total.toFixed(2));
			$('#orderRpt').html('-0.00');
		}
	}

    //编辑收货地址
    $(".edit_address").click(function(event){
        var url = SITE_URL + "?ctl=Buyer_Cart&met=resetAddress&id="+$(this).attr('data_id');

        $.dialog({
            title: '修改地址',
            content: 'url: ' + url ,
            height: 340,
            width: 580,
            lock: true,
            drag: false

        });

        if(event && event.stopPropagation)
        {
            event.stopPropagation();
        }
        else
        {
            event.cancelBubble = true;
        }
    });

    window.editAddress = function(val)
    {
        area = val.user_address_area + ' ' + val.user_address_address;
        $("#addr"+val.user_address_id).find("h5").html(val.user_address_contact);
        $("#addr"+val.user_address_id).find("p").html(area);
        $("#addr"+val.user_address_id).find(".phone").find("span").html(val.user_address_phone);

    }

    window.addAddress = function(val)
    {
        //地址中的参数
        var params= window.location.search;

        params = changeURLPar(params,'address_id',val.user_address_id);

        window.location.href = SITE_URL + params;

        if(val.user_address_default == 1)
        {
            def = 'add_choose';

            $(".add_choose").removeClass("add_choose");
        }
        else
        {
            def = '';
        }
        str = '<li class=" ' + def + ' " id="addr'+ val.user_address_id + ' "><div class="editbox"><a onclick="edit_address( ' + val.user_address_id + ' )">编辑</a> <a onclick="del_address( ' + val.user_address_id + ' )">删除</a></div><h5> ' + val.user_address_contact +' </h5><p> ' + val.user_address_area + ' ' + val.user_address_address +' </p><div><span class="phone"><i class="iconfont">&#xe64c;</i><span> ' + val.user_address_phone +' </span></span></div></li>';

        $("#address_list").append(str);
    }
})
