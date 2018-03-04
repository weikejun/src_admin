insert into `permission` (name, description, create_time) values('livestock', '直播商品管理', 1418096179),('livestock_w', '直播商品写', 1418096179);
insert into `action` (name, description, permission_id, create_time, update_time) select 'livestock_index', '', id, 1418096179 , 1418096179 from permission where name='livestock';
insert into `action` (name, description, permission_id, create_time, update_time) select 'livestock_read', '', id, 1418096179 , 1418096179 from permission where name='livestock';
insert into `action` (name, description, permission_id, create_time, update_time) select 'livestock_search', '', id, 1418096179 , 1418096179 from permission where name='livestock';
insert into `action` (name, description, permission_id, create_time, update_time) select 'livestock_select', '', id, 1418096179 , 1418096179 from permission where name='livestock';
insert into `action` (name, description, permission_id, create_time, update_time) select 'livestock_select_search', '', id, 1418096179 , 1418096179 from permission where name='livestock';
insert into `action` (name, description, permission_id, create_time, update_time) select 'livestock_create', '', id, 1418096179 , 1418096179 from permission where name='livestock_w';
insert into `action` (name, description, permission_id, create_time, update_time) select 'livestock_update', '', id, 1418096179 , 1418096179 from permission where name='livestock_w';
insert into `action` (name, description, permission_id, create_time, update_time) select 'livestock_delete', '', id, 1418096179 , 1418096179 from permission where name='livestock_w';