--
-- Table structure for table `system_log`
--

DROP TABLE IF EXISTS `system_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_log` (
    `id` int(11) NOT NULL auto_increment,
    `operator_id` int(11) DEFAULT NULL,
    `operator_ip` varchar(16) DEFAULT NULL COMMENT '操作IP',
    `resource` varchar(16) DEFAULT NULL COMMENT '操作资源',
    `res_id` varchar(16) DEFAULT NULL COMMENT '资源ID',
    `action` varchar(16) DEFAULT NULL COMMENT '动作',
    `detail` text DEFAULT NULL COMMENT '操作内容',
    `create_time` int(11) DEFAULT NULL,
    Index `res_id_idx` (`res_id`),
    Index `operator_id_idx` (`operator_id`),
    Index `create_time_idx` (`create_time`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
