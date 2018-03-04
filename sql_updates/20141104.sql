DROP TABLE IF EXISTS `easemob_msg`;
CREATE TABLE `easemob_msg` (
    `id` int(11) NOT NULL auto_increment COMMENT '自增ID',
    `msg_id` varchar(32) NOT NULL COMMENT '环信msg_id',
    `from` varchar(40) NOT NULL COMMENT '环信username',
    `to` varchar(40) NOT NULL COMMENT '环信username',
    `msg_type` varchar(10) default NULL COMMENT '消息类型',
    `msg_text` varchar(256) default NULL COMMENT '消息内容',
    `send_time` int(11) DEFAULT NULL COMMENT '发消息时间',
    `rawdata` text COMMENT '消息',
    PRIMARY KEY (`id`),
    UNIQUE KEY `msg_id_index` (`msg_id`),
    KEY `from_index` (`from`),
    KEY `to_index` (`to`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='环信消息';

