<?php if (!defined('ROOT_PATH')) exit('No Permission');

/**
 * Class Mb_BannerImageModel
 */
class Mb_BannerImageModel extends Mb_BannerImage
{
    /**
     * 读取分页列表
     *
     * @param array $cond_row
     * @param array $order_row
     * @param int $page
     * @param int $rows
     * @return array 返回的查询内容
     * @access public
     */
    public function getBannerImageList($cond_row = array(), $order_row = array(), $page=1, $rows=100)
    {
        return $this->listByWhere($cond_row, $order_row, $page, $rows);
    }
}