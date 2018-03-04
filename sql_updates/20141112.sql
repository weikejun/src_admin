
DROP TABLE IF EXISTS `stock_feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_feedback` (
  `id` int(11) not null auto_increment,
  `stock_id` int(11) not null,
  `live_id` int(11) not null,
  `buyer_id` int(11) not null,
  `user_id` int(11) not null,
  `type` varchar(30) default null COMMENT '如：与描述不符；高于市场价；其他。在前端都是固定选项',
  `info` text COMMENT '反馈内容',
  `create_time` int(11) default null,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户反馈表';
/*!40101 SET character_set_client = @saved_cs_client */;



alter table live add column `type` varchar(16) DEFAULT NULL COMMENT '直播类型';
