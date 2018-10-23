DROP TABLE IF EXISTS `knowledge_cate`; /*知识大类*/

CREATE TABLE `knowledge_cate` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(256) DEFAULT NULL COMMENT '名称',
    `description` text DEFAULT NULL COMMENT '说明', 
    `memo` text DEFAULT NULL COMMENT '备注', 
    `operator` varchar(32) DEFAULT NULL COMMENT '添加人', 
    `update_time` int(11) DEFAULT NULL COMMENT '更新时间', 
    `create_time` int(11) DEFAULT NULL COMMENT '更新时间', 
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `knowledge_list`; /*知识列表*/

CREATE TABLE `knowledge_list` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `cate_id` int(11) DEFAULT NULL COMMENT '大类ID',
    `name` varchar(256) DEFAULT NULL COMMENT '名称',
    `content` mediumtext DEFAULT NULL COMMENT '内容', 
    `reference` text DEFAULT NULL COMMENT '参考资料', 
    `memo` text DEFAULT NULL COMMENT '备注', 
    `operator` varchar(32) DEFAULT NULL COMMENT '添加人', 
    `update_time` int(11) DEFAULT NULL COMMENT '更新时间', 
    `create_time` int(11) DEFAULT NULL COMMENT '更新时间', 
    INDEX `cate_id_idx` (`cate_id`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
