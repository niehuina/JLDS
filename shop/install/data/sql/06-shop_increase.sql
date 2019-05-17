-- ALTER TABLE `yf_order_base` ADD COLUMN `order_invoice_id`  int(10) NOT NULL COMMENT '发票id' AFTER `order_invoice`;


ALTER TABLE `yf_order_goods` MODIFY COLUMN `order_spec_info`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '规格描述' AFTER `spec_id`;

ALTER TABLE `yf_order_return` ADD COLUMN `order_is_virtual`  tinyint(1) NOT NULL DEFAULT 0 COMMENT '虚拟订单' AFTER `order_number`;


ALTER TABLE `yf_user_info` MODIFY COLUMN `user_logo`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `user_area`;

ALTER TABLE `yf_order_base` ADD COLUMN `chain_id`  int(11) NOT NULL COMMENT '门店id' AFTER `order_shop_benefit`;

ALTER TABLE `yf_order_settlement` MODIFY COLUMN `os_id`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '结算单编号(年月店铺ID)' FIRST ;



-- 门店管理
-- ----------------------------
--  Table structure for `yf_chain_base`
-- ----------------------------
DROP TABLE IF EXISTS `yf_chain_base`;
CREATE TABLE `yf_chain_base` (
  `chain_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '门店Id',
  `chain_name` varchar(20) NOT NULL DEFAULT '' COMMENT '门店名称',
  `chain_mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号码',
  `chain_telephone` varchar(30) NOT NULL DEFAULT '' COMMENT '联系电话',
  `chain_contacter` varchar(20) NOT NULL DEFAULT '' COMMENT '联系人',
  `chain_province_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '省id',
  `chain_province` varchar(10) NOT NULL COMMENT '省份',
  `chain_city_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '市id',
  `chain_city` varchar(10) NOT NULL COMMENT '市',
  `chain_county_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '县',
  `chain_county` varchar(10) NOT NULL COMMENT '县区',
  `chain_address` varchar(50) NOT NULL DEFAULT '' COMMENT '详细地址',
  `chain_opening_hours` varchar(255) NOT NULL DEFAULT '' COMMENT '营业时间',
  `chain_traffic_line` varchar(255) NOT NULL DEFAULT '' COMMENT '交通路线',
  `chain_img` varchar(255) NOT NULL DEFAULT '' COMMENT '门店图片',
  `chain_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`chain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='门店表';

-- ----------------------------
--  Table structure for `yf_chain_goods`
-- ----------------------------
DROP TABLE IF EXISTS `yf_chain_goods`;
CREATE TABLE `yf_chain_goods` (
  `chain_goods_id` int(10) NOT NULL AUTO_INCREMENT,
  `chain_id` int(10) NOT NULL DEFAULT '0' COMMENT '门店id',
  `shop_id` int(10) NOT NULL DEFAULT '0' COMMENT '商店id',
  `goods_id` int(10) NOT NULL DEFAULT '0' COMMENT '商品id',
  `common_id` int(10) NOT NULL DEFAULT '0' COMMENT '商品common_id',
  `goods_stock` int(10) NOT NULL DEFAULT '0' COMMENT '商品商品库存',
  PRIMARY KEY (`chain_goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='门店商品表';

-- ----------------------------
--  Table structure for `yf_chain_user`
-- ----------------------------
DROP TABLE IF EXISTS `yf_chain_user`;
CREATE TABLE `yf_chain_user` (
  `chain_user_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '门店用户id',
  `user_id` int(10) unsigned NOT NULL COMMENT '会员ID',
  `chain_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属门店',
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺ID',
  `chain_user_login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后登录时间',
  PRIMARY KEY (`chain_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='门店用户表';

-- ----------------------------
--  Table structure for `yf_order_goods_chain_code`
-- ----------------------------
DROP TABLE IF EXISTS `yf_order_goods_chain_code`;
CREATE TABLE `yf_order_goods_chain_code` (
  `chain_code_id` varchar(50) NOT NULL COMMENT '虚拟码',
  `order_id` varchar(50) NOT NULL DEFAULT '' COMMENT '订单id',
  `chain_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店id',
  `order_goods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单商品id',
  `chain_code_status` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '虚拟码状态:0-未使用; 1-已使用; 2-冻结',
  `chain_code_usetime` datetime NOT NULL COMMENT '虚拟兑换码使用时间',
  PRIMARY KEY (`chain_code_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='门店自提兑换码表';



ALTER TABLE `yf_order_base` ADD COLUMN `order_seller_message` varchar(255) NOT NULL DEFAULT '' COMMENT '卖家给卖家留言';

DELETE FROM `yf_web_config` where `config_key`='directseller_is_open';
INSERT INTO `yf_web_config` (`config_key`, `config_value`, `config_type`, `config_enable`, `config_comment`, `config_datatype`) VALUES ('directseller_is_open', '0', 'directseller', '1', '是否开启销售员', 'number');


-- ----------------------------
-- Table structure for yf_distribution_shop_directseller
-- ----------------------------
DROP TABLE IF EXISTS `yf_distribution_shop_directseller`;
CREATE TABLE `yf_distribution_shop_directseller` (
  `shop_directseller_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属店铺id',
  `directseller_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推广销售员id = user_id',
  `directseller_parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级id-因为店铺不同，上级可能不同，如果用户主动成为某个店铺的销售员，则上级id为0',
  `directseller_shop_name` varchar(100) NOT NULL COMMENT '推广小店名称',
  `directseller_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否审核通过: 0-待审核  1-通过',
  `directseller_create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`shop_directseller_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺推广销售员表。';

-- ----------------------------
-- Table structure for yf_distribution_shop_directseller_config
-- ----------------------------
DROP TABLE IF EXISTS `yf_distribution_shop_directseller_config`;
CREATE TABLE `yf_distribution_shop_directseller_config` (
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺ID',
  `allow_seller_buy` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '销售员购买权限-购买权限开启状态下，销售员自己购买的订单将会算入业绩',
  `auto_settle` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '结算方式 0-手动结算 1-自动结算',
  `cps_rate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '一级佣金比例',
  `second_is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '二级销售 0关闭 1开启',
  `second_cps_rate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二级佣金比例',
  `directseller_customer_exptime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '客户关系 期限， 销售员带来的客户（成为店铺的消费者开始计算时间）超过一定期限后，则不再享受分佣。 消费者在店铺消费第一单时间后，在某个期限内消费才可以产生佣金。 ',
  `directseller_exptime_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1、永久，建立客户关系,客户以后在店铺的购买都分佣。    2、短期，只根据链接购买获取佣金， 且一定期限后，链接失效。 不需要建立客户关系',
  `directseller_rel_exptime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '客户关系保护期 - 带来的客户关系在一定期限内不给抢走， 其它销售可以通过购买链接生效，但是在保护期内部更改关系',
  `is_verify` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '销售员审核 0不需要审核 1需要审核',
  `settle_time_type` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '结算时间',
  `third_cps_rate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '三级分佣比例',
  `expenditure` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '消费额',
  PRIMARY KEY (`shop_id`),
  UNIQUE KEY `config_type` (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺销售员参数设置表-meta';

-- ----------------------------
-- Table structure for yf_distribution_shop_directseller_customer
-- ----------------------------
DROP TABLE IF EXISTS `yf_distribution_shop_directseller_customer`;
CREATE TABLE `yf_distribution_shop_directseller_customer` (
  `shop_directseller_customer_id` int(10) unsigned NOT NULL DEFAULT '0',
  `directseller_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推广销售员id = user_id',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属店铺id',
  `customer_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '客户Id = user_id - 这个可以改变，当客户根据其他销售者链接购买，则更改关系',
  PRIMARY KEY (`shop_directseller_customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺推广销售员客户表。';

-- ----------------------------
-- Table structure for yf_distribution_shop_directseller_generated_commission
-- ----------------------------
DROP TABLE IF EXISTS `yf_distribution_shop_directseller_generated_commission`;
CREATE TABLE `yf_distribution_shop_directseller_generated_commission` (
  `dgc_id` varchar(255) NOT NULL COMMENT '用户为不同店铺贡献的佣金:user_id + shop_id + level',
  `directseller_id` mediumint(8) unsigned NOT NULL COMMENT '销售员用户Id',
  `directseller_name` varchar(30) NOT NULL,
  `directseller_parent_id` mediumint(11) unsigned NOT NULL COMMENT '父用户Id',
  `directseller_parent_name` varchar(30) NOT NULL,
  `dgc_level` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '用户等级-分销层级: 1父  ,2祖父, 记录不变，如果关系更变，则增加其它记录',
  `dgc_amount` decimal(16,6) unsigned NOT NULL DEFAULT '0.000000' COMMENT '销售佣金',
  PRIMARY KEY (`dgc_id`),
  UNIQUE KEY `user_id` (`directseller_id`,`directseller_parent_id`) COMMENT '(null)',
  UNIQUE KEY `user_id_2` (`directseller_id`,`dgc_level`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺推广销售员贡献产生佣金汇总表-销售员推广销售员才产生记录。- 强调销售员与上级关系以及对上级的佣金贡献。强调销售员创造的佣金';

-- ----------------------------
-- Table structure for yf_distribution_shop_directseller_goods_common
-- ----------------------------
DROP TABLE IF EXISTS `yf_distribution_shop_directseller_goods_common`;
CREATE TABLE `yf_distribution_shop_directseller_goods_common` (
  `shop_directseller_goods_common_code` varchar(255) NOT NULL DEFAULT '' COMMENT '用户推广商品唯一ID',
  `directseller_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推广销售员id = user_id',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属店铺id',
  `common_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '产品id',
  `directseller_images_image` text COMMENT '商品图片',
  PRIMARY KEY (`shop_directseller_goods_common_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='销售员推广产品表。';

ALTER TABLE `yf_goods_common`
ADD COLUMN `cps_rate`  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '一级分佣比例' AFTER `common_shop_contract_6`,
ADD COLUMN `second_cps_rate`  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '二级分佣比例' AFTER `cps_rate`,
ADD COLUMN `third_cps_rate`  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '三级分佣比例' AFTER `second_cps_rate`;

ALTER TABLE `yf_goods_common`
ADD COLUMN `common_is_directseller`  tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否参与推广 0不参与 1参与' AFTER `third_cps_rate`;

ALTER TABLE `yf_goods_common`
CHANGE COLUMN `cps_rate` `common_cps_rate`  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '一级分佣比例' AFTER `common_shop_contract_6`,
CHANGE COLUMN `second_cps_rate` `common_second_cps_rate`  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '二级分佣比例' AFTER `common_cps_rate`,
CHANGE COLUMN `third_cps_rate` `common_third_cps_rate`  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '三级分佣比例' AFTER `common_second_cps_rate`;

ALTER TABLE `yf_goods_common`
ADD COLUMN `common_cps_commission`  decimal(10,2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '直属一级佣金-便于佣金排序' AFTER `common_is_directseller`;

ALTER TABLE `yf_order_base`
ADD COLUMN `directseller_flag` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否是分佣订单';

ALTER TABLE `yf_order_base`
ADD COLUMN `order_directseller_commission`  decimal(10,2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分销员三级总佣金';

ALTER TABLE `yf_order_base`
ADD COLUMN `directseller_p_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '推广员上级' AFTER `order_directseller_commission`,
ADD COLUMN `directseller_gp_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '推广员上级的上级' AFTER `directseller_p_id`,
ADD COLUMN `directseller_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '推广员' AFTER `directseller_gp_id`;

ALTER TABLE `yf_order_base`
ADD COLUMN `directseller_is_settlement`  tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分销佣金是否结算 1-已经结算 0-未结算' AFTER `directseller_id`;


ALTER TABLE `yf_order_goods`
ADD COLUMN `directseller_flag`  tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否参与分销';

ALTER TABLE `yf_order_goods`
ADD COLUMN `directseller_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '推广销售员-订单';

ALTER TABLE `yf_order_goods`
ADD COLUMN `directseller_is_settlement`  tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分销佣金是否结算 1-已经结算 0-未结算';

ALTER TABLE `yf_order_goods`
ADD COLUMN `directseller_commission_0`  decimal(10,2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '直属一级分佣',
ADD COLUMN `directseller_commission_1`  decimal(10,2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '直属二级分佣',
ADD COLUMN `directseller_commission_2`  decimal(10,2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '直属三级分佣';

ALTER TABLE `yf_user_info`
ADD COLUMN `user_parent_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户上级ID' AFTER `user_am`;

ALTER TABLE `yf_user_info`
ADD COLUMN `user_directseller_commission`  decimal(10,2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户获得的分销总佣金' AFTER `user_parent_id`;

ALTER TABLE `yf_user_grade`
MODIFY COLUMN `user_grade_rate`  float(5,1) NOT NULL DEFAULT 0.0 COMMENT '折扣率' AFTER `user_grade_sum`;



-- changed table `yf_base_district`

ALTER TABLE `yf_base_district`
DROP INDEX `area_parent_id`,
CHANGE COLUMN `district_id` `district_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '地区id' FIRST,
CHANGE COLUMN `district_name` `district_name` varchar(255) NOT NULL DEFAULT '' COMMENT '地区名称' AFTER `district_id`,
CHANGE COLUMN `district_parent_id` `district_parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '父id' AFTER `district_name`,
CHANGE COLUMN `district_displayorder` `district_displayorder` smallint(6) NOT NULL DEFAULT '0' COMMENT '排序' AFTER `district_parent_id`,
CHANGE COLUMN `district_region` `district_region` varchar(50) NOT NULL DEFAULT '' COMMENT '区域名称 - 华北、东北、华东、华南、华中、西南、西北、港澳台、海外' AFTER `district_displayorder`,
CHANGE COLUMN `district_is_leaf` `district_is_leaf` tinyint(1) NOT NULL DEFAULT '1' COMMENT '无子类' AFTER `district_region`,
CHANGE COLUMN `district_is_level` `district_is_level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '等级' AFTER `district_is_leaf`,
ADD KEY `upid` (`district_parent_id`,`district_displayorder`) COMMENT '(null)';

-- changed table `yf_cart`

ALTER TABLE `yf_cart`
DROP INDEX `user_id`,
ADD KEY `user_id` (`user_id`) COMMENT '(null)';

-- changed table `yf_distribution_shop_directseller`

ALTER TABLE `yf_distribution_shop_directseller`
CHANGE COLUMN `directseller_enable` `directseller_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否审核通过: 0-待审核;  1-通过' AFTER `directseller_shop_name`;

-- changed table `yf_express`

ALTER TABLE `yf_express`
CHANGE COLUMN `express_displayorder` `express_displayorder` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否常用0否1是' AFTER `express_status`;

-- changed table `yf_goods_common`

ALTER TABLE `yf_goods_common`
CHANGE COLUMN `shop_self_support` `shop_self_support` tinyint(1) NOT NULL DEFAULT '1' AFTER `goods_id`,
CHANGE COLUMN `common_is_return` `common_is_return` tinyint(1) NOT NULL DEFAULT '1' AFTER `common_invoices`,
CHANGE COLUMN `common_is_recommend` `common_is_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品推荐' AFTER `common_formatid_bottom`,
CHANGE COLUMN `common_virtual_date` `common_virtual_date` int(10) NOT NULL COMMENT '虚拟商品有效期' AFTER `common_is_virtual`,
ADD COLUMN `product_lock_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '店铺必须分销标记  1:不可删除   0：可以删除' AFTER `common_is_directseller`,
ADD COLUMN `product_agent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '代理商id-可更改，该店铺下级都属于该代理商。' AFTER `product_lock_flag`;

-- changed table `yf_goods_property`

ALTER TABLE `yf_goods_property`
DROP INDEX `catid`,
ADD KEY `catid` (`property_format`) COMMENT '(null)';

-- changed table `yf_groupbuy_base`

ALTER TABLE `yf_groupbuy_base`
CHANGE COLUMN `groupbuy_image` `groupbuy_image` varchar(255) NOT NULL COMMENT '团购图片' AFTER `groupbuy_area_id`,
CHANGE COLUMN `groupbuy_image_rec` `groupbuy_image_rec` varchar(255) NOT NULL COMMENT '团购推荐位图片' AFTER `groupbuy_image`;

-- changed table `yf_invoice`

ALTER TABLE `yf_invoice`
CHANGE COLUMN `invoice_state` `invoice_state` enum('1','2','3') CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '1普通发票2电子发票3增值税发票' AFTER `user_id`;

-- changed table `yf_log_action`

ALTER TABLE `yf_log_action`
DROP INDEX `log_date`,
DROP INDEX `player_id`,
ADD KEY `log_date` (`log_date`) COMMENT '(null)',
ADD KEY `player_id` (`user_id`) COMMENT '(null)';

-- changed table `yf_message`

ALTER TABLE `yf_message`
CHANGE COLUMN `message_type` `message_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '消息类型买家1订单信息3账户信息4其他' AFTER `message_user_name`;

-- changed table `yf_number_seq`

ALTER TABLE `yf_number_seq`
DROP INDEX `prefix`,
ADD UNIQUE KEY `prefix` (`prefix`) COMMENT '(null)';

-- changed table `yf_order_base`

ALTER TABLE `yf_order_base`
CHANGE COLUMN `order_shipping_express_id` `order_shipping_express_id` tinyint(1) NOT NULL DEFAULT '0' COMMENT '配送公司ID' AFTER `order_shipping_time`,
CHANGE COLUMN `order_goods_amount` `order_goods_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品总价格(不包含运费)' AFTER `order_invoice_id`,
CHANGE COLUMN `order_payment_amount` `order_payment_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '应付金额（商品实际支付金额 + 运费）' AFTER `order_goods_amount`,
CHANGE COLUMN `order_discount_fee` `order_discount_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠价格' AFTER `order_payment_amount`,
ADD COLUMN `redpacket_code` varchar(32) NOT NULL COMMENT '红包编码' AFTER `voucher_code`,
ADD COLUMN `redpacket_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '红包面额' AFTER `redpacket_code`,
ADD COLUMN `order_rpt_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '红包抵扣订单金额' AFTER `redpacket_price`,
CHANGE COLUMN `order_refund_status` `order_refund_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '退款状态:0是无退款,1是退款中,2是退款完成' AFTER `order_rpt_price`,
CHANGE COLUMN `order_return_status` `order_return_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '退货状态:0是无退货,1是退货中,2是退货完成' AFTER `order_refund_status`,
CHANGE COLUMN `order_refund_amount` `order_refund_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '退款金额' AFTER `order_return_status`,
CHANGE COLUMN `order_return_num` `order_return_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '退货数量' AFTER `order_refund_amount`,
CHANGE COLUMN `order_from` `order_from` enum('1','2') NOT NULL DEFAULT '1' COMMENT '手机端' AFTER `order_return_num`,
CHANGE COLUMN `order_commission_fee` `order_commission_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '交易佣金' AFTER `order_from`,
CHANGE COLUMN `order_commission_return_fee` `order_commission_return_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '交易佣金退款' AFTER `order_commission_fee`,
CHANGE COLUMN `order_is_virtual` `order_is_virtual` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '虚拟订单' AFTER `order_commission_return_fee`,
CHANGE COLUMN `order_virtual_code` `order_virtual_code` varchar(100) NOT NULL DEFAULT '' COMMENT '虚拟商品兑换码' AFTER `order_is_virtual`,
CHANGE COLUMN `order_virtual_use` `order_virtual_use` tinyint(1) NOT NULL DEFAULT '0' COMMENT '虚拟商品是否使用 0-未使用 1-已使用' AFTER `order_virtual_code`,
CHANGE COLUMN `order_shop_hidden` `order_shop_hidden` tinyint(1) NOT NULL DEFAULT '0' COMMENT '卖家删除' AFTER `order_virtual_use`,
CHANGE COLUMN `order_buyer_hidden` `order_buyer_hidden` tinyint(1) NOT NULL DEFAULT '0' COMMENT '买家删除' AFTER `order_shop_hidden`,
CHANGE COLUMN `order_cancel_identity` `order_cancel_identity` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单取消者身份   1-买家 2-卖家 3-系统' AFTER `order_buyer_hidden`,
CHANGE COLUMN `order_cancel_reason` `order_cancel_reason` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '订单取消原因' AFTER `order_cancel_identity`,
CHANGE COLUMN `order_cancel_date` `order_cancel_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '订单取消时间' AFTER `order_cancel_reason`,
CHANGE COLUMN `order_shop_benefit` `order_shop_benefit` varchar(255) NOT NULL DEFAULT '' COMMENT '店铺优惠' AFTER `order_cancel_date`,
CHANGE COLUMN `chain_id` `chain_id` int(11) NOT NULL COMMENT '门店id' AFTER `order_shop_benefit`,
CHANGE COLUMN `order_seller_message` `order_seller_message` varchar(255) NOT NULL DEFAULT '' COMMENT '卖家给卖家留言' AFTER `chain_id`,
ADD COLUMN `order_settlement_time` datetime NOT NULL COMMENT '订单结算时间' AFTER `order_seller_message`,
ADD COLUMN `order_is_settlement` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '订单是否结算 1-已结算 0-未结算' AFTER `order_settlement_time`,
ADD COLUMN `shop_distributor_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分销商' AFTER `order_is_settlement`,
ADD COLUMN `order_distribution_seller_type` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT 'SO订单分销类型 1:直销(E)  2:分销代销转发销售(P, SP)' AFTER `shop_distributor_id`,
ADD COLUMN `order_distribution_buyer_type` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT 'PO订单类型 1:购买(E采购，SP:代销采购)  2:分销采购,代客下单 (P开头)' AFTER `order_distribution_seller_type`,
ADD COLUMN `order_source_id` varchar(255) NOT NULL DEFAULT '0' COMMENT '源订单Id（P开头）:SP开头订单对应的P开头订单' AFTER `order_distribution_buyer_type`,
CHANGE COLUMN `directseller_flag` `directseller_flag` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '销售员推广' AFTER `order_source_id`,
CHANGE COLUMN `directseller_id` `directseller_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '推广销售员-订单' AFTER `directseller_flag`,
CHANGE COLUMN `directseller_p_id` `directseller_p_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推广员的上级' AFTER `directseller_id`,
CHANGE COLUMN `directseller_gp_id` `directseller_gp_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推广员上级的上级' AFTER `directseller_p_id`;

-- changed table `yf_order_goods`

ALTER TABLE `yf_order_goods`
DROP INDEX `order_id`,
CHANGE COLUMN `goods_price` `goods_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品价格（商品原价，goods_base中的商品价格未参加任何活动的价格）' AFTER `order_spec_info`,
CHANGE COLUMN `order_goods_amount` `order_goods_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品金额 （实付金额）= order_goods_payment_amount* order_goods_num' AFTER `order_goods_returnnum`,
CHANGE COLUMN `order_goods_discount_fee` `order_goods_discount_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠金额 = （商品原价-实付金额）*商品数量   如果优惠价格为负数说明最后的成交价比原来的价格高' AFTER `order_goods_amount`,
CHANGE COLUMN `order_goods_commission` `order_goods_commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单商品的佣金 (总)' AFTER `order_goods_point_fee`,
CHANGE COLUMN `order_goods_time` `order_goods_time` datetime NOT NULL COMMENT '时间' AFTER `goods_refund_status`,
ADD KEY `order_id` (`order_id`) COMMENT '(null)';

-- changed table `yf_order_return`

ALTER TABLE `yf_order_return`
ADD COLUMN `return_rpt_cash` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '退还红包的钱' AFTER `return_cash`,
CHANGE COLUMN `return_shop_time` `return_shop_time` datetime NOT NULL COMMENT '商家处理时间' AFTER `return_rpt_cash`,
CHANGE COLUMN `return_shop_message` `return_shop_message` varchar(300) NOT NULL COMMENT '商家备注' AFTER `return_shop_time`,
CHANGE COLUMN `return_finish_time` `return_finish_time` datetime NOT NULL COMMENT '退款完成时间' AFTER `return_shop_message`,
CHANGE COLUMN `return_commision_fee` `return_commision_fee` decimal(8,2) NOT NULL COMMENT '退还佣金' AFTER `return_finish_time`,
CHANGE COLUMN `return_platform_message` `return_platform_message` varchar(255) NOT NULL COMMENT '平台留言' AFTER `return_commision_fee`,
CHANGE COLUMN `return_goods_return` `return_goods_return` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否需要退货 0-不需要，1-需要' AFTER `return_platform_message`;

-- changed table `yf_order_settlement`

ALTER TABLE `yf_order_settlement`
ADD COLUMN `os_redpacket_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '红包金额' AFTER `os_commis_return_amount`,
ADD COLUMN `os_redpacket_return_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '退还红包' AFTER `os_redpacket_amount`,
CHANGE COLUMN `os_shop_cost_amount` `os_shop_cost_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '店铺促销活动费用' AFTER `os_redpacket_return_amount`,
CHANGE COLUMN `os_amount` `os_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '应结金额' AFTER `os_shop_cost_amount`,
CHANGE COLUMN `os_datetime` `os_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '生成结算单日期' AFTER `os_amount`,
CHANGE COLUMN `os_date` `os_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '结算单年月份' AFTER `os_datetime`,
CHANGE COLUMN `os_state` `os_state` enum('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1默认(已出账)2店家已确认3平台已审核4结算完成' AFTER `os_date`,
CHANGE COLUMN `os_pay_date` `os_pay_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '付款日期' AFTER `os_state`,
CHANGE COLUMN `os_pay_content` `os_pay_content` varchar(200) NOT NULL DEFAULT '' COMMENT '支付备注' AFTER `os_pay_date`,
CHANGE COLUMN `shop_id` `shop_id` int(10) unsigned NOT NULL COMMENT '店铺ID' AFTER `os_pay_content`,
CHANGE COLUMN `shop_name` `shop_name` varchar(50) NOT NULL DEFAULT '' COMMENT '店铺名' AFTER `shop_id`,
CHANGE COLUMN `os_order_type` `os_order_type` tinyint(1) NOT NULL COMMENT '结算订单类型 1-虚拟订单 2-实物订单' AFTER `shop_name`;

-- changed table `yf_seller_base`

ALTER TABLE `yf_seller_base`
ADD COLUMN `seller_group_id` int(10) unsigned NOT NULL COMMENT '卖家组ID' AFTER `user_id`,
CHANGE COLUMN `seller_is_admin` `seller_is_admin` tinyint(3) unsigned NOT NULL COMMENT '是否管理员(0-不是 1-是)' AFTER `seller_group_id`,
CHANGE COLUMN `seller_login_time` `seller_login_time` datetime NOT NULL COMMENT '最后登录时间' AFTER `seller_is_admin`;


-- changed table `yf_shop_base`

ALTER TABLE `yf_shop_base`
ADD COLUMN `shop_parent_id` int(11) NOT NULL COMMENT '上级店铺id-创建店铺决定，所属分销商-不可更改！ 佣金公平性考虑' AFTER `shop_stamp`,
ADD COLUMN `shop_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '店铺类型: 1-卖家店铺; 2:供应商店铺' AFTER `shop_parent_id`;

-- changed table `yf_shop_class_bind`

ALTER TABLE `yf_shop_class_bind`
CHANGE COLUMN `commission_rate` `commission_rate` decimal(4,2) NOT NULL DEFAULT '0.00' COMMENT '百分比' AFTER `product_class_id`;

-- changed table `yf_sub_site`


-- changed table `yf_transport_item`

ALTER TABLE `yf_transport_item`
DROP INDEX `temp_id`,
ADD KEY `temp_id` (`transport_type_id`,`logistics_type`) COMMENT '(null)';

-- changed table `yf_transport_type`

ALTER TABLE `yf_transport_type`
DROP INDEX `user_id`,
ADD KEY `user_id` (`shop_id`) COMMENT '(null)';

-- changed table `yf_user_base`

ALTER TABLE `yf_user_base`
DROP INDEX `user_account`,
CHANGE COLUMN `user_delete` `user_delete` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否被封禁，0：未封禁，1：封禁' AFTER `user_key`,
ADD COLUMN `user_parent_id` int(10) unsigned NOT NULL COMMENT '上级用户id - 注册决定，不可更改，推广公平性考虑' AFTER `user_login_ip`,
ADD UNIQUE KEY `user_account` (`user_account`) COMMENT '(null)';

-- changed table `yf_user_favorites_goods`

ALTER TABLE `yf_user_favorites_goods`
CHANGE COLUMN `favorites_goods_time` `favorites_goods_time` datetime NOT NULL COMMENT '收藏时间' AFTER `goods_id`;

-- changed table `yf_user_footprint`

ALTER TABLE `yf_user_footprint`
DROP INDEX `user_id`,
ADD KEY `user_id` (`user_id`,`common_id`) COMMENT '(null)';

-- changed table `yf_user_grade`

ALTER TABLE `yf_user_grade`
CHANGE COLUMN `user_grade_rate` `user_grade_rate` float(3,1) NOT NULL DEFAULT '0.0' COMMENT '折扣率' AFTER `user_grade_sum`;

-- changed table `yf_user_info`

ALTER TABLE `yf_user_info`
CHANGE COLUMN `user_sex` `user_sex` tinyint(1) NOT NULL DEFAULT '2' COMMENT '用户性别 0女 1男 2保密' AFTER `user_name`,
CHANGE COLUMN `user_am` `user_am` varchar(500) NOT NULL COMMENT '系统公告查看过id' AFTER `user_ww`;

-- changed table `yf_user_message`

ALTER TABLE `yf_user_message`
CHANGE COLUMN `user_message_pid` `user_message_pid` int(10) NOT NULL COMMENT '回复消息上级id' AFTER `user_message_content`,
CHANGE COLUMN `message_islook` `message_islook` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否读取0未1读取' AFTER `user_message_pid`,
CHANGE COLUMN `user_message_time` `user_message_time` datetime NOT NULL COMMENT '发送时间' AFTER `message_islook`;

-- new table `yf_distribution_base_config`

CREATE TABLE `yf_distribution_base_config` (
`config_key` varchar(50) NOT NULL COMMENT '设置key',
`config_value` varchar(10000) NOT NULL DEFAULT '' COMMENT '值',
`config_type` varchar(20) NOT NULL DEFAULT '' COMMENT '所属分类',
`config_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态值，1可能，0不可用',
`config_comment` varchar(255) NOT NULL DEFAULT '' COMMENT '释注',
`config_datatype` enum('string','json','number','dot') NOT NULL DEFAULT 'string' COMMENT '数据类型',
`config_name` varchar(50) NOT NULL DEFAULT '' COMMENT '设置名称',
`config_formater` varchar(255) NOT NULL DEFAULT '' COMMENT '输出格式-分别为key\\value两个输出',
`config_category` enum('系统参数','基础参数','扩展参数') NOT NULL DEFAULT '基础参数' COMMENT '设置类型-用来看数据，无使用价值',
PRIMARY KEY (`config_key`),
UNIQUE KEY `config_type` (`config_category`,`config_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='此表废弃--系统参数设置表';

-- new table `yf_distribution_goods_common`

CREATE TABLE `yf_distribution_goods_common` (
  `common_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `product_lock_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '店铺必须分销标记  1:不可删除   0：可以删除',
  `product_agent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '代理商id-可更改，该店铺下级都属于该代理商。',
  `product_distributor_flag` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为分销商品 0-自有商品',
  `supply_shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品来源-供应商店铺id',
  `product_is_allow_update` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否可以修改内容',
  `product_is_allow_price` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否可以修改价格-可以取消',
  `product_is_behalf_delivery` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否代发货',
  `common_parent_id` int(10) NOT NULL DEFAULT '0' COMMENT '分销原产品',
  PRIMARY KEY (`common_id`)
) ENGINE=InnoDB AUTO_INCREMENT=483 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='分销商品公共内容表';

-- new table `yf_distribution_order_base`

CREATE TABLE `yf_distribution_order_base` (
`order_id` varchar(50) NOT NULL COMMENT '订单号',
`shop_distributor_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分销商',
`order_distribution_seller_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'SO订单分销类型 1:直销(E)  2:分销代销转发销售(P, SP)',
`order_distribution_buyer_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'PO订单类型 1:购买(E采购，SP:代销采购)  2:分销采购,代客下单 (P开头)',
`order_source_id` varchar(50) NOT NULL DEFAULT '0' COMMENT '源订单Id（P开头）:SP开头订单对应的P开头订单',
`directseller_flag` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '销售员推广',
`directseller_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推广销售员-订单',
`directseller_p_id` int(10) NOT NULL DEFAULT '0' COMMENT '推官员上级',
`directseller_gp_id` int(10) NOT NULL,
PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='此表废弃--订单详细信息';

-- new table `yf_distribution_order_goods`

CREATE TABLE `yf_distribution_order_goods` (
`order_goods_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
`order_id` varchar(50) NOT NULL COMMENT '订单id',
`goods_id` int(10) NOT NULL COMMENT '商品id',
`common_id` int(10) NOT NULL DEFAULT '0' COMMENT '商品common_id',
`directseller_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推广销售员',
`directseller_p_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推官员上级',
`directseller_gp_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推官员上级',
PRIMARY KEY (`order_goods_id`),
KEY `order_id` (`order_id`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='此表废弃--订单分销商品关系表';

-- new table `yf_distribution_shop_agent`

CREATE TABLE `yf_distribution_shop_agent` (
`shop_agent_id` int(10) unsigned NOT NULL DEFAULT '0',
`shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属店铺id',
`agent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分销商id = shop_id',
`agent_parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级id',
PRIMARY KEY (`shop_agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺代理商表。';

-- new table `yf_distribution_shop_agent_generated_commission`

CREATE TABLE `yf_distribution_shop_agent_generated_commission` (
`agc_id` varchar(255) NOT NULL COMMENT '用户为不同店铺贡献的佣金:user_id + shop_id + level',
`directseller_id` mediumint(8) unsigned NOT NULL COMMENT '销售员用户Id',
`directseller_name` varchar(30) NOT NULL,
`directseller_parent_id` mediumint(11) NOT NULL COMMENT '父用户Id',
`directseller_parent_name` varchar(30) NOT NULL,
`agc_level` tinyint(4) NOT NULL DEFAULT '1' COMMENT '用户等级-分销层级: 1父  ,2祖父, 记录不变，如果关系更变，则增加其它记录',
PRIMARY KEY (`agc_id`),
UNIQUE KEY `user_id` (`directseller_id`,`directseller_parent_id`) COMMENT '(null)',
UNIQUE KEY `user_id_2` (`directseller_id`,`agc_level`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理商贡献产生佣金汇总表-代理发展下级代理商才产生记录。- 强调代理商对上级的佣金贡献。强调代理创造的佣金';

-- new table `yf_distribution_shop_agent_level`

CREATE TABLE `yf_distribution_shop_agent_level` (
`agent_level_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '代理商等级id',
`agent_leve_name` varchar(50) NOT NULL DEFAULT '' COMMENT '等级名称',
`agent_leve_discount_rate` decimal(6,2) NOT NULL DEFAULT '0.00' COMMENT '等级折扣',
`agent_leve_freeshipping` varchar(255) NOT NULL DEFAULT '0' COMMENT '包邮设置-开启后该等级代理商代销或采购所有商品全部免运费',
`agent_leve_order` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
`shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店铺Id',
PRIMARY KEY (`agent_level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理商等级表';

-- new table `yf_distribution_shop_base`

CREATE TABLE `yf_distribution_shop_base` (
`shop_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '店铺id',
`shop_parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级店铺id-创建店铺决定，所属分销商-不可更改！ 佣金公平性考虑',
`shop_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '店铺类型: 1-卖家店铺; 2:供应商店铺',
PRIMARY KEY (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='此表废弃--店铺基础信息表-分销店铺来源关系记录(上级)，特殊情况下此记录可以改变。';

-- new table `yf_distribution_shop_commission`

CREATE TABLE `yf_distribution_shop_commission` (
`shop_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '店铺id',
`commission_amount` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '佣金总额',
`commission_distributor_amount_0` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '本店分销佣金',
`commission_distributor_amount_1` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '一级分销佣金',
`commission_distributor_amount_2` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '二级分销佣金',
PRIMARY KEY (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收益表-代理/分销/推广';

-- new table `yf_distribution_shop_directseller_level`

CREATE TABLE `yf_distribution_shop_directseller_level` (
`directseller_level_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '分销商等级id',
`directseller_leve_name` varchar(50) NOT NULL DEFAULT '' COMMENT '等级名称',
`directseller_leve_discount_rate` decimal(6,2) NOT NULL DEFAULT '0.00' COMMENT '等级折扣',
`directseller_leve_freeshipping` varchar(255) NOT NULL DEFAULT '0' COMMENT '包邮设置-开启后该等级分销代销或采购所有商品全部免运费',
`directseller_leve_order` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
`shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店铺Id',
PRIMARY KEY (`directseller_level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='销售员等级表';

-- new table `yf_distribution_shop_distributor`

CREATE TABLE `yf_distribution_shop_distributor` (
`shop_distributor_id` int(10) unsigned NOT NULL DEFAULT '0',
`shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属店铺id',
`distributor_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分销商id = shop_id',
`distributor_parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父id - 加入此分销店铺的时候的来源-为不同供应商发展自己的分销商使用。如果存在供应商市场，此字段可以放弃',
`distributor_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否审核通过: 0-待审核;  1-通过',
PRIMARY KEY (`shop_distributor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺分销者表。- 不同供应商，可以具有同一个分销商';

-- new table `yf_distribution_shop_distributor_generated_commission`

CREATE TABLE `yf_distribution_shop_distributor_generated_commission` (
`fgc_id` varchar(255) NOT NULL COMMENT '用户为不同店铺贡献的佣金:user_id + shop_id + level',
`distributor_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分销商Id = shop_id',
`distributor_name` varchar(30) NOT NULL COMMENT '分销店铺名称',
`distributor_parent_id` mediumint(11) unsigned NOT NULL COMMENT '父用户Id',
`distributor_parent_name` varchar(30) NOT NULL,
`fgc_level` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '分销层级: 1父  ,2祖父, 记录不变，如果关系更变，则增加其它记录',
PRIMARY KEY (`fgc_id`),
UNIQUE KEY `user_id` (`distributor_id`,`distributor_parent_id`) COMMENT '(null)',
UNIQUE KEY `user_id_2` (`distributor_id`,`fgc_level`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分销商贡献产生佣金汇总表-分销商发展下级分销商才产生记录。- 强调分销商对上级的佣金贡献。强调分销商创造的佣金 fgc=distributor_generated_commission';

-- new table `yf_distribution_shop_distributor_level`

CREATE TABLE `yf_distribution_shop_distributor_level` (
`distributor_level_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '分销商等级id',
`distributor_leve_name` varchar(50) NOT NULL DEFAULT '' COMMENT '等级名称',
`distributor_leve_discount_rate` decimal(6,2) NOT NULL DEFAULT '0.00' COMMENT '等级折扣',
`distributor_leve_freeshipping` varchar(255) NOT NULL DEFAULT '0' COMMENT '包邮设置-开启后该等级分销代销或采购所有商品全部免运费',
`distributor_leve_order` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
`shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店铺Id',
PRIMARY KEY (`distributor_level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分销商等级表';

-- new table `yf_distribution_shop_team`

CREATE TABLE `yf_distribution_shop_team` (
`team_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '注释名称(in=>88,23) :0-不返点; 1-百分比返点 percentage; 2-等级差返点difference',
`team_name` varchar(255) NOT NULL COMMENT '团队名称',
`team_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '团队类型： 代理团',
`team_image` varchar(255) NOT NULL COMMENT '团队头像',
`distributor_level_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '团员等级',
`team_chat_flag` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '团队群聊',
`team_verify_flag` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '入团审核',
`team_leader_id` int(10) NOT NULL DEFAULT '0' COMMENT '设置团长',
`team_performance_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '团队业绩:是否允许普通成员查看业绩',
`team_split_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '返点模式: 0-不返点; 1-百分比返点 percentage; 2:等级差返点difference',
`team_invisible` tinyint(1) NOT NULL DEFAULT '0' COMMENT '秘密团队:开启后，用户无法在平台中搜索到该团队',
`team_permit` varchar(255) NOT NULL DEFAULT '0' COMMENT '授权团长(DOT):供应商可授权团长调整团员的代理等级，授权后，团长可在app内修改团员的代理等级',
`shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属供应商',
`team_quantity` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '累计成交量',
PRIMARY KEY (`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='供应商团队表';

-- new table `yf_distribution_shop_team_member`

CREATE TABLE `yf_distribution_shop_team_member` (
`team_member_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '团队成员id',
`team_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '团队id',
`shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店铺id',
PRIMARY KEY (`team_member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='供应商团队成员表';

-- new table `yf_distribution_shop_type`

CREATE TABLE `yf_distribution_shop_type` (
`shop_type_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '店铺id',
`shop_type_name` varchar(255) NOT NULL DEFAULT '' COMMENT '类型名称',
PRIMARY KEY (`shop_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='此表废弃--店铺类型';

-- new table `yf_distribution_user_base`

CREATE TABLE `yf_distribution_user_base` (
`user_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
`user_parent_id` int(10) NOT NULL DEFAULT '0' COMMENT '上级用户id - 注册决定，不可更改，推广公平性考虑',
PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='此表废弃--用户基础信息表-用户来源关系记录，此记录不可以改变。';

-- new table `yf_distribution_user_commission`

CREATE TABLE `yf_distribution_user_commission` (
`user_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '店铺id',
`commission_amount` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '佣金总额',
`commission_directseller_amount_0` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '获取推广销售佣金',
`commission_directseller_amount_1` decimal(15,6) NOT NULL,
`commission_directseller_amount_2` decimal(15,6) NOT NULL,
`commission_buy_amount_0` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '消费佣金',
`commission_buy_amount_1` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '消费佣金',
`commission_buy_amount_2` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '消费佣金',
`commission_click_amount_0` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '本店流量佣金',
`commission_click_amount_1` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '一级流量佣金',
`commission_click_amount_2` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '二级流量佣金',
`commission_reg_amount_0` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '本店注册佣金',
`commission_reg_amount_1` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '一级注册佣金',
`commission_reg_amount_2` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '二级注册佣金',
PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='推广收益表-用户赚取汇总';

-- new table `yf_goods_property_map_90`

CREATE TABLE `yf_goods_property_map_90` (
`goods_property_map_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
`common_id` int(10) unsigned NOT NULL COMMENT '商品Id',
`property_id_148` varchar(255) DEFAULT NULL,
PRIMARY KEY (`goods_property_map_id`),
KEY `common_id` (`common_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- new table `yf_goods_property_map_91`

CREATE TABLE `yf_goods_property_map_91` (
`goods_property_map_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
`common_id` int(10) unsigned NOT NULL COMMENT '商品Id',
`property_id_150` varchar(255) DEFAULT NULL,
PRIMARY KEY (`goods_property_map_id`),
KEY `common_id` (`common_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- new table `yf_redpacket_base`

CREATE TABLE `yf_redpacket_base` (
`redpacket_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '红包编号',
`redpacket_code` varchar(32) NOT NULL COMMENT '红包编码',
`redpacket_t_id` int(11) NOT NULL COMMENT '红包模版编号',
`redpacket_title` varchar(50) NOT NULL COMMENT '红包标题',
`redpacket_desc` varchar(255) NOT NULL COMMENT '红包描述',
`redpacket_start_date` datetime NOT NULL COMMENT '红包有效期开始时间',
`redpacket_end_date` datetime NOT NULL COMMENT '红包有效期结束时间',
`redpacket_price` int(11) NOT NULL COMMENT '红包面额',
`redpacket_t_orderlimit` decimal(10,2) NOT NULL COMMENT '红包使用时的订单限额',
`redpacket_state` tinyint(4) NOT NULL COMMENT '红包状态(1-未用,2-已用,3-过期)',
`redpacket_active_date` datetime NOT NULL COMMENT '红包发放日期',
`redpacket_owner_id` int(11) NOT NULL COMMENT '红包所有者id',
`redpacket_owner_name` varchar(50) NOT NULL COMMENT '红包所有者名称',
`redpacket_order_id` varchar(500) NOT NULL COMMENT '使用该红包的订单编号',
PRIMARY KEY (`redpacket_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='红包表';

-- new table `yf_redpacket_template`

CREATE TABLE `yf_redpacket_template` (
`redpacket_t_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '红包模版编号',
`redpacket_t_type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '红包类型，1-新人注册红包，2-普通红包，默认2',
`redpacket_t_title` varchar(50) NOT NULL COMMENT '红包模版名称',
`redpacket_t_desc` varchar(255) NOT NULL COMMENT '红包模版描述',
`redpacket_t_start_date` datetime NOT NULL COMMENT '红包模版有效期开始时间',
`redpacket_t_end_date` datetime NOT NULL COMMENT '红包模版有效期结束时间',
`redpacket_t_price` int(10) NOT NULL COMMENT '红包模版面额',
`redpacket_t_orderlimit` decimal(10,2) NOT NULL COMMENT '红包使用时的消费限额',
`redpacket_t_state` tinyint(4) NOT NULL DEFAULT '1' COMMENT '红包模版状态(1-有效,2-失效)',
`redpacket_t_total` int(10) NOT NULL COMMENT '模版可发放的红包总数',
`redpacket_t_giveout` int(10) NOT NULL COMMENT '模版已发放的红包数量',
`redpacket_t_used` int(10) NOT NULL COMMENT '模版已经使用过的红包',
`redpacket_t_add_date` datetime NOT NULL COMMENT '模版的创建时间',
`redpacket_t_update_date` datetime NOT NULL COMMENT '模版的最后修改时间',
`redpacket_t_points` int(10) NOT NULL DEFAULT '0' COMMENT '兑换所需积分',
`redpacket_t_eachlimit` int(10) NOT NULL DEFAULT '1' COMMENT '每人限领张数',
`redpacket_t_user_grade_limit` tinyint(4) NOT NULL DEFAULT '1' COMMENT '领取红包的用户等级限制',
`redpacket_t_img` varchar(200) NOT NULL COMMENT '红包图片',
`redpacket_t_access_method` tinyint(1) NOT NULL DEFAULT '1' COMMENT '红包领取方式，1-积分兑换(默认)，2-卡密兑换，3-免费领取',
`redpacket_t_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推荐状态，0-为不推荐，1-推荐',
PRIMARY KEY (`redpacket_t_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='红包模版表';

-- new table `yf_seller_group`

CREATE TABLE `yf_seller_group` (
`group_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '卖家组编号',
`group_name` varchar(50) NOT NULL COMMENT '组名',
`limits` text NOT NULL COMMENT '权限',
`smt_limits` text NOT NULL COMMENT '消息权限范围',
`shop_id` int(10) unsigned NOT NULL COMMENT '店铺编号',
PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='卖家用户组表';

-- new table `yf_shop_customer`

CREATE TABLE `yf_shop_customer` (
`shop_customer_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '客户Id',
`customer_number` varchar(32) NOT NULL DEFAULT '' COMMENT '客户编号',
`customer_name` varchar(20) NOT NULL DEFAULT '' COMMENT '客户名称',
`customer_type_id` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '客户类别',
`customer_level_id` smallint(4) unsigned NOT NULL DEFAULT '1' COMMENT '客户等级',
`customer_dif_money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '期初往来余额',
`customer_begin_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '余额日期',
`customer_amount_money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '期初应收款',
`customer_period_money` decimal(11,2) NOT NULL COMMENT '期初预收款',
`customer_tax_rate` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '税率',
`customer_remark` varchar(200) NOT NULL DEFAULT '' COMMENT '备注消息',
`user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
`shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店铺id',
`chain_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店id',
PRIMARY KEY (`shop_customer_id`),
KEY `customer_level_id` (`customer_level_id`),
KEY `erpbuilder_base_customer_ibfk_1` (`customer_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺客户信息表';



# Changed Tables

-- changed table `yf_distribution_shop_directseller`

ALTER TABLE `yf_distribution_shop_directseller`
  CHANGE COLUMN `directseller_enable` `directseller_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否审核通过: 0-待审核  1-通过' AFTER `directseller_shop_name`;

-- changed table `yf_goods_common`

ALTER TABLE `yf_goods_common`
  CHANGE COLUMN `common_virtual_date` `common_virtual_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '虚拟商品有效期' AFTER `common_is_virtual`;

-- changed table `yf_member_consume_log`

ALTER TABLE `yf_member_consume_log`
  ;

-- changed table `yf_order_base`

ALTER TABLE `yf_order_base`
  CHANGE COLUMN `directseller_flag` `directseller_flag` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是分佣订单' AFTER `order_seller_message`,
  CHANGE COLUMN `directseller_p_id` `directseller_p_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推广员上级' AFTER `directseller_flag`,
  CHANGE COLUMN `directseller_id` `directseller_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推广员' AFTER `directseller_gp_id`,
  CHANGE COLUMN `directseller_is_settlement` `directseller_is_settlement` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '分销佣金是否结算 1-已经结算 0-未结算' AFTER `directseller_id`;

-- changed table `yf_order_goods`

ALTER TABLE `yf_order_goods`
  CHANGE COLUMN `order_goods_discount_fee` `order_goods_discount_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠金额 = （商品原价-实付金额）*商品数量' AFTER `order_goods_amount`;

-- changed table `yf_order_return`

ALTER TABLE `yf_order_return`
  CHANGE COLUMN `return_rpt_cash` `return_rpt_cash` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '退还平台红包金额' AFTER `return_cash`;

-- changed table `yf_user_grade`

ALTER TABLE `yf_user_grade`
  CHANGE COLUMN `user_grade_rate` `user_grade_rate` float(5,1) NOT NULL DEFAULT '0.0' COMMENT '折扣率' AFTER `user_grade_sum`;


ALTER TABLE `yf_shop_company`
ADD COLUMN `company_apply_image` varchar(1024) NOT NULL COMMENT '申请扩展图片字段';


-- 城市分站
ALTER TABLE `yf_adv_page_settings` ADD COLUMN `sub_site_id` mediumint(4) NOT NULL DEFAULT '0' COMMENT '所属分站Id:0-总站';

ALTER TABLE `yf_shop_base`
ADD COLUMN `district_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '所在地，使用最后一级分类';

ALTER TABLE `yf_goods_common`
ADD COLUMN `district_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '所在地,从店铺中同步，冗余检索使用';

ALTER TABLE `yf_order_base` ADD COLUMN `district_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '所在地,从店铺中同步，冗余检索使用';
ALTER TABLE yf_shop_renewal ADD  COLUMN `district_id` MEDIUMINT(8) NOT NULL DEFAULT '0' COMMENT '所在地，使用最后一级分类';
ALTER TABLE yf_order_settlement ADD  COLUMN `district_id` MEDIUMINT(8) NOT NULL DEFAULT '0' COMMENT '地区id,0表示全国';


-- ALTER TABLE `yf_distribution_goods_common` ADD COLUMN `product_is_allow_update` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否可以修改内容';
-- ALTER TABLE `yf_distribution_goods_common` ADD COLUMN `product_is_allow_price` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否可以修改价格-可以取消';
-- ALTER TABLE `yf_distribution_goods_common` ADD COLUMN `product_is_behalf_delivery` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否代发货';
-- ALTER TABLE `yf_distribution_goods_common` ADD COLUMN `common_parent_id` int(10) NOT NULL DEFAULT '0' COMMENT '分销原产品';


CREATE TABLE `yf_distribution_goods_base` (
  `goods_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `goods_recommended_price` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '建议零售价-可以取消',
  `goods_recommended_min_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '建议最低零售价',
  `goods_recommended_max_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '建议最高零售价',
  PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1830 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='分销商品表';


-- ALTER TABLE `yf_distribution_goods_base` ADD COLUMN `goods_recommended_price` decimal(10,2) NOT NULL DEFAULT '0' COMMENT '建议零售价-可以取消';
-- ALTER TABLE `yf_distribution_goods_base` ADD COLUMN `goods_recommended_min_price` decimal(10,2) NOT NULL DEFAULT '0' COMMENT '建议最低零售价';
-- ALTER TABLE `yf_distribution_goods_base` ADD COLUMN `goods_recommended_max_price` decimal(10,2) NOT NULL DEFAULT '0' COMMENT '建议最高零售价';
-- ALTER TABLE `yf_distribution_goods_base` ADD COLUMN `goods_min_range_price` decimal(4,2) NOT NULL DEFAULT '0' COMMENT '销售价格范围:最小价格百分比';
-- ALTER TABLE `yf_distribution_goods_base` ADD COLUMN `goods_max_range_price` decimal(4,2) NOT NULL DEFAULT '0' COMMENT '销售价格范围:最大价格百分比';


ALTER TABLE `yf_shop_base` ADD COLUMN `shop_verify_reason` varchar(255) NOT NULL COMMENT '审核信息备注';


ALTER TABLE `yf_distribution_shop_distributor` ADD COLUMN `distributor_cat_ids` varchar(1024) NOT NULL COMMENT '分销商品授权分类';

-- 新加
ALTER TABLE `yf_goods_common`
MODIFY COLUMN `common_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '商品id' FIRST ,
ADD COLUMN `product_distributor_flag`  tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否为分销商品 0-自有商品';

ALTER TABLE `yf_user_info`
ADD COLUMN `user_directseller_shop`  varchar(255) NULL COMMENT '分销小店名称' AFTER `user_directseller_commission`;

ALTER TABLE `yf_goods_common`
ADD COLUMN `supply_shop_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品来源-供应商店铺id';

ALTER TABLE `yf_goods_common` ADD COLUMN `product_is_allow_update` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否可以修改内容';
ALTER TABLE `yf_goods_common` ADD COLUMN `product_is_allow_price` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否可以修改价格-可以取消';
ALTER TABLE `yf_goods_common` ADD COLUMN `product_is_behalf_delivery` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否代发货';
ALTER TABLE `yf_goods_common` ADD COLUMN `common_parent_id` int(10) NOT NULL DEFAULT '0' COMMENT '分销原产品';

ALTER TABLE `yf_goods_common` ADD COLUMN `goods_recommended_min_price` decimal(10,2) NOT NULL DEFAULT '0' COMMENT '建议最低零售价';
ALTER TABLE `yf_goods_common` ADD COLUMN `goods_recommended_max_price` decimal(10,2) NOT NULL DEFAULT '0' COMMENT '建议最高零售价';


ALTER TABLE `yf_order_base`
ADD COLUMN `directseller_discount`  decimal(10,2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '等级折扣金额';

ALTER TABLE `yf_order_goods`
ADD COLUMN `directseller_goods_discount`  decimal(10,2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '等级折扣金额';

ALTER TABLE `yf_shop_base`
ADD COLUMN `shop_settlement_last_time`  datetime NOT NULL COMMENT '店铺上次结算时间，若是新开店铺没有结算单，则是开店日期' AFTER `shop_settlement_cycle`;

ALTER TABLE `yf_distribution_shop_distributor`
ADD COLUMN `distributor_level_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分销商等级ID' AFTER `distributor_cat_ids`;

ALTER TABLE `yf_distribution_shop_distributor`
ADD COLUMN `distributor_new_cat_ids`  text NULL COMMENT '新增分类' AFTER `distributor_level_id`;

ALTER TABLE `yf_goods_common`
ADD COLUMN `common_distributor_description`  text NOT NULL COMMENT '分销说明' AFTER `product_distributor_flag`,
ADD COLUMN `common_distributor_flag`  tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '1价格修改 2内容修改' AFTER `common_distributor_description`;


ALTER TABLE `yf_order_goods`
ADD COLUMN `order_goods_source_id`  varchar(50) NOT NULL DEFAULT '' COMMENT 'SP订单号' AFTER `directseller_goods_discount`;

ALTER TABLE `yf_order_goods`
ADD COLUMN `order_goods_source_ship`  varchar(50) NOT NULL DEFAULT '' COMMENT '供应商物流' AFTER `order_goods_source_id`;

ALTER TABLE `yf_goods_base`
ADD COLUMN `goods_recommended_price`  decimal(10,2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '建议零售价-可以取消' AFTER `goods_is_shelves`,
ADD COLUMN `goods_recommended_min_price`  decimal(10,2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '建议最低零售价' AFTER `goods_recommended_price`,
ADD COLUMN `goods_recommended_max_price`  decimal(10,2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '建议最高零售价' AFTER `goods_recommended_min_price`,
ADD COLUMN `goods_parent_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品来源id' AFTER `goods_recommended_max_price`;


-- 供应商入驻信息
INSERT INTO `yf_shop_help` (`shop_help_id`, `help_sort`, `help_title`, `help_info`, `help_url`, `update_time`, `page_show`) VALUES ('1', '1', '供应商入驻', '<p style=\"text-align:left;\"><strong>2014年开放平台招商方向</strong> </p><p><br /></p><h1>\r\n	1.    品牌</h1><p><br /></p><p>       国际国内知名品牌<br /></p><p>\r\n	       开放平台将一如既往的最大程度地维护卖家的品牌利益，尊重品牌传统和内涵，欢迎优质品牌旗</p><p><br /></p><p>\r\n	舰店入驻，请参见《2014年开放平台重点招募品牌》。</p><h1><br /></h1><h1>\r\n	2.    货品</h1><p>       <br />       能够满足用户群体优质、有特色的货品。<br />       根据类目结构细分的货品配置。类目规划详见《2014年开放平台类目一览表》。<br /></p><h1>\r\n	3.   垂直电商</h1><p><br /></p><p>\r\n	      开放平台欢迎垂直类电商入驻。开放平台愿意和专业的垂直电商企业分享其优质用户群体，</p><p><br /></p><p>\r\n	并且欢迎垂直电商为用户提供该领域专业的货品及服务</p><br />', '', '2016-12-28', '3');
INSERT INTO `yf_shop_help` (`shop_help_id`, `help_sort`, `help_title`, `help_info`, `help_url`, `update_time`, `page_show`) VALUES ('2', '2', '供应商标准', '<p><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	本标准适用于除虚拟业务（包括但不限于旅游、酒店、票务、充值、彩票）外的平台开放平台所有卖家。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><strong>第一章 入驻</strong> </p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.1    平台开放平台暂未授权任何机构进行代理招商服务，入驻申请流程及相关的收费说明均以平台开放平台官方招商页面为准。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.2    平台开放平台有权根据包括但不限于品牌需求、公司经营状况、服务水平等其他因素退回卖家申请。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.3    平台开放平台有权在申请入驻及后续经营阶段要求卖家提供其他资质。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.4    平台开放平台将结合各行业发展动态、国家相关规定及消费者购买需求，不定期更新招商标准。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.5    卖家必须如实提供资料和信息：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.5.1 请务必确保申请入驻及后续经营阶段提供的相关资质和信息的真实性、完整性、有效性（若卖家提供的相关资质为第三方提供，包括但不限于商标注册证、授权书\r\n等，请务必先行核实文件的真实有效完整性），一旦发现虚假资质或信息的，平台开放平台将不再与卖家进行合作并有权根据平台开放平台规则及与卖家签署的相关 协议之约定进行处理；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.5.2  卖家应如实提供其店铺运营的主体及相关信息，包括但不限于店铺实际经营主体、代理运营公司等信息；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.5.3  平台开放平台关于卖家信息和资料变更有相关规定的从其规定，但卖家如变更1.5.2项所列信息，应提前十日书面告知平台；如未提前告知平台，平台将根据平台开放平台规则进行处理。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.6    平台开放平台暂不接受个体工商户的入驻申请，卖家须为正式注册企业，亦暂时不接受非中国大陆注册企业的入驻申请。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.7    平台开放平台暂不接受未取得国家商标总局颁发的商标注册证或商标受理通知书的品牌开店申请，亦不接受纯图形类商标的入驻申请。卖家提供商标受理通知书（TM状态商标）的，注册申请时间须满六个月。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><strong>第二章 平台店铺类型及相关要求</strong> </p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.1     旗舰店，卖家以自有品牌（商标为R或TM状态），或由权利人出具的在平台开放平台开设品牌旗舰店的授权文件（授权文件中应明确排他性、不可撤销性），入驻平台开放平台开设的店铺。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.1.1  旗舰店，可以有以下几种情形：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	经营一个自有品牌（商标为R或TM状态）商品入驻平台开放平台的卖家旗舰店，（自有品牌是指商标权利归卖家所有，自有品牌的子品牌可以放入旗舰店，主、子品牌的商标权利人应为同一实际控制人）；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	经营已获得明确排他性授权的一个品牌商品入驻平台开放平台的卖家旗舰店；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	卖场型品牌（服务类商标）商标权人开设的旗舰店；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.1.2  开店主体必须是品牌（商标）权利人或持有权利人出具的开设平台开放平台旗舰店排他性授权文件的被授权企业。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.2     专卖店，卖家持他人品牌（商标为R或TM状态）授权文件在平台开放平台开设的店铺。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.2.1  专卖店类型：经营一个或多个授权品牌商品（多个授权品牌的商标权人应为同一实际控制人）但未获得旗舰店排他授权入驻平台开放平台的卖家专卖店；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.2.2   品牌（商标）权利人出具的授权文件不应有地域限制。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.3     专营店，经营平台开放平台相同一级经营类目下两个及以上他人或自有品牌（商标为R或TM状态）商品的店铺。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.3.1  专营店，可以有以下几种情形：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	相同一级类目下经营两个及以上他人品牌商品入驻平台开放平台的卖家专营店；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	相同一级类目下既经营他人品牌商品又经营自有品牌商品入驻平台开放平台的卖家专营店。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.4     各类型店铺命名详细说明，请见《平台开放平台卖家店铺命名规则》。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><strong>第三章 平台申请入驻资质标准</strong> </p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	3.1    平台开放平台申请入驻资质标准详见《平台开放平台招商资质标准细则》。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><strong>第四章 开店入驻限制</strong> </p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.1    品牌入驻限制：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.1.1 与平台平台已有的自有品牌、频道、业务、类目等相同或相似名称的品牌；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.1.2  包含行业名称或通用名称的品牌；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.1.3  包含知名人士、地名的品牌；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.1.4  与知名品牌相同或近似的品牌。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.2     经营类目限制，卖家开店所经营的类目应当符合平台开放平台的相关标准，详细请参考《平台开放平台经营类目资费一览表》。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.3同一主体入驻的店铺限制：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.3.1  单个店铺只可对应一种经营模式。各经营模式内容请参考与卖家签署的对应合同；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.3.2  同一主体开设若干店铺的，经营模式总计不得超过两种，且须在开展第二种经营模式时提前10日向平台进行书面申请；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.3.3  商品重合度：要求店铺间经营的品牌及商品不得重复，4.3.2项下经过平台审批的店铺不受此项约束。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.4     同一主体重新入驻平台开放平台限制：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.4.1  严重违规、资质造假被平台清退的，永久限制入驻；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.4.2  若卖家一自然年内主动退出2次，则自最后一次完成退出之日起12个月内限制入驻。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.5     续签限制：须在每年3月1日18时之前完成续签申请的提交，每年3月20日18时之前完成平台使用费的缴纳，如果上一年及下一年资费及资料未补足，平台将在每年3月31日24时终止店铺服务并下架商品。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><strong>第五章 平台开放平台保证金/平台使用费/费率标准</strong> </p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.1     保证金：卖家向平台缴纳的用以保证店铺规范运营及对商品和服务质量进行担保的金额。当卖家发生违约、违规行为时，平台可以依照与卖家签署的协议中相关约定及平台开放平台规则扣除相应金额的保证金作为违约金或给予买家的赔偿。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.1.1  保证金的补足、退还、扣除等依据卖家签署的相关协议及平台开放平台规则约定办理；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.1.2  平台开放平台各经营类目对应的保证金标准详见《平台开放平台经营类目资费一览表》。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.2     平台使用费：卖家依照与平台签署的相关协议使用平台开放平台各项服务时缴纳的固定技术服务费用。平台开放平台各经营类目对应的平台使用费标准详见《平台开放平台经营类目资费一览表》。续签卖家的续展服务期间对应平台使用费须在每年3月20日18时前一次性缴纳；新签卖家须在申请入驻获得批准时一次性缴纳相应服务期间的平台使用费。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.2.1   平台使用费结算：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.2.1.1卖家主动要求停止店铺服务的不返还平台使用费；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.2.1.2卖家因违规行为或资质造假被清退的不返还平台使用费；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.2.1.3每个店铺的平台使用费依据相应的服务期计算并缴纳。服务开通之日在每月的1日至15日（含）间的，开通当月按一个月收取平台使用费，服务开通之日在每月的16日（含）至月底最后一日间的，开通当月不收取平台使用费；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.2.1.4拥有独立店铺ID的为一个店铺，若卖家根据经营情况须开通多个店铺的，须按照店铺数量缴纳相应的平台使用费。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.3     费率：卖家根据经营类目在达成每一单交易时按比例（该比例在与卖家签署的相关协议中称为“技术服务费费率”或“毛利保证率”）向平台缴纳的费用。平台开放平台各经营模式各经营类目对应的费率标准详见《平台开放平台经营类目资费一览表》。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><strong>第六章 店铺服务期</strong> </p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	6.1     卖家每个店铺的第一个服务期自服务开通之日起至最先到达的3月31日止，第二个服务期自4月1日起至次年3月31日止，第三个、第四个……服务期以此类推，以一年为周期，除非店铺或与卖家签署的相关协议提前终止。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	6.2     卖家每个店铺的服务开通之日以平台通知或系统记录的时间为准。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	6.3     卖家应在店铺每个服务期届满前30日向甲方提出续展的申请，缴纳续展服务期的平台使用费和提交其经营所需的全部有效资质，并经平台审核。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	6.4     卖家未提出续展申请或申请未通过平台审核的，自店铺服务期满之日起，平台开放平台将不再向卖家提供该店铺的任何服务。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	发布日期：2014年11月19日</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	生效日期：2014年1月1日</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><a></a><strong>平台开放平台招商资质标准细则</strong> </p><p class=\"MsoNormal\"> </p><p><br /></p>', '', '2016-12-28', '3');
INSERT INTO `yf_shop_help` (`shop_help_id`, `help_sort`, `help_title`, `help_info`, `help_url`, `update_time`, `page_show`) VALUES ('3', '3', '供应商资质要求', '<p><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><a></a><strong>平台开放平台招商资质标准细则</strong> \r\n	</p><p class=\"MsoNormal\"> </p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	本标准适用于除虚拟业务（包括但不限于旅游、酒店、票务、充值、彩票）外的平台开放平台所有卖家。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><strong>第一章 入驻</strong> </p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.1    平台开放平台暂未授权任何机构进行代理招商服务，入驻申请流程及相关的收费说明均以平台开放平台官方招商页面为准。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.2    平台开放平台有权根据包括但不限于品牌需求、公司经营状况、服务水平等其他因素退回卖家申请。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.3    平台开放平台有权在申请入驻及后续经营阶段要求卖家提供其他资质。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.4    平台开放平台将结合各行业发展动态、国家相关规定及消费者购买需求，不定期更新招商标准。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.5    卖家必须如实提供资料和信息：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.5.1 请务必确保申请入驻及后续经营阶段提供的相关资质和信息的真实性、完整性、有效性（若卖家提供的相关资质为第三方提供，包括但不限于商标注册证、授权书\r\n等，请务必先行核实文件的真实有效完整性），一旦发现虚假资质或信息的，平台开放平台将不再与卖家进行合作并有权根据平台开放平台规则及与卖家签署的相关 协议之约定进行处理；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.5.2  卖家应如实提供其店铺运营的主体及相关信息，包括但不限于店铺实际经营主体、代理运营公司等信息；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.5.3  平台开放平台关于卖家信息和资料变更有相关规定的从其规定，但卖家如变更1.5.2项所列信息，应提前十日书面告知平台；如未提前告知平台，平台将根据平台开放平台规则进行处理。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.6    平台开放平台暂不接受个体工商户的入驻申请，卖家须为正式注册企业，亦暂时不接受非中国大陆注册企业的入驻申请。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.7    平台开放平台暂不接受未取得国家商标总局颁发的商标注册证或商标受理通知书的品牌开店申请，亦不接受纯图形类商标的入驻申请。卖家提供商标受理通知书（TM状态商标）的，注册申请时间须满六个月。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><strong>第二章 平台店铺类型及相关要求</strong> </p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.1     旗舰店，卖家以自有品牌（商标为R或TM状态），或由权利人出具的在平台开放平台开设品牌旗舰店的授权文件（授权文件中应明确排他性、不可撤销性），入驻平台开放平台开设的店铺。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.1.1  旗舰店，可以有以下几种情形：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	经营一个自有品牌（商标为R或TM状态）商品入驻平台开放平台的卖家旗舰店，（自有品牌是指商标权利归卖家所有，自有品牌的子品牌可以放入旗舰店，主、子品牌的商标权利人应为同一实际控制人）；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	经营已获得明确排他性授权的一个品牌商品入驻平台开放平台的卖家旗舰店；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	卖场型品牌（服务类商标）商标权人开设的旗舰店；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.1.2  开店主体必须是品牌（商标）权利人或持有权利人出具的开设平台开放平台旗舰店排他性授权文件的被授权企业。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.2     专卖店，卖家持他人品牌（商标为R或TM状态）授权文件在平台开放平台开设的店铺。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.2.1  专卖店类型：经营一个或多个授权品牌商品（多个授权品牌的商标权人应为同一实际控制人）但未获得旗舰店排他授权入驻平台开放平台的卖家专卖店；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.2.2   品牌（商标）权利人出具的授权文件不应有地域限制。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.3     专营店，经营平台开放平台相同一级经营类目下两个及以上他人或自有品牌（商标为R或TM状态）商品的店铺。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.3.1  专营店，可以有以下几种情形：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	相同一级类目下经营两个及以上他人品牌商品入驻平台开放平台的卖家专营店；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	相同一级类目下既经营他人品牌商品又经营自有品牌商品入驻平台开放平台的卖家专营店。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.4     各类型店铺命名详细说明，请见《平台开放平台卖家店铺命名规则》。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><strong>第三章 平台申请入驻资质标准</strong> </p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	3.1    平台开放平台申请入驻资质标准详见《平台开放平台招商资质标准细则》。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><strong>第四章 开店入驻限制</strong> </p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.1    品牌入驻限制：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.1.1 与平台平台已有的自有品牌、频道、业务、类目等相同或相似名称的品牌；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.1.2  包含行业名称或通用名称的品牌；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.1.3  包含知名人士、地名的品牌；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.1.4  与知名品牌相同或近似的品牌。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.2     经营类目限制，卖家开店所经营的类目应当符合平台开放平台的相关标准，详细请参考《平台开放平台经营类目资费一览表》。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.3同一主体入驻的店铺限制：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.3.1  单个店铺只可对应一种经营模式。各经营模式内容请参考与卖家签署的对应合同；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.3.2  同一主体开设若干店铺的，经营模式总计不得超过两种，且须在开展第二种经营模式时提前10日向平台进行书面申请；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.3.3  商品重合度：要求店铺间经营的品牌及商品不得重复，4.3.2项下经过平台审批的店铺不受此项约束。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.4     同一主体重新入驻平台开放平台限制：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.4.1  严重违规、资质造假被平台清退的，永久限制入驻；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.4.2  若卖家一自然年内主动退出2次，则自最后一次完成退出之日起12个月内限制入驻。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.5     续签限制：须在每年3月1日18时之前完成续签申请的提交，每年3月20日18时之前完成平台使用费的缴纳，如果上一年及下一年资费及资料未补足，平台将在每年3月31日24时终止店铺服务并下架商品。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><strong>第五章 平台开放平台保证金/平台使用费/费率标准</strong> </p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.1     保证金：卖家向平台缴纳的用以保证店铺规范运营及对商品和服务质量进行担保的金额。当卖家发生违约、违规行为时，平台可以依照与卖家签署的协议中相关约定及平台开放平台规则扣除相应金额的保证金作为违约金或给予买家的赔偿。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.1.1  保证金的补足、退还、扣除等依据卖家签署的相关协议及平台开放平台规则约定办理；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.1.2  平台开放平台各经营类目对应的保证金标准详见《平台开放平台经营类目资费一览表》。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.2     平台使用费：卖家依照与平台签署的相关协议使用平台开放平台各项服务时缴纳的固定技术服务费用。平台开放平台各经营类目对应的平台使用费标准详见《平台开放平台经营类目资费一览表》。续签卖家的续展服务期间对应平台使用费须在每年3月20日18时前一次性缴纳；新签卖家须在申请入驻获得批准时一次性缴纳相应服务期间的平台使用费。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.2.1   平台使用费结算：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.2.1.1卖家主动要求停止店铺服务的不返还平台使用费；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.2.1.2卖家因违规行为或资质造假被清退的不返还平台使用费；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.2.1.3每个店铺的平台使用费依据相应的服务期计算并缴纳。服务开通之日在每月的1日至15日（含）间的，开通当月按一个月收取平台使用费，服务开通之日在每月的16日（含）至月底最后一日间的，开通当月不收取平台使用费；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.2.1.4拥有独立店铺ID的为一个店铺，若卖家根据经营情况须开通多个店铺的，须按照店铺数量缴纳相应的平台使用费。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.3     费率：卖家根据经营类目在达成每一单交易时按比例（该比例在与卖家签署的相关协议中称为“技术服务费费率”或“毛利保证率”）向平台缴纳的费用。平台开放平台各经营模式各经营类目对应的费率标准详见《平台开放平台经营类目资费一览表》。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><strong>第六章 店铺服务期</strong> </p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	6.1     卖家每个店铺的第一个服务期自服务开通之日起至最先到达的3月31日止，第二个服务期自4月1日起至次年3月31日止，第三个、第四个……服务期以此类推，以一年为周期，除非店铺或与卖家签署的相关协议提前终止。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	6.2     卖家每个店铺的服务开通之日以平台通知或系统记录的时间为准。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	6.3     卖家应在店铺每个服务期届满前30日向甲方提出续展的申请，缴纳续展服务期的平台使用费和提交其经营所需的全部有效资质，并经平台审核。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	6.4     卖家未提出续展申请或申请未通过平台审核的，自店铺服务期满之日起，平台开放平台将不再向卖家提供该店铺的任何服务。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	发布日期：2014年11月19日</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	生效日期：2014年1月1日</p><p><br /></p>', '', '2017-01-06', '3');
INSERT INTO `yf_shop_help` (`shop_help_id`, `help_sort`, `help_title`, `help_info`, `help_url`, `update_time`, `page_show`) VALUES ('4', '4', '供应商资费标准', '<p><br /></p><p><br /></p><h3 class=\"help_tit\"><strong>2014年开放平台重点招募品牌</strong> \r\n	</h3><p><br /></p><p><br /></p><p><br /></p><p>\r\n		发布日期：2014年04月20日 	</p><p>\r\n		修订日期：2014年05月01日	</p>', '', '2017-01-06', '3');
INSERT INTO `yf_shop_help` (`shop_help_id`, `help_sort`, `help_title`, `help_info`, `help_url`, `update_time`, `page_show`) VALUES ('5', '1', '入驻协议', '<p><br /></p><p><br /></p><h3 class=\"help_tit\"><strong>2014年开放平台重点招募品牌</strong> \r\n	</h3><p><br /></p><p>供应商入驻</p><p><br /></p><p>供应商入驻</p><p><br /></p><p>\r\n		发布日期：2014年04月20日 	</p><p>\r\n		修订日期：2014年05月01日	</p><p>99	4	资费标准	<br /></p><p><br /></p><h3 class=\"help_tit\"><strong>2014年开放平台重点招募品牌</strong> \r\n	</h3><p><br /></p><p><br /></p><p>\r\n		发布日期：2014年04月20日 	</p><p>\r\n		修订日期：2014年05月01日	</p><p>		2016-07-13	1</p>', '', '2017-01-06', '4');


ALTER TABLE `yf_distribution_shop_distributor`
ADD COLUMN `shop_distributor_time`  datetime NOT NULL COMMENT '申请时间' AFTER `distributor_new_cat_ids`;

ALTER TABLE `yf_distribution_shop_distributor`
MODIFY COLUMN `shop_distributor_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST;


ALTER TABLE `yf_chain_base`
MODIFY COLUMN `chain_address`  varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址';


ALTER TABLE `yf_goods_common`
MODIFY COLUMN `common_virtual_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '虚拟商品有效期';



--wap城市分站
ALTER TABLE `yf_mb_tpl_layout` ADD COLUMN `sub_site_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属分站Id:0-总站';