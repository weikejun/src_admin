alter table `pack` add column `logistic_provider_fixed` varchar(18) DEFAULT '' COMMENT '快递公司编码' after `buyer_id`;
alter table `logistic` add column `logistic_provider_fixed` varchar(18) DEFAULT '' COMMENT '快递公司编码' after `logistic_provider`;
alter table `logistic` add index `idx_order_id`(`order_id`);
create table `logistic_tracking` (
     `id` int(11) NOT NULL AUTO_INCREMENT,
     `logistic_no` varchar(32) NOT NULL DEFAULT '' COMMENT '物流跟踪单号',
     `logistic_provider` varchar(18) NOT NULL DEFAULT '' COMMENT '物流公司',
     `context` varchar(120) NOT NULL DEFAULT '' COMMENT '物流跟踪信息',
     `ftime` int(11) NOT NULL DEFAULT 0 COMMENT '信息录入时间',
     `create_time` int(11) DEFAULT NULL,
     PRIMARY KEY (`id`),
     KEY `idx_logistic_no_provider_ftime`(`logistic_no`, `logistic_provider`, `ftime`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='物流跟踪表';
