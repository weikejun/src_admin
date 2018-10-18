alter table project add `each_side_duty` text DEFAULT NULL COMMENT '交割前重要义务' after delivery_duty;
alter table project add `before_delivery_duty` text DEFAULT NULL COMMENT '交割前重要义务' after delivery_duty;
