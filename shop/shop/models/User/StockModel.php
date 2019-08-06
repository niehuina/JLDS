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

    public function editStockFromOrder($order_goods_data, $buyer_user_id, $buyer_user_name)
    {
        //添加到个人仓库
        $User_Stock_Model = new User_StockModel();
        $user_stock_list = $User_Stock_Model->getByWhere(['user_id' => $buyer_user_id]);
        $goods_id_array = array_reduce($user_stock_list, function ($carry, $item) {
            $carry[$item['goods_id']] = $item['stock_id'];
            return $carry;
        });

        foreach ($order_goods_data as $i => $order_goods) {
            $goods_id = $order_goods['goods_id'];
            $order_goods_num_real = $order_goods['order_goods_num'] - $order_goods['order_goods_returnnum'];
            if($order_goods_num_real == 0) continue;
            if (array_key_exists($goods_id, $goods_id_array)) {
                $stock_row = array();
                $stock_row['goods_stock'] = $order_goods_num_real;

                //修改用户仓储商品数量
                $stock_id = $goods_id_array[$goods_id];
                $s_flag = $User_Stock_Model->editUserStock($stock_id, $stock_row, true);
                check_rs($s_flag, $rs_row);
            } else {
                $stock_row = array();
                $stock_row['user_id'] = $buyer_user_id;
                $stock_row['user_name'] = $buyer_user_name;
                $stock_row['goods_id'] = $order_goods['goods_id'];
                $stock_row['common_id'] = $order_goods['common_id'];
                $stock_row['goods_name'] = $order_goods['goods_name'];
                $stock_row['goods_stock'] = $order_goods_num_real;
                $stock_row['alarm_stock'] = 0;
                $stock_row['stock_date_time'] = get_date_time();

                //添加到用户仓储
                $s_flag = $User_Stock_Model->addUserStock($stock_row);
                check_rs($s_flag, $rs_row);
            }
        }
    }
}