DROP TABLE IF EXISTS `compliance_matter`; /*合规审查事项*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compliance_matter` (
    `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `entity_id` int(11) DEFAULT NULL COMMENT '主体ID',
    `limit_source` varchar(16) DEFAULT NULL COMMENT '限制来源', 
    `category` varchar(16) DEFAULT NULL COMMENT '事项分类', 
    `sub_cate` varchar(256) DEFAULT NULL COMMENT '事项小类', 
    `scene` text DEFAULT NULL COMMENT '场景', 
    `requirement` text DEFAULT NULL COMMENT '具体要求', 
    `expiry` varchar(32) DEFAULT NULL COMMENT '有效期', 
    `action` varchar(16) DEFAULT NULL COMMENT '动作要求', 
    `action_target` varchar(16) DEFAULT NULL COMMENT '动作对象', 
    `terms_from` varchar(256) DEFAULT NULL COMMENT '条款来源', 
    `terms_raw` text DEFAULT NULL COMMENT '条款原文', 
    `create_time` int(11) DEFAULT NULL COMMENT '创建时间', 
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

