<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class User_RecordModel extends User_Record
{
    /**
     * 读取最近搜索记录
     *
     * @param  array $cond_row 查询条件
     * @param  array $order_row 排序信息
     * @param  array $page 当前页码
     * @param  array $rows 每页记录数
     * @return array $data 返回的查询内容
     * @access public
     */
    public function getRecordByUserId($cond_row = array(), $order_row = array())
    {
        $data = $this->listByWhere($cond_row, $order_row,1, 10);
        return $data;
    }
}

?>