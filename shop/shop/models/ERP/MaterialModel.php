<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/15
 * Time: 17:57
 */
class ERP_MaterialModel extends ERP_Material
{


	public function __construct()
	{
		parent::__construct();

	}

	//限时折扣商品列表，分页
	public function getMaterialList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$rows   = $this->listByWhere($cond_row, $order_row, $page, $rows);
		return $rows;
	}


}