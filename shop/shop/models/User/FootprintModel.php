<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class User_FootprintModel extends User_Footprint
{

	/**
	 * 读取分页列表
	 *
	 * @param  array $cond_row 查询条件
	 * @param  array $order_row 排序信息
	 * @param  array $page 当前页码
	 * @param  array $rows 每页记录数
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getFootprintList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{

		$data = $this->listByWhere($cond_row, $order_row, $page, $rows,true,'footprint_date');
		return $data;
	}

	/**
	 * 读取足迹所有数据
	 *
	 * @param  array $order_row 查询条件
	 * @return array $data 返回的查询内容
	 * @access public
	 */
	public function getFootprintAll($order_row)
	{
		$data = $this->getByWhere($order_row,array('footprint_time' => 'DESC'));

		return $data;
	}
	
	/**
	 * 读取一个足迹数据
	 *
	 * @param  array $order_row 查询条件
	 * @return array $data 返回的查询内容
	 */
	public function getFootprintDetail($order_row)
	{
		$data = $this->getOneByWhere($order_row);

		return $data;
	}

	//根据user_id获取用户的足迹商品数量
	public function getFootprintNum($cond_row)
	{
		return $this->getNum($cond_row);
	}

	public function getFootGoodCommonId()
	{
		$query = 'SELECT footprint_id,common_id FROM ' . $this->_tableName . ' where user_id='. Perm::$userId . ' group by common_id';

		$row = $this->sql->getAll($query);

		return $row;

	}
}

?>