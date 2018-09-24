alter table `company` add `main_founders` varchar(64) DEFAULT NULL COMMENT '最主要创始人' after `partner`;
alter table `project` add `supervisor` varchar(8) DEFAULT NULL COMMENT '源码监事' after `observer`;
alter table `project` add `delivery_duty` text DEFAULT NULL COMMENT '重要交割后义务' after `rights_memo`;
alter table `project` add `dilution_rate` varchar(8) DEFAULT NULL COMMENT '本轮稀释比例' after `raw_stock_memo`;
alter table `project` add `preemptive_memo` text DEFAULT NULL COMMENT '优先认购权备注' after `preemptive`;
alter table `project` add `most_favored_memo` text DEFAULT NULL COMMENT '最惠国待遇备注' after `most_favored`;
alter table `project` add `shareholding_cofounders` text DEFAULT NULL COMMENT 'co-founders股数' after `shareholding_member`;
alter table `project` add `affiliate_transaction` varchar(8) DEFAULT NULL COMMENT '是否为关联交易' after `value_change`;
alter table `project` add `captable_memo` text DEFAULT NULL COMMENT 'Captable备注' after `stocknum_all`;
alter table `entity` add `cate` text DEFAULT NULL COMMENT '类型' after `tp`;
alter table `entity` add `org_type` varchar(16) DEFAULT NULL COMMENT '组织形式' after `cate`;

DROP TABLE IF EXISTS `deal_memo`; /*交易备注*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `deal_memo` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `project_id` int(11) DEFAULT NULL COMMENT '交易ID',
    `title` text DEFAULT NULL COMMENT '事项',
    `content` text DEFAULT NULL COMMENT '内容',
    `operator` varchar(32) DEFAULT NULL COMMENT '添加人',
    `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
