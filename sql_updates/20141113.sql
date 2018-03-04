alter table `user` add column `source` varchar(256) DEFAULT NULL COMMENT '注册来源，用来判断用户设备';
