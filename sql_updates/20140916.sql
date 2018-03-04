alter table `order` add `buyer_id` int(11) DEFAULT NULL COMMENT '买手ID' after `live_id`;
update `order` o, `live` l set o.buyer_id=l.buyer_id where o.live_id=l.id;
