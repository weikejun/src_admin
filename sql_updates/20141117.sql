insert into `permission` (name, description, create_time) values('easemobmsg', '聊天消息记录读', unix_timestamp()),('easemobmsg_w', '聊天消息记录写', unix_timestamp());
insert into `action` (name, description, permission_id, create_time, update_time) select 'easemobmsg_index', '', id, unix_timestamp() , unix_timestamp() from permission where name='easemobmsg';
insert into `action` (name, description, permission_id, create_time, update_time) select 'easemobmsg_read', '', id, unix_timestamp() , unix_timestamp() from permission where name='easemobmsg';
insert into `action` (name, description, permission_id, create_time, update_time) select 'easemobmsg_search', '', id, unix_timestamp() , unix_timestamp() from permission where name='easemobmsg';
insert into `action` (name, description, permission_id, create_time, update_time) select 'easemobmsg_select', '', id, unix_timestamp() , unix_timestamp() from permission where name='easemobmsg';
insert into `action` (name, description, permission_id, create_time, update_time) select 'easemobmsg_select_search', '', id, unix_timestamp() , unix_timestamp() from permission where name='easemobmsg';
insert into `action` (name, description, permission_id, create_time, update_time) select 'easemobmsg_create', '', id, unix_timestamp() , unix_timestamp() from permission where name='easemobmsg_w';
insert into `action` (name, description, permission_id, create_time, update_time) select 'easemobmsg_update', '', id, unix_timestamp() , unix_timestamp() from permission where name='easemobmsg_w';
insert into `action` (name, description, permission_id, create_time, update_time) select 'easemobmsg_delete', '', id, unix_timestamp() , unix_timestamp() from permission where name='easemobmsg_w';

alter table `easemob_msg` add KEY `send_time_index` (`send_time`);
