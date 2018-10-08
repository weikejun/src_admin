--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`; /*企业信息*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(32) DEFAULT NULL COMMENT '目标企业', 
    `short` varchar(32) DEFAULT NULL COMMENT '项目简称', 
    `hold_status` varchar(32) DEFAULT '正常' COMMENT '持有状态', 
    `project_type` varchar(32) DEFAULT NULL COMMENT '项目类别', 
    `management` varchar(32) DEFAULT NULL COMMENT '是否在管', 
    `bussiness` text DEFAULT NULL COMMENT '所属行业', 
    `bussiness_change` text DEFAULT NULL COMMENT '主营行业变化', 
    `region` text DEFAULT NULL COMMENT '所属地域', 
    `register_region` varchar(32) DEFAULT NULL COMMENT '注册地', 
    `main_founders` varchar(64) DEFAULT NULL COMMENT '最主要创始人', 
    `partner` varchar(32) DEFAULT NULL COMMENT '主管合伙人', 
    `manager` varchar(32) DEFAULT NULL COMMENT '项目负责人', 
    `legal_person` varchar(32) DEFAULT NULL COMMENT '法务负责人', 
    `finance_person` varchar(32) DEFAULT NULL COMMENT '财务负责人', 
    `filling_keeper` varchar(32) DEFAULT NULL COMMENT '文件Filing保管人', 
    `memo` text DEFAULT NULL COMMENT '备注', 
    `update_time` int(11) DEFAULT NULL COMMENT '更新时间', 
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `companyMemo`
--

DROP TABLE IF EXISTS `companyMemo`; /*企业备注*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `companyMemo` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `company_id` int(11) DEFAULT NULL COMMENT '企业ID',
    `title` text DEFAULT NULL COMMENT '事项', 
    `content` text DEFAULT NULL COMMENT '内容', 
    `operator` varchar(32) DEFAULT NULL COMMENT '添加人', 
    `update_time` int(11) DEFAULT NULL COMMENT '更新时间', 
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `project_memo`
--

DROP TABLE IF EXISTS `deal_memo`; /*交易备注*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `deal_memo` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `project_id` int(11) DEFAULT NULL COMMENT '交易ID',
    `title` text DEFAULT NULL COMMENT '事项', 
    `content` text DEFAULT NULL COMMENT '内容', 
    `operator` varchar(32) DEFAULT NULL COMMENT '添加人', 
    `update_time` int(11) DEFAULT NULL COMMENT '更新时间', 
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET character_set_client = @saved_cs_client */;
--
-- Table structure for table `entity`
--

DROP TABLE IF EXISTS `entity`; /*投资主体信息*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entity` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(32) DEFAULT NULL COMMENT '名称', 
    `register_country` varchar(32) DEFAULT NULL COMMENT '注册国', 
    `description` varchar(32) DEFAULT NULL COMMENT '描述', 
    `cate` text DEFAULT NULL COMMENT '类型', 
    `tp` text DEFAULT NULL COMMENT '类别', 
    `org_type` varchar(16) DEFAULT NULL COMMENT '组织形式', 
    `co_investment` varchar(32) DEFAULT '否' COMMENT 'co-investment', 
    `currency` varchar(32) DEFAULT 'USD' COMMENT '资金货币', 
    `memo` text DEFAULT NULL COMMENT '备注', 
    `update_time` int(11) DEFAULT NULL COMMENT '更新时间', 
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `entity_rel`
--

