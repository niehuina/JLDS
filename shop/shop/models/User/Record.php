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
class User_Record extends Yf_Model
{
    public $_cacheKeyPrefix  = 'c|user_record|';
    public $_cacheName       = 'user';
    public $_tableName       = 'user_record';
    public $_tablePrimaryKey = 'user_record_id';

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
     * @param  int $user_record_id 主键值
     * @return array $rows 返回的查询内容
     * @access public
     */
    public function getRecord($user_record_id = null, $sort_key_row = null)
    {
        $rows = array();
        $rows = $this->get($user_record_id, $sort_key_row);

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
    public function addRecord($field_row, $return_insert_id = false)
    {
        $add_flag = $this->add($field_row, $return_insert_id);

        //$this->removeKey($user_id);
        return $add_flag;
    }


    /**
     * 删除操作
     * @param int $user_record_id
     * @return bool $del_flag 是否成功
     * @access public
     */
    public function removeRecord($user_record_id)
    {
        $del_flag = $this->remove($user_record_id);

        //$this->removeKey($user_id);
        return $del_flag;
    }
}

?>