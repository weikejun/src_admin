-- 推送任务表
DROP TABLE IF EXISTS `task_push`;
CREATE TABLE `task_push` (
      `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
      `status` smallint(6) NOT NULL DEFAULT '0' COMMENT '任务状态, 0-未处理；1-处理中；2-已完成',
      `content` varchar(256) NOT NULL COMMENT '推送内容',
      `creator_id` int(11) DEFAULT '0' COMMENT '创建人ID',
      `type` smallint(6) NOT NULL DEFAULT '0' COMMENT '推送范围, 0-全部；1-指定',
      `user_ids` text DEFAULT NULL COMMENT '创建人ID',
      `success` int(11) NOT NULL COMMENT '成功数',
      `fail` int(11) NOT NULL COMMENT '失败数',
      `create_time` int(11) NOT NULL COMMENT '任务创建时间',
      `push_time` int(11) NOT NULL COMMENT '开始推送时间',
      `end_time` int(11) NOT NULL COMMENT '推送完成时间',
      PRIMARY KEY (`id`),
      KEY `push_time_index` (`push_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='推送任务表';

insert into `permission` (name, description, create_time) values('taskpush', '定时消息读', unix_timestamp()), ('taskpush_w', '定时消息写', unix_timestamp());
insert into `action` (name, description, permission_id, create_time, update_time) select 'taskpush_index', '', id, unix_timestamp() , unix_timestamp() from permission where name='taskpush';
insert into `action` (name, description, permission_id, create_time, update_time) select 'taskpush_read', '', id, unix_timestamp() , unix_timestamp() from permission where name='taskpush';
insert into `action` (name, description, permission_id, create_time, update_time) select 'taskpush_search', '', id, unix_timestamp() , unix_timestamp() from permission where name='taskpush';
insert into `action` (name, description, permission_id, create_time, update_time) select 'taskpush_select', '', id, unix_timestamp() , unix_timestamp() from permission where name='taskpush';
insert into `action` (name, description, permission_id, create_time, update_time) select 'taskpush_select_search', '', id, unix_timestamp() , unix_timestamp() from permission where name='taskpush';
insert into `action` (name, description, permission_id, create_time, update_time) select 'taskpush_create', '', id, unix_timestamp() , unix_timestamp() from permission where name='taskpush_w';
insert into `action` (name, description, permission_id, create_time, update_time) select 'taskpush_update', '', id, unix_timestamp() , unix_timestamp() from permission where name='taskpush_w';
insert into `action` (name, description, permission_id, create_time, update_time) select 'taskpush_delete', '', id, unix_timestamp() , unix_timestamp() from permission where name='taskpush_w';
