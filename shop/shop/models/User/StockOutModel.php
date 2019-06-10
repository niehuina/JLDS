<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}


class User_StockOutModel extends User_StockOut
{
    const OUT_SELF = 1;             //自用
}