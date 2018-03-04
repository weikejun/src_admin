use aimeizhuyi;

DROP TABLE IF EXISTS `live_forenotice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `live_forenotice` (
  `id` int(11) not null auto_increment,
  `live_id` int(11) not null,
  `title` varchar(250) default null,
  `content` mediumtext,
  `create_time` int(11) default null,
  `update_time` int(11) default null,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='直播预告';
/*!40101 SET character_set_client = @saved_cs_client */;
