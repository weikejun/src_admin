alter table `company` add `main_founders` varchar(64) DEFAULT NULL COMMENT '最主要创始人' after `partner`;
alter table `project` add `supervisor` varchar(8) DEFAULT NULL COMMENT '源码监事' after `observer`;
alter table `entity` add `cate` text DEFAULT NULL COMMENT '类型' after `tp`;
alter table `entity` add `org_type` varchar(16) DEFAULT NULL COMMENT '组织形式' after `cate`;
