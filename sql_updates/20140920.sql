DROP TABLE IF EXISTS `storage`;
CREATE TABLE `storage` (
      `id` int(11) NOT NULL auto_increment COMMENT '自增ID',
      `order_id` int(11) NOT NULL COMMENT '订单ID',
      `pack_id` int(11) NOT NULL COMMENT '包裹ID',
      `logistic_id` int(11) NOT NULL COMMENT '国内物流ID',
      `stock_amount_id` int(11) NOT NULL COMMENT 'SKU ID',
      `location` varchar(16) NOT NULL COMMENT '货架号',
      `memo` text NOT NULL COMMENT '备忘',
      `status` varchar(16) DEFAULT 'waiting' COMMENT '库存状态, waiting-等待入库；in-已入库；out-已出库；dead-死库；return-退回',
      `stock_status` varchar(16) DEFAULT 'normal' COMMENT '商品状态，normal-正常；flaw-瑕疵；breakdown-损坏；loss-缺件',
      `create_time` int(11) DEFAULT NULL COMMENT '库存登记时间',
      `in_time` int(11) DEFAULT NULL COMMENT '入库时间',
      `out_time` int(11) DEFAULT NULL COMMENT '出库时间',
      `action_time` int(11) DEFAULT NULL COMMENT '处理时间',
      PRIMARY KEY (`id`),
      KEY `order_id_index` (`order_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='库存管理';

DROP TABLE IF EXISTS `sms_queue`;
CREATE TABLE `sms_queue` (
      `id` int(11) NOT NULL auto_increment COMMENT '自增ID',
      `phone` varchar(16) NOT NULL COMMENT '手机号',
      `content` varchar(256) NOT NULL COMMENT '短信内容',
      `order_id` int(11) NOT NULL COMMENT '商品ID',
      `status` smallint DEFAULT 0 COMMENT '状态，0-未发送；1-已发送',
      `create_time` int(11) DEFAULT NULL COMMENT '',
      `send_time` int(11) DEFAULT NULL COMMENT '处理时间',
      PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='短信发送队列';
