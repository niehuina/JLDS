insert into `pay_web_config` ( `config_type`, `config_key`, `config_enable`, `config_datatype`) values ( 'site', 'site_logo', '1', 'string');

	ALTER TABLE `pay_card_info`
MODIFY COLUMN `card_id`  int(10) NOT NULL COMMENT '卡片id' AFTER `card_password`;

ALTER TABLE `pay_card_base`
MODIFY COLUMN `card_id`  int(10) NOT NULL COMMENT '卡的id' FIRST ;

ALTER TABLE `pay_consume_record`
ADD COLUMN `record_payorder`  varchar(50) NOT NULL COMMENT '实际支付单号' AFTER `record_status`;