-- insert into `permission` (name, description, create_time) values('coupon', '代金券管理读', unix_timestamp()),('coupon_w', '代金券管理写', unix_timestamp());
-- insert into `action` (name, description, permission_id, create_time, update_time) select 'coupon_index', '', id, unix_timestamp() , unix_timestamp() from permission where name='coupon';
-- insert into `action` (name, description, permission_id, create_time, update_time) select 'coupon_read', '', id, unix_timestamp() , unix_timestamp() from permission where name='coupon';
insert into `action` (name, description, permission_id, create_time, update_time) select 'coupon_search', '', id, unix_timestamp() , unix_timestamp() from permission where name='coupon';
insert into `action` (name, description, permission_id, create_time, update_time) select 'coupon_select', '', id, unix_timestamp() , unix_timestamp() from permission where name='coupon';
insert into `action` (name, description, permission_id, create_time, update_time) select 'coupon_select_search', '', id, unix_timestamp() , unix_timestamp() from permission where name='coupon';
-- insert into `action` (name, description, permission_id, create_time, update_time) select 'coupon_create', '', id, unix_timestamp() , unix_timestamp() from permission where name='coupon_w';
-- insert into `action` (name, description, permission_id, create_time, update_time) select 'coupon_update', '', id, unix_timestamp() , unix_timestamp() from permission where name='coupon_w';
-- insert into `action` (name, description, permission_id, create_time, update_time) select 'coupon_delete', '', id, unix_timestamp() , unix_timestamp() from permission where name='coupon_w';
