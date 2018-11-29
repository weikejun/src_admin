DROP TABLE IF EXISTS `knowledge_cate`; /*知识大类*/

CREATE TABLE `knowledge_cate` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(256) DEFAULT NULL COMMENT '名称',
    `description` text DEFAULT NULL COMMENT '说明', 
    `memo` text DEFAULT NULL COMMENT '备注', 
    `operator` varchar(32) DEFAULT NULL COMMENT '添加人', 
    `update_time` int(11) DEFAULT NULL COMMENT '更新时间', 
    `create_time` int(11) DEFAULT NULL COMMENT '更新时间', 
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `knowledge_list`; /*知识列表*/

CREATE TABLE `knowledge_list` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `cate_id` int(11) DEFAULT NULL COMMENT '大类ID',
    `name` varchar(256) DEFAULT NULL COMMENT '名称',
    `content` mediumtext DEFAULT NULL COMMENT '内容', 
    `reference` text DEFAULT NULL COMMENT '参考资料', 
    `memo` text DEFAULT NULL COMMENT '备注', 
    `operator` varchar(32) DEFAULT NULL COMMENT '添加人', 
    `update_time` int(11) DEFAULT NULL COMMENT '更新时间', 
    `create_time` int(11) DEFAULT NULL COMMENT '更新时间', 
    INDEX `cate_id_idx` (`cate_id`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `contract_term`; /*合同条款*/

CREATE TABLE `contract_term` (
    `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
    `status` varchar(16) DEFAULT '未审核' COMMENT '审核状态', 
    `trade_doc` varchar(32) DEFAULT NULL COMMENT '交易文件', 
    `term` varchar(256) DEFAULT NULL COMMENT '所属条款', 
    `term_detail` text DEFAULT NULL COMMENT '条款具体事项', 
    `standard` text DEFAULT NULL COMMENT '源码标准及关注点', 
    `permission` text DEFAULT NULL COMMENT '权限说明', 
    `lawyer_reminder` text DEFAULT NULL COMMENT '提醒律师的事项', 
    `our_reason` text DEFAULT NULL COMMENT '我方主要理由', 
    `opp_reason` text DEFAULT NULL COMMENT '对方主要理由', 
    `terms_rmb` text DEFAULT NULL COMMENT '标准条款-RMB', 
    `terms_usd` text DEFAULT NULL COMMENT '标准条款-USD', 
    `exceptional` text DEFAULT NULL COMMENT '特殊情况处理', 
    `compromise` text DEFAULT NULL COMMENT '折中处理', 
    `compromise_rmb` text DEFAULT NULL COMMENT '折中方案的示范条款-RMB项目', 
    `compromise_usd` text DEFAULT NULL COMMENT '折中方案的示范条款-USD项目', 
    `baseline` text DEFAULT NULL COMMENT '底线方案', 
    `baseline_rmb` text DEFAULT NULL COMMENT '底线方案示范条款-RMB', 
    `baseline_usd` text DEFAULT NULL COMMENT '底线方案示范条款-USD', 
    `other_case` text DEFAULT NULL COMMENT '其他可参考方案', 
    `other_term` text DEFAULT NULL COMMENT '其他可参考条款', 
    `other_detail` text DEFAULT NULL COMMENT '其他说明或要注意的问题', 
    `memo` text DEFAULT NULL COMMENT '备注', 
    `operator` varchar(32) DEFAULT NULL COMMENT '添加人', 
    `update_time` int(11) DEFAULT NULL COMMENT '更新时间', 
    `create_time` int(11) DEFAULT NULL COMMENT '创建时间', 
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `knowledge_checklist`; /*知识经验checklist*/
CREATE TABLE `knowledge_checklist` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `version` varchar(32) DEFAULT NULL COMMENT '版本', 
    `list_info` text DEFAULT NULL COMMENT '清单说明', 
    `content` text DEFAULT NULL COMMENT '内容', 
    `operator` varchar(32) DEFAULT NULL COMMENT '添加人', 
    `update_time` int(11) DEFAULT NULL COMMENT '更新时间', 
    `create_time` int(11) DEFAULT NULL COMMENT '更新时间', 
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
