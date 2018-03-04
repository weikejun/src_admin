alter table aimeizhuyi.user_reminder add column `push_id` varchar(64) NOT NULL after `user_id`;
alter table aimeizhuyi.`user_reminder` drop index `index_user_id`;

DROP TABLE IF EXISTS `exchange_rate`;
CREATE TABLE `exchange_rate` (
    `id` int(11) NOT NULL auto_increment COMMENT '自增ID',
    `currency_short` char(5) NOT NULL COMMENT '货币简称',
    `currency_name` varchar(16) NOT NULL COMMENT '货币名称',
    `buy` varchar(10) NOT NULL COMMENT '汇买价',
    `cash_buy` varchar(10) NOT NULL COMMENT '钞买价',
    `sell` varchar(10) NOT NULL COMMENT '卖出价',
    `cash_sell` varchar(10) NOT NULL COMMENT '钞卖价',
    `pub_time` int(11) NOT NULL COMMENT '发布时间',
    `create_time` int(11) NOT NULL COMMENT '入库时间',
    PRIMARY KEY (`id`),
    UNIQUE KEY `pub_time_currency_short_index` (`pub_time`,`currency_short`),
    KEY `pub_time_index` (`pub_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='汇率记录表';
