<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Zhuyt
 */
class Buyer_CartCtl extends Controller
{
	/**
	 * Constructor
	 *
	 * @param  string $ctl 控制器目录
	 * @param  string $met 控制器方法
	 * @param  string $typ 返回数据类型
	 * @access public
	 */
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);

		$this->title       = '';
		$this->description = '';
		$this->keyword     = '';
		$this->web         = $this->webConfig();
		$this->bnav		 = 		$this->bnavIndex();

		$this->cartModel = new CartModel();
	}

	public function index()
	{
		include $this->view->getView();
	}

	/**
	 * 首页
	 *
	 * @author Zhuyt
	 */
	public function cart()
	{
		$data = $this->getCart();

		if ($this->typ == 'json')
		{
			$new_data = array();
			$sum = 0;
            $number=0;
			$count = $data['count'];
			unset($data['count']);
			$new_data['count'] = $count;

			$cart_list = array_values($data);
			$new_data['cart_list'] = $cart_list;

			if ( !empty($cart_list) )
			{
				foreach ($cart_list as $key => $val)
				{
					foreach ($val['goods'] as $k => $v)
					{
						$sum += $v['goods_num'] * $v['now_price'];
                        $number +=$v['goods_num'];
						$count_1[] = count($v['common_base']['goods_id']);
						if(2 === count($v['common_base']['goods_id']))
						{
							$new_data['cart_list'][$key]['goods'][$k]['common_base']['goods_id'] = array($new_data['cart_list'][$key]['goods'][$k]['common_base']['goods_id']);
						}
					}
				}
			}

			$new_data['sum'] = $sum;
            $new_data['number'] = $number;
			$this->data->addBody(-140, $new_data);
		}
		else
		{
            $cartids  = request_string('cartids');
			include $this->view->getView();
		}
	}

	/**
	 * 获取用户的收货地址
	 *
	 * @author Zhuyt
	 */
	public function resetAddress()
	{
		$user_id         = Perm::$row['user_id'];
		$user_address_id = request_int('id');

		//获取一级地址
		$district_parent_id = request_int('pid', 0);
		$baseDistrictModel  = new Base_DistrictModel();
		$district           = $baseDistrictModel->getDistrictTree($district_parent_id);
		//$user_address_id存在说明是修改地址，不存在则是新增地址
		$cond_row          = array();
		$User_AddressModel = new User_AddressModel();
		if ($user_address_id)
		{
			$cond_row          = array(
				'user_id' => $user_id,
				'user_address_id' => $user_address_id
			);
			$data              = $User_AddressModel->getOneByWhere($cond_row);
		}
		//判断当前用户是否设置过收货地址
		$user_address_list = $User_AddressModel->getOneByWhere(array('user_id' => $user_id));
		if($user_address_list)
		{
			$address_is_null = 0;
		}
		else
		{
			$address_is_null = 1;
		}
		
		/*fb($data);
		fb("用户地址");*/
		include $this->view->getView();
	}

	/**
	 * 获取用户的发票信息
	 *
	 * @author Zhuyt
	 */
	public function piao()
	{
		//获取一级地址
		$district_parent_id = request_int('pid', 0);
		$baseDistrictModel  = new Base_DistrictModel();
		$district           = $baseDistrictModel->getDistrictTree($district_parent_id);

		//获取用户的发票信息
		$user_id      = Perm::$row['user_id'];
		$InvoiceModel = new InvoiceModel();
		$data         = $InvoiceModel->getInvoiceByUser($user_id);

		if($this->typ == 'json')
		{
			if($data['normal'])
			{
				$da['normal'] = $data['normal'];
			}
			else
			{
				$da['normal'] = array();
			}

			if($data['electron'])
			{
				$da['electron'] = $data['electron'];
			}
			else
			{
				$da['electron'] = array();
			}

			if($data['addtax'])
			{
				$da['addtax'] = $data['addtax'];
			}
			else
			{
				$da['addtax'] = array();
			}
			$this->data->addBody(-140, $da);
		}
		else
		{
			include $this->view->getView();
		}


	}

	/**
	 * 确认订单信息后生成订单
	 *
	 * @author Zhuyt
	 */
	public function confirm()
	{
		$user_id = Perm::$row['user_id'];
		$address_id = request_int('address_id');
		$cart_type = request_string('cart_type');//积分商品购物车
        $cart_id = request_row('product_id');
		$Goods_BaseModel = new Goods_BaseModel();
		//获取用户的折扣信息
		$User_InfoMode = new User_InfoModel();
		$user_info     = $User_InfoMode->getOne($user_id);

        $User_GradeModel = new User_GradeModel();
		fb($user_info['user_grade']);
		$user_grade     = $User_GradeModel->getGradeRate($user_info['user_grade']);

		if (!$user_grade)
		{
			$user_rate = 100;  //不享受折扣时，折扣率为100%
		}
		else
		{
			$user_rate = $user_grade['user_grade_rate'];
		}


		if(!is_array($cart_id))
		{
			$product_id = request_string('product_id');
			$cart_id = explode(',',$product_id);
		}

		$order_row = array();

		$cond_row['cart_id:IN'] = $cart_id;
		$cond_row['user_id']    = $user_id;
		//购物车中的商品信息
		if($cart_type)
		{
			$Points_CartModel = new Points_CartModel();
			$cond_points_row['points_cart_id:IN'] = $cart_id;
			$cond_points_row['points_user_id']    = $user_id;
			$data['glist'] = $Points_CartModel->getOnePointsCartByWhere($cond_points_row, $order_row);
		}
		else
		{
			$data['glist'] = $this->cartModel->getCardList($cond_row, $order_row);
		}

		if(!$cart_type)
		{
			$data['user_rate'] = $user_rate;
		}
		//平台优惠券（优惠券）,与各个商家无关
		$RedPacket_BaseModel        = new RedPacket_BaseModel();
		if(!$cart_type)
		{
			$red_packet_base_support_all            = $RedPacket_BaseModel->getUserRedPacketByWhere(Perm::$userId, true);
            $red_packet_base                        = $RedPacket_BaseModel->getUserRedPacketByWhere(Perm::$userId, false);
		}
		
		if($red_packet_base_support_all)
		{
			$red_packet_desc 	= array_sort($red_packet_base_support_all, 'redpacket_price', 'desc');
			$data['rpt_list_support_all'] 	= array_values($red_packet_desc);
			$data['rpt_info_support_all']   = current($red_packet_desc);
		}
		else
		{
			$data['rpt_list_support_all']	= array();
            $data['rpt_info_support_all']   = array();
		}

        if($red_packet_base)
        {
            $red_packet_desc 	= array_sort($red_packet_base, 'redpacket_price', 'desc');
            $data['rpt_list'] 	= array_values($red_packet_desc);
            $data['rpt_info']   = current($red_packet_desc);
        }
        else
        {
            $data['rpt_list']	= array();
            $data['rpt_info']   = array();
        }

		//获取收货地址
		$User_AddressModel = new User_AddressModel();

		$cond_address    = array('user_id' => $user_id);
		$address         = array_values($User_AddressModel->getAddressList($cond_address, array('user_address_default' => 'DESC', 'user_address_id'=> 'DESC')));
		$data['address'] = $address;

        $shop_Ids=array_values($this->cartModel->getByWhere($cond_row));
        if(count($shop_Ids)>1){
            $data['isDispalyShipping']=false;
        }else{
            $data['isDispalyShipping']=true;
        }

		$city_id = 0;
        if($address_id){
            //如果传递了address_id,根据address_id获取运费信息
			$address_row = $User_AddressModel->getOne($address_id);
            $city_id  = $address_row['user_id'] != $user_id ? 0 : $address_row['user_address_city_id'];
        }else{
            //获取默认地址
            $address_row = $User_AddressModel->getDefaultAddress($user_id);
            $city_id = $address_row['user_address_city_id'] ? $address_row['user_address_city_id'] : 0;
        }

        $buy_able = 1;
        $checkArea = true;
		if($city_id  && !$cart_type){
            //判断商品的售卖区域
            $area_model = new Transport_AreaModel();
			foreach($data['glist'] as $key => $val){
                if(!is_array($val['goods'])){
                    continue;
                }
				foreach($val['goods'] as $gkey => $gval){
					$checkArea = $area_model->isSale($gval['transport_area_id'], $city_id);
                    $data['glist'][$key]['goods'][$gkey]['buy_able'] =  !$checkArea ? 0 : $buy_able;
				}
			}
            //获取商品运费
            $Transport_TemplateModel = new Transport_TemplateModel();
            $transport_cost = $Transport_TemplateModel->cartTransportCost($city_id, $cart_id);
		}

        $data['cost'] = !$transport_cost ? array() : $transport_cost;

		if(!$data['glist']['count'])
		{
			$this->view->setMet('error');
		}

		if ( $this->typ == 'json' )
		{
			foreach($data['glist'] as $key=>$val)
			{
				//只取出符合条件的代金券，过滤垃圾数据
				if (!empty($val['voucher_base']) && is_array($val['voucher_base'])) {
					$now_price_sum = array_sum(array_column($val['goods'], 'sumprice'));

					$voucher_rows = [];
					foreach ($val['voucher_base'] as $voucher_data) {
						if ($voucher_data['voucher_limit'] <= $now_price_sum) {
							$voucher_data['voucher_limit'] = intval($voucher_data['voucher_limit']);
							$voucher_rows[] = $voucher_data;
						}
					}

					$data['glist'][$key]['voucher_base'] = $voucher_rows;
				}

				//app端暂时用不到这三个字段，为了方便调试，暂时注释掉
				if(!$cart_type)
				{
					unset($data['glist'][$key]['increase_info']);
					unset($data['glist'][$key]['shop_voucher']);
					if(empty($data['glist'][$key]['mansong_info']))
					{
						if(is_array($data['glist'][$key]['mansong_info']))
						{
							$data['glist'][$key]['mansong_info']['rule_discount'] = 0;
							$data['glist'][$key]['mansong_info']['gift_goods_id'] = 0;
							$data['glist'][$key]['mansong_info']['shop_id'] = 0;
							$data['glist'][$key]['mansong_info']['common_id'] = 0;
							$data['glist'][$key]['mansong_info']['goods_name'] = '';
							$data['glist'][$key]['mansong_info']['goods_image'] = '';
						}
					}
					if(is_array($data['glist'][$key]['goods']))
					{
						foreach($data['glist'][$key]['goods'] as $gkey=>$gval)
						{
							unset($data['glist'][$key]['goods'][$gkey]['common_base']['goods_id']);
							if(isset($data['glist'][$key]['goods'][$gkey]['goods_base']['xianshi_info']))
							{
								unset($data['glist'][$key]['goods'][$gkey]['goods_base']['xianshi_info']);
								$data['glist'][$key]['goods'][$gkey]['goods_base']['promotion_type'] = '';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['title'] = '';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['remark'] = '';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['promotion_price'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['down_price'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['lower_limit'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['explain'] = '';
							}
							if(empty($data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']))
							{
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['groupbuy_id'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['groupbuy_name'] = '';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['groupbuy_starttime'] = '2017-04-01 11:00:00';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['groupbuy_endtime'] = '2017-04-03 10:00:00';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['goods_id'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['common_id'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['goods_name'] = '';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['shop_id'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['shop_name'] = '';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['goods_price'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['groupbuy_price'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['groupbuy_rebate'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['groupbuy_virtual_quantity'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['groupbuy_upper_limit'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['groupbuy_buyer_count'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['groupbuy_buy_quantity'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['groupbuy_intro'] = '';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['groupbuy_state'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['groupbuy_recommend'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['groupbuy_type'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['groupbuy_views'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['groupbuy_cat_id'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['groupbuy_scat_id'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['groupbuy_city_id'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['groupbuy_area_id'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['groupbuy_image'] = '';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['groupbuy_image_rec'] = '';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['groupbuy_remark'] = '';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['id'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['recommend_label'] = '';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['groupbuy_state_label'] = '已关闭';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['reduce'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['groupbuy_info']['rate'] = 0;
							}
							if(empty($data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']))
							{
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['increase_id'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['increase_name'] = '';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['combo_id'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['increase_start_time'] = '2017-04-01 10:00:00';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['increase_end_time'] = '2017-05-31 09:00:00';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['shop_id'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['shop_name'] = '';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['user_id'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['user_nickname'] = '';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['increase_state'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['id'] = 0;

								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['rule'][0]['rule_id'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['rule'][0]['increase_id'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['rule'][0]['rule_price'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['rule'][0]['rule_goods_limit'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['rule'][0]['id'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['rule'][0]['redemption_goods'][1]['redemp_goods_id'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['rule'][0]['redemption_goods'][1]['redemp_price'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['rule'][0]['redemption_goods'][1]['goods_name'] = '';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['rule'][0]['redemption_goods'][1]['goods_price'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['rule'][0]['redemption_goods'][1]['goods_image'] = '';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['rule'][0]['redemption_goods'][1]['goods_id'] = 0;

								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['goods_id'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['common_id'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['shop_id'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['shop_name'] = '';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['goods_name'] = '';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['goods_promotion_tips'] = '';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['cat_id'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['brand_id'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['goods_spec'] = '';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['goods_price'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['goods_market_price'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['goods_stock'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['goods_alarm'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['goods_code'] = '';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['goods_barcode'] = '';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['goods_is_recommend'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['goods_click'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['goods_salenum'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['goods_collect'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['goods_image'] = '';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['color_id'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['goods_evaluation_good_star'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['goods_evaluation_count'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['goods_max_sale'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['goods_is_shelves'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['goods_recommended_price'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['goods_recommended_min_price'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['goods_recommended_max_price'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['goods_parent_id'] = 0;
								$data['glist'][$key]['goods'][$gkey]['goods_base']['increase_info']['goods'][0]['id'] = 0;

							}
							if(empty($data['glist'][$key]['goods'][$gkey]['goods_base']['spec']))
							{
								$data['glist'][$key]['goods'][$gkey]['goods_base']['spec']['版本'] = '';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['spec']['容量'] = '';
								$data['glist'][$key]['goods'][$gkey]['goods_base']['spec'] = array_values($data['glist'][$key]['goods'][$gkey]['goods_base']['spec']);
							}

							if(2 === count($data['glist'][$key]['goods'][$gkey]['common_base']['goods_id']))
							{
								$data['glist'][$key]['goods'][$gkey]['common_base']['goods_id'] = array($data['glist'][$key]['goods'][$gkey]['common_base']['goods_id']);
							}
						}
					}
				}

			}
            if(empty($data['rpt_list_support_all']))
            {
                unset($data['rpt_list_support_all']);
            }
            if(empty($data['rpt_info_support_all']))
            {
                unset($data['rpt_info_support_all']);
            }
			if(empty($data['rpt_list']))
			{
				unset($data['rpt_list']);
			}
			if(empty($data['rpt_info']))
			{
				unset($data['rpt_info']);
			}
			if(empty($data['address']))
			{
                $data['address'] = array();
			}
			unset($data['glist']['count']);

			$data['glist'] = array_values($data['glist']);
			$data['cost'] = array_values($data['cost']);


			//在不是积分兑换的情况下获取加价购促销信息
			if(!$cart_type)
			{
				foreach($data['glist'] as $k=> $shop_goods) {
					$shop_id = $shop_goods['shop_id'];
					$goods_list = $shop_goods['goods'];
					$shop_goods_rows = [];
					foreach ($goods_list as $goods_data) {
						$shop_goods_rows[$goods_data['goods_id']] = [
							'now_price'=> $goods_data['now_price'],
							'goods_num'=> $goods_data['goods_num'],
						];
					}
					array_column($goods_list, 'now_price', 'goods_id');
					$data['glist'][$k]['jia_jia_gou'] = $this->getPromotionInfoByJiaJia($shop_id, $shop_goods_rows);
                    $data['glist'][$k]['man_song'] = $this->getPromotionInfoByManSong($shop_id, $shop_goods_rows);
				}
			}
			if($cart_type)
			{
				$data['glist'] = [];
			}

			//计算会员折扣
			$this->computeMemberRebate($data, $user_rate);
			//计算物流费用
            $this->computeFreight($data);
			//计算满即送、扣除满即送优惠
			$this->computeManSong($data);
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}
	}

	/**
	 * @author yuli
	 * @param $shop_id int
	 * @param $shop_goods_rows array ['goods_id'=> 'now_price']
	 *
	 * @return boolean or array
	 *
	 * 确认订单页面判断商品是否满足加价购
	 *
	 * 首先判断该店铺是否含有加价购活动
	 * 其次判断该商品是否参加加价购活动
	 * 最后判断参加活动商品价格累加是否符合加价购条件
	 *
	 * 注意：有可能出现多条加价购情况，当同一时间、同一商品只能参加一条活动
	 */
	public function getPromotionInfoByJiaJia($shop_id, $shop_goods_rows)
	{
		$increaseBaseModel = new Increase_BaseModel;

		$shop_goods_ids = array_keys($shop_goods_rows);

		//获取正常的加价购列表
		$increase_rows = $increaseBaseModel->getByWhere([
			'shop_id'=> $shop_id, //对应店铺
			'increase_state'=> Increase_BaseModel::NORMAL //活动状态正常
		]);

		if (empty($increase_rows)) {
			return []; //没有该促销信息
		}

		//筛选出加价购促销是否含有所需要的商品
		$increase_ids = array_keys($increase_rows);
		$increaseGoodsModel = new Increase_GoodsModel;

		$increase_goods_rows = $increaseGoodsModel->getByWhere([
			'increase_id:IN'=> $increase_ids,
			'goods_id:IN'=> $shop_goods_ids
		]);

		if (empty($increase_goods_rows)) {
			return []; //没有该商品促销信息
		}

		//筛选出符合条件的加价购
		$answer_increase_ids = array_column($increase_goods_rows, 'increase_id');

		//需要返回的数据
		//返回符合条件加价购数组，把其中不满足条件rule删除
		$jia_jia_rows = [];
		
		foreach ($answer_increase_ids as $answer_increase_id) {
			$jia_jia_data = $increaseBaseModel->getIncreaseActDetail($answer_increase_id);

			$jia_jia_goods_list = $jia_jia_data['goods'];


			$now_price_sum = 0; //当前活动商品累计价格
			foreach ($jia_jia_goods_list as $jia_jia_goods_data) {
				$goods_id = $jia_jia_goods_data['goods_id'];

				$now_price_sum += in_array($goods_id, $shop_goods_ids)
					? $shop_goods_rows[$goods_id]['now_price'] * $shop_goods_rows[$goods_id]['goods_num']
					: 0;
			}

			//如果不为零，代表参加该加价购活动
			if ($now_price_sum != 0) {
				$rules = $jia_jia_data['rule']; //加价购规则数组

				//过滤符合条件的规则
				$answer_rules = array_filter($rules, function($val) use ($now_price_sum) {
					return $now_price_sum >= $val['rule_price']
						? true
						: false;
				});

				//符合条件
				if ($answer_rules) {
					//格式化redemption_goods
					foreach ($answer_rules as $k=> $rule) {
						$answer_rules[$k]['redemption_goods'] = array_values($rule['redemption_goods']);
					}
					$jia_jia_data['rule'] = $answer_rules;
                    $jia_jia_data['goods'] = array_values($jia_jia_data['goods']);
					$jia_jia_rows[] = $jia_jia_data;
				}
			}
		}

		return $jia_jia_rows;
	}

    /**
     * @param $shop_id
     * @param $shop_goods_rows
     * @return array
     * 获取满送信息
     * 逻辑：
     *     1.同一店铺同一时间只会对应一个满送活动
     *     2.一个满送活动对应的多条规则
     */
	public function getPromotionInfoByManSong($shop_id, $shop_goods_rows)
    {
        $manSongBaseModel  = new ManSong_BaseModel;
        $man_song_data = $manSongBaseModel->getManSongActItem([
            'shop_id'=> $shop_id,
            'mansong_state'=> ManSong_BaseModel::NORMAL,
            'mansong_start_time:<='=> date('Y-m-d H:i:s')
        ]);

        if (! isset($man_song_data['rule'])) {
            return [];
        }

        $shop_order_amount = array_sum(array_map(function ($v) {
            return $v['now_price'] * $v['goods_num'];
        }, $shop_goods_rows));

        foreach($man_song_data['rule'] as $k=> $v) {
            $man_song_data['rule'][$k]['accord'] = $shop_order_amount >= $v['rule_price']
                ? 1
                : 0;
        }

        return $man_song_data;
	}
    
    
	//根据收货地址与商品id计算出物流运费
	public function getTransportCost()
	{
		$transportTemplateModel = new Transport_TemplateModel();

		$city = request_string('city');

		$cart_id = request_string('cart_id');

		$data = $transportTemplateModel->cartTransportCost($city, $cart_id);

		$this->data->addBody(-140, $data);
	}

	/**
	 * 确认订单信息后生成订单(服务商品)
	 *
	 * @author Zhuyt
	 */
	public function confirmVirtual()
	{
		$nums     = request_int("nums");
		$goods_id = request_int('goods_id');

		$user_id = Perm::$userId;
		//获取用户的折扣信息
		$User_InfoMidel = new User_InfoModel();
		$user_info      = $User_InfoMidel->getOne($user_id);

		$User_GradeModel = new User_GradeModel();
		$user_grade     = $User_GradeModel->getGradeRate($user_info['user_grade']);
		if (!$user_grade)
		{
			$user_rate = 100;  //不享受折扣时，折扣率为100%
		}
		else
		{
			$user_rate = $user_grade['user_grade_rate'];
		}

        //获取收货地址
        $User_AddressModel = new User_AddressModel();

        $cond_address    = array('user_id' => $user_id);
        $address         = array_values($User_AddressModel->getAddressList($cond_address, array('user_address_default' => 'DESC', 'user_address_id'=> 'DESC')));
        $goods_info['address'] = $address;
        $city_id = 0;
        $address_id = request_int('address_id');
        if($address_id){
            //如果传递了address_id,根据address_id获取运费信息
            $address_row = $User_AddressModel->getOne($address_id);
            $city_id  = $address_row['user_id'] != $user_id ? 0 : $address_row['user_address_city_id'];
        }else{
            //获取默认地址
            $address_row = $User_AddressModel->getDefaultAddress($user_id);
            $city_id = $address_row['user_address_city_id'] ? $address_row['user_address_city_id'] : 0;
            $address_id = $address_row['user_address_id'] ? $address_row['user_address_id'] : 0;
        }

		//获取服务商品的信息
		$data = $this->cartModel->getVirtualCart($goods_id, $nums);

		$RedPacket_BaseModel        = new RedPacket_BaseModel();
        $red_packet_base_support_all            = $RedPacket_BaseModel->getUserRedPacketByWhere(Perm::$userId, true);
		$red_packet_base                        = $RedPacket_BaseModel->getUserOrderRedPacketByWhere(Perm::$userId, false);

        if($red_packet_base_support_all)
        {
            $red_packet_desc 	= array_sort($red_packet_base_support_all, 'redpacket_price', 'desc');
            $data['rpt_list_support_all'] 	= array_values($red_packet_desc);
            $data['rpt_info_support_all']   = current($red_packet_desc);
        }
        else
        {
            $data['rpt_list_support_all']	= array();
            $data['rpt_info_support_all']   = array();
        }

		if($red_packet_base)
		{
			$red_packet_desc 	= array_sort($red_packet_base, 'redpacket_price', 'desc');
			$data['rpt_list'] 	= array_values($red_packet_desc);
			$data['rpt_info']   = current($red_packet_desc);
		}
		else
		{
			$data['rpt_list']	= array();
			$data['rpt_info']   = array();
		}

		fb($data);
		fb('服务确认订单');

		if($user_rate > 0 && (!Web_ConfigModel::value('rate_service_status') ||(Web_ConfigModel::value('rate_service_status') && $data['shop_base']['shop_self_support'] == 'true')))
		{

		}
		else
		{
			$user_rate = 100;
		}

		if ( $this->typ == 'json' )
		{
			$data['user_rate'] = $user_rate;
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}
	}

	/**
	 * 获取购物车列表
	 *
	 * @author Zhuyt
	 */
	public function getCart()
	{
		$user_id = Perm::$row['user_id'];
		$Goods_BaseModel = new Goods_BaseModel();
		$cord_row  = array();
		$order_row = array();

		$cond_row = array('user_id' => $user_id);
		$order_row['cart_id'] = 'DESC';
		$data     = $this->cartModel->getCardList($cond_row, $order_row);
		foreach($data as $key=>$value)
		{
			$goods_detail    = $Goods_BaseModel->getGoodsDetailInfoByGoodId($value['goods'][0]['goods_id']);
			if ( !empty($goods_detail['common_base']['common_spec_name']) )
			{
				//商品规格颜色图
				if ( !empty($goods_detail['common_base']['common_spec_value_color']) )
				{
					$data[$key]['goods'][0]['goods_base']['goods_image'] = $goods_detail['common_base']['common_spec_value_color'][$value['goods'][0]['goods_base']['color_id']];
				}
			}
		}
		if ($data)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}


		$this->data->addBody(-140, $data, $msg, $status);

		return $data;
	}

	/**
	 * 修改购物车数量
	 *
	 * @author Zhuyt
	 */
	public function editCartNum()
	{
		$cart_id = request_int('cart_id');
		$num     = (int)request_int('num');
		$user_id = Perm::$userId;
		if($num<1){
			exit('not human!!!');
		}
		$edit_row = array('goods_num' => $num);


		//获取商品信息
		$cart_base = $this->cartModel->getOne($cart_id);
		$goods_id = $cart_base['goods_id'];

		$Goods_BaseModel = new Goods_BaseModel();
		$goods_base = $Goods_BaseModel->getOne($goods_id);


		//查询该用户是否已购买过该商品
		$Order_GoodsModel = new Order_GoodsModel();
		$order_goods_cond['common_id']             = $goods_base['common_id'];
		$order_goods_cond['buyer_user_id']         = $user_id;
		$order_goods_cond['order_goods_status:!='] = Order_StateModel::ORDER_REFUND_FINISH;
		$order_list                                = $Order_GoodsModel->getByWhere($order_goods_cond);

		$order_goods_count         = count($order_list);

		//如果有限购数量就计算还剩多少可购买的商品数量
		if($goods_base['goods_max_sale'])
		{
			$limit_num = $goods_base['goods_max_sale'] - $order_goods_count;

			$limit_num = $limit_num < 0 ? 0:$limit_num;

			if($limit_num < $num)
			{
				$num = $limit_num;
			}
		}



		//判断加入购物车的数量和库存
		if($num <= $goods_base['goods_stock'])
		{
			$flag = $this->cartModel->editCartNum($cart_id, $edit_row);
		}
		else
		{
			$flag = false;
		}

		$data = array();
		if ($flag)
		{
			//获取此商品的总价
			$data['price'] = $this->cartModel->getCartGoodPrice($cart_id);

			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除购物车中的商品
	 *
	 * @author Zhuyt
	 */
	public function delCartByCid()
	{
        $cart_ids = $_REQUEST['id'];
        $cart_id = !is_array($cart_ids) ? explode(',', $cart_ids) : $cart_ids;
        $user_id = Perm::$userId;
        $cart_list = $this->cartModel->getCatGoodsList(array('user_id'=>$user_id));
        if(!isset($cart_list['items']) || !$cart_list['items']){
            return $this->data->addBody(-140, array(), __('您的购物车没有商品'), 250);
        }
        $cart_id_arr = array_column($cart_list['items'], 'cart_id');
        $check = true;
        foreach ($cart_id as $key => $value){
            if(!$value){
                unset($cart_id[$key]);
                continue;
            }
            if(!in_array($value, $cart_id_arr)){
                $check = false;
                break;
            }
        }
        if(!$check){
            return $this->data->addBody(-140, array(), __('数据有误'), 250);
        }
        $flag = $this->cartModel->removeCart($cart_id);
		if ($flag)
		{
            $status = 200;
			$msg    = __('success');
		} else {
			$status = 250;
			$msg    = __('failure');
		}
		$data = array($cart_id);
		return $this->data->addBody(-140, $data, $msg, $status);

	}

	/**
	 * 立即购买服务产品
	 *
	 * @author Zhuyt
	 */
	public function buyVirtual()
	{
		$user_id   = Perm::$row['user_id'];
		$goods_id  = request_int('goods_id');
		$goods_num = request_int('goods_num');

		//获取服务商品的信息
		$Goods_BaseModel = new Goods_BaseModel();
		$data            = $Goods_BaseModel->getGoodsInfo($goods_id);
//		echo '<pre>';print_r($data);exit;
		$data['goods_base']['old_price']  = 0;
        $data['goods_base']['now_price']  = $data['goods_base']['goods_price'];
        $data['goods_base']['down_price'] = 0;
		//计算商品价格
		if (isset($data['goods_base']['promotion_price']) && !empty($data['goods_base']['promotion_price']) && $data['goods_base']['promotion_price'] < $data['goods_base']['goods_price'])
		{
            if ($data['goods_base']['groupbuy_starttime'] < date('Y-m-d H:i:s') && $data['goods_base']['groupbuy_endtime'] > date('Y-m-d H:i:s'))
            {
                $data['goods_base']['old_price']  = $data['goods_base']['goods_price'];
                $data['goods_base']['now_price']  = $data['goods_base']['promotion_price'];
                $data['goods_base']['down_price'] = $data['goods_base']['down_price'];
            }
			
		}

		$data['goods_base']['cart_num'] = $goods_num;
		$data['goods_base']['sumprice'] = $goods_num * $data['goods_base']['now_price'];

		$Order_GoodsModel = new Order_GoodsModel();
		if ($user_id)
		{
			//团购商品是否已经开始
			//查询该用户是否已购买过该商品
			$order_goods_cond['common_id']             = $data['goods_base']['common_id'];
			$order_goods_cond['buyer_user_id']         = $user_id;
			$order_goods_cond['order_goods_status:!='] = Order_StateModel::ORDER_REFUND_FINISH;
			$order_list                                = $Order_GoodsModel->getByWhere($order_goods_cond);

			$order_goods_count         = count($order_list);
			$data['order_goods_count'] = $order_goods_count;

		}

		//计算商品购买数量
		//计算限购数量
		if (isset($data['goods_base']['upper_limit']))
		{
			if ($data['goods_base']['upper_limit'] && $data['common_base']['common_limit'])
			{
				if ($data['goods_base']['upper_limit'] >= $data['common_base']['common_limit'])
				{
					$data['buy_limit'] = $data['common_base']['common_limit'];
				}
				else
				{
					$data['buy_limit'] = $data['goods_base']['upper_limit'];
				}
			}
			elseif ($data['goods_base']['upper_limit'] && !$data['common_base']['common_limit'])
			{
				$data['buy_limit'] = $data['goods_base']['upper_limit'];
			}
			elseif (!$data['goods_base']['upper_limit'] && $data['common_base']['common_limit'])
			{
				$data['buy_limit'] = $data['common_base']['common_limit'];
			}
			else
			{
				$data['buy_limit'] = 0;
			}
		}
		else
		{
			$data['buy_limit'] = $data['common_base']['common_limit'];
		}

		//有限购数量且仍可以购买，计算还可购买的数量
		if ($data['buy_limit'])
		{
			$data['buy_residue'] = $data['buy_limit'] - $order_goods_count;
		}
		

		fb("服务购物车");

		if ('json' == $this->typ)
		{
			$this->data->addBody(-140, $data);
		}else
		{
//			echo '<pre>';print_r($data);exit;
			include $this->view->getView();
		}

	}

	/**
	 * 加入购物车
	 *
	 * @author Zhuyt
	 */
	public function add()
	{
		include $this->view->getView();
	}

	public function addCartRow()
	{
		$cart_list = request_string('cartlist');
		$user_id = request_int('u');

		$cart_list = explode('|',$cart_list);


		foreach($cart_list as $key => $val)
		{
			$val = explode(',',$val);
			if(count($val) > 1)
			{
				//将商品id与数量添加到购物车表中
				$this->cartModel->updateCart($user_id,$val[0],$val[1]);
			}
		}

		$this->data->addBody(-140, array());
	}

	public function addCart()
	{
		$user_id   = Perm::$row['user_id'];
        $goods_id  = request_int('goods_id');
        $goods_num = request_int('goods_num');

        if($goods_id < 1 || $goods_num < 1){
            return $this->data->setError("数据有误"); 
        }

        /********************************************************************/
        //判断商品是否满足限购条件，如果限时折扣设置最低购买数量大于商品本身限购数，按照限时折扣最低数量计算
        $msg = $this->goodIfMeetCondition($goods_id, $goods_num);
        if(!empty($msg)){
            return $this->data->setError($msg);
        }

        $flag = true;
        //添加商品到购物车
        $msg = $this->addGoodToCart($goods_id, $goods_num, $user_id, $flag);
        if(!empty($msg)){
            return $this->data->setError($msg);
        }

        if ($flag)
        {
            $status = 200;
            $msg    = $msg ? $msg : __('success');
        } else {
            $status = 250;
            $msg    = $msg ? $msg : __('failure');
        }

		$data = array(
			'flag' => $flag,
			'msg' => $msg,
			'cart_id' => $flag
		);
		return $this->data->addBody(-140, $data, $msg, $status);

	}

	private function goodIfMeetCondition($goods_id, $goods_num)
    {
        $msg = '';
        //判断商品是否满足限购条件，如果限时折扣设置最低购买数量大于商品本身限购数，按照限时折扣最低数量计算
        $cartModel = new CartModel;
        $Promotion = new Promotion();
        $goods_xianshi = $Promotion->getXianShiGoodsInfoByGoodsID($goods_id);
        if($goods_xianshi && $goods_xianshi['goods_lower_limit'] > $goods_num)
        {
            $msg = "添加失败，低于最低购买数量";
            return $msg;
        }
        else if(!$goods_xianshi)
        {
            $limit_flag = $cartModel->checkCartGoodsLimits($goods_id, $goods_num);

            if (!$limit_flag) {
                $msg = "添加失败，超出限购数量";
                return $msg;
            }
        }
        return $msg;
    }

    private function addGoodToCart($goods_id, $goods_num, $user_id, &$flag)
    {
        $msg = '';
        $Goods_BaseModel = new Goods_BaseModel();
        $Goods_CommonModel = new Goods_CommonModel();

        $goods_base      = $Goods_BaseModel->getOne($goods_id);
        $common_base = $Goods_CommonModel->getOne($goods_base['common_id']);

        //如果是供货商的商品
        $ShopBaseModel = new Shop_BaseModel();
        $goods_shop_base  = $ShopBaseModel ->getOne($common_base['shop_id']);

        if(Perm::$shopId && $goods_shop_base['shop_type'] == 2){
            //分销商申请是否通过
            $shopDistributorModel = new Distribution_ShopDistributorModel();
            $shopDistributorBase = $shopDistributorModel -> getOneByWhere(array('distributor_id' => Perm::$shopId,'shop_id' => $common_base['shop_id']));

            $allow_shop_cat = explode(',',$shopDistributorBase['distributor_cat_ids']);//分销商申请的店铺分类

            $common_shopcat_id = trim($common_base['shop_cat_id'],',');
            $common_shopcat_id = explode(',',$common_shopcat_id);

            if($shopDistributorBase['distributor_enable'] == 1 && (array_intersect($common_shopcat_id, $allow_shop_cat) || empty($common_base['shop_cat_id']))){

            }else{

                if(!$shopDistributorBase['distributor_enable'])
                {
                    $msg = "分销申请未通过！";
                    return $msg;
                }

                if(!array_intersect($common_shopcat_id, $allow_shop_cat) )
                {
                    $msg = "该分类未授权";
                    return $msg;
                }
            }
        }

        //判断购物车中是否存在该商品
        $cart_cond             = array();
        $cart_cond['user_id']  = $user_id;
        $cart_cond['shop_id']  = $goods_base['shop_id'];
        $cart_cond['goods_id'] = $goods_id;
        $cart_row              = $this->cartModel->getByWhere($cart_cond);

        if(is_array($cart_row) && $cart_row){
            $cart_row = array_shift($cart_row);
            //需求现改为购物车内的商品与立即购买的商品数不累加，所以如果购物车存在此商品就将购物车商品数量修改为现在购买的数量
            $edit_cond_rows['goods_num'] = $goods_num;
            $flag = $this->cartModel->editCart($cart_row['cart_id'], $edit_cond_rows, false);
            if($flag !== false){
                $flag = $cart_row['cart_id'];
            }
        }else{
            $add_row              = array();
            $add_row['user_id']   = $user_id;
            $add_row['shop_id']   = $goods_base['shop_id'];
            $add_row['goods_id']  = $goods_id;
            $add_row['goods_num'] = $goods_num;
            $flag = $this->cartModel->addCart($add_row, true);
        }
    }

	//获取购物车中的数量
	public function getCartGoodsNum()
	{
		$user_id = Perm::$row['user_id'];

		$cord_row  = array();
		$order_row = array();

		$cond_row = array('user_id' => $user_id);

		$CartModel = new CartModel();

		$count  = $CartModel->getCartGoodsNum($cond_row, $order_row);
		$data[] = $count;
		$data['cart_count'] = $count;

		$this->data->addBody(-140, $data);
	}

    /**
     * 确认订单信息后生成订单(门店自提)
     *
     * @author Zhuyt
     */
    public function confirmChain()
    {
		$Chain_GoodsModel = new Chain_GoodsModel();
		$CartModel = new CartModel();
        $goods_id = request_int('goods_id');
        $chain_id = request_int('chain_id');
        $user_id = Perm::$userId;

		//获取自提商品的信息
		$chain_goods_data = $Chain_GoodsModel->getOneByWhere(['chain_id'=>$chain_id, 'goods_id'=>$goods_id]);
		//获取购物车信息
		$cart_data = $CartModel->getOneByWhere(['user_id'=>$user_id, 'goods_id'=>$goods_id, 'shop_id'=>$chain_goods_data['shop_id']]);
		$nums     = $cart_data['goods_num'];
        //获取用户的折扣信息
        $User_InfoModel = new User_InfoModel();
        $user_info      = $User_InfoModel->getOne($user_id);

		$User_GradeModel = new User_GradeModel();
		$user_grade     = $User_GradeModel->getGradeRate($user_info['user_grade']);
		if (!$user_grade)
		{
			$user_rate = 100;  //不享受折扣时，折扣率为100%
		}
		else
		{
			$user_rate = $user_grade['user_grade_rate'];
		}

        //获取门店商品的信息
        $data = $this->cartModel->getVirtualCart($goods_id, $nums);

        //获取门店信息
        $Chain_BaseModel=new Chain_BaseModel();
        $chain_base=current($Chain_BaseModel->getByWhere(array('chain_id'=>$chain_id)));

        if ( $this->typ == 'json' )
        {
            $data['user_rate'] = $user_rate;
            $this->data->addBody(-140, $data);
        }
        else
        {
            include $this->view->getView();
        }
    }
    
    
	/**
	 * 不能加入购物车的商品确认订单
     * 1. 检验参数，商品，数量
     * 2. 获取商品信息
     * 3. 获取优惠信息
	 * 
	 */
	public function confirmGoods() {
		$user_id = Perm::$row['user_id'];
        $goods_id = request_int('goods_id');
		$address_id = request_int('address_id');
        $goods_num = request_int('goods_num');
        //获取商品信息
        $Goods_BaseModel   = new Goods_BaseModel();
        $goods_info = $Goods_BaseModel->getGoodsAndCommon($goods_id);
        $check_goods_info = $this->checkGoodsInfo($goods_info, $goods_num);
        if(!$check_goods_info['status']){
            if($this->typ == 'json'){
                return $this->data->addBody(-140, array(),__('商品已下架或库存不足'),250);
            }
            $error = $check_goods_info['msg'];
            $this->view->setMet('error');
            return include $this->view->getView();
        }
        $goods_info['base']['goods_num'] = $goods_num;
        
		//获取收货地址
		$User_AddressModel = new User_AddressModel();

		$cond_address    = array('user_id' => $user_id);
		$address         = array_values($User_AddressModel->getAddressList($cond_address, array('user_address_default' => 'DESC', 'user_address_id'=> 'DESC')));
		$goods_info['address'] = $address;

		$city_id = 0;
        if($address_id){
            //如果传递了address_id,根据address_id获取运费信息
			$address_row = $User_AddressModel->getOne($address_id);
            $city_id  = $address_row['user_id'] != $user_id ? 0 : $address_row['user_address_city_id'];
        }else{
            //获取默认地址
            $address_row = $User_AddressModel->getDefaultAddress($user_id);
            $city_id = $address_row['user_address_city_id'] ? $address_row['user_address_city_id'] : 0;
            $address_id = $address_row['user_address_id'] ? $address_row['user_address_id'] : 0;
        }
        
        $goods_info['common']['buy_able'] = 1;
        $checkArea = true;
		if($city_id){
            //判断商品的售卖区域
            $area_model = new Transport_AreaModel();
            $checkArea = $area_model->isSale($goods_info['common']['transport_area_id'], $city_id);
            $goods_info['common']['buy_able'] =  !$checkArea ? 0 : 1;
            //获取商品运费
            $Transport_TemplateModel = new Transport_TemplateModel();
            $weight = $goods_info['common']['common_cubage'] * $goods_num;
            $order = array('weight'=>$weight,'count'=>$goods_num,'price'=>$goods_info['base']['goods_price']);
            //如果是分销，使用供应商的运费
            if($goods_info['common']['product_is_behalf_delivery'] == 1 && $goods_info['common']['common_parent_id'] && $goods_info['common']['supply_shop_id']){
                $order['shop_id'] = $goods_info['common']['supply_shop_id'];
            }else{
                $order['shop_id'] = $goods_info['common']['shop_id'];
            }
			$shop_data = $Transport_TemplateModel->shopTransportCost($city_id, $order);
            $goods_info['transport'] = $shop_data;
        }else{
            $goods_info['common']['buy_able'] = 0;
            $goods_info['transport'] = array('cost' => 0,'con' => '');
        }
        
        //计算商品总价格
        $goods_info['base']['sumprice'] = number_format($goods_info['base']['goods_price'] * $goods_num , 2, '.', '');
		
        //获取商品的折扣价
        $price_rate = $Goods_BaseModel->getGoodsRatePrice($user_id,array('shop_id'=>$goods_info['common']['shop_id'],'goods_price'=>$goods_info['base']['goods_price'])); 
        $goods_info['base']['sumprice'] = $price_rate['now_price'] * $goods_num;
        $goods_info['base']['rate_price']  = $price_rate['rate_price'] * $goods_num;
        
        //获取店铺信息
        $shop_model = new Shop_BaseModel();
        $goods_info['shop'] = $shop_model->getOne($goods_info['common']['shop_id']);
        $goods_info['shop']['distributor_rate'] = $goods_info['base']['rate_price'];

        //该商品的交易佣金计算
        $Goods_CatModel = new Goods_CatModel();
        $goods_info['base']['commission'] = $Goods_CatModel->getCatCommission($goods_info['base']['sumprice']);
        //订单佣金和总价格
        $goods_info['shop']['commission'] = number_format($goods_info['base']['commission'] * 1, 2, '.', '');
        $goods_info['shop']['sprice']     = number_format($goods_info['base']['sumprice'] + $goods_info['transport']['cost'] * 1, 2, '.', '');
        $goods_info['token'] = md5(md5($user_id.$goods_id.$goods_num.$address_id).'#confirmGoods#');
        
        if($this->typ == 'json'){
            return $this->data->addBody(-140, $goods_info,__('success'),200);
        }else{
            return include $this->view->getView();
        }
	}
    
    /**
     * 判断商品数量
     * @param type $goods_info
     * @param type $goods_num
     * @return type
     */
    private function checkGoodsInfo($goods_info,$goods_num){
        if(!$goods_info){
            return array('status'=>false,'msg'=>__('商品已下架'));
        }
        if($goods_num <= 0 || ($goods_num > $goods_info['common']['common_limit'] && $goods_info['common']['common_limit'] > 0)){
            return array('status'=>false,'msg'=>__('购买数量有误'));
        }
        if($goods_num > $goods_info['common']['common_stock']){
            return array('status'=>false,'msg'=>__('商品库存不足'));
        }
        return array('status'=>true,'msg'=>'');
    }


	/**
	 * 计算会员折扣
	 * @param $data
	 * @param $user_rate
	 * @param $distribution_shop_id
	 * @return int
	 */
	private function computeMemberRebate(&$data, $user_rate)
	{
		$order_discounted_price = 0; //折扣后订单价格
		$order_cost_sum = 0; //订单折扣总额

		foreach($data['glist'] as $k=> $shop_data) {
			//判断后台是否开启了会员折扣，如果开启会员折扣则判断是否为自营店铺。计算店铺的折扣
			if (
				!Web_ConfigModel::value('rate_service_status')
				||
				(Web_ConfigModel::value('rate_service_status') && $shop_data['shop_self_support'] == 'true')
			) {
				$dian_rate = ($shop_data['sprice']-$shop_data['mansong_info']['rule_discount']) * (100 - $user_rate) / 100;
			} else {
				$dian_rate = 0;
			}

			//扣除折扣后店铺的店铺价格（本店合计）
            $shop_all_cost = $shop_data['sprice'] - $dian_rate;
			$data['glist'][$k]['shop_cost'] = number_format($dian_rate,2,".","");
			$data['glist'][$k]['shop_discounted_price'] = number_format($shop_all_cost,2,".","");

			$order_discounted_price += $shop_all_cost;
			$order_cost_sum += $dian_rate;
		}
		$data['order_discounted_price'] = number_format($order_discounted_price, 2,".","");
		$data['order_cost_sum'] = number_format($order_cost_sum,2,".","");
	}

    /**
     * @param $data
     * 加入运费计算
     * 在computeMemberRebate之后调用此方法
     */
    private function computeFreight(&$data)
    {
        foreach($data['glist'] as $k=> $shop_data) {
            $freight = $data['cost'][$k]['cost'];
            $data['glist'][$k]['freight'] = $freight;
            $data['glist'][$k]['shop_discounted_price'] += $freight;

            $data['order_discounted_price'] += $freight;
        }
    }

    /**
     * @param $data
     * 计算满即送优惠金额
     */
    public function computeManSong(&$data)
    {

        foreach($data['glist'] as $k=> $shop_data) {
            if (!empty($shop_data['mansong_info'])) {
                $data['glist'][$k]['shop_discounted_price'] -= $shop_data['mansong_info']['rule_discount'];
                $data['order_discounted_price'] -= $shop_data['mansong_info']['rule_discount'];
            }
        }
    }

    public function againOrderToAddCart()
    {
        $order_id   = request_string('order_id');

        //获取订单详情，判断订单的当前状态与下单这是否为当前用户
        $Order_GoodsModel = new Order_GoodsModel();
        $order_goods_list = $Order_GoodsModel->getGoodsListByOrderId($order_id);

        $flag = true;
        $this->cartModel->sql->startTransactionDb();
        $cart_ids = array();
        foreach($order_goods_list['items'] as $order_good)
        {
            $user_id   = $order_good['buyer_user_id'];
            $goods_id  = $order_good['goods_id'];
            $goods_num = $order_good['order_goods_num'];

            if($goods_id < 1 || $goods_num < 1){
                return $this->data->setError("数据有误");
            }

            $msg = $this->goodIfMeetCondition($goods_id, $goods_num);
            if(!empty($msg)){
                return $this->data->setError($msg);
            }

            $msg = $this->addGoodToCart($goods_id, $goods_num, $user_id, $flag);
            if(!empty($msg)){
                return $this->data->setError($msg);
            }

            if ($flag){
                array_push($cart_ids, $flag);
            }
        }

        if ($flag && $this->cartModel->sql->commitDb())
        {
            $status = 200;
            $msg    = __('success');
            $cart_ids = implode(',', $cart_ids);
        }
        else
        {
            $this->cartModel->sql->rollBackDb();
            $m      = $this->cartModel->msg->getMessages();
            $msg    = $m ? $m[0] : __('failure');
            $status = 250;
        }

        $data = array(
            'flag' => $flag,
            'msg' => $msg,
            'cart_ids' => $cart_ids
        );
        return $this->data->addBody(-140, $data, $msg, $status);
    }
}

?>