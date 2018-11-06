/******************** create table ********************/

/******************** alter table ********************/

/******************** insert ********************/
insert into `action` (`name`,`description`,`update_time`,`create_time`) values('project_import_index','交易记录：导入csv',unix_timestamp(),unix_timestamp()) on duplicate key update `name`=`name`;

/******************** update ********************/
