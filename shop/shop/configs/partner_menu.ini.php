<?php
$ctl = request_string('ctl');
$met = request_string('met');
$act = request_string("act");
$level_row = array();
$seller_menu = array(
    10000 => array(
        'menu_id' => '10000',
        'menu_parent_id' => '-1',
        'menu_name' => __('首页'),
        'menu_icon' => '',
        'menu_url_ctl' => 'Seller_Index',
        'menu_url_met' => 'index',
        'menu_url_parem' => '',
    ),
    12000 => array(
        'menu_id' => '12000',
        'menu_parent_id' => '-1',
        'menu_name' => __('订单物流'),
        'menu_icon' => '',
        'menu_url_ctl' => 'Seller_Trade_Order',
        'menu_url_met' => 'physical',
        'menu_url_parem' => '',
        'sub' => array(
            120001 => array(
                'menu_id' => '120001',
                'menu_parent_id' => '12000',
                'menu_name' => __('已售订单管理'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Trade_Order',
                'menu_url_met' => 'physical',
                'menu_url_parem' => '',
                'sub' => array(
                    1200011 => array(
                        'menu_id' => '1200011',
                        'menu_parent_id' => '120001',
                        'menu_name' => __('所有订单'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Order',
                        'menu_url_met' => 'physical',
                        'menu_url_parem' => '',
                    ),
                    1200012 => array(
                        'menu_id' => '1200012',
                        'menu_parent_id' => '120001',
                        'menu_name' => __('待付款'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Order',
                        'menu_url_met' => 'getPhysicalNew',
                        'menu_url_parem' => '',
                    ),
                    1200013 => array(
                        'menu_id' => '1200013',
                        'menu_parent_id' => '120001',
                        'menu_name' => __('已付款'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Order',
                        'menu_url_met' => 'getPhysicalPay',
                        'menu_url_parem' => '',
                    ),
                    /*1200014 => array(
                        'menu_id' => '1200014',
                        'menu_parent_id' => '120001',
                        'menu_name' => __('待自提'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Order',
                        'menu_url_met' => 'getPhysicalNotakes',
                        'menu_url_parem' => '',
                    ),*/
                    120005 => array(
                        'menu_id' => '120005',
                        'menu_parent_id' => '120001',
                        'menu_name' => __('已发货'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Order',
                        'menu_url_met' => 'getPhysicalSend',
                        'menu_url_parem' => '',
                    ),
                    120006 => array(
                        'menu_id' => '120006',
                        'menu_parent_id' => '120001',
                        'menu_name' => __('已完成'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Order',
                        'menu_url_met' => 'getPhysicalSuccess',
                        'menu_url_parem' => '',
                    ),
                    120007 => array(
                        'menu_id' => '120007',
                        'menu_parent_id' => '120001',
                        'menu_name' => __('已取消'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Order',
                        'menu_url_met' => 'getPhysicalCancel',
                        'menu_url_parem' => '',
                    ),
                    120008 => array(
                        'menu_id' => '120008',
                        'menu_parent_id' => '120001',
                        'menu_name' => __('回收站'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Order',
                        'menu_url_met' => 'getPhysicalHideOrder',
                        'menu_url_parem' => '',
                    ),
                    120009 => array(
                        'menu_id' => '120009',
                        'menu_parent_id' => '120001',
                        'menu_name' => __('订单详情'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Order',
                        'menu_url_met' => 'physicalInfo',
                        'menu_url_parem' => '',
                    ),
                    1200010 => array(
                        'menu_id' => '1200010',
                        'menu_parent_id' => '120001',
                        'menu_name' => __('发货'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Order',
                        'menu_url_met' => 'send',
                        'menu_url_parem' => '',
                    )
                )
            ),
            120003 => array(
                'menu_id' => '120003',
                'menu_parent_id' => '12000',
                'menu_name' => __('普通发货'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Trade_Deliver',
                'menu_url_met' => 'deliver',
                'menu_url_parem' => '',
                'sub' => array(
                    1200031 => array(
                        'menu_id' => '1200031',
                        'menu_parent_id' => '120003',
                        'menu_name' => __('待发货'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Deliver',
                        'menu_url_met' => 'deliver',
                        'menu_url_parem' => '',
                    ),
                    1200032 => array(
                        'menu_id' => '1200032',
                        'menu_parent_id' => '120003',
                        'menu_name' => __('发货中'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Deliver',
                        'menu_url_met' => 'delivering',
                        'menu_url_parem' => '',
                    ),
                    1200033 => array(
                        'menu_id' => '1200033',
                        'menu_parent_id' => '120003',
                        'menu_name' => __('已收货'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Deliver',
                        'menu_url_met' => 'delivered',
                        'menu_url_parem' => '',
                    )
                )
            ),
            120012 => array(
                'menu_id' => '120012',
                'menu_parent_id' => '12000',
                'menu_name' => __('备货订单'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Stock_Order',
                'menu_url_met' => 'physical',
                'menu_url_parem' => '',
                'sub' => array(
                    1200120 => array(
                        'menu_id' => '1200120',
                        'menu_parent_id' => '120012',
                        'menu_name' => __('所有订单'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Stock_Order',
                        'menu_url_met' => 'physical',
                        'menu_url_parem' => '',
                    ),
                    1200121 => array(
                        'menu_id' => '1200121',
                        'menu_parent_id' => '120012',
                        'menu_name' => __('发货中'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Stock_Order',
                        'menu_url_met' => 'delivering',
                        'menu_url_parem' => '',
                    ),
                    1200122 => array(
                        'menu_id' => '1200122',
                        'menu_parent_id' => '120012',
                        'menu_name' => __('已收货'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Stock_Order',
                        'menu_url_met' => 'delivered',
                        'menu_url_parem' => '',
                    ),
                    1200125 => array(
                        'menu_id' => '1200125',
                        'menu_parent_id' => '120012',
                        'menu_name' => __('备货订单详情'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Stock_Order',
                        'menu_url_met' => 'physicalInfo',
                        'menu_url_parem' => '',
                    ),
                    1200126 => array(
                        'menu_id' => '1200126',
                        'menu_parent_id' => '120012',
                        'menu_name' => __('发货'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Stock_Order',
                        'menu_url_met' => 'send',
                        'menu_url_parem' => '',
                    )
                )
            ),
            120013 => array(
                'menu_id' => '120013',
                'menu_parent_id' => '12000',
                'menu_name' => __('用户仓储管理'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Stock_Order',
                'menu_url_met' => 'user_stock',
                'menu_url_parem' => '',
                'sub' => array(
                    1200130 => array(
                        'menu_id' => '1200130',
                        'menu_parent_id' => '120013',
                        'menu_name' => __('所有商品'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Stock_Order',
                        'menu_url_met' => 'user_stock',
                        'menu_url_parem' => '',
                    ),
                    1200131 => array(
                        'menu_id' => '1200131',
                        'menu_parent_id' => '120013',
                        'menu_name' => __('库存盘点记录'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Stock_Order',
                        'menu_url_met' => 'stock_check_list',
                        'menu_url_parem' => '',
                    ),
                    1200132 => array(
                        'menu_id' => '1200132',
                        'menu_parent_id' => '120013',
                        'menu_name' => __('商品自用'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Stock_Order',
                        'menu_url_met' => 'stock_self_use',
                        'menu_url_parem' => '',
                    ),
                    1200133 => array(
                        'menu_id' => '1200133',
                        'menu_parent_id' => '120013',
                        'menu_name' => __('库存盘点'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Stock_Order',
                        'menu_url_met' => 'stock_check',
                        'menu_url_parem' => '',
                    ),
                    1200134 => array(
                        'menu_id' => '1200134',
                        'menu_parent_id' => '120013',
                        'menu_name' => __('库存盘点明细'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Stock_Order',
                        'menu_url_met' => 'stock_check_detail',
                        'menu_url_parem' => '',
                    ),
                )
            ),
            120004 => array(
                'menu_id' => '120004',
                'menu_parent_id' => '12000',
                'menu_name' => __('发货设置'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Trade_Deliver',
                'menu_url_met' => 'deliverSetting',
                'menu_url_parem' => '',
                'sub' => array(
                    1240001 => array(
                        'menu_id' => '1240001',
                        'menu_parent_id' => '120004',
                        'menu_name' => __('地址库'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Deliver',
                        'menu_url_met' => 'deliverSetting',
                        'menu_url_parem' => '',
                    ),
                    1240002 => array(
                        'menu_id' => '1240002',
                        'menu_parent_id' => '120004',
                        'menu_name' => __('默认物流公司'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Deliver',
                        'menu_url_met' => 'express',
                        'menu_url_parem' => '',
                    ),
                    1240003 => array(
                        'menu_id' => '1240003',
                        'menu_parent_id' => '120004',
                        'menu_name' => __('免运费额度'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Deliver',
                        'menu_url_met' => 'freightAmount',
                        'menu_url_parem' => '',
                    ),
                    1240005 => array(
                        'menu_id' => '1240005',
                        'menu_parent_id' => '120004',
                        'menu_name' => __('发货单打印设置'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Deliver',
                        'menu_url_met' => 'printSetting',
                        'menu_url_parem' => '',
                    )
                ),
            ),
            120005 => array(
                'menu_id' => '120005',
                'menu_parent_id' => '12000',
                'menu_name' => __('运单模板'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Trade_Waybill',
                'menu_url_met' => 'waybillManage',
                'menu_url_parem' => '',
                'sub' => array(
                    1200051 => array(
                        'menu_id' => '1200051',
                        'menu_parent_id' => '120005',
                        'menu_name' => __('模板绑定'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Waybill',
                        'menu_url_met' => 'waybillManage',
                        'menu_url_parem' => '',
                    ),
                    1200052 => array(
                        'menu_id' => '1200052',
                        'menu_parent_id' => '120005',
                        'menu_name' => __('自建模板'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Waybill',
                        'menu_url_met' => 'waybillIndex',
                        'menu_url_parem' => '',
                    ),
                    1200053 => array(
                        'menu_id' => '1200053',
                        'menu_parent_id' => '120005',
                        'menu_name' => __('选择模板'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Waybill',
                        'menu_url_met' => 'waybillBind',
                        'menu_url_parem' => '',
                    ),
                    1200054 => array(
                        'menu_id' => '1200054',
                        'menu_parent_id' => '120005',
                        'menu_name' => __('模板设置'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Waybill',
                        'menu_url_met' => 'waybillSetting',
                        'menu_url_parem' => '',
                    ),
                    1200055 => array(
                        'menu_id' => '1200055',
                        'menu_parent_id' => '120005',
                        'menu_name' => __('添加模板'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Waybill',
                        'menu_url_met' => 'addTpl',
                        'menu_url_parem' => '',
                    ),
                    1200056 => array(
                        'menu_id' => '1200056',
                        'menu_parent_id' => '120005',
                        'menu_name' => __('编辑模板'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Waybill',
                        'menu_url_met' => 'editTpl',
                        'menu_url_parem' => '',
                    ),
                    1200057 => array(
                        'menu_id' => '1200057',
                        'menu_parent_id' => '120005',
                        'menu_name' => __('设计模板'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Waybill',
                        'menu_url_met' => 'designTpl',
                        'menu_url_parem' => '',
                    ),
                ),
            ),
            120006 => array(
                'menu_id' => '120006',
                'menu_parent_id' => '12000',
                'menu_name' => __('评价管理'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Goods_Evaluation',
                'menu_url_met' => 'evaluation',
                'menu_url_parem' => '',
                'sub' => array(
                    1120006 => array(
                        'menu_id' => '1120006',
                        'menu_parent_id' => '120006',
                        'menu_name' => __('来自买家的评价'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Goods_Evaluation',
                        'menu_url_met' => 'evaluation',
                        'menu_url_parem' => '',
                    ),
                ),
            ),
            120007 => array(
                'menu_id' => '120007',
                'menu_parent_id' => '12000',
                'menu_name' => __('物流工具'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Transport',
                'menu_url_met' => 'transport',
                'menu_url_parem' => '',
            ),
            120010 => array(
                'menu_id' => '120010',
                'menu_parent_id' => '12000',
                'menu_name' => __('售卖区域'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Transport',
                'menu_url_met' => 'tplarea',
                'menu_url_parem' => '',
            ),
        ),
    ),

    15000 => array(
        'menu_id' => '15000',
        'menu_parent_id' => '-1',
        'menu_name' => __('售后服务'),
        'menu_icon' => '',
        'menu_url_ctl' => 'Seller_Service_Consult',
        'menu_url_met' => 'index',
        'menu_url_parem' => '',
        'sub' => array(
            150001 => array(
                'menu_id' => '150001',
                'menu_parent_id' => '15000',
                'menu_name' => __('咨询管理'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Service_Consult',
                'menu_url_met' => 'index',
                'menu_url_parem' => '',
            ),
            150002 => array(
                'menu_id' => '150002',
                'menu_parent_id' => '15000',
                'menu_name' => __('投诉管理'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Service_Complain',
                'menu_url_met' => 'index',
                'menu_url_parem' => '',
            ),
            150003 => array(
                'menu_id' => '150003',
                'menu_parent_id' => '15000',
                'menu_name' => __('退款管理'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Service_Return',
                'menu_url_met' => 'orderReturn',
                'menu_url_parem' => '',
            ),
            150004 => array(
                'menu_id' => '150004',
                'menu_parent_id' => '15000',
                'menu_name' => __('退货管理'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Service_Return',
                'menu_url_met' => 'goodsReturn',
                'menu_url_parem' => '',
            ),
        ),
    ),
    16000 => array(
        'menu_id' => '16000',
        'menu_parent_id' => '-1',
        'menu_name' => __('统计结算'),
        'menu_icon' => '',
        'menu_url_ctl' => 'Seller_Analysis_General',
        'menu_url_met' => 'index',
        'menu_url_parem' => '',
        'sub' => array(

            160001 => array(
                'menu_id' => '160001',
                'menu_parent_id' => '16000',
                'menu_name' => __('店铺概况'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Analysis_General',
                'menu_url_met' => 'index',
                'menu_url_parem' => '',
            ),
            160002 => array(
                'menu_id' => '160002',
                'menu_parent_id' => '16000',
                'menu_name' => __('商品分析'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Analysis_Goods',
                'menu_url_met' => 'detail',
                'menu_url_parem' => '',
                'sub' => array(
                    1160002 => array(
                        'menu_id' => '1160002',
                        'menu_parent_id' => '160002',
                        'menu_name' => __('商品详情'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Analysis_Goods',
                        'menu_url_met' => 'detail',
                        'menu_url_parem' => '',
                    ),
                    2160002 => array(
                        'menu_id' => '2160002',
                        'menu_parent_id' => '160002',
                        'menu_name' => __('热卖商品'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Analysis_Goods',
                        'menu_url_met' => 'hot',
                        'menu_url_parem' => '',
                    ),
                ),
            ),
            160003 => array(
                'menu_id' => '160003',
                'menu_parent_id' => '16000',
                'menu_name' => __('运营报告'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Analysis_Operation',
                'menu_url_met' => 'index',
                'menu_url_parem' => '',
            ),
            160006 => array(
                'menu_id' => '160006',
                'menu_parent_id' => '16000',
                'menu_name' => __('实物结算'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Order_Settlement',
                'menu_url_met' => 'normal',
                'menu_url_parem' => '',
                'sub' => array(
                    1160006 => array(
                        'menu_id' => '1160006',
                        'menu_parent_id' => '160006',
                        'menu_name' => __('实物订单结算'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Order_Settlement',
                        'menu_url_met' => 'normal',
                        'menu_url_parem' => '',
                    ),
                ),
            ),
            160007 => array(
                'menu_id' => '160007',
                'menu_parent_id' => '16000',
                'menu_name' => __('服务结算'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Order_Settlement',
                'menu_url_met' => 'virtual',
                'menu_url_parem' => '',
                'sub' => array(
                    1160007 => array(
                        'menu_id' => '1160007',
                        'menu_parent_id' => '160007',
                        'menu_name' => __('服务订单结算'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Order_Settlement',
                        'menu_url_met' => 'virtual',
                        'menu_url_parem' => '',
                    ),
                ),
            ),
        ),
    ),
    17000 => array(
        'menu_id' => '17000',
        'menu_parent_id' => '-1',
        'menu_name' => __('客服消息'),
        'menu_icon' => '',
        'menu_url_ctl' => 'Seller_Message',
        'menu_url_met' => 'index',
        'menu_url_parem' => '',
        'sub' => array(
            170001 => array(
                'menu_id' => '170001',
                'menu_parent_id' => '17000',
                'menu_name' => __('客服设置'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Message',
                'menu_url_met' => 'index',
                'menu_url_parem' => '',
                'sub' => array(
                    1700011 => array(
                        'menu_id' => '1700011',
                        'menu_parent_id' => '170001',
                        'menu_name' => __('客服设置'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Message',
                        'menu_url_met' => 'index',
                        'menu_url_parem' => '',
                    ),
                ),
            ),
            170002 => array(
                'menu_id' => '170002',
                'menu_parent_id' => '17000',
                'menu_name' => __('系统消息'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Message',
                'menu_url_met' => 'message',
                'menu_url_parem' => '',
                'sub' => array(
                    1700021 => array(
                        'menu_id' => '1700021',
                        'menu_parent_id' => '170002',
                        'menu_name' => __('系统消息'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Message',
                        'menu_url_met' => 'message',
                        'menu_url_parem' => '',
                    ),
                    1700022 => array(
                        'menu_id' => '1700022',
                        'menu_parent_id' => '170002',
                        'menu_name' => __('系统公告'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Message',
                        'menu_url_met' => 'messageAnnouncement',
                        'menu_url_parem' => '',
                    ),
                    1700023 => array(
                        'menu_id' => '1700023',
                        'menu_parent_id' => '170002',
                        'menu_name' => __('消息接收设置'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Message',
                        'menu_url_met' => 'messageManage',
                        'menu_url_parem' => '',
                    ),
                ),
            ),
        ),
    ),
);

//根据后台配置，去除一些不需要限时的功能模块
if(!(Web_ConfigModel::value('pointshop_isuse') && Web_ConfigModel::value('pointprod_isuse') && Web_ConfigModel::value('voucher_allow')))//代金券功能开启限制，代金券功能、积分功能、积分中心启用后，商家可以申请代金券活动
{
    unset($seller_menu[13000]['sub'][130011]);
}

if (!Web_ConfigModel::value('Plugin_Directseller') || @$this->shopBase['shop_type'] == 2) {
    unset($seller_menu[13000]['sub'][130012]);
}

if(count($seller_menu[13000]['sub']) == 0){
    unset($seller_menu[13000]['sub']);
    unset($seller_menu[13000]);
}

//if (!Web_ConfigModel::value('Plugin_Distribution')) {
//    unset($seller_menu[11000]['sub'][110004]);  //分销商品
//    unset($seller_menu[14000]['sub'][140013]);  //我的供应商
//    unset($seller_menu[14000]['sub'][140014]);  //分销明细
//    unset($seller_menu[14000]['sub'][140012]);  //我的分销商菜单
//    unset($seller_menu[14000]['sub'][140015]);  //批发市场
//} else {
//    if (@$this->shopBase['shop_type'] == 2) {
//        //供货商店铺
//        unset($seller_menu[14000]['sub'][140013]);  //我的供应商
//        unset($seller_menu[14000]['sub'][140014]);  //分销明细
//        unset($seller_menu[11000]['sub'][110004]);//分销商品
//    } else {
//        unset($seller_menu[14000]['sub'][140012]);  //我的分销商菜单
//    }
//}

//行
global $seller_menu_rows;
$seller_menu_rows = array();


function get_menu_rows($seller_menu, &$seller_menu_rows)
{
    foreach ($seller_menu as $id => $item) {
        if (isset($item['sub']) && $item['sub']) {
            get_menu_rows($item['sub'], $seller_menu_rows);

            unset($item['sub']);
            $seller_menu_rows[$id] = $item;
        } else {
            $seller_menu_rows[$id] = $item;
        }

    }
}

get_menu_rows($seller_menu, $seller_menu_rows);


//$ctl       = request_string('ctl');
//$met       = request_string('met');
//$level_row = array();

//echo $ctl, "\n",	$met;
//echo "\n";

function get_menu_id($seller_menu, $level = 0, &$level_row, $ctl, $met)
{
    global $seller_menu_rows;

    $level++;

    foreach ($seller_menu as $menu_row) {
        if ($menu_row['menu_url_ctl'] == $ctl && $menu_row['menu_url_met'] == $met) {
            $level_row[$ctl][$met][$level] = $menu_row['menu_id'];
            $level_row[$ctl][$met][$level - 1] = $menu_row['menu_parent_id'];

            //向上查找一次
            if (isset($seller_menu_rows[$menu_row['menu_parent_id']])) {
                $level_row[$ctl][$met][$level - 2] = $seller_menu_rows[$menu_row['menu_parent_id']]['menu_parent_id'];
            }
        } else {
        }

        if (isset($menu_row['sub'])) {
            get_menu_id($menu_row['sub'], $level, $level_row, $ctl, $met);
        }
    }
}

function get_menu_url_map($seller_menu, &$level_row, $seller_menu_ori)
{
    foreach ($seller_menu as $menu_row) {
        get_menu_id($seller_menu, 0, $level_row, $menu_row['menu_url_ctl'], $menu_row['menu_url_met']);

        if (isset($menu_row['sub'])) {
            get_menu_url_map($menu_row['sub'], $level_row, $seller_menu_ori);
        }
    }
}

//缓存点亮规则
//get_menu_url_map($seller_menu, $level_row, $seller_menu);

//计算当前高亮
get_menu_id($seller_menu, 0, $level_row, $ctl, $met);
$level_row = $level_row[$ctl][$met];
return $seller_menu;
?>