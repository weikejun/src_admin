/******************** create table ********************/
CREATE TABLE `knowledge_checklist` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `version` varchar(32) DEFAULT NULL COMMENT '版本',
    `list_info` text DEFAULT NULL COMMENT '清单说明',
    `content` text DEFAULT NULL COMMENT '内容',
    `operator` varchar(32) DEFAULT NULL COMMENT '添加人',
    `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
    `create_time` int(11) DEFAULT NULL COMMENT '更新时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/******************** alter table ********************/
alter table `compliance_matter` add `constrained_entitys` text DEFAULT NULL COMMENT '相关受限实体';

/******************** insert ********************/
insert into `action` (`name`,`description`,`update_time`,`create_time`) values('compliancematter_index','合规审查事项：首页',unix_timestamp(),unix_timestamp()),('compliancematter_read','合规审查事项：查看',unix_timestamp(),unix_timestamp()),('compliancematter_create','合规审查事项：新增',unix_timestamp(),unix_timestamp()),('compliancematter_update','合规审查事项：更新',unix_timestamp(),unix_timestamp()),('compliancematter_delete','合规审查事项：删除',unix_timestamp(),unix_timestamp()),('compliancematter_search','合规审查事项：搜索',unix_timestamp(),unix_timestamp()),('compliancematter_select','合规审查事项：选择页',unix_timestamp(),unix_timestamp()),('compliancematter_select_search','合规审查事项：选择页搜索',unix_timestamp(),unix_timestamp()),('compliancematter_check_index','合规审查事项：预览',unix_timestamp(),unix_timestamp()),('compliancematter_exporttocsv_index','合规审查事项：导出csv',unix_timestamp(),unix_timestamp()),('compliancematter_autosave_update','合规审查事项：自动更新',unix_timestamp(),unix_timestamp()),('compliancematter_autosave_create','合规审查事项：自动新增',unix_timestamp(),unix_timestamp()) on duplicate key update `name`=`name`;
insert into `action` (`name`,`description`,`update_time`,`create_time`) values('compliancematter_full_index','合规审查事项（全部字段）：首页',unix_timestamp(),unix_timestamp()),('compliancematter_full_read','合规审查事项（全部字段）：查看',unix_timestamp(),unix_timestamp()),('compliancematter_full_create','合规审查事项（全部字段）：新增',unix_timestamp(),unix_timestamp()),('compliancematter_full_update','合规审查事项（全部字段）：更新',unix_timestamp(),unix_timestamp()),('compliancematter_full_delete','合规审查事项（全部字段）：删除',unix_timestamp(),unix_timestamp()),('compliancematter_full_search','合规审查事项（全部字段）：搜索',unix_timestamp(),unix_timestamp()),('compliancematter_full_select','合规审查事项（全部字段）：选择页',unix_timestamp(),unix_timestamp()),('compliancematter_full_select_search','合规审查事项（全部字段）：选择页搜索',unix_timestamp(),unix_timestamp()) on duplicate key update `name`=`name`;

/******************** update ********************/
