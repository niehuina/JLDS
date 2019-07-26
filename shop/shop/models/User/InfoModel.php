<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class User_InfoModel extends User_Info
{
	public static $userSex = array(
		"0" => '女',
		"1" => '男',
		"2" => '保密'
	);

	/**
	 * 读取分页列表
	 *
	 * @param  array $cond_row 查询条件
	 * @param  array $order_row 排序信息
	 * @param  array $page 当前页码
	 * @param  array $rows 每页记录数
	 * @return array $data 返回的查询内容
	 * @access public
	 */
	public function getInfoList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows, false);
        $User_GradeModel = new User_GradeModel();
        $shopBaseModel = new Shop_BaseModel();
		foreach ($data["items"] as $key => $value)
		{
			$data["items"][$key]["user_sex"] = __(User_InfoModel::$userSex[$value["user_sex"]]);
			$user_grade_info = $User_GradeModel->getOne($value['user_grade']);
			$data['items'][$key]['user_grade'] = $user_grade_info['user_grade_name'];
			if($value['user_parent_id']){
			    $user_parent = $this->getOne($value['user_parent_id']);
                $data["items"][$key]['user_parent'] = $user_parent['user_realname'];
            }else{
                $data["items"][$key]['user_parent'] = '';
            }
            $shop_info = 	$shopBaseModel->getOneByWhere(array('user_id'=>$value['user_id']));
            if(!empty($shop_info)){
                $data['items'][$key]['shop_type'] = $shop_info['shop_type'];
            }
		}
        $data['items'] = array_values($data['items']);
		return $data;
	}

	/**
	 * 读取一个会员信息
	 *
	 * @param  array $order 查询条件
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getUserInfo($order_row = array())
	{
		return $this->getOneByWhere($order_row);
	}

	/**
	 * 读取头部会员信息
	 *
	 * @param  int $user_id 主键值
	 * @return array $user 返回的查询内容
	 * @access public
	 */
	public function getUserMore($user_id)
	{

		$user = array();

		$user['info'] = $this->getOne($user_id);

		$user_grade_id = $user['info']['user_grade'];

		$this->userGradeModel = new User_GradeModel();
		$user['grade']        = $this->userGradeModel->getOne($user_grade_id);
		if (empty($user['grade']))
		{
			$user['grade']['user_grade_name'] = __('普通会员');
		}
		$this->userResourceModel = new User_ResourceModel();
		$user['points']          = $this->userResourceModel->getOne($user_id);

		$this->voucherBaseModel = new Voucher_BaseModel();

		$cond_row['voucher_owner_id'] = $user_id;
		$vo                           = $this->voucherBaseModel->getCount($cond_row);

		$user['voucher'] = $vo;

		return $user;
	}
	
	//获取用户的直属下级用户数量
	public function getSubQuantity($cond_row)
	{
		return $this->getNum($cond_row);
	}

	//获取所有用户id
	public function getAllUserId()
	{
		$sql = "SELECT
						user_id,user_name
					FROM
						" . TABEL_PREFIX . "user_info
					";
		$rows = $this->sql->getAll($sql);

		if($rows)
		{
			$rows = array_column($rows,'user_id');
		}

		return $rows;
	}

	/**
	 * 获取订单数量
     * 1.实物订单
     * 2.服务订单
     * 3.门店自提订单
     * @param type $user_id
     */
    public function getUserOrderCount($user_id){
        $order_count = array();
        $order_model = new Order_BaseModel();
        //待付款
        $cond_row1 = array();
        $cond_row1['buyer_user_id']        = $user_id;
        $cond_row1['order_buyer_hidden:<'] = Order_BaseModel::IS_BUYER_REMOVE;
        $cond_row1['order_status'] = Order_StateModel::ORDER_WAIT_PAY;
        $order_count['wait'] = $order_model->getCount($cond_row1);
        //待发货
        $cond_row2 = array();
        $cond_row2['buyer_user_id']        = $user_id;
        $cond_row2['order_buyer_hidden:<'] = Order_BaseModel::IS_BUYER_REMOVE;
        $cond_row2['order_status:IN'] = array(Order_StateModel::ORDER_WAIT_PREPARE_GOODS,Order_StateModel::ORDER_PAYED);
        $order_count['payed'] = $order_model->getCount($cond_row2);
        //待收货
        $cond_row3 = array();
        $cond_row3['buyer_user_id']        = $user_id;
        $cond_row3['order_buyer_hidden:<'] = Order_BaseModel::IS_BUYER_REMOVE;
        $cond_row3['order_buyer_hidden:!='] = Order_BaseModel::IS_BUYER_HIDDEN;
        $cond_row3['order_status'] = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
        //$cond_row3['order_is_virtual']     = Order_BaseModel::ORDER_IS_REAL; //实物订单
        $order_count['confirm'] = $order_model->getCount($cond_row3);
        //待评价
        $cond_row4 = array();
        $cond_row4['buyer_user_id']        = $user_id;
        $cond_row4['order_buyer_hidden:<'] = Order_BaseModel::IS_BUYER_REMOVE;
        $cond_row4['order_status'] = Order_StateModel::ORDER_FINISH;
        $cond_row4['order_buyer_evaluation_status'] = 0; //买家未评价
        $order_count['finish'] = $order_model->getCount($cond_row4);
        //退款退货
        $return_model = new Order_ReturnModel();
        $cond_row5 = array();
        $cond_row5['buyer_user_id']        = $user_id;
        $cond_row5['return_state:!='] = Order_ReturnModel::RETURN_PLAT_PASS;
        $order_count['return'] = $return_model->getCount($cond_row5);
        return $order_count;
    }

    public function getParentId($user_id)
    {
        $sql = "select getUserParent({$user_id})";
        $data_rows = $this->sql->getAll($sql);
        $rs = current($data_rows);
        $rs = array_values($rs);
        return $rs[0];
    }
    public function getUserChildren($user_id, $is_hasParent = 1)
    {
        $sql = "select getUserChildren({$user_id}, {$is_hasParent})";
        $data_rows = $this->sql->getAll($sql);
        $rs = current($data_rows);
        $rs = array_values($rs);
        return $rs[0];
    }
    public function user_count($cond_row)
    {
        return $this->getNum($cond_row);
    }

    public function getUserInfoByKeys($keys)
    {
        $where = 'user_grade >= 2 and user_realname = \''.$keys.'\' or user_mobile = \''.$keys.'\'';

        $sql = 'select user_id, user_name, user_realname as user_truename, user_mobile from '.$this->_tableName.' where '.$where;
        $sql .= $this->sql->getLimit();
        $data_rows = $this->sql->getAll($sql);
        return $data_rows;
    }

    public function updateWebpos($user_id,$bir,$phone)
    {
        if($bir) {
            $sql = "UPDATE `webpos`.`wp_users` SET `bron`=" . strtotime($bir) . " WHERE `ucenter_id`=" . $user_id . "";
        }
        if($phone) {
            $sql = "UPDATE `webpos`.`wp_users` SET `phone`=" . $phone . " WHERE `ucenter_id`=" . $user_id . "";
        }
        $flag= $this->sql->exec($sql);
        return $flag;
    }

    public function updateCurrentPartnerCount($count =0)
    {
        $sql = "UPDATE ".$this->_tableName." SET current_year_partner_count=" . $count . " WHERE user_grade = 4";
        $flag= $this->sql->exec($sql);
        $this->removeCache();
        return $flag;
    }
}

User_InfoModel::$userSex = array(
	"0" => __('女'),
	"1" => __('男'),
	"2" => __('保密')
);
?>