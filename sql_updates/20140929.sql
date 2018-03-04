DROP TABLE IF EXISTS `delivery_abroad`;
CREATE TABLE `delivery_abroad` (
    `id` int(11) NOT NULL auto_increment COMMENT '自增ID',
    `order_id` int(11) NOT NULL COMMENT '订单ID',
    `buyer_id` int(11) NOT NULL COMMENT '买手ID',
    `pack_id` int(11) NOT NULL COMMENT '包裹ID',
    `live_id` int(11) NOT NULL COMMENT '直播ID',
    `stock_id` int(11) NOT NULL COMMENT '直播ID',
    `sku_id` int(11) NOT NULL COMMENT 'SKU ID',
    `status` smallint NOT NULL COMMENT '结算标志, 0 未结算；1 已结算',
    `pay_time` int(11) DEFAULT NULL COMMENT '结算时间',
    `delivery_time` int(11) DEFAULT NULL COMMENT '海外发货时间',
    PRIMARY KEY (`id`),
    UNIQUE KEY `order_id_index` (`order_id`),
    KEY `buyer_id_index` (`buyer_id`),
    KEY `pack_id_index` (`pack_id`),
    KEY `live_id_index` (`live_id`),
    KEY `delivery_time_index` (`delivery_time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='海外发货列表';

insert into delivery_abroad (buyer_id,live_id,sku_id,pack_id,stock_id,order_id,delivery_time) select o.buyer_id,o.live_id,o.stock_amount_id,o.pack_id,o.stock_id,o.id,p.update_time from `pack` p left join `order` o on (o.pack_id=p.id) where p.status='send';
