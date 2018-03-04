alter table `stock` add column `flow_time` int(11) default null after `update_time`;
update `stock` set `flow_time`=`create_time`;
