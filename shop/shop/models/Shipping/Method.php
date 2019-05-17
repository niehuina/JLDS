<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 *
 *
 * @category   Framework
 * @package    __init__
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo
 */
class Shipping_Method extends Yf_Model
{
    public $_cacheKeyPrefix  = 'c|shipping_method|';
    public $_cacheName       = 'shipping';
    public $_tableName       = 'shipping_method';
    public $_tablePrimaryKey = 'shipping_method_id';

    /**
     * @param string $shipping Shipping Object
     * @var   string $db_id 指定需要连接的数据库Id
     * @return void
     */
    public function __construct(&$db_id = 'shop', &$shipping = null)
    {
        $this->_tableName = TABEL_PREFIX . $this->_tableName;
        $this->_cacheFlag = CHE;
        parent::__construct($db_id, $shipping);
    }

    /**
     * 根据主键值，从数据库读取数据
     *
     * @param  int $shipping_method_id 主键值
     * @return array $rows 返回的查询内容
     * @access public
     */
    public function getShippingMethod($shipping_method_id = null, $sort_key_row = null)
    {
        $rows = array();
        $rows = $this->get($shipping_method_id, $sort_key_row);

        return $rows;
    }

    /**
     * 插入
     * @param array $field_row 插入数据信息
     * @param bool $return_insert_id 是否返回inset id
     * @param array $field_row 信息
     * @return bool  是否成功
     * @access public
     */
    public function addShippingMethod($field_row, $return_insert_id = false)
    {
        $add_flag = $this->add($field_row, $return_insert_id);

        //$this->removeKey($user_id);
        return $add_flag;
    }
    /**
     * 根据主键更新表内容
     * @param mix $shipping_method_id 主键
     * @param array $field_row key=>value数组
     * @return bool $update_flag 是否成功
     * @access public
     */
    public function editShippingMethod($shipping_method_id = null, $field_row)
    {
        $update_flag = $this->edit($shipping_method_id, $field_row);

        return $update_flag;
    }

    /**
     * 删除操作
     * @param int $shipping_method_id
     * @return bool $del_flag 是否成功
     * @access public
     */
    public function removeShippingMethod($shipping_method_id)
    {
        $del_flag = $this->remove($shipping_method_id);

        //$this->removeKey($user_id);
        return $del_flag;
    }
}

?>