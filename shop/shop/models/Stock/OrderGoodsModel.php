<?php


class Stock_OrderGoodsModel extends Stock_OrderGoods
{
    /**
     * 读取订单列表
     *
     * @param  array $cond_row 查询条件
     * @param  array $order_row 排序信息
     * @return array $rows 返回的查询内容
     * @access public
     */
    public function getOrderGoodsList($cond_row = array(), $order_row = array(), $page, $rows)
    {
        return $this->listByWhere($cond_row, $order_row, $page, $rows);
    }
}