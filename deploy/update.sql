/******************** create table ********************/
/******************** alter table ********************/
alter table `project` add `pay_memo` text DEFAULT NULL COMMENT '源码支付备注' after `pay_amount`;

/******************** insert ********************/

/******************** update ********************/
update `project` set `entity_odi` = '已完成ODI' where `entity_odi` = '已做ODI';
update `project` set `entity_odi` = '可能/待做ODI' where `entity_odi` = '可能要做ODI';
