<?php if (!defined('ROOT_PATH')) exit('No Permission');

class Mb_BillTypeModel extends Mb_BillType
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
    public function getBillTypeList($cond_row = array(), $order_row = array(), $page=1, $rows=100)
    {
        return $this->listByWhere($cond_row, $order_row, $page, $rows);
    }
}