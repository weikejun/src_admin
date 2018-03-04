-- 推广记录表（domob合作）
DROP TABLE IF EXISTS `promote_channel`;
CREATE TABLE `promote_channel` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `udid` varchar(64) DEFAULT NULL COMMENT '唯一设备标识',
  `mac` varchar(64) DEFAULT NULL COMMENT '唯一设备标识',
  `ifa` varchar(64) DEFAULT NULL COMMENT '唯一设备标识',
  `oid` varchar(64) DEFAULT NULL COMMENT '唯一设备标识',
  `appid` varchar(32) NOT NULL COMMENT 'appid',
  `source` varchar(32) DEFAULT NULL COMMENT '渠道来源',
  `click_ip` varchar(16) NOT NULL COMMENT '点击ip',
  `active_ip` varchar(16) DEFAULT NULL COMMENT '激活ip',
  `click_time` int(11) NOT NULL COMMENT '点击时间',
  `active_time` int(11) DEFAULT NULL COMMENT '激活时间',
  PRIMARY KEY (`id`),
  KEY `ifa_index` (`ifa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='推送任务表';
