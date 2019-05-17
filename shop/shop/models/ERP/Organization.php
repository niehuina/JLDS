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
class ERP_Organization extends Yf_Model
{

	public $_cacheKeyPrefix  = 'c|erp_organization|';
	public $_cacheName       = 'erp_organization';
	public $_tableName       = 'erp_organization';
	public $_tablePrimaryKey = 'code';

	/**
	 * @param string $user User Object
	 * @var   string $db_id 指定需要连接的数据库Id
	 * @return void
	 */
	public function __construct(&$db_id = 'shop', &$user = null)
	{
		$this->_tableName = TABEL_PREFIX . $this->_tableName;
		$this->_cacheFlag = CHE;
		parent::__construct($db_id, $user);
	}

    /**
     * 根据主键值，从数据库读取数据
     *
     * @param  int $config_key 主键值
     * @return array $rows 返回的查询内容
     * @access public
     */
    public function getOrganization($config_key = null, $sort_key_row = null)
    {
        $rows = array();
        $rows = $this->get($config_key, $sort_key_row);

        return $rows;
    }


}