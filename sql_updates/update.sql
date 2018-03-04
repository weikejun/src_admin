use aimeizhuyi;
/*
alter table buyer drop column country_pic;
alter table `pack` add column `update_time` int(11) DEFAULT NULL;
alter table `pack` add column `logistic_imgs` text after `imgs`;
*/
/* 2014-07-11添加*/
alter table `user` add column `easemob_username` varchar(64) DEFAULT NULL;
alter table `user` add column `easemob_password` varchar(64) DEFAULT NULL;

alter table `buyer` add column `easemob_username` varchar(64) DEFAULT NULL;
alter table `buyer` add column `easemob_password` varchar(64) DEFAULT NULL;

--
-- Table structure for table `easemob_anonymous`
--

DROP TABLE IF EXISTS `easemob_anonymous`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `easemob_anonymous` (
  `id` int(11) not null auto_increment,
  `username` varchar(64) not null,
  `password` varchar(64)  not null,
  `session_id` varchar(64) not null,
  `create_time` int(11) default null,
  `update_time` int(11) default null,
  PRIMARY KEY (`id`),
  UNIQUE KEY `i_username` (`username`),
  UNIQUE KEY `i_session_id` (`session_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='easemob匿名用户表';
/*!40101 SET character_set_client = @saved_cs_client */;


ALTER TABLE `buyer` ADD UNIQUE KEY `i_easemob_username` (`easemob_username`);
ALTER TABLE `user` ADD UNIQUE KEY `i_easemob_username` (`easemob_username`);

/* 2104-07-23 update by nash */
ALTER TABLE `permission` ADD UNIQUE KEY (`name`);
ALTER TABLE `group` ADD UNIQUE KEY (`name`);

DROP TABLE IF EXISTS `action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `action` (
  `id` int(11) not null auto_increment,
  `name` varchar(32) not null COMMENT '名称',
  `description` text COMMENT '详细说明',
  `permission_name` varchar(32) default null COMMENT '权限名称',
  `create_time` int(11) default null COMMENT '创建时间',
  `update_time` int(11) default null COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='ACTION权限管理';
/*!40101 SET character_set_client = @saved_cs_client */;
insert into action(`name`,`permission_name`,`create_time`,`update_time`) values
("logistic_index","logistic",1406125305,1406125305),
("logistic_select_search","logistic",1406125305,1406125305),
("logistic_update","logistic",1406125305,1406125305),
("logistic_search","logistic",1406125305,1406125305),
("logistic_delete","logistic",1406125305,1406125305),
("logistic_read","logistic",1406125305,1406125305),
("logistic_select","logistic",1406125305,1406125305),
("logistic_create","logistic",1406125305,1406125305),
("stockamount_index","stockamount",1406125305,1406125305),
("stockamount_select_search","stockamount",1406125305,1406125305),
("stockamount_update","stockamount",1406125305,1406125305),
("stockamount_search","stockamount",1406125305,1406125305),
("stockamount_delete","stockamount",1406125305,1406125305),
("stockamount_read","stockamount",1406125305,1406125305),
("stockamount_select","stockamount",1406125305,1406125305),
("stockamount_create","stockamount",1406125305,1406125305),
("user_index","user",1406125305,1406125305),
("user_select_search","user",1406125305,1406125305),
("user_update","user",1406125305,1406125305),
("user_search","user",1406125305,1406125305),
("user_delete","user",1406125305,1406125305),
("user_read","user",1406125305,1406125305),
("user_select","user",1406125305,1406125305),
("user_create","user",1406125305,1406125305),
("systemlog_index","systemlog",1406125305,1406125305),
("systemlog_select_search","systemlog",1406125305,1406125305),
("systemlog_update","systemlog",1406125305,1406125305),
("systemlog_search","systemlog",1406125305,1406125305),
("systemlog_delete","systemlog",1406125305,1406125305),
("systemlog_read","systemlog",1406125305,1406125305),
("systemlog_select","systemlog",1406125305,1406125305),
("systemlog_create","systemlog",1406125305,1406125305),
("buyer_index","buyer",1406125305,1406125305),
("buyer_select_search","buyer",1406125305,1406125305),
("buyer_update","buyer",1406125305,1406125305),
("buyer_search","buyer",1406125305,1406125305),
("buyer_delete","buyer",1406125305,1406125305),
("buyer_read","buyer",1406125305,1406125305),
("buyer_select","buyer",1406125305,1406125305),
("buyer_create","buyer",1406125305,1406125305),
("live_index","live",1406125305,1406125305),
("live_select_search","live",1406125305,1406125305),
("live_update","live",1406125305,1406125305),
("live_search","live",1406125305,1406125305),
("live_delete","live",1406125305,1406125305),
("live_read","live",1406125305,1406125305),
("live_select","live",1406125305,1406125305),
("live_create","live",1406125305,1406125305),
("payment_index","payment",1406125305,1406125305),
("payment_select_search","payment",1406125305,1406125305),
("payment_update","payment",1406125305,1406125305),
("payment_search","payment",1406125305,1406125305),
("payment_delete","payment",1406125305,1406125305),
("payment_read","payment",1406125305,1406125305),
("payment_select","payment",1406125305,1406125305),
("payment_create","payment",1406125305,1406125305),
("pack_index","pack",1406125305,1406125305),
("pack_select_search","pack",1406125305,1406125305),
("pack_update","pack",1406125305,1406125305),
("pack_search","pack",1406125305,1406125305),
("pack_delete","pack",1406125305,1406125305),
("pack_read","pack",1406125305,1406125305),
("pack_select","pack",1406125305,1406125305),
("pack_create","pack",1406125305,1406125305),
("stock_index","stock",1406125305,1406125305),
("stock_select_search","stock",1406125305,1406125305),
("stock_update","stock",1406125305,1406125305),
("stock_search","stock",1406125305,1406125305),
("stock_delete","stock",1406125305,1406125305),
("stock_read","stock",1406125305,1406125305),
("stock_select","stock",1406125305,1406125305),
("stock_create","stock",1406125305,1406125305),
("order_index","order",1406125305,1406125305),
("order_select_search","order",1406125305,1406125305),
("order_update","order",1406125305,1406125305),
("order_search","order",1406125305,1406125305),
("order_delete","order",1406125305,1406125305),
("order_read","order",1406125305,1406125305),
("order_select","order",1406125305,1406125305),
("order_create","order",1406125305,1406125305),
("admin_index","auth",1406125305,1406125305),
("admin_select_search","auth",1406125305,1406125305),
("admin_update","auth",1406125305,1406125305),
("admin_search","auth",1406125305,1406125305),
("admin_delete","auth",1406125305,1406125305),
("admin_read","auth",1406125305,1406125305),
("admin_select","auth",1406125305,1406125305),
("admin_create","auth",1406125305,1406125305),
("permission_index","auth",1406125305,1406125305),
("permission_select_search","auth",1406125305,1406125305),
("permission_update","auth",1406125305,1406125305),
("permission_search","auth",1406125305,1406125305),
("permission_delete","auth",1406125305,1406125305),
("permission_read","auth",1406125305,1406125305),
("permission_select","auth",1406125305,1406125305),
("permission_create","auth",1406125305,1406125305),
("group_index","auth",1406125305,1406125305),
("group_select_search","auth",1406125305,1406125305),
("group_update","auth",1406125305,1406125305),
("group_search","auth",1406125305,1406125305),
("group_delete","auth",1406125305,1406125305),
("group_read","auth",1406125305,1406125305),
("group_select","auth",1406125305,1406125305),
("group_create","auth",1406125305,1406125305),
("rolepermission_index","auth",1406125305,1406125305),
("rolepermission_select_search","auth",1406125305,1406125305),
("rolepermission_update","auth",1406125305,1406125305),
("rolepermission_search","auth",1406125305,1406125305),
("rolepermission_delete","auth",1406125305,1406125305),
("rolepermission_read","auth",1406125305,1406125305),
("rolepermission_select","auth",1406125305,1406125305),
("rolepermission_create","auth",1406125305,1406125305),
("admingroup_index","auth",1406125305,1406125305),
("admingroup_select_search","auth",1406125305,1406125305),
("admingroup_update","auth",1406125305,1406125305),
("admingroup_search","auth",1406125305,1406125305),
("admingroup_delete","auth",1406125305,1406125305),
("admingroup_read","auth",1406125305,1406125305),
("admingroup_select","auth",1406125305,1406125305),
("admingroup_create","auth",1406125305,1406125305),
("action_index","auth",1406125305,1406125305),
("action_select_search","auth",1406125305,1406125305),
("action_update","auth",1406125305,1406125305),
("action_search","auth",1406125305,1406125305),
("action_delete","auth",1406125305,1406125305),
("action_read","auth",1406125305,1406125305),
("action_select","auth",1406125305,1406125305),
("action_create","auth",1406125305,1406125305);

insert into permission(`name`,`create_time`) values
("logistic", 1406127305),
("stockamount", 1406127305),
("user", 1406127305),
("systemlog", 1406127305),
("buyer", 1406127305),
("live", 1406127305),
("payment", 1406127305),
("pack", 1406127305),
("stock", 1406127305),
("order", 1406127305),
("auth", 1406127305);

/** update by nash at 2014-07-27 */
alter table `role_permission` modify `group_id`  text DEFAULT NULL COMMENT '权限组ID';
alter table `role_permission` modify `admin_id`  text DEFAULT NULL COMMENT '系统用户ID';

/** update by nash at 2014-07-29 */
alter table payment add source enum('zfb','wx','wy') DEFAULT 'zfb' COMMENT '支付来源:支付宝,微信,网银' after amount;
alter table payment add remark varchar(1024) DEFAULT NULL after source;

/** action对应permissionName改为permissionId */
alter table action add `permission_id` int(11) DEFAULT NULL after `permission_name`;
update action set permission_id=1 where permission_name="auth";
update action set permission_id=13 where permission_name="auth_w";
update action set permission_id=6 where permission_name="buyer";
update action set permission_id=14 where permission_name="buyer_w";
update action set permission_id=7 where permission_name="live";
update action set permission_id=15 where permission_name="live_w";
update action set permission_id=2 where permission_name="logistic";
update action set permission_id=16 where permission_name="logistic_w";
update action set permission_id=11 where permission_name="order";
update action set permission_id=17 where permission_name="order_w";
update action set permission_id=9 where permission_name="pack";
update action set permission_id=18 where permission_name="pack_w";
update action set permission_id=8 where permission_name="payment";
update action set permission_id=19 where permission_name="payment_w";
update action set permission_id=10 where permission_name="stock";
update action set permission_id=3 where permission_name="stockamount";
update action set permission_id=21 where permission_name="stockamount_w";
update action set permission_id=20 where permission_name="stock_w";
update action set permission_id=5 where permission_name="systemlog";
update action set permission_id=22 where permission_name="systemlog_w";
update action set permission_id=4 where permission_name="user";
update action set permission_id=23 where permission_name="user_w";
alter table action drop column permission_name;

/** update by nash at 2014-08-13 */
alter table `payment` modify `source` varchar(64) DEFAULT 'zfb' COMMENT '支付来源';
alter table `payment` add `trade_no` varchar(64) DEFAULT NULL  COMMENT '订单号' after `amount`;

/** update by jim at 2014-08-13 */
alter table `order` add `sys_note` text DEFAULT NULL COMMENT '后台备注' after `note`;

/** update by jim at 2014-09-02 */
alter table `order_log` add column `operator` varchar(32) DEFAULT '';
alter table `order_log` add column `operator_id` varchar(32) DEFAULT '';

/** update by nash at 2014-09-06 */
DROP TABLE IF EXISTS `live_flow`;
CREATE TABLE `live_flow` (
  `id` int(11) not null auto_increment,
  `live_id` varchar(64) not null,
  `imgs` text not null,
  `content` text not null,
  `flow_time` int(11) not null,
  `create_time` int(11) default null,
  `update_time` int(11) default null,
  PRIMARY KEY (`id`),
  KEY `live_id_index` (`live_id`),
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='直播内容流';
