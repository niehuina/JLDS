<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}


class User_StockOutModel extends User_StockOut
{
    const OUT_SELF = 1;             //自用

    public function get_count($cond_row)
    {
        return $this->getNum($cond_row);
    }

    public function get_sum_count($out_order_id)
    {
        $sql = "select sum(out_num) as goods_count from {$this->_tableName} where out_order_id='{$out_order_id}'";

        $data = $this->sql->getAll($sql);
        $data = current($data);
        $goods_count = $data['goods_count'];

        return $goods_count;
    }
}