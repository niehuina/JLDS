<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}


class Stock_CheckGoodsModel extends Stock_CheckGoods
{

    public function get_count($cond_row)
    {
        return $this->getNum($cond_row);
    }
}