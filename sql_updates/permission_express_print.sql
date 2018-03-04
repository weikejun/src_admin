insert into `permission` (name, description, create_time) values('expressprint', '发货打印记录读', unix_timestamp()),('expressprint_w', '发货打印记录写', unix_timestamp());
insert into `action` (name, description, permission_id, create_time, update_time) select 'expressprint_index', '', id, unix_timestamp() , unix_timestamp() from permission where name='expressprint';
insert into `action` (name, description, permission_id, create_time, update_time) select 'expressprint_read', '', id, unix_timestamp() , unix_timestamp() from permission where name='expressprint';
insert into `action` (name, description, permission_id, create_time, update_time) select 'expressprint_search', '', id, unix_timestamp() , unix_timestamp() from permission where name='expressprint';
insert into `action` (name, description, permission_id, create_time, update_time) select 'expressprint_select', '', id, unix_timestamp() , unix_timestamp() from permission where name='expressprint';
insert into `action` (name, description, permission_id, create_time, update_time) select 'expressprint_select_search', '', id, unix_timestamp() , unix_timestamp() from permission where name='expressprint';
insert into `action` (name, description, permission_id, create_time, update_time) select 'expressprint_create', '', id, unix_timestamp() , unix_timestamp() from permission where name='expressprint_w';
insert into `action` (name, description, permission_id, create_time, update_time) select 'expressprint_update', '', id, unix_timestamp() , unix_timestamp() from permission where name='expressprint_w';
insert into `action` (name, description, permission_id, create_time, update_time) select 'expressprint_delete', '', id, unix_timestamp() , unix_timestamp() from permission where name='expressprint_w';
