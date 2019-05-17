-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2017 年 09 月 26 日 16:58
-- 服务器版本: 5.5.53
-- PHP 版本: 5.4.45

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- 数据库: `webpos`
--

-- --------------------------------------------------------

--
-- 表的结构 `acl`
--

DROP TABLE IF EXISTS `acl`;
CREATE TABLE IF NOT EXISTS `acl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL COMMENT 'module  controller  action',
  `title` varchar(255) NOT NULL,
  `pid` int(11) DEFAULT '0' COMMENT '上一层',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ACL' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `acl_roles`
--

DROP TABLE IF EXISTS `acl_roles`;
CREATE TABLE IF NOT EXISTS `acl_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acl_id` int(11) NOT NULL COMMENT 'ACL id',
  `roles_id` int(11) NOT NULL COMMENT '角色ID',
  `users_id` int(11) NOT NULL COMMENT 'BOSSid',
  `status` int(4) DEFAULT '0' COMMENT '权限状态，1开启，2关闭',
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='acl与roles关联' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(20) CHARACTER SET utf8 NOT NULL,
  `pwd` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT '密码',
  `level` tinyint(1) DEFAULT '1' COMMENT '等级',
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='云平台管理员' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `record_succession`
--

DROP TABLE IF EXISTS `record_succession`;
CREATE TABLE IF NOT EXISTS `record_succession` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_users_id` int(11) DEFAULT NULL COMMENT '收银员帐号',
  `start_time` int(11) DEFAULT NULL COMMENT '开始时间',
  `end_time` int(11) DEFAULT NULL COMMENT '结束时间',
  `cash_payments` decimal(16,2) DEFAULT '0.00' COMMENT '现金支付',
  `unionpay_pay` decimal(16,2) DEFAULT '0.00' COMMENT '银联支付',
  `weixin_pay` decimal(16,2) DEFAULT '0.00' COMMENT '微信支付',
  `alipay_pay` decimal(16,2) DEFAULT '0.00' COMMENT '支付宝支付',
  `wp_users_pay` decimal(16,2) DEFAULT '0.00' COMMENT '会员余额支付',
  `standby_money` decimal(16,2) DEFAULT '0.00' COMMENT '备用金',
  `yf_shop_base_id` int(11) DEFAULT NULL COMMENT '所属门店id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='员工交接班列表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL COMMENT '角色名称',
  `slug` varchar(200) NOT NULL COMMENT '唯一标识',
  `created` int(11) NOT NULL,
  `updated` int(11) DEFAULT NULL,
  `deleted_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色管理' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `role_users`
--

DROP TABLE IF EXISTS `role_users`;
CREATE TABLE IF NOT EXISTS `role_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `role_id` int(11) NOT NULL COMMENT '角色ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=' 用户对应角色' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `shop_users`
--

DROP TABLE IF EXISTS `shop_users`;
CREATE TABLE IF NOT EXISTS `shop_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yf_shop_base_id` int(11) NOT NULL COMMENT '所属店铺id',
  `user` varchar(200) NOT NULL COMMENT '帐号',
  `pwd` varchar(200) NOT NULL,
  `created` int(11) NOT NULL COMMENT '创建时间',
  `ucenter_id` int(11) NOT NULL,
  `nickname` varchar(50) NOT NULL COMMENT '员工姓名',
  `sex` tinyint(1) NOT NULL COMMENT '性别',
  `phone` varchar(50) NOT NULL COMMENT '手机号',
  `id_card` varchar(200) NOT NULL COMMENT '身份证',
  `num` varchar(200) NOT NULL COMMENT '员工编号',
  `level` tinyint(1) DEFAULT '3' COMMENT '等级',
  `deleted_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='员工帐号' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ucenter_id` bigint(20) NOT NULL COMMENT '绑定商城的ucenter_id',
  `ucenter_name` varchar(200) NOT NULL COMMENT 'boss帐号',
  `service_start_time` datetime NOT NULL COMMENT '服务开始时间',
  `service_end_time` datetime NOT NULL COMMENT '服务结束时间',
  `max_stores` int(11) NOT NULL COMMENT '最多拥有门店数',
  `max_nums` int(11) NOT NULL COMMENT '单个门店最多人数',
  `authorization_module` int(11) NOT NULL COMMENT '授权模块',
  `level` tinyint(1) DEFAULT '2' COMMENT '等级',
  `created` int(11) NOT NULL COMMENT '创建时间',
  `updated` int(11) NOT NULL COMMENT '更新时间',
  `deleted_at` datetime NOT NULL,
  `local_ucenter_id` bigint(20) NOT NULL COMMENT '本地的ucenter_id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='店铺管理员' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wp_coupon`
--

