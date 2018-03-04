alter table `payment` add column `pay_account` varchar(64) DEFAULT '' COMMENT '支付账号';
alter table `payment` add column `platform_trade_no` varchar(64) DEFAULT '' COMMENT '支付平台订单号';
