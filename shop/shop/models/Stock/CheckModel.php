<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}


class Stock_CheckModel extends Stock_Check
{
    const STOCK_LOSSES = -1;             //盘亏
    const STOCK_NORMAL = 0;             //正常
    const STOCK_SURPLUS = 1;            //盘盈
}