DROP TABLE IF EXISTS `wp_coupon`;
CREATE TABLE IF NOT EXISTS `wp_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '优惠券',
  `price` decimal(18,2) NOT NULL COMMENT '面值',
  `start_time` int(11) NOT NULL COMMENT '有效期开始时间',
  `end_time` int(11) NOT NULL COMMENT '有效期结束时间',
  `created` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '1为启用，0不启用',
  `max_num` int(11) NOT NULL COMMENT '数量',
  `shop_id` int(11) NOT NULL COMMENT '店铺',
  `cat_id` int(11) NOT NULL COMMENT '分类',
  `condition` decimal(18,2) NOT NULL COMMENT '至少花多少钱可以使用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='优惠券' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wp_order`
--

DROP TABLE IF EXISTS `wp_order`;
CREATE TABLE IF NOT EXISTS `wp_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(200) NOT NULL COMMENT '订单编号',
  `shop_users_id` int(11) NOT NULL COMMENT '收银员帐号',
  `user_id` int(11) DEFAULT NULL COMMENT '购买者',
  `payid` int(11) NOT NULL COMMENT '支付方式',
  `good_price` decimal(18,2) NOT NULL COMMENT '实际支付的费用',
  `user_price` decimal(18,2) DEFAULT NULL COMMENT '顾客支付的金额',
  `return_price` decimal(18,2) DEFAULT '0.00' COMMENT '找零',
  `good_num` varchar(200) NOT NULL COMMENT '数量',
  `created` int(11) NOT NULL COMMENT '下单时间',
  `ended` int(11) NOT NULL COMMENT '结单时间',
  `good_price_ori` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT '产品总价',
  `order_status` tinyint(1) NOT NULL COMMENT '订单状态 6.已完成 1.待支付',
  `coupon` varchar(255) DEFAULT NULL COMMENT '优惠券',
  `type` tinyint(4) NOT NULL COMMENT '1代表商城导入',
  `deleted_at` datetime NOT NULL,
  `shop_id` int(11) NOT NULL COMMENT '店铺id',
  `payment_way` int(11) DEFAULT NULL COMMENT '记录小票扫码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wp_order_return`
--

DROP TABLE IF EXISTS `wp_order_return`;
CREATE TABLE IF NOT EXISTS `wp_order_return` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(200) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `goods_name` varchar(255) NOT NULL,
  `goods_values` text NOT NULL,
  `goods_price` decimal(18,2) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `shop_name` varchar(255) NOT NULL,
  `shop_users_id` int(11) NOT NULL COMMENT '收银员账号',
  `num` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `return_payid` tinyint(4) NOT NULL COMMENT '退货方式',
  `good_price_ori` decimal(18,2) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '1代表商城导入',
  `created` int(11) NOT NULL,
  `updated` int(11) NOT NULL,
  `deleted_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='退货表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wp_order_value`
--

DROP TABLE IF EXISTS `wp_order_value`;
CREATE TABLE IF NOT EXISTS `wp_order_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(200) NOT NULL COMMENT '订单ID',
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `goods_name` varchar(255) NOT NULL COMMENT '商品名称',
  `goods_values` text NOT NULL COMMENT '商品其他信息',
  `goods_price` decimal(18,2) NOT NULL COMMENT '实际支付的费用',
  `shop_id` int(11) NOT NULL COMMENT '店铺ID',
  `shop_name` varchar(255) NOT NULL COMMENT '店铺名称',
  `shop_users_id` int(11) NOT NULL COMMENT '员工id',
  `num` int(11) NOT NULL COMMENT '购买数量',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1为正常，-1为退货',
  `good_price_ori` decimal(18,2) NOT NULL COMMENT '原价',
  `type` tinyint(4) NOT NULL COMMENT '1代表商城导入',
  `created` int(11) NOT NULL,
  `updated` int(11) DEFAULT NULL,
  `deleted_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------



-- --------------------------------------------------------

--
-- 表的结构 `wp_users`
--

DROP TABLE IF EXISTS `wp_users`;
CREATE TABLE IF NOT EXISTS `wp_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ucenter_id` int(11) NOT NULL COMMENT 'ucenter用户ID',
  `sex` tinyint(1) NOT NULL COMMENT '性别',
  `phone` varchar(200) NOT NULL COMMENT '手机号',
  `email` varchar(200) DEFAULT NULL COMMENT '邮箱',
  `bron` int(11) DEFAULT NULL COMMENT '生日',
  `realname` varchar(200) DEFAULT NULL COMMENT '真实性名',
  `ucenter_name` varchar(200) NOT NULL COMMENT '用户名',
  `account_balance` decimal(18,2) DEFAULT '0.00' COMMENT '会员余额',
  `type` tinyint(4) DEFAULT '0' COMMENT '1代表商城导入',
  `created` int(11) NOT NULL COMMENT '注册时间',
  `deleted_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wp_users_discount`
--

