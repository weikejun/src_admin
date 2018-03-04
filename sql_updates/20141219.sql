alter table `user` add index `idx_phone`(`phone`);
alter table `user` change column `wx_accesstoken` `wx_accesstoken` varchar(256) DEFAULT NULL;
alter table `user` change column `wx_refreshtoken` `wx_refreshtoken` varchar(256) DEFAULT NULL;
alter table `user` add index `idx_openid_platform_type`(`wx_openid`, `third_platform_type`);
alter table `user` drop index `i_phone`;

alter table `live` add index `idx_start_time_end_time_status`(`start_time`, `end_time`, `status`);

alter table `stock` add index `idx_live_id_status_create_time`(`live_id`, `status`, `create_time`), add index `idx_buyer_id_onshelf`(`buyer_id`, `onshelf`);

alter table `user_reminder` add index `idx_model_id`(`model_id`);

alter table `order_log` add index `idx_order_id`(`order_id`);

alter table `pack` add index `idx_buyer_id`(`buyer_id`);

update `buyer` set `background_pic`='/public_upload/7/3/e/73e05bae969ec779ce4d0365b1a2ddc4.png', `background_pic_small`='/public_upload/b/c/1/bc1aec935e92666ebb73846b29b9c8c0.png';

alter table `order` add index `idx_buyer_id_status`(`buyer_id`, `status`);

CREATE TABLE `pay_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `amount` float(10,2) DEFAULT NULL COMMENT '订单总价',
  `status` varchar(32) DEFAULT 'wait_prepay' NOT NULL COMMENT '订单状态：wait_prepay,prepayed,wait_pay,payed,packed,wait_refund,refund,returned,fail,to_demostic,to_user,post_sale,success,canceled,timeout,full_refund',
  `pre_payment_id` int(11) DEFAULT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `coupon_id` varchar(12) NOT NULL COMMENT '代金券号',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `vid` tinyint(2) DEFAULT '0' COMMENT '数据版本0旧1新',
  `pay_type` tinyint(2) DEFAULT '0' COMMENT '支付方式，1：全款，0：预付款',
  PRIMARY KEY (`id`),
  KEY `idx_userid` (`user_id`),
  KEY `idx_create` (`create_time`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='支付订单';


alter table `order` add column `vid` tinyint(2) default '0' comment '订单版本标识';
alter table `order` add column `pay_order_id` int(11) comment '支付订单的id';
alter table `order` add column `pay_type` tinyint(2)  default '0' comment '支付方式：0，预付金模式；1，全款模式';

alter table `buyer_pic` change column `liked` `liked` int(11) default '0' comment '喜欢数';
alter table `buyer_pic` change column `commented` `commented` int(11) default '0' comment '评论数';

alter table `index_new` add column `url` varchar(256) DEFAULT NULL COMMENT '活动链接';



alter table `order` add index `idx_pay_order_id`(`pay_order_id`);
alter table `payment` add index `idx_order_id`(`order_id`);
alter table `securecode` add index `idx_user_id`(`user_id`);
alter table `stock_amount` add index `idx_stock_id`(`stock_id`, `valid`);
alter table `user_addr` add index `idx_user_id`(`user_id`);
