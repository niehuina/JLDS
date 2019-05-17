<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}


class TradeTypeModel
{
    //trade_type_id
    const SHOPPING = 1;  //购物
    const TRANSFER = 2;  //转账
    const DEPOSIT  = 3; //充值
    const WITHDRAW = 4;  //提现
    const REFUND	= 5;  //退款
    const RECEIPT  = 6;  //收款
    const PAY		= 7;   //付款
    const CREDIT_RETURN		= 8;   //白条还款
    const WEB_POS = 9; //webPos付款
    const SHARES = 10; //股金

    //user_type
    const BENEFICIARY = 1; //收款方
    const PAYER = 2; //付款方
    const RECORD = 3; //记录
}