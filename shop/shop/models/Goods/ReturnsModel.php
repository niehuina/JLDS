<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Goods_ReturnsModel extends Goods_Returns
{
    const GOOD_NOT_RETURN           = 1;                      //不可退
    const GOOD_CAN_RETURN              = 2;                   // 随时退
    const GOOD_CAN_RETURN_BEFORE_VIRTUAL_NOT_START = 3;      //未服务可退

    /**
     * 读取分页列表
     *
     * @param  array $cond_row 查询条件
     * @param  array $order_row 排序信息
     * @param  array $page 当前页码
     * @param  array $rows 每页记录数
     * @return array $data 返回的查询内容
     * @access public
     */
    public function getPageInfoList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
    {
        $data = $this->listByWhere($cond_row, $order_row, $page, $rows);
        return $data;
    }
}

?>