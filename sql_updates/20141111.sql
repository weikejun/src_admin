alter table user_refund add column `range` smallint(6) NOT NULL DEFAULT '0' COMMENT '退款范围, 0-全额；1-部分；';
alter table user_refund add column `amount` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '退款指定金额';

