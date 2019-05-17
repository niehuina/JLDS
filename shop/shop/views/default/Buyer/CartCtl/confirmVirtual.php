<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'site_nav.php';
?>

<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/shop-cart.css" />
<script type="text/javascript" src="<?=$this->view->js?>/virtual.js"></script>
<script type="text/javascript" src="<?=$this->view->js?>/alert.js"></script>
<script  type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.dialog.js"></script>
<link type="text/css" rel="stylesheet" href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css">
<link href="<?= $this->view->css ?>/tips.css" rel="stylesheet">
<script type="text/javascript" src="<?=$this->view->js?>/common.js"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.toastr.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.ui.js"></script>
    <link href="<?= $this->view->css_com ?>/jquery/plugins/datepicker/dateTimePicker.css?ver=<?= VER ?>" rel="stylesheet"
          type="text/css">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.datetimepicker.js"
            charset="utf-8"></script>
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.toastr.min.js" charset="utf-8"></script>





	<script>
        var shop_self_support = <?=$data['shop_base']['shop_self_support']?>;
        var rpt_list_support_all_json = <?=encode_json($data['rpt_list_support_all'])?>;
		var rpt_list_json = <?=encode_json($data['rpt_list'])?>;

		//平台红包
		function iniRpt(order_total) {
			var _tmp,_hide_flag = true;
			$('#rpt').empty();
			$('#rpt').append('<option value="|0.00">-<?=__('选择使用平台红包')?>-</option>');
			//添加全平台可用的红包
            for (var i = 0; i < rpt_list_support_all_json.length; i++)
            {
                _tmp = parseFloat(rpt_list_support_all_json[i]['redpacket_t_orderlimit']);
                order_total = parseFloat(order_total);
                if (order_total > 0 && order_total >= _tmp.toFixed(2))
                {
                    $('#rpt').append("<option value='" + rpt_list_json[i]['redpacket_id'] + '|' + rpt_list_json[i]['redpacket_price'] + "'>" + rpt_list_json[i]['redpacket_title'] + "</option>")
                    _hide_flag = false;
                }
            }
            //如果当前店铺是自营的，添加只用于自营店铺可用的红包
            if(shop_self_support == "true" && rpt_list_json.length > 0) {
                for (var i = 0; i < rpt_list_json.length; i++) {
                    _tmp = parseFloat(rpt_list_json[i]['redpacket_t_orderlimit']);
                    order_total = parseFloat(order_total);
                    if (order_total > 0 && order_total >= _tmp.toFixed(2)) {
                        $('#rpt').append("<option value='" + rpt_list_json[i]['redpacket_id'] + '|' + rpt_list_json[i]['redpacket_price'] + "'>" + rpt_list_json[i]['redpacket_title'] + "</option>")
                        _hide_flag = false;
                    }
                }
            }

			if (_hide_flag)
			{
				$('#rpt_panel').hide();
			}
			else {
				$('#rpt_panel').show();
			}
		}

		//清除平台红包选择
		function clanRpt()
		{
			var allTotal = parseFloat($('.after_total').html());
			var orderRpr = $('#orderRpt').html();
			if(orderRpr !== undefined)
			{
				orderRpr = Number($('#orderRpt').html());
				$('#orderRpt').html('-0.00');
				var paytotal = allTotal + orderRpr*(-1);
				$('.after_total').html(paytotal.toFixed(2));
			}

		}

		$(function(){
			var allTotal = parseFloat($('.after_total').html());

			$('#rpt').on('change',function(){
				var allTotal = parseFloat($('.after_total').html());
				var items = $(this).val().split('|');

				if (items[0] == '')
				{
					var orderRpr = Number($('#orderRpt').html());
					$('#orderRpt').html('-0.00');
					var paytotal = allTotal + Math.abs(orderRpr);
					$('.after_total').html(paytotal.toFixed(2));
				}
				else
				{
					var items = $(this).val().split('|');
					$('#orderRpt').html('-'+number_format(items[1],2));
					var paytotal = allTotal - parseFloat(items[1]);
					if (paytotal < 0) paytotal = 0;

					$('.after_total').html(paytotal.toFixed(2));
				}
			});

			if (rpt_list_json.length == 0)
			{
				$('#rpt_panel').remove();
			}

			if ($('#orderRpt').length > 0)
			{
				iniRpt(allTotal.toFixed(2));
				$('#orderRpt').html('-0.00');
			}

		});
	</script>
<div class="cart-head">
	<div class="wrap">
		<div class="head_cont clearfix">
			<div class="nav_left" style="float:none;">
				<a href="index.php" class=""><img src="<?=$this->web['web_logo']?>"/></a>
				<a class="download iconfont"></a>
			</div>
		</div>
	</div>
