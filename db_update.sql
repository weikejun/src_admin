alter table `project` modify `decision_date` varchar(11) DEFAULT NULL COMMENT '决策日期'; 
alter table `project` modify `kickoff_date` varchar(11) DEFAULT NULL COMMENT 'Kickoff日期'; 
alter table `project` modify `close_date` varchar(11) DEFAULT NULL COMMENT '交割日期'; 
alter table `project` modify `loan_sign_date` varchar(11) DEFAULT NULL COMMENT '借款合同签署日期'; 
alter table `project` modify `loan_end_date` varchar(11) DEFAULT NULL COMMENT '借款到期日'; 
alter table `project` modify `buyback_date` varchar(11) DEFAULT NULL COMMENT '本轮投资人可回购时间'; 

--
-- Table structure for table `permission_action`
--

DROP TABLE IF EXISTS `permission_action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_id` varchar(11) NOT NULL,
  `action_id` varchar(11) NOT NULL,
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `permission_id_action_id_index` (`permission_id`,`action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='权限组配置';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `item_permission`
--

DROP TABLE IF EXISTS `item_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` varchar(16) DEFAULT NULL,
  `company_id` varchar(16) DEFAULT NULL,
  `project_id` varchar(16) DEFAULT NULL,
  `operator_id` varchar(16) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_uni` (`admin_id`,`company_id`, `project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

insert into permission_action (action_id, permission_id, create_time) select id,permission_id,unix_timestamp() from action;
