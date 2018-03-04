alter table `pack` add column `live_ids` varchar(256) DEFAULT null COMMENT '直播ID列表' after `live_id`;
update `pack` set `live_ids`=`live_id`;
