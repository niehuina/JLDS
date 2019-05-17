<?php if (!defined('ROOT_PATH')) exit('No Permission');

/**
 * Class Mb_Banner
 */
class Mb_BannerImage extends Yf_Model
{
    public $_cacheKeyPrefix  = 'c|mb_banner_image|';
    public $_cacheName       = 'mb';
    public $_tableName       = 'mb_banner_image';
    public $_tablePrimaryKey = 'mb_banner_image_id';

    /**
     * Mb_BannerImage constructor.
     * @param string $db_id 指定需要连接的数据库Id
     * @param null $user User Object
     */
    public function __construct(&$db_id='shop', &$user=null)
    {
        $this->_tableName = TABEL_PREFIX . $this->_tableName;
        $this->_cacheFlag        = CHE;
        parent::__construct($db_id, $user);
    }

    /**
     * 根据主键值，从数据库读取数据
     *
     * @param  int   $mb_banner_image_id  主键值
     * @return array $rows 返回的查询内容
     * @access public
     */
    public function getBannerImage($mb_banner_image_id=null, $sort_key_row=null)
    {
        $rows = array();
        $rows = $this->get($mb_banner_image_id, $sort_key_row);

        return $rows;
    }

    /**
     * 插入
     * @param array $field_row 插入数据信息
     * @param bool  $return_insert_id 是否返回inset id
     * @param array $field_row 信息
     * @return bool  是否成功
     * @access public
     */
    public function addBannerImage($field_row, $return_insert_id=false)
    {
        $add_flag = $this->add($field_row, $return_insert_id);

        //$this->removeKey($mb_banner_image_id);
        return $add_flag;
    }

    /**
     * 根据主键更新表内容
     * @param mix   $mb_banner_image_id  主键
     * @param array $field_row   key=>value数组
     * @return bool $update_flag 是否成功
     * @access public
     */
    public function editBannerImage($mb_banner_image_id=null, $field_row)
    {
        $update_flag = $this->edit($mb_banner_image_id, $field_row);

        return $update_flag;
    }

    /**
     * 更新单个字段
     * @param mix   $mb_banner_image_id
     * @param array $field_name
     * @param array $field_value_new
     * @param array $field_value_old
     * @return bool $update_flag 是否成功
     * @access public
     */
    public function editBannerImageSingleField($mb_banner_image_id, $field_name, $field_value_new, $field_value_old)
    {
        $update_flag = $this->editSingleField($mb_banner_image_id, $field_name, $field_value_new, $field_value_old);

        return $update_flag;
    }

    /**
     * 删除操作
     * @param int $mb_banner_image_id
     * @return bool $del_flag 是否成功
     * @access public
     */
    public function removeBannerImage($mb_banner_image_id)
    {
        $del_flag = $this->remove($mb_banner_image_id);

        //$this->removeKey($mb_banner_image_id);
        return $del_flag;
    }
}