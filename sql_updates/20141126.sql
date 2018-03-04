insert into `permission` (name, description, create_time) values('cs', '客服账号管理读', unix_timestamp()),('cs_w', '客服账号管理写', unix_timestamp());
insert into `action` (name, description, permission_id, create_time, update_time) select 'cs_index', '', id, unix_timestamp() , unix_timestamp() from permission where name='cs';
insert into `action` (name, description, permission_id, create_time, update_time) select 'cs_read', '', id, unix_timestamp() , unix_timestamp() from permission where name='cs';
insert into `action` (name, description, permission_id, create_time, update_time) select 'cs_search', '', id, unix_timestamp() , unix_timestamp() from permission where name='cs';
insert into `action` (name, description, permission_id, create_time, update_time) select 'cs_select', '', id, unix_timestamp() , unix_timestamp() from permission where name='cs';
insert into `action` (name, description, permission_id, create_time, update_time) select 'cs_select_search', '', id, unix_timestamp() , unix_timestamp() from permission where name='cs';
insert into `action` (name, description, permission_id, create_time, update_time) select 'cs_create', '', id, unix_timestamp() , unix_timestamp() from permission where name='cs_w';
insert into `action` (name, description, permission_id, create_time, update_time) select 'cs_update', '', id, unix_timestamp() , unix_timestamp() from permission where name='cs_w';
insert into `action` (name, description, permission_id, create_time, update_time) select 'cs_delete', '', id, unix_timestamp() , unix_timestamp() from permission where name='cs_w';

-- 快递单打印记录 
DROP TABLE IF EXISTS `express_print`;
CREATE TABLE `express_print` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `storage_ids` varchar(1024) DEFAULT NULL COMMENT '订单号',
  `print_time` int(11) DEFAULT NULL COMMENT '打印时间',
  PRIMARY KEY (`id`),
  KEY `print_time_index` (`print_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='快递单打印记录';