DROP TABLE IF EXISTS `entity_rel`; /*主体关系*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entity_rel` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `subject_id` int(11) DEFAULT NULL COMMENT '目标主体ID',
    `holder_id` int(11) DEFAULT NULL COMMENT '持有主体ID',
    `ratio` varchar(16) DEFAULT NULL COMMENT '持有比例',
    `update_time` int(11) DEFAULT NULL COMMENT '创建时间',
    PRIMARY KEY (`id`),
    UNIQUE KEY `subject_id_holder_id_index` (`subject_id`,`holder_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`; /*付款记录*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `project_id` int(11) DEFAULT NULL COMMENT '项目ID',
    `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
    `amount` varchar(16) DEFAULT NULL COMMENT '金额',
    `currency` varchar(16) DEFAULT NULL COMMENT '货币',
    `operator` varchar(16) DEFAULT NULL COMMENT '负责人',
    `pay_time` int(11) DEFAULT NULL COMMENT '打款时间',
    `memo` text DEFAULT NULL COMMENT '备注',
    `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `deal_decision`
--

DROP TABLE IF EXISTS `deal_decision`; /*投决意见*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `deal_decision` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `project_id` int(11) DEFAULT NULL COMMENT '交易ID',
    `partner` varchar(16) DEFAULT NULL COMMENT '合伙人',
    `decision` varchar(8) DEFAULT NULL COMMENT '投资意见',
    `ip` varchar(32) DEFAULT NULL COMMENT '来源IP',
    `expiration` varchar(11) DEFAULT NULL COMMENT '有效期',
    `memo` text DEFAULT NULL COMMENT '备注',
    `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=3366;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `project`
--

DROP TABLE IF EXISTS `project`; /*交易表*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project` (
    `id` int(11) not NULL AUTO_INCREMENT COMMENT '交易ID',
    `status` varchar(32) DEFAULT 'valid' COMMENT '数据状态', 
    `company_id` varchar(32) DEFAULT NULL COMMENT '目标企业', 
    `first_financing` varchar(32) DEFAULT NULL COMMENT '企业是否首次融资', 
    `company_period` text DEFAULT NULL COMMENT '目标企业阶段', 
    `company_character` text DEFAULT NULL COMMENT '目标企业性质', 
    `item_status` varchar(32) DEFAULT NULL COMMENT '整理状态', 
    `decision_date` varchar(11) DEFAULT NULL COMMENT '决策日期', 
    `kickoff_date` varchar(11) DEFAULT NULL COMMENT 'Kickoff日期', 
    `longstop_date` text DEFAULT NULL COMMENT 'LongStopDate', 
    `proj_status` varchar(32) DEFAULT NULL COMMENT '交易状态', 
    `close_date` varchar(11) DEFAULT NULL COMMENT '交割日期', 
    `count_captable` text DEFAULT NULL COMMENT '是否计入Captable', 
    `deal_type` varchar(32) DEFAULT NULL COMMENT '本轮交易类型', 
    `turn_sub` varchar(32) DEFAULT NULL COMMENT '企业所处轮次', 
    `turn` varchar(32) DEFAULT NULL COMMENT '企业轮次归类', 
    `new_follow` varchar(32) DEFAULT NULL COMMENT '项目新老类型', 
    `enter_exit_type` varchar(32) DEFAULT NULL COMMENT '源码投退类型', 
    `other_enter_exit_type` varchar(32) DEFAULT NULL COMMENT '其他投资人投退类型', 
    `res_consideration` varchar(32) DEFAULT NULL COMMENT '是否有资源作价', 
    `consideration_memo` text DEFAULT NULL COMMENT '资源作价备注', 
    `raw_stock_memo` text DEFAULT NULL COMMENT '老股转让情况备注', 
    `dilution_rate` varchar(8) DEFAULT NULL COMMENT '本轮稀释比例', 
    `deal_memo` text DEFAULT NULL COMMENT '本轮交易方案备注', 
    `value_currency` varchar(32) DEFAULT NULL COMMENT '估值计价货币', 
    `pre_money` varchar(32) DEFAULT NULL COMMENT '企业投前估值', 
    `financing_amount` varchar(32) DEFAULT NULL COMMENT '本轮新股融资总额', 
    `post_money` varchar(32) DEFAULT NULL COMMENT '企业投后估值', 
    `value_change` varchar(32) DEFAULT NULL COMMENT '企业估值涨幅（VS上轮）', 
    `affiliate_transaction` varchar(8) DEFAULT NULL COMMENT '是否为关联交易', 
    `new_old_stock` varchar(32) DEFAULT NULL COMMENT '源码购新股老股', 
    `invest_currency` varchar(32) DEFAULT NULL COMMENT '源码投资计价货币', 
    `entity_id` varchar(32) DEFAULT NULL COMMENT '源码投资主体', 
    `our_amount` varchar(32) DEFAULT NULL COMMENT '源码合同投资金额', 
    `stocknum_get` varchar(32) DEFAULT NULL COMMENT '本主体投时持有本轮股数', 
    `invest_turn` varchar(32) DEFAULT NULL COMMENT '本主体购买股权所属轮次', 
    `stock_property` varchar(32) DEFAULT NULL COMMENT '本主体购买股权属性', 
    `pay_amount` text DEFAULT NULL COMMENT '源码实际支付投资金额', 
    `amount_memo` text DEFAULT NULL COMMENT '金额备注', 
    `committee_view` text DEFAULT NULL COMMENT '投决意见', 
    `loan_cb` varchar(32) DEFAULT NULL COMMENT '源码借款或CB', 
    `loan_currency` varchar(32) DEFAULT NULL COMMENT '借款计价货币', 
    `loan_type` varchar(32) DEFAULT NULL COMMENT '借款类型', 
    `loan_entity_id` varchar(32) DEFAULT NULL COMMENT '源码出借主体', 
    `loan_amount` text DEFAULT NULL COMMENT '源码借款合同金额', 
    `loan_sign_date` varchar(11) DEFAULT NULL COMMENT '借款合同签署日期', 
    `loan_end_date` varchar(11) DEFAULT NULL COMMENT '借款到期日', 
    `loan_process` varchar(32) DEFAULT NULL COMMENT '借款处理', 
    `loan_memo` text DEFAULT NULL COMMENT '借款备注', 
    `other_investor` varchar(32) DEFAULT NULL COMMENT '本轮其他投资方', 
    `other_investor_summary` text DEFAULT NULL COMMENT '其他投资人及金额与投资比例摘要', 
    `has_exit` varchar(32) DEFAULT NULL COMMENT '源码是否有退出', 
    `exit_currency` varchar(32) DEFAULT NULL COMMENT '源码退出计价货币', 
    `exit_type` varchar(32) DEFAULT NULL COMMENT '源码退出方式', 
    `exit_profit` varchar(32) DEFAULT NULL COMMENT '退出盈亏情况', 
    `exit_entity_id` varchar(32) DEFAULT NULL COMMENT '源码退出主体', 
    `exit_entity_name` varchar(32) DEFAULT NULL COMMENT '源码退出描述', 
    `exit_company_value` varchar(32) DEFAULT NULL COMMENT '源码退出的企业估值', 
    `exit_stock_number` varchar(32) DEFAULT NULL COMMENT '源码退出的股数', 
    `exit_amount` varchar(32) DEFAULT NULL COMMENT '本交易退出的合同金额', 
    `exit_turn` varchar(32) DEFAULT NULL COMMENT '源码出售股权所属轮次', 
    `exit_stock_property` varchar(32) DEFAULT NULL COMMENT '源码出售的股权属性', 
    `exit_receive_amount` varchar(32) DEFAULT NULL COMMENT '源码本次退出实收金额', 
    `exit_memo` varchar(32) DEFAULT NULL COMMENT '源码退出备注', 
    `stocknum_all` varchar(32) DEFAULT NULL COMMENT '本轮企业总股数', 
    `captable_memo` text DEFAULT NULL COMMENT 'Captable备注', 
    `shareholding_founder` text DEFAULT NULL COMMENT '最主要创始人股数', 
    `shareholding_member` text DEFAULT NULL COMMENT '团队持股比例(不含ESOP)', 
    `shareholding_cofounders` text DEFAULT NULL COMMENT 'co-founders股数', 
    `shareholding_esop` varchar(32) DEFAULT NULL COMMENT 'ESOP股数', 
    `invest_competitor_limit` varchar(32) DEFAULT NULL COMMENT '投资人投资竞品限制', 
    `stock_transfer_limit` varchar(32) DEFAULT NULL COMMENT '投资人股权转让竞品限制', 
    `stock_transfer_limit_other` text DEFAULT NULL COMMENT '投资人股权转让其他限制', 
    `limit_other` text DEFAULT NULL COMMENT '源码其他限制/责任备注', 
    `founder_transfer_limit` varchar(32) DEFAULT NULL COMMENT '创始人股转限制', 
    `founder_transfer_limit_abstract` text DEFAULT NULL COMMENT '创始人股转限制简述', 
    `founder_vesting` varchar(32) DEFAULT NULL COMMENT '对创始人Vesting', 
    `founder_vesting_expiration` text DEFAULT NULL COMMENT '创始人Vesting期限', 
    `founder_vesting_memo` varchar(32) DEFAULT NULL COMMENT '创始人Vesting备注', 
    `founder_bussiness_limit` varchar(32) DEFAULT NULL COMMENT '创始人竞业限制', 
    `founder_limit_memo` text DEFAULT NULL COMMENT '创始人限制备忘', 
    `board_number` varchar(32) DEFAULT NULL COMMENT '公司董事席位数', 
    `our_board` varchar(32) DEFAULT NULL COMMENT '源码董事会席位', 
    `our_board_person` varchar(32) DEFAULT NULL COMMENT '源码董事姓名', 
    `our_board_status` varchar(32) DEFAULT NULL COMMENT '源码董事状态', 
    `our_board_register` varchar(32) DEFAULT NULL COMMENT '源码董事登记', 
    `observer` varchar(32) DEFAULT NULL COMMENT '源码观察员', 
    `supervisor` varchar(8) DEFAULT NULL COMMENT '源码监事', 
    `info_right` varchar(32) DEFAULT NULL COMMENT '源码信息权', 
    `info_right_threshold` text DEFAULT NULL COMMENT '信息权门槛', 
    `info_right_memo` text DEFAULT NULL COMMENT '信息权说明', 
    `check_right` varchar(32) DEFAULT NULL COMMENT '源码检查权', 
    `audit_right` varchar(32) DEFAULT NULL COMMENT '源码审计权', 
    `holder_veto` varchar(32) DEFAULT NULL COMMENT '股东会veto', 
    `board_veto` varchar(32) DEFAULT NULL COMMENT '董事会veto', 
    `veto_memo` text DEFAULT NULL COMMENT 'Veto备注', 
    `warrant` varchar(32) DEFAULT NULL COMMENT '源码是否有Warrant', 
    `warrant_memo` text DEFAULT NULL COMMENT 'Warrant备注', 
    `others_warrant` varchar(32) DEFAULT NULL COMMENT '其他投资人是否有Warrant', 
    `others_warrant_memo` text DEFAULT NULL COMMENT '其他投资人Warrant备注', 
    `preemptive` varchar(32) DEFAULT NULL COMMENT '优先认购权', 
    `preemptive_memo` text DEFAULT NULL COMMENT '优先认购权备注', 
    `excess_preemptive` varchar(32) DEFAULT NULL COMMENT '超额优先认购权', 
    `pri_assignee` varchar(32) DEFAULT NULL COMMENT '对创始人优先受让权', 
    `sell_together` varchar(32) DEFAULT NULL COMMENT '对创始人共售权', 
    `pri_common_stock` varchar(32) DEFAULT NULL COMMENT '对非创始人普通股优先权', 
    `buyback_right` varchar(32) DEFAULT NULL COMMENT '回购权', 
    `buyback_obligor` text DEFAULT NULL COMMENT '回购义务人', 
    `buyback_standard` text DEFAULT NULL COMMENT '回购金额计算标准', 
    `buyback_date` varchar(11) DEFAULT NULL COMMENT '本轮投资人可回购时间', 
    `buyback_memo` text DEFAULT NULL COMMENT '回购权备注', 
    `qualified_ipo_period` text DEFAULT NULL COMMENT '合格IPO年限', 
    `anti_dilution` varchar(32) DEFAULT NULL COMMENT '反稀释权', 
    `anti_dilution_way` varchar(32) DEFAULT NULL COMMENT '反稀释方法', 
    `drag_along` varchar(32) DEFAULT NULL COMMENT '拖售权', 
    `drag_along_veto` varchar(32) DEFAULT NULL COMMENT '源码对拖售权独立Veto', 
    `drag_along_memo` text DEFAULT NULL COMMENT '拖售权备注', 
    `liquidation_preference` varchar(32) DEFAULT NULL COMMENT '优先清算权', 
    `liquidation_preference_way` varchar(32) DEFAULT NULL COMMENT '优先清算权方法', 
    `liquidation_preference_memo` text DEFAULT NULL COMMENT '优先清算权备注', 
    `register_right` varchar(32) DEFAULT NULL COMMENT '登记权', 
    `dividends_preference` varchar(32) DEFAULT NULL COMMENT '优先分红权', 
    `dividends_preference_memo` text DEFAULT NULL COMMENT '优先分红备注', 
    `valuation_adjustment` varchar(32) DEFAULT NULL COMMENT '源码对赌/估值调整', 
    `valuation_adjustment_memo` text DEFAULT NULL COMMENT '源码对赌/估值调整简述', 
    `others_valuation_adjustment` varchar(32) DEFAULT NULL COMMENT '本轮其他投资人对赌/估值调整', 
    `others_valuation_adjustment_memo` text DEFAULT NULL COMMENT '本轮其他投资人对赌/估值调整简述', 
    `most_favored` varchar(32) DEFAULT NULL COMMENT '源码是否有最惠国待遇', 
    `most_favored_memo` text DEFAULT NULL COMMENT '最惠国待遇备注', 
    `rights_memo` text DEFAULT NULL COMMENT '源码权利备注', 
    `delivery_duty` text DEFAULT NULL COMMENT '重要交割后义务', 
    `right_changes` varchar(32) DEFAULT NULL COMMENT '源码前轮重要权利变化', 
    `right_update_record` varchar(32) DEFAULT NULL COMMENT '源码前轮权利更新记录', 
    `right_changes_memo` text DEFAULT NULL COMMENT '源码前轮权利变化备注', 
    `latest_right_changes` text DEFAULT NULL COMMENT '本轮与上轮投资权利变化', 
    `spouse_consent` varchar(32) DEFAULT NULL COMMENT '配偶同意函', 
    `risk_tip` text DEFAULT NULL COMMENT '重大风险提示', 
    `ts_changes` varchar(8) DEFAULT NULL COMMENT '与TS比重大变化', 
    `ts_changes_memo` text DEFAULT NULL COMMENT '与TS比重大变化备注', 
    `risk_management_view` text DEFAULT NULL COMMENT '风控保留意见', 
    `good_item` text DEFAULT NULL COMMENT '好条款摘选', 
    `terms_memo` text DEFAULT NULL COMMENT '条款其他备注', 
    `entity_odi` varchar(32) DEFAULT NULL COMMENT '源码主体ODI', 
    `mirror_hold` varchar(32) DEFAULT NULL COMMENT '镜像持股', 
    `mirror_hold_ratio` varchar(32) DEFAULT NULL COMMENT '镜像持股比例', 
    `entrustment` varchar(32) DEFAULT NULL COMMENT '是否存在代持情况', 
    `entrustment_entity_id` varchar(32) DEFAULT NULL COMMENT '代持主体', 
    `partner` varchar(16) DEFAULT NULL COMMENT '本轮主管合伙人', 
    `manager` varchar(16) DEFAULT NULL COMMENT '本轮项目负责人', 
    `finance_person` varchar(16) DEFAULT NULL COMMENT '本轮法务负责人', 
    `legal_person` varchar(16) DEFAULT NULL COMMENT '本轮法务负责人', 
    `deal_manager` text DEFAULT NULL COMMENT '本轮交易负责人', 
    `law_firm` text DEFAULT NULL COMMENT '源码委托律所', 
    `final_captable` varchar(32) DEFAULT NULL COMMENT 'FinalCaptalbe', 
    `final_word` varchar(32) DEFAULT NULL COMMENT 'FinalWord', 
    `closing_pdf` varchar(32) DEFAULT NULL COMMENT 'ClosingPDF', 
    `closing_original` varchar(32) DEFAULT NULL COMMENT 'Closing原件', 
    `overseas_stockcert` varchar(32) DEFAULT NULL COMMENT '境外股票证书', 
    `aic_registration` varchar(32) DEFAULT NULL COMMENT '人民币项目工商', 
    `pending` varchar(32) DEFAULT NULL COMMENT '有无未决事项', 
    `pending_detail` text DEFAULT NULL COMMENT '未决事项说明', 
    `work_memo` text DEFAULT NULL COMMENT '工作备忘', 
    `update_time` int(11) DEFAULT NULL COMMENT '更新时间', 
    `finance_check_sign` varchar(8) DEFAULT NULL COMMENT '财务审核签名',
    `finance_check_time` varchar(11) DEFAULT NULL COMMENT '财务审核时间', 
    `legal_check_sign` varchar(8) DEFAULT NULL COMMENT '法务审核签名',
    `legal_check_time` varchar(11) DEFAULT NULL COMMENT '法务审核时间', 
    `loan_schedule` varchar(8) DEFAULT NULL COMMENT '借款进度',
    `trade_schedule_todo` varchar(8) DEFAULT NULL COMMENT '交易文件进度',
    `expect_sign_date` varchar(11) DEFAULT NULL COMMENT '预计签约日期', 
    `expect_pay_schedule` text DEFAULT NULL COMMENT '预计交割付款安排', 
    `trade_schedule_memo` text DEFAULT NULL COMMENT '交易进度其他说明', 
    `trade_schedule_todo` text DEFAULT NULL COMMENT '交易进度ToDo', 
    `ts_ratio` varchar(8) DEFAULT NULL COMMENT 'TS/决策口径占比',
    `lawyer_fee` varchar(8) DEFAULT NULL COMMENT '律师费',
    `active_deal` varchar(8) DEFAULT NULL COMMENT '活动交易',
    `create_time` int(11) DEFAULT NULL COMMENT '创建时间', 
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mail_strategy`
--

DROP TABLE IF EXISTS `mail_strategy`; /*邮件策略*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail_strategy` (
    `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '策略ID',
    `name` varchar(16) DEFAULT NULL COMMENT '策略名称', 
    `mail_to` text DEFAULT NULL COMMENT '收件人', 
    `mail_cc` text DEFAULT NULL COMMENT '抄送', 
    `title` text DEFAULT NULL COMMENT '邮件标题', 
    `content` text DEFAULT NULL COMMENT '邮件内容', 
    `create_time` int(11) DEFAULT NULL COMMENT '创建时间', 
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mail_trigger`
--

DROP TABLE IF EXISTS `mail_trigger`; /*邮件触发条件*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail_trigger` (
    `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '条件ID',
    `strategy_id` int(11) DEFAULT NULL COMMENT '邮件策略ID',
    `field` varchar(32) DEFAULT NULL COMMENT '引用字段', 
    `value` text DEFAULT NULL COMMENT '字段匹配值', 
    `field_opr` text DEFAULT NULL COMMENT '字段匹配运算符，> < == != >= <=', 
    `logic_opr` text DEFAULT NULL COMMENT '触发条件运算符，&& ||', 
    `create_time` int(11) DEFAULT NULL COMMENT '创建时间', 
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mail_cycle`
--

DROP TABLE IF EXISTS `mail_cycle`; /*邮件触发条件*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail_cycle` (
    `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '条件ID',
    `strategy_id` int(11) DEFAULT NULL COMMENT '邮件策略ID',
    `field` varchar(32) DEFAULT NULL COMMENT '时间起点字段', 
    `duration` varchar(8) DEFAULT NULL COMMENT '间隔时长', 
    `unit` varchar(8) DEFAULT NULL COMMENT '时长单位，天、小时', 
    `repeat` varchar(8) DEFAULT NULL COMMENT '重复次数', 
    `create_time` int(11) DEFAULT NULL COMMENT '创建时间', 
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mail_list`
--

DROP TABLE IF EXISTS `mail_list`; /*邮件触发条件*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail_list` (
    `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '提醒ID',
    `strategy_id` int(11) DEFAULT NULL COMMENT '邮件策略ID',
    `project_id` int(11) DEFAULT NULL COMMENT '交易ID',
    `mail_to` text DEFAULT NULL COMMENT '收件人', 
    `mail_cc` text DEFAULT NULL COMMENT '抄送', 
    `title` text DEFAULT NULL COMMENT '邮件标题', 
    `content` text DEFAULT NULL COMMENT '邮件内容', 
    `status` varchar(8) DEFAULT NULL COMMENT '邮件状态，待发送、已发送、发送中',
    `create_type` varchar(8) DEFAULT NULL COMMENT '创建方式，自动、手动',
    `expect_time` varchar(11) DEFAULT NULL COMMENT '预计发送时间', 
    `send_time` varchar(11) DEFAULT NULL COMMENT '实际发送时间', 
    `create_time` int(11) DEFAULT NULL COMMENT '创建时间', 
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
