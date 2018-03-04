use aimeizhuyi;

DROP TABLE IF EXISTS `index_new`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `index_new` (
  `id` int(11) not null auto_increment,
  `type` varchar(20) not null,/*stock：商品页，live：直播页，buyer：买手页*/
  `model_id` varchar(64)  not null,/*（和type对应，stock_id、live_id、buyer_id）*/
  `title` varchar(250),
  `imgs` text,
  `order` int(11) default 0,
  `valid` enum('valid','invalid') default 'valid',
  `create_time` int(11) default null,
  `update_time` int(11) default null,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='首页推荐位';
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `user_reminder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_reminder` (
  `id` int(11) not null auto_increment,
  `model_type` varchar(20) not null,/*live：直播开始5分钟前提醒*/
  `model_id` varchar(64)  not null,/*（和type对应，stock_id、live_id、buyer_id）*/
  `user_id` int(11) not null,
  `type` varchar(20),/* before5:直播开始前5分钟提醒*/
  `status` varchar(10),/* not_send:还没提醒过，send:已经提醒过*/
  `create_time` int(11) default null,
  `update_time` int(11) default null,
  `desc` text,
  KEY `index_user_id` (`user_id`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户定的提醒';