DROP TABLE IF EXISTS `wp_users_discount`;
CREATE TABLE IF NOT EXISTS `wp_users_discount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wp_user_id` int(11) NOT NULL COMMENT 'ucenter用户ID',
  `discount` int(11) DEFAULT '100' COMMENT '折扣卡',
  `users_id` int(11) DEFAULT NULL COMMENT '商家id',
  `numbers` varchar(200) NOT NULL COMMENT '会员卡号，自动生成',
  `type` tinyint(4) DEFAULT '0' COMMENT '1代表商城导入',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否享受折扣，1享受',
  `started` int(11) NOT NULL COMMENT '开始时间',
  `ended` int(11) NOT NULL COMMENT '结束时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员折扣' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `yf_goods_cat`
--

DROP TABLE IF EXISTS `yf_goods_cat`;
CREATE TABLE IF NOT EXISTS `yf_goods_cat` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(50) NOT NULL COMMENT ' 分类名称',
  `cat_parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父类',
  `cat_displayorder` smallint(3) NOT NULL DEFAULT '255' COMMENT '排序',
  `level` int(11) NOT NULL COMMENT '分类级别',
  `type` tinyint(4) DEFAULT NULL COMMENT '导入类型 1，商城导入',
  `deleted_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cat_parent_id` (`cat_parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品分类表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `yf_goods_common`
--

DROP TABLE IF EXISTS `yf_goods_common`;
CREATE TABLE IF NOT EXISTS `yf_goods_common` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `goods_id` int(11) NOT NULL COMMENT '商品同步商城id',
  `common_name` varchar(50) NOT NULL COMMENT '商品名称',
  `cat_id` int(10) unsigned NOT NULL COMMENT '商品分类',
  `common_spec_name` varchar(255) DEFAULT NULL COMMENT '规格名称',
  `file` varchar(255) NOT NULL COMMENT '商品主图',
  `common_state` tinyint(3) unsigned DEFAULT NULL COMMENT '商品状态 2下架，1正常，',
  `common_add_time` int(11) DEFAULT NULL COMMENT '商品添加时间',
  `common_sell_time` int(11) DEFAULT NULL COMMENT '上架时间',
  `common_price` decimal(10,2) NOT NULL COMMENT '商品价格',
  `common_market_price` decimal(10,2) DEFAULT NULL COMMENT '市场价',
  `common_cost_price` decimal(10,2) DEFAULT NULL COMMENT '成本价',
  `common_stock` int(10) unsigned DEFAULT NULL COMMENT '商品库存',
  `common_alarm` int(10) unsigned DEFAULT '0' COMMENT '库存预警值',
  `common_cubage` decimal(10,2) DEFAULT NULL COMMENT '商品重量',
  `common_salenum` int(10) unsigned DEFAULT '0' COMMENT '商品销量',
  `common_discounts` tinyint(1) DEFAULT '0' COMMENT '折扣状态',
  `common_invoices` tinyint(3) unsigned DEFAULT '0' COMMENT '是否开具增值税发票 1是，0否',
  `common_goods_from` tinyint(1) DEFAULT '0' COMMENT '1代表商城导入',
  `common_code` varchar(200) DEFAULT NULL COMMENT '商品条码',
  `deleted_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cat_id` (`cat_id`),
  KEY `common_state` (`common_state`),
  KEY `common_name` (`common_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品公共内容表-未来可分表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `yf_goods_shop_common`
--

DROP TABLE IF EXISTS `yf_goods_shop_common`;
CREATE TABLE IF NOT EXISTS `yf_goods_shop_common` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted_at` datetime NOT NULL,
  `common_id` int(11) NOT NULL COMMENT '商品id',
  `shop_id` int(11) NOT NULL COMMENT '店铺id',
  `type` tinyint(4) DEFAULT '0' COMMENT '1，商城导入',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺商品关联表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `yf_payment_way`
--

DROP TABLE IF EXISTS `yf_payment_way`;
CREATE TABLE IF NOT EXISTS `yf_payment_way` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paycenter_id` int(11) NOT NULL,
  `pay_way` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `yf_shop_base`
--

DROP TABLE IF EXISTS `yf_shop_base`;
CREATE TABLE IF NOT EXISTS `yf_shop_base` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '属于哪个用户的',
  `title` varchar(200) NOT NULL COMMENT '门店名称',
  `address` varchar(255) NOT NULL COMMENT '门店地址',
  `phone` varchar(20) NOT NULL COMMENT '电话',
  `created` int(11) NOT NULL,
  `updated` int(11) DEFAULT NULL,
  `type_id` int(11) NOT NULL COMMENT '类型',
  `yf_shop_id` int(11) DEFAULT NULL COMMENT '同步商城店铺id',
  `shop_num` int(11) NOT NULL COMMENT '店员数',
  `deleted_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='门店信息' AUTO_INCREMENT=1 ;
