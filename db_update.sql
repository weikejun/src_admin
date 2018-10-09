alter table `project` modify `deal_manager` text DEFAULT NULL COMMENT '本轮交易负责人';
alter table `project` modify `law_firm` text DEFAULT NULL COMMENT '源码委托律所';
alter table `project` add `loan_schedule` varchar(8) DEFAULT NULL COMMENT '借款进度';
alter table `project` add `trade_file_schedule` varchar(8) DEFAULT NULL COMMENT '交易文件进度';
alter table `project` add `expect_sign_date` varchar(11) DEFAULT NULL COMMENT '预计签约日期'; 
alter table `project` add `expect_pay_schedule` text DEFAULT NULL COMMENT '预计交割付款安排'; 
alter table `project` add `trade_schedule_memo` text DEFAULT NULL COMMENT '交易进度其他说明'; 
alter table `project` add `trade_schedule_todo` text DEFAULT NULL COMMENT '交易进度ToDo'; 
alter table `project` add `ts_ratio` varchar(8) DEFAULT NULL COMMENT 'TS/决策口径占比';
alter table `project` add `lawyer_fee` varchar(8) DEFAULT NULL COMMENT '律师费';
alter table `project` add `active_deal` varchar(8) DEFAULT NULL COMMENT 'active项目进度';

alter table `project` add `create_time` int(11) DEFAULT NULL COMMENT '创建时间';
update `project` p,(select res_id,create_time from system_log where resource='Project' and action='create' and res_id > 0) t set p.create_time=t.create_time where p.id=t.res_id;

DROP TABLE IF EXISTS `deal_decision`; /*投决意见*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `deal_decision` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `project_id` int(11) DEFAULT NULL COMMENT '交易ID',
    `partner` varchar(16) DEFAULT NULL COMMENT '合伙人',
    `decision` varchar(8) DEFAULT NULL COMMENT '投资意见',
    `ip` varchar(32) DEFAULT NULL COMMENT '来源IP',
    `expiration` varchar(11) DEFAULT NULL COMMENT '有效期',
    `memo` text DEFAULT NULL COMMENT '备注',
    `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=3366;

insert into member (name,create_time) select partner,unix_timestamp() from company where partner != '' group by partner;
insert into member (name,create_time) select manager,unix_timestamp() from company where manager != '' group by manager;
insert into member (name,create_time) select legal_person,unix_timestamp() from company where legal_person != '' group by legal_person;
insert into member (name,create_time) select finance_person,unix_timestamp() from company where finance_person != '' group by finance_person;
insert into member (name,create_time) select filling_keeper,unix_timestamp() from company where filling_keeper != '' group by filling_keeper;

update company c, (select c.id as id, m.id as partner from company c left join member m on (c.partner=m.name)) t set c.partner=t.partner where c.id=t.id and t.partner is not null;
update company c, (select c.id as id, m.id as manager from company c left join member m on (c.manager=m.name)) t set c.manager=t.manager where c.id=t.id and t.manager is not null;
update company c, (select c.id as id, m.id as legal_person from company c left join member m on (c.legal_person=m.name)) t set c.legal_person=t.legal_person where c.id=t.id and t.legal_person is not null;
update company c, (select c.id as id, m.id as finance_person from company c left join member m on (c.finance_person=m.name)) t set c.finance_person=t.finance_person where c.id=t.id and t.finance_person is not null;
update company c, (select c.id as id, m.id as filling_keeper from company c left join member m on (c.filling_keeper=m.name)) t set c.filling_keeper=t.filling_keeper where c.id=t.id and t.filling_keeper is not null;
