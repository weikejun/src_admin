alter table `live` add column `fee` varchar(10) default null COMMENT '直播费';
alter table `buyer` add column `fee_rate` varchar(10) default null COMMENT '代购费率' after `account_name`;
