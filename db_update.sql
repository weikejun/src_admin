alter table `admin` add `email` varchar(32) DEFAULT NULL after `real_name`;


alter table `project` modify `deal_manager` text DEFAULT NULL COMMENT '本轮交易负责人';
alter table `project` modify `law_firm` text DEFAULT NULL COMMENT '源码委托律所';
alter table `project` add `loan_schedule` varchar(8) DEFAULT NULL COMMENT '借款进度';
alter table `project` add `trade_file_schedule` varchar(8) DEFAULT NULL COMMENT '交易文件进度';
alter table `project` add `expect_sign_date` varchar(11) DEFAULT NULL COMMENT '预计签约日期'; 
alter table `project` add `expect_pay_schedule` text DEFAULT NULL COMMENT '预计交割付款安排'; 
alter table `project` add `trade_schedule_memo` text DEFAULT NULL COMMENT '交易进度其他说明'; 
alter table `project` add `trade_schedule_todo` text DEFAULT NULL COMMENT '交易进度ToDo'; 
alter table `project` add `ts_ratio` varchar(8) DEFAULT NULL COMMENT 'TS/决策口径占比';
alter table `project` add `lawyer_fee` varchar(8) DEFAULT NULL COMMENT '律师费';
