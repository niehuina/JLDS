<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}


class User_StockModel extends User_Stock
{
    public function getUserStockList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
    {
        $data = $this->listByWhere($cond_row, $order_row, $page, $rows);

        $common_ids = array_column($data['items'], 'common_id');

        $Goods_CommonModel = new Goods_CommonModel();
        $goods_list = $Goods_CommonModel->getByWhere(['common_id:in'=>$common_ids]);

        foreach ($data['items'] as $key=>$item){
            $data['items'][$key]['common_info'] =$goods_list[$item['common_id']];
        }
        return $data;
    }
}