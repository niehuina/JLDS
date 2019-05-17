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
class ERP_OrganizationModel extends ERP_Organization
{


	public function __construct()
	{
		parent::__construct();

	}

	//限时折扣商品列表，分页
	public function getOrganizationList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$rows   = $this->listByWhere($cond_row, $order_row, $page, $rows);
		return $rows;
	}

    /**
     * 根据分类id查询出分类的名字
     *
     * @param  int $config_key 主键值
     * @return array $rows 返回的查询内容
     * @access public
     */
    public function getOrganizationName($config_key = null)
    {
        $data       = $this->getOrganization($config_key);
        $organization_name = array();
        foreach ($data as $key => $value)
        {
            $organization_name = $value['name'];
        }
        return $organization_name;


    }

}