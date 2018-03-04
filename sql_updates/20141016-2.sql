alter table `buyer` add column `ship_percent` varchar(5) default null comment "发货结算比例";
update `buyer` set `ship_percent`='50%';

