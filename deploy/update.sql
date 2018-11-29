/******************** create table ********************/
CREATE TABLE `controller_actual` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(256) DEFAULT NULL COMMENT '名称',
    `description` text DEFAULT NULL COMMENT '认购人背景简介',
    `contact` varchar(64) DEFAULT NULL COMMENT '联系人',
    `contact_info` text DEFAULT NULL COMMENT '联系人信息',
    `create_time` int(11) DEFAULT NULL COMMENT '更新时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/******************** alter table ********************/
alter table `entity` add `register_address` varchar(64) DEFAULT NULL after `register_country`;
alter table `fund_lp` add `top_special` varchar(8) DEFAULT NULL COMMENT '上层是否有特殊情况' after `cert_no`;
alter table `fund_lp` add `is_gov_capital` varchar(8) DEFAULT NULL COMMENT '是否是国资' after `cert_no`;
alter table `fund_lp` add `have_for_capital` varchar(8) DEFAULT NULL COMMENT '是否有外资' after `cert_no`;
alter table `fund_lp` add `top_special_memo` text DEFAULT NULL COMMENT '上层特殊情况备注' after `cert_no`;
alter table `fund_lp` add `join_way` varchar(16) DEFAULT NULL COMMENT '进入方式' after `join_turn`;
alter table `fund_lp` add `subscribe_amount_memo` text DEFAULT NULL COMMENT '认缴金额备注' after `subscribe_amount`;
alter table `fund_lp` add `paid_amount_memo` text DEFAULT NULL COMMENT '实缴金额备注' after `paid_amount`;
alter table `fund_lp` add `share_transfer_currency` varchar(8) DEFAULT NULL COMMENT '份额转让货币' after `share_transfer`;
alter table `fund_lp` add `share_transfer_amount` varchar(16) DEFAULT NULL COMMENT '份额转让额度' after `share_transfer`;
alter table `fund_lp` add `share_transfer_file` varchar(16) DEFAULT NULL COMMENT '份额转让文件' after `share_transfer`;
alter table `fund_lp` add `capital_reduce` varchar(8) DEFAULT NULL COMMENT '有无减资' after `share_transfer_memo`;
alter table `fund_lp` add `capital_reduce_currency` varchar(8) DEFAULT NULL COMMENT '减资货币' after `share_transfer_memo`;
alter table `fund_lp` add `capital_reduce_amount` varchar(16) DEFAULT NULL COMMENT '减资额度' after `share_transfer_memo`;
alter table `fund_lp` add `capital_reduce_file` varchar(16) DEFAULT NULL COMMENT '减资文件' after `share_transfer_memo`;
alter table `fund_lp` add `capital_reduce_memo` text DEFAULT NULL COMMENT '减资备注' after `share_transfer_memo`;
alter table `fund_lp` add `admin_fee_agreement` varchar(8) DEFAULT NULL COMMENT '管理费是否有特别约定' after `side_letter`;
alter table `fund_lp` add `other_fee_agreement` varchar(8) DEFAULT NULL COMMENT '其它费用是否有特别约定' after `side_letter`;
alter table `entity` add `compliance_person` varchar(16) DEFAULT NULL COMMENT '合规负责人' after `legal_person`;
alter table `compliance_matter` add `action_req` varchar(16) DEFAULT NULL COMMENT '动作要求' after `action`;
alter table `fund_lp` add `gb_sign_memo` text DEFAULT NULL COMMENT 'GP&管理人已章备注';
alter table `fund_lp` add `aic_material_memo` text DEFAULT NULL COMMENT '工商变更资料提供备注';
alter table `fund_lp` add `lpac_commission_memo` text DEFAULT NULL COMMENT 'LPAC委任书备注';
alter table `fund_lp` add `entrust_agreement_memo` text DEFAULT NULL COMMENT '委托管理协议备注';
alter table `fund_lp` add `bank_entrustment_memo` text DEFAULT NULL COMMENT '银行托管信息页备注';
alter table `fund_lp` add `no_entrustment_memo` text DEFAULT NULL COMMENT '不托管协议备注';
alter table `fund_lp` add `investor_type_memo` text DEFAULT NULL COMMENT '投资者类型确认备注';
alter table `fund_lp` add `review` varchar(8) DEFAULT NULL COMMENT '回访';
alter table `fund_lp` add `review_memo` text DEFAULT NULL COMMENT '回访备注';
alter table `project` add `pay_currency` varchar(16) DEFAULT NULL COMMENT '源码实际支付币种' after `pay_amount`;
alter table `compliance_matter` add `expiry_memo` text DEFAULT NULL COMMENT '有效期备注' after `expiry`;
alter table `compliance_matter` add `action_freq` varchar(16) DEFAULT NULL COMMENT '动作频率' after `action_req`;
alter table `compliance_matter` add `limit_source_memo` text DEFAULT NULL COMMENT '限制来源备注';
alter table `compliance_matter` add `limit_source_type` text DEFAULT NULL COMMENT '限制来源类型';
alter table `compliance_matter` add `potence` varchar(8) DEFAULT NULL COMMENT '效力情况' after `requirement`;
alter table `fund_lp` add `status` varchar(32) DEFAULT 'valid' COMMENT '数据状态' after `id`;

/******************** insert ********************/
insert into `action` (`name`,`description`,`update_time`,`create_time`) values('fundlp_full_index','LP认购表（全部字段）：首页',unix_timestamp(),unix_timestamp()),('fundlp_full_read','LP认购表（全部字段）：查看',unix_timestamp(),unix_timestamp()),('fundlp_full_create','LP认购表（全部字段）：新增',unix_timestamp(),unix_timestamp()),('fundlp_full_update','LP认购表（全部字段）：更新',unix_timestamp(),unix_timestamp()),('fundlp_full_delete','LP认购表（全部字段）：删除',unix_timestamp(),unix_timestamp()),('fundlp_full_search','LP认购表（全部字段）：搜索',unix_timestamp(),unix_timestamp()),('fundlp_full_select','LP认购表（全部字段）：选择页',unix_timestamp(),unix_timestamp()),('fundlp_full_select_search','LP认购表（全部字段）：选择页搜索',unix_timestamp(),unix_timestamp()),('fundlp_recovery_index','LP认购表（全部字段）：回收站',unix_timestamp(),unix_timestamp()),('fundlp_captable_index','LP认购表（全部字段）：Captable',unix_timestamp(),unix_timestamp()),('fundlp_autosave_update','LP认购表：自动更新',unix_timestamp(),unix_timestamp()),('fundlp_autosave_create','LP认购表：自动新增',unix_timestamp(),unix_timestamp()) on duplicate key update `name`=`name`;
insert into `action` (`name`,`description`,`update_time`,`create_time`) values('controlleractual_index','LP实际控制人：首页',unix_timestamp(),unix_timestamp()),('controlleractual_read','LP实际控制人：查看',unix_timestamp(),unix_timestamp()),('controlleractual_create','LP实际控制人：新增',unix_timestamp(),unix_timestamp()),('controlleractual_update','LP实际控制人：更新',unix_timestamp(),unix_timestamp()),('controlleractual_delete','LP实际控制人：删除',unix_timestamp(),unix_timestamp()),('controlleractual_search','LP实际控制人：搜索',unix_timestamp(),unix_timestamp()),('controlleractual_select','LP实际控制人：选择页',unix_timestamp(),unix_timestamp()),('controlleractual_select_search','LP实际控制人：选择页搜索',unix_timestamp(),unix_timestamp()) on duplicate key update `name`=`name`;

/******************** update ********************/
update `project` set `pay_currency` = `invest_currency`;
