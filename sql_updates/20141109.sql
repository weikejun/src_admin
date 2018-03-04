-- 用户退款表
DROP TABLE IF EXISTS `user_refund`;
CREATE TABLE `user_refund` (
      `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
      `order_id` int(11) NOT NULL COMMENT '订单ID',
      `status` smallint(6) NOT NULL DEFAULT '0' COMMENT '退款状态, 0-未处理；1-处理中；2-已完成',
      `type` smallint(6) NOT NULL DEFAULT '0' COMMENT '退款方式, 0-原路退；1-手工退；',
      `note` varchar(256) NOT NULL COMMENT '退款备注',
      `account` varchar(256) NOT NULL COMMENT '账户信息',
      `creator` varchar(32) DEFAULT 'system' COMMENT '创建人类型，system；admin; buyer',
      `creator_id` varchar(32) DEFAULT '0' COMMENT '创建人ID',
      `operator` varchar(32) DEFAULT 'admin' COMMENT '操作人',
      `operator_id` varchar(32) DEFAULT '0' COMMENT '操作人ID',
      `create_time` int(11) NOT NULL COMMENT '创建时间',
      `update_time` int(11) NOT NULL COMMENT '更新时间',
      `reason` varchar(256) NOT NULL COMMENT '退款原因',
      PRIMARY KEY (`id`),
      KEY `order_id_index` (`order_id`),
      KEY `create_time_index` (`create_time`)
) ENGINE=InnoDB AUTO_INCREMENT=1010001 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户退款表';

alter table `payment` add column `refund_amount` float(10,2) DEFAULT NULL COMMENT '退款金额';
alter table `payment` add column `refund_memo` varchar(256) DEFAULT NULL  COMMENT '退款说明';
alter table `payment` add KEY `trade_no_index` (`trade_no`);
alter table `payment` add KEY `platform_trade_no_index` (`platform_trade_no`);
alter table `payment` add KEY `create_time_index` (`create_time`);

insert into `permission` (name, description, create_time) values('userrefund', '退款管理读', unix_timestamp()), ('userrefund_w', '退款管理写', unix_timestamp());
insert into `action` (name, description, permission_id, create_time, update_time) select 'userrefund_index', '', id, unix_timestamp() , unix_timestamp() from permission where name='userrefund';
insert into `action` (name, description, permission_id, create_time, update_time) select 'userrefund_read', '', id, unix_timestamp() , unix_timestamp() from permission where name='userrefund';
insert into `action` (name, description, permission_id, create_time, update_time) select 'userrefund_search', '', id, unix_timestamp() , unix_timestamp() from permission where name='userrefund';
insert into `action` (name, description, permission_id, create_time, update_time) select 'userrefund_select', '', id, unix_timestamp() , unix_timestamp() from permission where name='userrefund';
insert into `action` (name, description, permission_id, create_time, update_time) select 'userrefund_select_search', '', id, unix_timestamp() , unix_timestamp() from permission where name='userrefund';
insert into `action` (name, description, permission_id, create_time, update_time) select 'userrefund_create', '', id, unix_timestamp() , unix_timestamp() from permission where name='userrefund_w';
insert into `action` (name, description, permission_id, create_time, update_time) select 'userrefund_update', '', id, unix_timestamp() , unix_timestamp() from permission where name='userrefund_w';
insert into `action` (name, description, permission_id, create_time, update_time) select 'userrefund_delete', '', id, unix_timestamp() , unix_timestamp() from permission where name='userrefund_w';


