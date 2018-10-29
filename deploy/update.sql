/******************** create table ********************/

DROP TABLE IF EXISTS `checklist`; /*基金checklist清单*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `checklist` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `version` varchar(32) DEFAULT NULL COMMENT '版本', 
    `field` varchar(16) DEFAULT NULL COMMENT '字段类型', 
    `content` text DEFAULT NULL COMMENT '内容', 
    `operator` varchar(32) DEFAULT NULL COMMENT '添加人', 
    `update_time` int(11) DEFAULT NULL COMMENT '更新时间', 
    `create_time` int(11) DEFAULT NULL COMMENT '更新时间', 
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/******************** alter table ********************/
alter table `fund_lp` add `compliance_checklist` varchar(32) DEFAULT NULL COMMENT '合规要求清单';
alter table `fund_lp` add `filing_checklist` varchar(32) DEFAULT NULL COMMENT 'filing文件清单';

/******************** insert ********************/
insert into `action` (`name`,`description`,`update_time`,`create_time`) values('checklist_index','Checklist清单：首页',unix_timestamp(),unix_timestamp()),('checklist_read','Checklist清单：查看',unix_timestamp(),unix_timestamp()),('checklist_create','Checklist清单：新增',unix_timestamp(),unix_timestamp()),('checklist_update','Checklist清单：更新',unix_timestamp(),unix_timestamp()),('checklist_delete','Checklist清单：删除',unix_timestamp(),unix_timestamp()),('checklist_search','Checklist清单：搜索',unix_timestamp(),unix_timestamp()),('checklist_select','Checklist清单：选择页',unix_timestamp(),unix_timestamp()),('checklist_select_search','Checklist清单：选择页搜索',unix_timestamp(),unix_timestamp()),('checklist_check_index','Checklist清单：预览页',unix_timestamp(),unix_timestamp()),('checklist_clone','Checklist清单：复制',unix_timestamp(),unix_timestamp()) on duplicate key update `name`=`name`;

insert into `permission` (`name`,`description`,`create_time`) values('checklist_read','Checklist清单：模块读',unix_timestamp()),('checklist_write','Checklist清单：模块写',unix_timestamp()) on duplicate key update `create_time`=unix_timestamp();

/******************** update ********************/
