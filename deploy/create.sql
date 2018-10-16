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
    `sign_key` varchar(16) DEFAULT NULL COMMENT '认证签名',
    `update_time` varchar(11) DEFAULT NULL COMMENT '更新时间',
    `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
    UNIQUE KEY `uniq_index` (`project_id`, `partner`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=3366;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `entity_rel`; /*主体关系*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entity_rel` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `parent_id` int(11) DEFAULT NULL COMMENT '目标主体ID',
    `sub_id` int(11) DEFAULT NULL COMMENT '持有主体ID',
    `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_index` (`parent_id`,`sub_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `mail_list`; /*邮件触发条件*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail_list` (
    `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '提醒ID',
    `strategy_id` int(11) DEFAULT NULL COMMENT '邮件策略ID',
    `ref` varchar(16) DEFAULT NULL COMMENT '触发资源',
    `ref_id` int(11) DEFAULT NULL COMMENT '资源ID',
    `mail_to` text DEFAULT NULL COMMENT '收件人', 
    `mail_cc` text DEFAULT NULL COMMENT '抄送', 
    `title` text DEFAULT NULL COMMENT '邮件标题', 
    `content` text DEFAULT NULL COMMENT '邮件内容', 
    `status` varchar(8) DEFAULT NULL COMMENT '邮件状态，待发送、已发送、发送中、发送失败',
    `create_type` varchar(8) DEFAULT NULL COMMENT '创建方式，自动、手动',
    `expect_time` varchar(11) DEFAULT NULL COMMENT '预计发送时间', 
    `send_time` varchar(11) DEFAULT NULL COMMENT '实际发送时间', 
    `create_time` int(11) DEFAULT NULL COMMENT '创建时间', 
    INDEX `strategy_ref_mail` (`strategy_id`,`ref`,`ref_id`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `mail_strategy`; /*邮件策略*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail_strategy` (
    `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '策略ID',
    `name` varchar(16) DEFAULT NULL COMMENT '策略名称', 
    `mail_to` text DEFAULT NULL COMMENT '收件人', 
    `mail_cc` text DEFAULT NULL COMMENT '抄送', 
    `title` text DEFAULT NULL COMMENT '邮件标题', 
    `content` text DEFAULT NULL COMMENT '邮件内容', 
    `condition` text DEFAULT NULL COMMENT '条件说明', 
    `cycle` text DEFAULT NULL COMMENT '周期说明', 
    `create_time` int(11) DEFAULT NULL COMMENT '创建时间', 
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `member`; /*项目成员*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `member` (
    `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `name` varchar(16) DEFAULT NULL COMMENT '全名', 
    `mail` varchar(32) DEFAULT NULL COMMENT '邮箱', 
    `create_time` int(11) DEFAULT NULL COMMENT '创建时间', 
    UNIQUE KEY `mail_uniq` (`mail`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
