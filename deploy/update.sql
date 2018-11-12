/******************** create table ********************/

/******************** alter table ********************/
alter table `project` add `sequ` int(11) DEFAULT NULL after `status`;

/******************** insert ********************/

/******************** update ********************/
update `project` set `sequ` = `id`;