</div>
	<div class="wrap">
		<div class="shop_cart_head clearfix">
			<div class="cart_head_left">
				<h4><?=__('填写核对购物信息')?></h4>
				
			</div>
			<div class="cart-head-module clearfix">
				<p class="tips-p"><span><i class="iconfont icon-orders-tips"></i></span><?=__('请填写相应信息。')?></p>
				<ul class="cart_process">
					<li class="mycart">
						<div class="fl">
							<i class="iconfont icon-wodegouwuche bbc_color"></i>
								<h4><?=__('我的购物车')?></h4><h4>
							</h4>
						</div>
						
					</li>
					<li class="mycart process_selected1">
						<div class="fl to"></div>
						<div class="fl">
							<i class="iconfont icon-iconquerendingdan bbc_color"></i>
								<h4 class=""><?=__('确认订单')?></h4><h4>
							</h4>
						</div>
						
					</li>
					<li class="mycart">
						<div class="fl to"></div>
						<div class="fl">
							<i class="iconfont icon-icontijiaozhifu"></i>
								<h4><?=__('支付提交')?></h4><h4>
							</h4>
						</div>
						
					</li>
					<li class="mycart">
						<div class="fl to"></div>
						<div class="fl">
							<i class="iconfont icon-dingdanwancheng"></i>
								<h4><?=__('订单完成')?></h4><h4>
							</h4>
						</div>
					</li>
				</ul>

			</div>
			
		</div>


        <ul class="receipt_address clearfix">
            <div id="address_list">
                <?php if(isset($goods_info['address'])){$total = 0; $total_dian_rate = 0; foreach ($goods_info['address'] as $key => $value) {
                    ?>
                    <li class="<?php if(!$address_id && $value['user_address_default'] == 1){?>add_choose<?php }?><?php if($address_id && $value['user_address_id'] == $address_id){?>add_choose<?php }?> " id="addr<?=($value['user_address_id'])?>">
                        <input type ="hidden" id="address_id" value="<?=($value['user_address_id'])?>">
                        <input type="hidden" id="user_address_province_id" value="<?=($value['user_address_province_id'])?>">
                        <input type="hidden" id="user_address_city_id" value="<?=($value['user_address_city_id'])?>">
                        <input type="hidden" id="user_address_area_id" value="<?=($value['user_address_area_id'])?>">
                        <div class="editbox">
                            <a class="edit_address" data_id="<?=($value['user_address_id'])?>"><?=__('编辑')?></a>
                            <a class="del_address" data_id="<?=($value['user_address_id'])?>"><?=__('删除')?></a>
                        </div>
                        <h5><?=($value['user_address_contact'])?></h5>
                        <p class="addr-len"><?=($value['user_address_area'])?> <?=($value['user_address_address'])?></p><span class="phone"><?=($value['user_address_phone'])?></span>
                    </li>
                <?php }}?>
            </div>
            <div class="add_address">
                <a><?=__('+')?></a>
            </div>
        </ul>



		<div class="ncc-receipt-info">

			<h4 class="confirm"><?=__('支付方式')?></h4>
			<div class="pay_way pay-selected" pay_id="1">
				<i></i><?=__('在线支付')?>
			</div>

            <div class="">
                <h3 class="confirm"><?=__('预约上门时间')?></h3>
            </div>
            <div id="invoice_list" class="ncc-candidate-items">
                <ul style="overflow: visible;">
                    <li><?=__('上门时间：')?>
                        <div class="parentCls">
                            <input type="text" name="g_appointment_date" id="g_appointment_date" class="w80 text hasDatepicker" value="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" readonly="readonly"><em class="add-on"><i class="iconfont icon-rili"></i></em>
                        </div>
                    </li>
                </ul>
                <p class=""><i class="icon-info-sign"></i><?=__('请选择预约上门时间。')?></p>
            </div>



  </div>
		<h4 class="confirm contfirm_1"><?=__('服务服务类商品清单')?></h4>
		<div class="cart_goods">
			<ul class="cart_goods_head clearfix">
				<li class="price_all"><?=__('小计')?>(<?=(Web_ConfigModel::value('monetary_unit'))?>)</li>
				<li class="goods_num goods_num_1"><?=__('数量')?></li>
				<li class="goods_price goods_price_1"><?=__('单价')?>(<?=(Web_ConfigModel::value('monetary_unit'))?>)</li>
				<li class="goods_name goods_name_1"><?=__('商品')?></li>
			</ul>

			<!-- S 计算店铺的会员折扣和总价 -->
			<?php
			$reduced_money = 0;//满送活动优惠的金额单独赋予一个变量
			$voucher_money = 0;//代金券活动优惠的金额单独赋予一个变量
			//判断后台是否开启了会员折扣，如果开启会员折扣则判断是否为自营店铺。计算店铺的折扣
			if(!Web_ConfigModel::value('rate_service_status') ||(Web_ConfigModel::value('rate_service_status') && $data['shop_base']['shop_self_support'] == 'true'))
			{
				$dian_rate = $data['goods_base']['sumprice']*(100-$user_rate)/100;
			}
			else
			{
				$dian_rate = 0;
			}

			//扣除折扣后店铺的店铺价格（本店合计）
			$shop_all_cost = number_format($data['goods_base']['sumprice']-$dian_rate,2,'.','');

			?>
			<!-- E 计算店铺的会员折扣和总价 -->

			<ul class="cart_goods_list clearfix">
				<li>
					<div class="bus_imfor clearfix">
						<p class="bus_name">
							<span>
								<i class="iconfont icon-icoshop"></i>
								<a href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&id=<?=($data['goods_base']['shop_id'])?>" class="cus_ser" ><?=($data['shop_base']['shop_name'])?></a>
								<?php if($data['shop_base']['shop_self_support'] == 'true'){ ?>
									<span><?=__('自营店铺')?></span>
								<?php } ?>
							</span>


						</p>

					</div>
					<table>
						<tbody class="rel_good_infor">
							<tr>

								<td class="goods_img"><img src="<?=($data['goods_base']['goods_image'])?>"/></td>
								<td class="goods_name" style="width:536px;"><a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=($data['goods_base']['goods_id'])?>"><?=($data['goods_base']['goods_name'])?></a>
									<p>
											<input type="hidden" id="goods_id" value="<?=($data['goods_base']['goods_id'])?>">
											<input type="hidden" id="goods_num" value="<?=($nums)?>">
										<?php if(!empty($data['goods_base']['spec'])){foreach($data['goods_base']['spec'] as $sk => $sv){ ?>
											<?=($sv)?> &nbsp;&nbsp;
										<?php }}?>
									</p>
								</td>
								<td class="goods_price goods_price_1 ">
									<?php if($data['goods_base']['old_price'] > 0){?><p class="ori_price"><?=($data['goods_base']['old_price'])?></p><?php }?>
									<p class="now_price"><?=($data['goods_base']['now_price'])?></p>

								</td>

								<td class="goods_num goods_num_1">
									<span><?=($nums)?></span>
								</td>
								<td class="price_all">
									<span class="subtotal"><?=($data['goods_base']['sumprice'])?></span>
								</td>
							</tr>
						</tbody>
					</table>

				</li>
			</ul>
			<?php
			$cart_total_price = $data['goods_base']['now_price'] * $data['goods_base']['goods_num'];
			//							$cart_total_price = array_sum($cart_goods_price);
			?>

			<span class="shop_voucher"></span>

            <div class="goods_remark clearfix">
                <input type="hidden" id="has_physical" name="has_physical" value="<?=$data['has_physical']?>" />
                <?php if($data['has_physical'] == 1){ ?>
				<p class="remarks"><span><?=__('备注：')?></span><input placeholder="<?=__('请填写收货人姓名、地址和联系方式')?>" type="text" id="goodsremarks"></p>
                <?php }else{ ?>
                <p class="remarks"><span><?=__('备注：')?></span><input placeholder="<?=__('补充填写其他信息,如有快递不到也请留言备注')?>" type="text" id="goodsremarks"></p>
                <?php } ?>
			</div>

			<div class="tlr bgf">

			</div>

			<div class="frank clearfix">
				<p class="back_cart"><a></a></p>

				<p class="submit" style="text-align: center;">
					<span>
						<?=__('订单金额：')?>
						<strong>
							<?=(Web_ConfigModel::value('monetary_unit'))?><i class="total" total_price="<?=($data['goods_base']['sumprice'])?>"><?=($data['goods_base']['sumprice'])?></i>
						</strong>
					</span>

					<?php if($user_rate > 0 && (!Web_ConfigModel::value('rate_service_status') ||(Web_ConfigModel::value('rate_service_status') && $data['shop_base']['shop_self_support'] == 'true'))){?>
							<span>
							<?=__('会员折扣：')?>
								<strong>
									-<?=(Web_ConfigModel::value('monetary_unit'))?><i class="rate_total"><?=number_format((100-$user_rate)*$data['goods_base']['sumprice']/100,2,'.','')?></i>
								</strong>
						</span>
					<?php }else{$user_rate = 100;}?>

					<?php if($user_rate > 0){?>
						<span>
							<?php $after_total = number_format($data['goods_base']['sumprice'],2,'.','')*$user_rate/100;?>
							<?=__('支付金额：')?>
							<strong>
								<?=(Web_ConfigModel::value('monetary_unit'))?>
								<i class="after_total bbc_color" after_total="<?=(number_format($after_total,2,'.','') )?>"><?=(number_format($after_total,2,'.','') )?></i>
							</strong>
						</span>
					<?php }?>

					<a id="pay_btn" class="bbc_btns"><?=__('提交订单')?></a>
				</p>

			</div>
		</div>
	</div>

	<!-- 订单提交遮罩 -->
	<div id="mask_box" style="display:none;">
		<div class='loading-mask'></div>
		<div class="loading">
			<div class="loading-indicator">
				<img src="<?= $this->view->img ?>/large-loading.gif" width="32" height="32" style="margin-right:8px;vertical-align:top;"/>
				<br/><span class="loading-msg"><?=__('正在提交订单，请稍后...')?></span>
			</div>
		</div>
	</div>
<script>

	if(<?=($user_rate)?>)
	{
		rate = <?=($user_rate)?>;
	}
	else
	{
		rate = 100;
	}

    $('#g_appointment_date').datepicker({
        format:'Y-m-d',
        timepicker:false,
        minDate:0
    });


</script>

<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>