<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}
 
/**
 * @author     windfnn
 */
class Seller_Trade_OrderCtl extends Seller_Controller
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
	}
 
	/**
	 * 实物交易订单
	 *
	 * @access public
	 */
	public function physical()
	{
        $condition       = array();
		$Order_BaseModel = new Order_BaseModel();
        $order_row = array('order_create_time'=>'desc');
        if(self::$is_partner){
            $condition['shop_id'] = Web_ConfigModel::value('self_shop_id');
        }
		$data            = $Order_BaseModel->getPhysicalList($condition,$order_row);
		$condition       = $data['condi'];

		include $this->view->getView();
	}

	/**
	 * 服务交易订单
	 *
	 * @access public
	 */
	public function virtual()
	{

		$Order_BaseModel = new Order_BaseModel();

        if(self::$is_partner){
            $condition['shop_id'] = Web_ConfigModel::value('self_shop_id');
        }else{
            $condition['shop_id']           = Perm::$shopId;
        }
		$condition['order_is_virtual']  = Order_BaseModel::ORDER_IS_VIRTUAL;
		$condition['order_shop_hidden'] = Order_BaseModel::ORDER_IS_REAL;
		$Order_BaseModel->createSearchCondi($condition);
        $order_row = array('order_create_time'=>'desc');
		$order_virtual_list = $Order_BaseModel->getOrderList($condition,$order_row);  //获取店铺订单列表


		include $this->view->getView();
	}

    /**
     * 门店自提订单
     *
     * @access public
     */
    public function chain()
    {
        if(self::$is_partner){
            $condition['shop_id'] = Web_ConfigModel::value('self_shop_id');
        }else{
            $condition['shop_id']           = Perm::$shopId;
        }
        $Order_BaseModel = new Order_BaseModel();
        $condition['chain_id:!=']       = 0;
        if(self::$is_partner){
            $condition['shop_id'] = Web_ConfigModel::value('self_shop_id');
        }
        $order_row = array('order_create_time'=>'desc');
        $data            = $Order_BaseModel->getPhysicalList($condition,$order_row);
        $condition       = $data['condi'];
		$condition['chain_name'] = request_string('chain_name');

		//获取门店信息
		if($data['totalsize'] > 0) {
			$chain_base = new Chain_BaseModel;
			$chain_ids = array_unique(array_column($data['items'], 'chain_id'));
			$chain_rows = $chain_base->getBase($chain_ids);
		}

        include $this->view->getView();
    }

	/**
	 * 服务交易订单--待付款订单
	 *
	 * @access public
	 */
	public function getVirtualNew()
	{
		$Order_BaseModel = new Order_BaseModel();
		$Order_BaseModel->createSearchCondi($condition);

		$condition['shop_id']           = Perm::$shopId;
		$condition['order_is_virtual']  = Order_BaseModel::ORDER_IS_VIRTUAL;
		$condition['order_shop_hidden'] = Order_BaseModel::ORDER_IS_REAL;
		$condition['order_status']      = Order_StateModel::ORDER_WAIT_PAY;
        $order_row = array('order_create_time'=>'desc');
		$order_virtual_list = $Order_BaseModel->getOrderList($condition,$order_row);  //获取店铺订单列表

		$this->view->setMet('virtual');
		include $this->view->getView();
	}

	/**
	 * 服务交易订单--已付款订单(已发货)
	 *
	 * @access public
	 */
	public function getVirtualPay()
	{
		$Order_BaseModel = new Order_BaseModel();
		$Order_BaseModel->createSearchCondi($condition);

		$condition['shop_id']           = Perm::$shopId;
		$condition['order_is_virtual']  = Order_BaseModel::ORDER_IS_VIRTUAL;
		$condition['order_shop_hidden'] = Order_BaseModel::ORDER_IS_REAL;
		$condition['order_status']      = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
        $order_row = array('order_create_time'=>'desc');
		$order_virtual_list = $Order_BaseModel->getOrderList($condition,$order_row);  //获取店铺订单列表

		$this->view->setMet('virtual');
		include $this->view->getView();
	}

	/**
	 * 服务交易订单--交易成功订单
	 *
	 * @access public
	 */
	public function getVirtualSuccess()
	{
		$Order_BaseModel = new Order_BaseModel();
		$Order_BaseModel->createSearchCondi($condition);

		$condition['shop_id']           = Perm::$shopId;
		$condition['order_is_virtual']  = Order_BaseModel::ORDER_IS_VIRTUAL;
		$condition['order_shop_hidden'] = Order_BaseModel::ORDER_IS_REAL;
		$condition['order_status']      = Order_StateModel::ORDER_FINISH;
        $order_row = array('order_create_time'=>'desc');
		$order_virtual_list = $Order_BaseModel->getOrderList($condition,$order_row);  //获取店铺订单列表

		$this->view->setMet('virtual');
		include $this->view->getView();
	}

	/**
	 * 服务交易订单--取消订单列表
	 *
	 * @access public
	 */
	public function getVirtualCancel()
	{
		$Order_BaseModel = new Order_BaseModel();
		$Order_BaseModel->createSearchCondi($condition);

		$condition['shop_id']           = Perm::$shopId;
		$condition['order_is_virtual']  = Order_BaseModel::ORDER_IS_VIRTUAL;
		$condition['order_shop_hidden'] = Order_BaseModel::ORDER_IS_REAL;
		$condition['order_status']      = Order_StateModel::ORDER_CANCEL;
        $order_row = array('order_create_time'=>'desc');
		$order_virtual_list = $Order_BaseModel->getOrderList($condition,$order_row);  //获取店铺订单列表

		$this->view->setMet('virtual');
		include $this->view->getView();
	}

	/**
	 * 取消订单
	 *
	 * @access public
	 */
	public function orderCancel()
	{
		$typ  = request_string('typ');
		$rs_row = array();

		if ($typ == 'e')
		{
			$cancel_row['cancel_identity'] = Order_CancelReasonModel::CANCEL_SELLER;

			//获取取消原因
			$Order_CancelReasonModel = new Order_CancelReasonModel;
			$reason                  = array_values($Order_CancelReasonModel->getByWhere($cancel_row));

			include $this->view->getView();
		}
		else
		{
			$Order_BaseModel = new Order_BaseModel();

			//开启事物
			$Order_BaseModel->sql->startTransactionDb();

			$order_id   = request_string('order_id');
			$state_info = request_string('state_info');

			//获取订单详情，判断订单的当前状态与下单这是否为当前用户
			$order_base = $Order_BaseModel->getOne($order_id);

			if( ($order_base['payment_id'] == PaymentChannlModel::PAY_CONFIRM
					&& $order_base['order_status'] == Order_StateModel::ORDER_WAIT_PREPARE_GOODS) //货到付款+等待发货
				|| $order_base['order_status'] == Order_StateModel::ORDER_WAIT_PAY
				&& $order_base['seller_user_id'] == Perm::$userId
			)
			{
				if (empty($state_info))
				{
					$state_info = request_string('state_info1');
				}
				//加入取消时间
				$condition['order_status']        = Order_StateModel::ORDER_CANCEL;
				$condition['order_cancel_reason'] = addslashes($state_info);


				$condition['order_cancel_identity'] = Order_BaseModel::IS_SELLER_CANCEL;

				$condition['order_cancel_date'] = get_date_time();

				$edit_flag = $Order_BaseModel->editBase($order_id, $condition);
				check_rs($edit_flag, $rs_row);

				//修改订单商品表中的订单状态
				$edit_row['order_goods_status'] = Order_StateModel::ORDER_CANCEL;
				$Order_GoodsModel               = new Order_GoodsModel();
				$order_goods_id                 = $Order_GoodsModel->getKeyByWhere(array('order_id' => $order_id));

				$edit_flag1 = $Order_GoodsModel->editGoods($order_goods_id, $edit_row);
				check_rs($edit_flag1, $rs_row);

				//退还订单商品的库存
				$Goods_BaseModel = new Goods_BaseModel();
				$Chain_GoodsModel = new Chain_GoodsModel();
				if($order_base['chain_id']!=0){
					$chain_row['chain_id:='] = $order_base['chain_id'];
					$chain_row['goods_id:='] = is_array($order_goods_id)?$order_goods_id[0]:$order_goods_id;
					$chain_row['shop_id:='] = $order_base['shop_id'];
					$chain_goods = current($Chain_GoodsModel->getByWhere($chain_row));
					$chain_goods_id = $chain_goods['chain_goods_id'];
					$goods_stock['goods_stock'] = $chain_goods['goods_stock'] + 1;
					$edit_flag2 = $Chain_GoodsModel->editGoods($chain_goods_id, $goods_stock);
					check_rs($edit_flag2, $rs_row);
				}else{
					//$edit_flag2 = $Goods_BaseModel->returnGoodsStock($order_goods_id);
					//check_rs($edit_flag2, $rs_row);
				}

				//将需要取消的订单号远程发送给Paycenter修改订单状态
				//远程修改paycenter中的订单状态
				$key      = Yf_Registry::get('shop_api_key');
				$url         = Yf_Registry::get('paycenter_api_url');
				$shop_app_id = Yf_Registry::get('shop_app_id');
				$formvars = array();

				$formvars['order_id']    = $order_id;
				$formvars['app_id']        = $shop_app_id;


				$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=cancelOrder&typ=json', $url), $formvars);
				
				//如果是供货商取消进货订单，同时取消买家的订单或减少买家订单的金额
				$dist_order  =  $Order_BaseModel->getOne($order_base['order_source_id']);
				if(!empty($dist_order)){
					$dist_goods_order   =  $Order_GoodsModel -> getByWhere(array('order_id' => $dist_order['order_id']));
					if(count($dist_goods_order) == 1){
						$Order_BaseModel-> editBase($dist_order['order_id'], $condition);
						$Order_GoodsModel-> editGoods($dist_goods_order[0]['order_goods_id'], $edit_row);
						//$Goods_BaseModel -> returnGoodsStock($dist_goods_order[0]['order_goods_id']);
					}else{
						foreach($dist_goods_order as $key => $value){
							if($value['order_goods_source_id'] == $order_id){
								$dist_edit_row = array();
								$dist_edit_row['order_goods_amount']       = $dist_order['order_goods_amount'] - $value['goods_price']*$value['order_goods_num'];
								$dist_edit_row['order_payment_amount']   = $dist_order['order_payment_amount'] - $value['order_goods_amount'];
								$Order_BaseModel-> editBase($dist_order['order_id'], $dist_edit_row);
								$Order_GoodsModel-> editGoods($dist_goods_order[$key]['order_goods_id'], $edit_row);
								//$Goods_BaseModel -> returnGoodsStock($dist_goods_order[$key]['order_goods_id']);
							}
						}
						$formvars['payment_amount'] = $dist_edit_row['order_payment_amount'];
					}
					$formvars['order_id']    = $dist_order['order_id'];
					$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=cancelOrder&typ=json', $url), $formvars);
				}
				
				
				
				if($rs['status'] == 200)
				{
					$edit_flag3 = true;
					check_rs($edit_flag3, $rs_row);
				}else
				{
					$edit_flag3 = false;
					check_rs($edit_flag3, $rs_row);
				}
			}

			$flag = is_ok($rs_row);

			if ($flag && $Order_BaseModel->sql->commitDb())
			{
				$status = 200;
				$msg    = __('success');
			}
			else
			{
				$Order_BaseModel->sql->rollBackDb();
				$m      = $Order_BaseModel->msg->getMessages();
				$msg    = $m ? $m[0] : __('failure');
				$status = 250;
			}

			$this->data->addBody(-140, array(), $msg, $status);
		}

	}

	/**
	 * 服务订单列表详情
	 *
	 * @access public
	 */
	public function virtualInfo()
	{
		$Goods_BaseModel       = new Goods_BaseModel();
		$Order_BaseModel       = new Order_BaseModel();
		$condition['order_id'] = request_string('order_id');

		$order_data = $Order_BaseModel->getOrderList($condition);
		$order_data = isset($order_data['items']) ? pos($order_data['items']) : array();
		$goods_list = isset($order_data['goods_list']) ? pos($order_data['goods_list']) : array();
        if($goods_list){
            //取出服务商品有效期 common_base => common_virtual_date
            $goods_id                          = $goods_list['goods_id'];
            $common_data                       = $Goods_BaseModel->getCommonInfo($goods_id);
            $order_data['common_virtual_date'] = isset($common_data['common_virtual_date']) ? $common_data['common_virtual_date'] : '';
        }

		$data = $Order_BaseModel->getOrderDetail($condition['order_id']);

		include $this->view->getView();
	}

	/**
	 * 兑换服务订单
	 *
	 * @access public
	 */
	public function virtualExchange()
	{
		$rs_row = array();
		$typ = request_string('typ');

		if ($typ == 'e')
		{
			include $this->view->getView();
		}
		else
		{
			$data            = array();
			$virtual_code_id = request_string('vr_code');

			if (empty($virtual_code_id))
			{
				return $this->data->addBody(-140, $data, __('请输入服务码'), 250);
			}

			$orderBaseModel             = new Order_BaseModel();
			$orderGoodsVirtualCodeModel = new Order_GoodsVirtualCodeModel();
			$virtual_base               = $orderGoodsVirtualCodeModel->getCode($virtual_code_id);
			
			if (empty($virtual_base))
			{
				$flag = false;
				check_rs($flag,$rs_row);
			}
			else
			{
				$virtual_base = pos($virtual_base);

				if ($virtual_base['virtual_code_status'] == Order_GoodsVirtualCodeModel::VIRTUAL_CODE_NEW)
				{
					$update['virtual_code_status']  = Order_GoodsVirtualCodeModel::VIRTUAL_CODE_USED;
					$update['virtual_code_usetime'] = date('Y-m-d H:i:s', time());                            //兑换时间
					$flag                           = $orderGoodsVirtualCodeModel->editCode($virtual_code_id, $update);
					check_rs($flag,$rs_row);

					$conid['order_id'] = $virtual_base['order_id'];

					$order_data = $orderBaseModel->getOrderList($conid);
					$order_data = pos($order_data['items']);
					$goods_list = pos($order_data['goods_list']);

					$data['goods_url']  = $goods_list['goods_link'];
					$data['img_240']    = $goods_list['goods_image'];
					$data['img_60']     = $goods_list['goods_image'];
					$data['goods_name'] = $goods_list['goods_name'];
					$data['order_url']  = $goods_list['order_id'];
					$data['order_sn']   = $goods_list['order_id'];
					$data['buyer_msg']  = $order_data['order_message'];

					//判断该笔订单中有多少服务商品，如果是最后一笔服务商品，则修改订单状态为已完成，将订单金额转到商家账户
					$order_all = $orderGoodsVirtualCodeModel->getByWhere(array('order_id'=>$virtual_base['order_id']));
					$sum = count($order_all);

					$order_used = $orderGoodsVirtualCodeModel->getByWhere(array('order_id'=>$virtual_base['order_id'],'virtual_code_status'=>Order_GoodsVirtualCodeModel::VIRTUAL_CODE_USED));
					$used = count($order_used);
					fb($sum);
					fb($used);

					if($used == $sum)
					{
						$edit_flag = $orderBaseModel->editBase($order_data['order_id'], array('order_status' => Order_StateModel::ORDER_FINISH , 'order_finished_time' => get_date_time()));
						check_rs($edit_flag,$rs_row);

						//远程同步paycenter中的订单状态
						$key      = Yf_Registry::get('shop_api_key');
						$url         = Yf_Registry::get('paycenter_api_url');
						$shop_app_id = Yf_Registry::get('shop_app_id');
						$formvars = array();

						$formvars['order_id']    = $order_data['order_id'];
						$formvars['app_id']        = $shop_app_id;
						$formvars['from_app_id'] = Yf_Registry::get('shop_app_id');


						$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=confirmOrder&typ=json', $url), $formvars);

						if($rs['status'] == 250)
						{
							$flag = false;
							check_rs($flag,$rs_row);
						}
					}


				}
				elseif ($virtual_base['virtual_code_status'] == Order_GoodsVirtualCodeModel::SHOP_STATUS_OPEN)
				{
					$flag = false;
					check_rs($flag,$rs_row);
					$msg  = __('已使用或已冻结');
				}
			}

			$flag = is_ok($rs_row);

			if ($flag)
			{
				$status = 200;
				$msg    = __('success');
			}
			else
			{
				$msg    = __('failure');
				$status = 250;
			}

			$this->data->addBody(-140, $data, $msg, $status);
		}
	}

	/**
	 * 实物交易订单 ==> 待付款
	 *
	 * @access public
	 */
	public function getPhysicalNew()
	{
		$Order_BaseModel       = new Order_BaseModel();
		$condi['order_status'] = Order_StateModel::ORDER_WAIT_PAY;
        if(self::$is_partner){
            $condi['shop_id'] = Web_ConfigModel::value('self_shop_id');
        }
        $order_row = array('order_create_time'=>'desc');
		$data                  = $Order_BaseModel->getPhysicalList($condi,$order_row);
		$condition             = $data['condi'];

		$this->view->setMet('physical');
		include $this->view->getView();
	}

	/**
	 * 实物交易订单 ==> 已付款
	 *
	 * @access public
	 */
	public function getPhysicalPay()
	{
		$Order_BaseModel       = new Order_BaseModel();
		$condi['order_status'] = Order_StateModel::ORDER_PAYED;
        if(self::$is_partner){
            $condi['shop_id'] = Web_ConfigModel::value('self_shop_id');
        }
        $order_row = array('order_create_time'=>'desc');
		$data                  = $Order_BaseModel->getPhysicalList($condi,$order_row);
		$condition             = $data['condi'];

		$this->view->setMet('physical');
		include $this->view->getView();
	}

	/**
	 * 实物交易订单 ==> 待自提
	 *
	 * @access public
	 */
	public function getPhysicalNotakes()
	{
		$Order_BaseModel       = new Order_BaseModel();
		$condi['order_status'] = Order_StateModel::ORDER_SELF_PICKUP;
        if(self::$is_partner){
            $condi['shop_id'] = Web_ConfigModel::value('self_shop_id');
        }
        $order_row = array('order_create_time'=>'desc');
		$data                  = $Order_BaseModel->getPhysicalList($condi,$order_row);
		$condition             = $data['condi'];

		$this->view->setMet('physical');
		include $this->view->getView();
	}

	/**
	 * 实物交易订单 ==> 已发货
	 *
	 * @access public
	 */
	public function getPhysicalSend()
	{
		$Order_BaseModel       = new Order_BaseModel();
		$condi['order_status'] = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
        if(self::$is_partner){
            $condi['shop_id'] = Web_ConfigModel::value('self_shop_id');
        }
        $order_row = array('order_create_time'=>'desc');
		$data                  = $Order_BaseModel->getPhysicalList($condi,$order_row);
		$condition             = $data['condi'];

		$this->view->setMet('physical');
		include $this->view->getView();
	}

	/**
	 * 实物交易订单 ==> 已完成
	 *
	 * @access public
	 */
	public function getPhysicalSuccess()
	{
		$Order_BaseModel       = new Order_BaseModel();
		$condi['order_status'] = Order_StateModel::ORDER_FINISH;
        if(self::$is_partner){
            $condi['shop_id'] = Web_ConfigModel::value('self_shop_id');
        }
        $order_row = array('order_create_time'=>'desc');
		$data                  = $Order_BaseModel->getPhysicalList($condi,$order_row);
		$condition             = $data['condi'];

		$this->view->setMet('physical');
		include $this->view->getView();
	}

	/**
	 * 实物交易订单 ==> 已取消
	 *
	 * @access public
	 */
	public function getPhysicalCancel()
	{
		$Order_BaseModel       = new Order_BaseModel();
		$condi['order_status'] = Order_StateModel::ORDER_CANCEL;
        if(self::$is_partner){
            $condi['shop_id'] = Web_ConfigModel::value('self_shop_id');
        }
        $order_row = array('order_create_time'=>'desc');
		$data                  = $Order_BaseModel->getPhysicalList($condi,$order_row);
		$condition             = $data['condi'];

		$this->view->setMet('physical');
		include $this->view->getView();
	}

	/**
	 * 实物交易订单 ==> 订单详情
	 *
	 * @access public
	 */
	public function physicalInfo()
	{
		$order_id        = request_string('order_id');
		$Order_BaseModel = new Order_BaseModel();
		$data            = $Order_BaseModel->getPhysicalInfoData(array('order_id' => $order_id));
		include $this->view->getView();
	}

	/**
	 * 实物交易订单 ==> 打印发货单
	 *
	 * @access public
	 */
	public function getOrderPrint()
	{
		$Order_BaseModel   = new Order_BaseModel();
		$condi['order_id'] = request_string('order_id');

		$data = $Order_BaseModel->getOrderList($condi);
		$data = pos($data['items']);

		$data['goods_count'] = 0;
		foreach($data['goods_list'] as $key => $val)
		{
			$data['goods_count'] += $val['order_goods_num'];
			//如果商品有规格属性，则展示

			if (!empty($val['order_spec_info'])) {
				$data['goods_list'][$key]['goods_name'] .= "($val[order_spec_info])";
			}
		}

		//读取店铺印章等信息
		$shop_id = Perm::$shopId;
		$shop_BaseModel = new Shop_BaseModel();
		$shop_base = $shop_BaseModel->getBase( $shop_id );
		$shop_base = pos($shop_base);
		$shop_print_desc = $shop_base['shop_print_desc'];
		$shop_stamp = $shop_base['shop_stamp'];

		$this->view->setMet('orderPrint');
		include $this->view->getView();
	}

	/**
	 * 实物交易订单 ==> 设置发货
	 *
	 * @access public
	 */
	public function send()
	{
		$typ      = request_string('typ');
		$order_id = request_string('order_id');

		$Order_BaseModel   = new Order_BaseModel();
		$Shop_ExpressModel = new Shop_ExpressModel();
		$Order_GoodsModel               = new Order_GoodsModel();

		if ($typ == 'e')
		{
			$condi['order_id'] = $order_id;
			$data              = $Order_BaseModel->getOrderList($condi);
			$data              = pos($data['items']);

			$can_send = 1;
            if(self::$is_partner){
                $User_StockModel = new User_StockModel();
                foreach ($data['goods_list'] as $key=> $goods){
                    $user_stock = $User_StockModel->getOneByWhere(['user_id'=>Perm::$userId, 'goods_id'=>$goods['goods_id']]);
                    if($user_stock) {
                        $data['goods_list'][$key]['goods_stock'] = $user_stock['goods_stock'];
                    }else{
                        $data['goods_list'][$key]['goods_stock'] = 0;
                    }
                    $temp_can_send = $user_stock['goods_stock']*1 >= $goods['order_goods_num']-$goods['order_goods_returnnum'];
                    if(!$temp_can_send) {
                        $can_send = 0;
                        break;
                    }
                }
            }else{
                $Goods_BaseModel = new Goods_BaseModel();
                foreach ($data['goods_list'] as $key=> $goods){
                    $goods_base = $Goods_BaseModel->getOne($goods['goods_id']);
                    if($goods_base) {
                        $data['goods_list'][$key]['goods_stock'] = $goods_base['goods_stock'];
                    }else{
                        $data['goods_list'][$key]['goods_stock'] = 0;
                    }
                    $temp_can_send = $goods_base['goods_stock']*1 >= $goods['order_goods_num']-$goods['order_goods_returnnum'];
                    if(!$temp_can_send) {
                        $can_send = 0;
                        break;
                    }
                }
            }
            $data['can_send'] = $can_send;

            //默认物流公司 url
			$default_express_url = Yf_Registry::get('url') . '?ctl=Seller_Trade_Deliver&met=express&typ=e';
			//打印运单URL
			$print_tpl_url = Yf_Registry::get('url') . '?ctl=Seller_Trade_Waybill&met=printTpl&typ=e&order_id=' . $order_id;

			//默认物流公司
			$express_list = $Shop_ExpressModel->getDefaultShopExpress();
            if(is_array($express_list) && $express_list){
                $express_list = array_values($express_list);
            }
			include $this->view->getView();
		}
		else
		{
			//判断该笔订单是否是自己的单子
			$order_base = $Order_BaseModel->getOne($order_id);

			$rs_row = array();

			//开启事物
			$Order_BaseModel->sql->startTransactionDb();
            
            //判断账号是否可以发货
            $check_send = $this->checkSend($order_base['seller_user_id'],$order_base['shop_id']);

			if($check_send && $order_base['order_status'] < Order_StateModel::ORDER_RECEIVED)
			{
				//设置发货
				$update_data['order_status']              = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
				$update_data['order_shipping_express_id'] = request_int('order_shipping_express_id');
				$update_data['order_shipping_code']       = request_int('order_shipping_code');
				$update_data['order_shipping_message']    = request_string('order_shipping_message');
				$update_data['order_seller_message']      = request_string('order_seller_message');

				//配送时间 收货时间
				$current_time                       = time();
				$confirm_order_time                 = Yf_Registry::get('confirm_order_time');
				$update_data['order_shipping_time'] = date('Y-m-d H:i:s', $current_time);
				$update_data['order_receiver_date'] = date('Y-m-d H:i:s', $current_time + $confirm_order_time);

				$edit_flag = $Order_BaseModel->editBase($order_id, $update_data);
				check_rs($edit_flag,$rs_row);

                $order_list = $Order_GoodsModel -> getByWhere(array('order_id' => $order_base['order_source_id'],'order_goods_source_id' => ''));//查看不是分销商品的订单
                if(!empty($order_list) && $order_base['order_source_id']){
                    foreach ($order_list as $key => $value) {
						$edit_flag1 = $Order_GoodsModel -> editGoods($key,array('order_goods_source_ship' => $update_data['order_shipping_code'].'-'.$update_data['order_shipping_express_id']));
						check_rs($edit_flag1,$rs_row);
					}
				}

                $order_goods_list = $Order_GoodsModel->getByWhere(array('order_id' => $order_id));
				$order_goods_ids = array_column($order_goods_list, 'order_goods_id');
                $update_goods_data['order_goods_status'] = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
                $edit_goods_flag = $Order_GoodsModel->editGoods($order_goods_ids, $update_goods_data);
                check_rs($edit_goods_flag,$rs_row);

				//减商品库存
                $seller_user_id = $order_base['seller_user_id'];
                if($seller_user_id == Web_ConfigModel::value('self_user_id')){
                    $seller_user_id = null;
                }
                $Goods_BaseModel = new Goods_BaseModel();
                foreach ($order_goods_list as $key=>$goods) {
                    $goods_id = $goods['goods_id'];
                    $send_goods_num = $goods['order_goods_num'];
                    if($goods['goods_return_status'] == 2 || $goods['goods_refund_status'] == 2) {
                        $send_goods_num = $goods['order_goods_num'] * 1 - $goods['order_goods_returnnum'] * 1;
                    }
                    $flag_stock = $Goods_BaseModel->delStock($goods_id, $send_goods_num, $seller_user_id);
                    check_rs($flag_stock,$rs_row);
                }

				//如果为采购单，改变 "买家<-->分销商" 订单状态
				if($order_base['order_source_id'])
				{
					$dist_order = $Order_BaseModel->getOneByWhere(array('order_id'=>$order_base['order_source_id']));
					if(!empty($dist_order)){
						/*
                            只有订单中不含分销商自己的商品时改变订单状态，如果含有分销商自己的商品，
                            供货商发货改变订单状态，分销商自己就发不了货了.
                            所以如果订单中含有分销商自己的商品，只有分销商的商品发货了，才能改变订单状态
                        */
						if(empty($order_list)){
							$dist_flag = $Order_BaseModel->editBase($dist_order['order_id'], $update_data);
							check_rs($dist_flag,$rs_row);
						}
						//买家商品订单表里添加物流单号
						$order_goods_id = $Order_GoodsModel->getKeyByWhere(array('order_goods_source_id' => $order_id));
						$edit_flag2 = $Order_GoodsModel -> editGoods($order_goods_id,array('order_goods_source_ship' => $update_data['order_shipping_code'].'-'.$update_data['order_shipping_express_id']));

						check_rs($edit_flag2,$rs_row);
					}
				}

				$message = new MessageModel();
				//远程修改paycenter中的订单信息
				$key      = Yf_Registry::get('shop_api_key');
				$url         = Yf_Registry::get('paycenter_api_url');
				$shop_app_id = Yf_Registry::get('shop_app_id');
				$formvars = array();

				$formvars['order_id']    = $order_id;
				$formvars['app_id']        = $shop_app_id;

				$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=sendOrderGoods&typ=json', $url), $formvars);
				if($rs['status'] == 200)
				{
					$rs_flag = true;
					check_rs($rs_flag,$rs_row);
				}
				else
				{
					$rs_flag = false;
					check_rs($rs_flag,$rs_row);
				}
				if(!empty($dist_order) && isset($dist_flag) && $dist_flag){//如果为采购单，改变 "买家<-->分销商" 订单状态
					$message->sendMessage('ordor_complete_shipping', $dist_order['buyer_user_id'], $dist_order['buyer_user_name'], $dist_order['order_id'], $dist_order['shop_name'], 0, MessageModel::ORDER_MESSAGE);
					$formvars['order_id']    = $dist_order['order_id'];
					$formvars['app_id']        = $shop_app_id;

					$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=sendOrderGoods&typ=json', $url), $formvars);
					if($rs['status'] == 200)
					{
						$rs_flag = true;
						check_rs($rs_flag,$rs_row);
					}
					else
					{
						$rs_flag = false;
						check_rs($rs_flag,$rs_row);
					}
				}
			}
			else
			{
				$flag = false;
				check_rs($flag,$rs_row);
			}
			$flag = is_ok($rs_row);

			if ($flag && $Order_BaseModel->sql->commitDb())
			{
				//发送站内信
				$message = new MessageModel();
				$message->sendMessage('ordor_complete_shipping', $order_base['buyer_user_id'], $order_base['buyer_user_name'], $order_id, $order_base['shop_name'], 0, MessageModel::ORDER_MESSAGE);

				$msg    = __('success');
				$status = 200;
			}
			else
			{
				$Order_BaseModel->sql->rollBackDb();
				$msg    = __('failure');
				$status = 250;
			}

			$this->data->addBody(-140, array(), $msg, $status);
		}
	}

	/**
	 * 实物交易订单 ==> 选择发货地址
	 *
	 * @access public
	 */
	public function chooseSendAddress()
	{
		$typ = request_string('typ');

		if ($typ == 'e')
		{
            $shop_id = Perm::$shopId;
			$Shop_ShippingAddressModel = new Shop_ShippingAddressModel();
			$address_list              = $Shop_ShippingAddressModel->getByWhere(array('shop_id' => $shop_id));
			$address_list              = array_values($address_list);
			foreach ($address_list as $key => $val)
			{
				$address_list[$key]['address_info']  = $val['shipping_address_area'] . " " . $val['shipping_address_address'];
				$address_list[$key]['address_value'] = $val['shipping_address_contact'] . "&nbsp" . $val['shipping_address_phone'] . "&nbsp" . $val['shipping_address_area'] . "&nbsp" . $val['shipping_address_address'];
			}

			include $this->view->getView();
		}
		else
		{
			$order_id     = request_string('order_id');
			$send_address = request_row('send_address');

			$Order_BaseModel                     = new Order_BaseModel();
			$update_data['order_seller_name']    = $send_address['order_seller_name'];
			$update_data['order_seller_address'] = $send_address['order_seller_address'];
			$update_data['order_seller_contact'] = $send_address['order_seller_contact'];
			$flag                                = $Order_BaseModel->editBase($order_id, $update_data);

			if ($flag !== false)
			{
				$msg    = __('设置成功');
				$status = 200;
			}
			else
			{
				$msg    = __('设置失败');
				$status = 250;
			}

			$this->data->addBody(-140, array(), $msg, $status);
		}

	}

	/**
	 * 实物交易订单 ==> 选择发货地址
	 *
	 * @access public
	 */
	public function editBuyerAddress()
	{
		$typ = request_string('typ');

		if ($typ == 'e')
		{
			include $this->view->getView();
		}
		else
		{
			$Order_BaseModel = new Order_BaseModel();

			$order_id = request_string('order_id');

			$update_data['order_receiver_name']    = request_string('order_receiver_name');
			$update_data['order_receiver_address'] = request_string('order_receiver_address');
			$update_data['order_receiver_contact'] = request_string('order_receiver_contact');

			$flag = $Order_BaseModel->editBase($order_id, $update_data);

			if ($flag !== false)
			{
				$update_data['receiver_info'] = $update_data['order_receiver_name'] . "&nbsp;" . $update_data['order_receiver_address'] . "&nbsp;" . $update_data['order_receiver_contact'];
				$msg                          = __('success');
				$status                       = 200;
			}
			else
			{
				$msg    = __('failure');
				$status = 250;
			}

			$this->data->addBody(-140, $update_data, $msg, $status);
		}

	}


	/**
	 * 商家中心首页不同状态订单数目
	 *
	 * @access public
	 */
	public function getOrderNum()
	{
		$order_type = request_int('order_type');

		$orderBaseModel = new Order_BaseModel();
		$orderReturn = new Order_ReturnModel();

		//待付款订单
		$condi                 = array();
        if(self::$is_partner){
            $condi['seller_user_id']        = Perm::$userId;
        }else{
            $condi['seller_user_id']        = Web_ConfigModel::value('self_user_id');
        }
		$condi['order_status'] = Order_StateModel::ORDER_WAIT_PAY;
		$wait_pay_data         = $orderBaseModel->getByWhere($condi);

		//待发货订单
		$condi                     = array();
        if(self::$is_partner){
            $condi['seller_user_id']        = Perm::$userId;
        }else{
            $condi['seller_user_id']        = Web_ConfigModel::value('self_user_id');
        }
		$condi['order_status:IN']  = array(
			Order_StateModel::ORDER_PAYED,
			Order_StateModel::ORDER_WAIT_PREPARE_GOODS
		);
		$payed_data                = $orderBaseModel->getByWhere($condi);

		//退款订单
		$condi                        = array();
        if(self::$is_partner){
            $condi['seller_user_id']        = Perm::$shopId;
        }else{
            $condi['seller_user_id']        = Web_ConfigModel::value('self_shop_id');
        }
		$condi['return_state'] = Order_ReturnModel::RETURN_WAIT_PASS;
		$condi['return_type:!='] = Order_ReturnModel::RETURN_TYPE_GOODS;
		$refund_data                  = $orderReturn->getByWhere($condi);

		//退货订单
		$condi                        = array();
        if(self::$is_partner){
            $condi['seller_user_id']        = Perm::$shopId;
        }else{
            $condi['seller_user_id']        = Web_ConfigModel::value('self_shop_id');
        }
		$condi['return_state'] = Order_ReturnModel::RETURN_WAIT_PASS;
		$condi['return_type'] = Order_ReturnModel::RETURN_TYPE_GOODS;
		$return_data                  = $orderReturn->getByWhere($condi);


		$data['wait_pay_num'] = count($wait_pay_data);
		$data['payed_num']    = count($payed_data);
		$data['refund_num']   = count($refund_data);
		$data['return_num']   = count($return_data);

		$this->data->addBody(-140, $data);

	}

    /**
     * 门店自提订单 ==> 待付款
     *
     * @access public
     */
    public function getChainNew()
    {
        $Order_BaseModel       = new Order_BaseModel();
        $condi['order_status'] = Order_StateModel::ORDER_WAIT_PAY;
        $condi['chain_id:!=']       = 0;
        if(self::$is_partner){
            $condi['shop_id'] = Web_ConfigModel::value('self_shop_id');
        }
        $order_row = array('order_create_time'=>'desc');
        $data                  = $Order_BaseModel->getPhysicalList($condi,$order_row);
        $condition             = $data['condi'];

        $this->view->setMet('chain');
        include $this->view->getView();
    }

    /**
     * 门店自提订单 ==> 待自提
     *
     * @access public
     */
    public function getChainNotakes()
    {
        $Order_BaseModel       = new Order_BaseModel();
        $condi['order_status'] = Order_StateModel::ORDER_SELF_PICKUP;
        $condi['chain_id:!=']       = 0;
        if(self::$is_partner){
            $condi['shop_id'] = Web_ConfigModel::value('self_shop_id');
        }
        $order_row = array('order_create_time'=>'desc');
        $data                  = $Order_BaseModel->getPhysicalList($condi,$order_row);
        $condition             = $data['condi'];

        $this->view->setMet('chain');
        include $this->view->getView();
    }

    /**
     * 门店自提订单 ==> 已完成
     *
     * @access public
     */
    public function getChainSuccess()
    {
        $Order_BaseModel       = new Order_BaseModel();
        $condi['order_status'] = Order_StateModel::ORDER_FINISH;
        $condi['chain_id:!=']       = 0;
        if(self::$is_partner){
            $condi['shop_id'] = Web_ConfigModel::value('self_shop_id');
        }
        $order_row = array('order_create_time'=>'desc');
        $data                  = $Order_BaseModel->getPhysicalList($condi,$order_row);
        $condition             = $data['condi'];

        $this->view->setMet('chain');
        include $this->view->getView();
    }

    /**
     * 门店自提订单 ==> 已取消
     *
     * @access public
     */
    public function getChainCancel()
    {
        $Order_BaseModel       = new Order_BaseModel();
        $condi['order_status'] = Order_StateModel::ORDER_CANCEL;
        $condi['chain_id:!=']       = 0;
        if(self::$is_partner){
            $condi['shop_id'] = Web_ConfigModel::value('self_shop_id');
        }
        $order_row = array('order_create_time'=>'desc');
        $data                  = $Order_BaseModel->getPhysicalList($condi,$order_row);
        $condition             = $data['condi'];

        $this->view->setMet('chain');
        include $this->view->getView();
    }

    /**
     * 门店自提订单 ==> 订单详情
     *
     * @access public
     */public function chainInfo()
    {
        $order_id        = request_string('order_id');
        $Order_BaseModel = new Order_BaseModel();
        $data            = $Order_BaseModel->getChainInfoData(array('order_id' => $order_id));

		//获取门店信息
		$chain_id = $data['chain_id'];
		$chain_model = new Chain_BaseModel;
		$chain_data = $chain_model->getChainInfo($chain_id);
        include $this->view->getView();
    }

	/**
	 * 修改订单金额
	 *
	 * @access public
	 */
	public function cost()
	{

		$Order_BaseModel = new Order_BaseModel();

		$order_id = request_string('order_id');

		$order_base = $Order_BaseModel->getBase($order_id);  //获取店铺订单列表
		$order_base = $order_base[$order_id];

		//获取订单商品信息
		$Order_GoodsModel = new Order_GoodsModel();
		$order_goods_row = $Order_GoodsModel->getGoodsListByOrderId($order_id);
		$data = $order_goods_row['items'];

		include $this->view->getView();
	}

	public function editCost()
	{
		$order_id = request_string('order_id');
		$product_row  = request_row('product_id');
		$shipping = request_float('shipping');
		$goods_edit_flag = false;
		$shipping_edit_flag = false;
		$flag = true;

		$Order_GoodsModel = new Order_GoodsModel();

		//开启事物
		$Order_GoodsModel->sql->startTransactionDb();

		$order_goods_row = $Order_GoodsModel->getGoodsListByOrderId($order_id);
		//订单商品列表
		$data = $order_goods_row['items'];

		$Order_BaseModel = new Order_BaseModel();
		//订单详情
		$order_base = $Order_BaseModel->getBase($order_id);
		$order_base = $order_base[$order_id];

		$Goods_CatModel = new Goods_CatModel();
		$Order_GoodsSnapshot = new Order_GoodsSnapshot();

		//1.修改订单商品表中商品的价格
		$order_edit_row = array();
		$order_goods_amount = 0;    //商品总价（不包含运费）
		$order_payment_amount = 0;  //实际应付金额（商品总价 + 运费）
		$order_discount_fee = 0;   //优惠价格
		$order_commission_fee = 0;   //交易佣金

		//判断该订单是否为待付款订单
		if($order_base['order_status'] == Order_StateModel::ORDER_WAIT_PAY)
		{
			foreach ($data as $key => $val)
			{
				//判断商品价格是否被修改了
				if($val['order_goods_payment_amount'] != $product_row[$val['goods_id']])
				{
                    if(intval($product_row[$val['goods_id']]) > intval($val['goods_price'])){
                        $flag = false;
                    }else{
                        $goods_edit_flag = true;

                        $edit_row = array();

                        //每件商品实际支付金额
                        $edit_row['order_goods_payment_amount'] = $product_row[$val['goods_id']];
                        //手工调整金额
                        $edit_row['order_goods_adjust_fee'] = $val['order_goods_payment_amount'] - $product_row[$val['goods_id']];
                        //商品实际支付总金额
                        $edit_row['order_goods_amount'] = $product_row[$val['goods_id']] * $val['order_goods_num'];
                        //优惠价格
                        $edit_row['order_goods_benefit'] = $val['order_goods_benefit'] + $edit_row['order_goods_adjust_fee'];

                        //重新计算该件商品的佣金
                        //获取分类佣金
                        $cat_base = $Goods_CatModel->getOne($val['goods_class_id']);
                        if ($cat_base)
                        {
                            $cat_commission = $cat_base['cat_commission'];
                        }
                        else
                        {
                            $cat_commission = 0;
                        }

                        //订单商品的佣金
                        $edit_row['order_goods_commission'] = number_format(($product_row[$val['goods_id']] * $cat_commission / 100), 2, '.', '');

                        $Order_GoodsModel->editGoods($val['order_goods_id'], $edit_row);

                        $order_goods_amount += $edit_row['order_goods_amount'];
                        $order_discount_fee += $edit_row['order_goods_benefit'];
                        $order_commission_fee += $edit_row['order_goods_commission'];


                        //2.修改快照表
                        $array = array();
                        $array['order_id'] = $order_id;
                        $array['goods_id'] = $val['goods_id'];
                        $snapshot_id = $Order_GoodsSnapshot->getKeyByWhere($array);

                        $edit_snapshot_row = array();
                        $edit_snapshot_row['goods_price'] = $product_row[$val['goods_id']];
                        $edit_snapshot_row['freight'] = $shipping;
                        $Order_GoodsSnapshot->editSnapshot($snapshot_id, $edit_snapshot_row);
                    }


				}
				else
				{
					$order_goods_amount += $val['order_goods_amount'];
					$order_discount_fee += $val['order_goods_benefit'];
					$order_commission_fee += $val['order_goods_commission'];
				}

			}

			//3.修改订单表
			//判断运费是否改变
            if($order_base['order_shipping_fee'] < $shipping){
                $flag = false;
            }else{
                if($order_base['order_shipping_fee'] != $shipping)
                {
                    $shipping_edit_flag = true;
                    $order_edit_row['order_shipping_fee'] = $shipping;
                }
            }


			//如果修改了商品价格或者修改了运费则需要修改订单表
			if($shipping_edit_flag || $goods_edit_flag)
			{
				//商品总价（不包含运费）
				$order_edit_row['order_goods_amount'] = $order_goods_amount;
				//应付金额（商品总价 + 运费）
				$order_edit_row['order_payment_amount'] = $order_goods_amount + $shipping;
				//优惠价格
				$order_edit_row['order_discount_fee'] = $order_discount_fee;
				//交易佣金
				$order_edit_row['order_commission_fee'] = $order_commission_fee;

				$Order_BaseModel->editBase($order_id,$order_edit_row );


				//远程修改paycenter中的订单数据
				//生成合并支付订单
				$key      = Yf_Registry::get('shop_api_key');
				$url         = Yf_Registry::get('paycenter_api_url');
				$shop_app_id = Yf_Registry::get('shop_app_id');
				$formvars = array();

				$formvars['order_id']    = $order_id;
				$formvars['uorder_id']     = $order_base['payment_number'];
				$formvars['app_id']        = $shop_app_id;
				$formvars['edit_row']  = $order_edit_row;


				$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=editOrderCost&typ=json', $url), $formvars);

				if($rs['status'] == 200)
				{
					$flag = true;
				}
				else
				{
					$flag = false;
				}
			}
		}
		else
		{
			$flag = false;
		}

		if ($flag && $Order_GoodsModel->sql->commitDb())
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$Order_GoodsModel->sql->rollBackDb();
			$m      = $Order_GoodsModel->msg->getMessages();
			$msg    = $m ? $m[0] : __('failure');
			$status = 250;
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}
    /**
     *  获取隐藏的实物订单
     * @author Str
     */
    public function getPhysicalHideOrder(){
        $Order_BaseModel       = new Order_BaseModel();
        $condi['order_shop_hidden'] = $Order_BaseModel::IS_SELLER_HIDDEN;
        if(self::$is_partner){
            $condi['shop_id'] = Web_ConfigModel::value('self_shop_id');
        }
        $order_row = array('order_create_time'=>'desc');
        $data                  = $Order_BaseModel->getPhysicalList($condi,$order_row);
        $condition             = $data['condi'];
        $this->view->setMet('physical');
        include $this->view->getView();
    }

    public function getVirtualHideOrder(){
        $Order_BaseModel = new Order_BaseModel();
        $condition['order_shop_hidden'] = $Order_BaseModel::IS_SELLER_HIDDEN;
		$condition['shop_id']           = Perm::$shopId;
		$condition['order_is_virtual']  = Order_BaseModel::ORDER_IS_VIRTUAL;
		$Order_BaseModel->createSearchCondi($condition);
        $order_row = array('order_create_time'=>'desc');
		$order_virtual_list = $Order_BaseModel->getOrderList($condition,$order_row);  //获取店铺订单列表
        $this->view->setMet('virtual');
		include $this->view->getView();
    }
    
    public function getChainHideOrder(){
        $Order_BaseModel = new Order_BaseModel();
        $condition['order_shop_hidden'] = $Order_BaseModel::IS_SELLER_HIDDEN;
        $condition['chain_id:!=']       = 0;
        if(self::$is_partner){
            $condition['shop_id'] = Web_ConfigModel::value('self_shop_id');
        }
        $order_row = array('order_create_time'=>'desc');
        $data            = $Order_BaseModel->getPhysicalList($condition,$order_row);
        $condition       = $data['condi'];
        $this->view->setMet('chain');
		include $this->view->getView();
    }
    
    
    /**
	 * 删除订单
	 *
	 * @author     Str
	 */
	public function hideOrder()
	{
		$order_id = request_string('order_id');
		$user     = request_string('user');
		$op       = request_string('op');

		$edit_row = array();
        $flag = false;
		$Order_BaseModel = new Order_BaseModel();
		$order_base = $Order_BaseModel->getOne($order_id);

		//买家删除订单
		if ($user == 'seller')
		{
			//判断订单状态是否是已完成（6）或者已取消（7）状态
			if($order_base['order_status'] >= Order_StateModel::ORDER_FINISH)
			{
				//判断当前用户是否是卖家
				if($order_base['seller_user_id'] == Perm::$userId)
				{
					if ($op == 'del')
					{
						$edit_row['order_shop_hidden'] = Order_BaseModel::IS_SELLER_REMOVE;
					}
					else
					{
						$edit_row['order_shop_hidden'] = Order_BaseModel::IS_SELLER_HIDDEN;
					}
				}
			}

            $flag = $Order_BaseModel->editBase($order_id, $edit_row);
		}

		if ($flag)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$this->data->addBody(-140, array(), $msg, $status);
	}

	/**
	 * 还原回收站中的订单
	 *
	 * @author     Str
	 */
	public function restoreOrder()
	{
		$order_id = request_string('order_id');
		$user     = request_string('user');

		$edit_row = array();
		$flag = false;
		$Order_BaseModel = new Order_BaseModel();
		
		if ($user == 'seller')
		{
			$edit_row['order_shop_hidden'] = Order_BaseModel::NO_SELLER_HIDDEN;
			$flag = $Order_BaseModel->editBase($order_id, $edit_row);
		}

		

		if ($flag)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$this->data->addBody(-140, array(), $msg, $status);

	}
    
    /**
     * 检验该账户是否可以发货
     * @param type $seller_id
     * @param type $shop_id
     * @return boolean
     */
    public function checkSend($seller_id,$shop_id){
        $user_id = Perm::$userId;
        if($seller_id == $user_id){
            return true;
        }else{
            //判断是否为子账号
            $seller_base_model = new Seller_BaseModel();
            $result = $seller_base_model->getByWhere(array('user_id'=>$user_id));
            $seller_info = array_shift($result);
            if($seller_info['shop_id'] == $shop_id){
                return true;
            }else{
                return false;
            }
        }
    }

	/**
	 * 货到付款订单确认收款
	 * @param type $order_id
	 * @return boolean
	 */
	public function confirmCollection()
	{
		$order_id = request_string('order_id');

		$Order_BaseModel = new Order_BaseModel();
		//查找订单信息
		$order_base = $Order_BaseModel->getOne($order_id);

		//判断当前用户是否是商家，判断订单状态是否是已发货或者已完成状态，判断当前订单是否是货到付款订单，判断是否已经确认收款
		if($order_base['seller_user_id'] == Perm::$userId && ($order_base['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS || $order_base['order_status'] == Order_StateModel::ORDER_FINISH) && $order_base['payment_id'] == PaymentChannlModel::PAY_CONFIRM && $order_base['payment_time'] <= 0)
		{
			//修改订单的付款时间
			$flag = $Order_BaseModel->editBase($order_id, array('payment_time'=>get_date_time()));
		}
		else
		{
			$flag = false;
		}
		if ($flag)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$this->data->addBody(-140, array(), $msg, $status);

	}
}

?>