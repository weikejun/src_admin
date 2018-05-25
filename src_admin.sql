-- MySQL dump 10.13  Distrib 5.5.35, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: aimeizhuyi
-- ------------------------------------------------------
-- Server version	5.5.35-0ubuntu0.12.04.1

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`; /*企业信息*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL COMMENT '公司名称',
  `short` varchar(32) DEFAULT NULL COMMENT '项目名称',
  `bussiness` varchar(32) DEFAULT NULL COMMENT '所属行业',
  `total_stock` varchar(16) DEFAULT NULL,
  `init_manager` varchar(16) DEFAULT NULL COMMENT '初始负责人',
  `current_manager` varchar(16) DEFAULT NULL COMMENT '当前负责人',
  `legal_person` varchar(16) DEFAULT NULL,
  `director` varchar(16) DEFAULT NULL,
  `director_turn` varchar(16) DEFAULT NULL,
  `director_status` varchar(16) DEFAULT NULL,
  `filling_keeper` varchar(16) DEFAULT NULL,
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
  `name` varchar(32) DEFAULT NULL COMMENT '主体名称',
  `tp` varchar(16) DEFAULT NULL COMMENT '主体类型',
  `currency` varchar(16) DEFAULT NULL COMMENT '货币类型',
  `co_investment` varchar(16) DEFAULT NULL COMMENT '',
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
-- Table structure for table `investment_exit`
--

DROP TABLE IF EXISTS `investment_exit`; /*退出记录*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `investment_exit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL COMMENT '项目ID',
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `amount` varchar(16) DEFAULT NULL COMMENT '金额',
  `currency` varchar(16) DEFAULT NULL COMMENT '货币',
  `exit_way` varchar(16) DEFAULT NULL COMMENT '退出方式',
  `exit_num` varchar(16) DEFAULT NULL COMMENT '退出股数',
  `memo` varchar(16) DEFAULT NULL COMMENT '备忘',
  `exit_time` int(11) DEFAULT NULL COMMENT '退出时间',
  `update_time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
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
  `memo` varchar(1024) DEFAULT NULL COMMENT '备注',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `project`
--

DROP TABLE IF EXISTS `project`; /*交易表*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project` (
  `id` int(11) not NULL AUTO_INCREMENT COMMENT '交易ID',
  `status` varchar(8) DEFAULT 'valid' COMMENT '数据状态',
  `company_id` int(11) DEFAULT NULL COMMENT '企业ID',
  `code` varchar(32) DEFAULT NULL COMMENT '项目编号',
  `item_status` varchar(32) DEFAULT NULL COMMENT '记录状态',
  `turn` varchar(16) DEFAULT NULL COMMENT '轮次',
  `turn_sub` varchar(16) DEFAULT NULL COMMENT '子轮次',
  `enter_exit_type` varchar(16) DEFAULT NULL COMMENT '投退类型',
  `new_follow` varchar(16) DEFAULT NULL COMMENT '新老类型',
  `new_old_stock` varchar(16) DEFAULT NULL COMMENT '新老股',
  `decision_date` int(11) DEFAULT NULL COMMENT '决策日期',
  `proj_status` varchar(16) DEFAULT NULL COMMENT '项目状态',
  `close_date` int(11) DEFAULT NULL COMMENT 'close日期',
  `law_firm` varchar(32) DEFAULT NULL COMMENT '律所及律师',
  `observer` varchar(16) DEFAULT NULL COMMENT '观察员',
  `info_right` varchar(16) DEFAULT NULL COMMENT '',
  `info_right_threshold` text DEFAULT NULL COMMENT '',
  `currency` varchar(8) DEFAULT NULL COMMENT '货币单位, RMB/USD',
  `pre_money` varchar(16) DEFAULT NULL COMMENT '投前估值',
  `financing_amount` varchar(16) DEFAULT NULL COMMENT '融资额度',
  `value_change` varchar(16) DEFAULT NULL COMMENT '',
  `entity_id` int(11) DEFAULT NULL COMMENT '主体ID',
  `rmb_usd` varchar(16) DEFAULT NULL COMMENT '',
  `period` varchar(16) DEFAULT NULL COMMENT '期数',
  `mirror` varchar(16) DEFAULT NULL COMMENT '镜像持股',
  `entrustment` varchar(1024) DEFAULT NULL COMMENT '',
  `our_amount` varchar(16) DEFAULT NULL COMMENT '我方额度',
  `other_amount` varchar(32) DEFAULT NULL COMMENT '其他方面额度',
  `amount_memo` varchar(64) DEFAULT NULL COMMENT '金额备注',
  `loan` varchar(16) DEFAULT NULL COMMENT '借款',
  `loan_expiration` varchar(16) DEFAULT NULL COMMENT '借款期限',
  `loan_memo` varchar(16) DEFAULT NULL COMMENT '借款备注',
  `stocknum_all` varchar(16) DEFAULT NULL COMMENT '投时公司总股数',
  `stocknum_get` varchar(16) DEFAULT NULL COMMENT '投时持有本轮股数',
  `stocknum_new` varchar(16) DEFAULT NULL COMMENT '最新持有本轮股数',
  `shareholding_member` varchar(16) DEFAULT NULL COMMENT '团队持股比例',
  `shareholding_esop` varchar(16) DEFAULT NULL COMMENT 'esop比例',
  `term_limit` varchar(1024) DEFAULT NULL COMMENT '',
  `term_stock_transfer_limit` varchar(1024) DEFAULT NULL COMMENT '',
  `term_stock_transfer_limit_other` varchar(1024) DEFAULT NULL COMMENT '',
  `term_limit_other` varchar(1024) DEFAULT NULL COMMENT '',
  `term_founder_transfer_limit` varchar(1024) DEFAULT NULL COMMENT '',
  `term_holder_veto` varchar(32) DEFAULT NULL COMMENT '',
  `term_board_veto` varchar(32) DEFAULT NULL COMMENT '',
  `term_preemptive` varchar(16) DEFAULT NULL COMMENT '',
  `term_pri_assignee` varchar(16) DEFAULT NULL COMMENT '',
  `term_sell_together` varchar(16) DEFAULT NULL COMMENT '',
  `term_pri_common_stock` varchar(16) DEFAULT NULL COMMENT '',
  `term_buyback_standard` varchar(64) DEFAULT NULL COMMENT '',
  `term_buyback_start` varchar(16) DEFAULT NULL COMMENT '',
  `term_buyback_period` varchar(16) DEFAULT NULL COMMENT '',
  `term_buyback_date` varchar(16) DEFAULT NULL COMMENT '',
  `term_waiting_period` varchar(16) DEFAULT NULL COMMENT '',
  `term_ipo_period` varchar(16) DEFAULT NULL COMMENT '',
  `term_anti_dilution` varchar(16) DEFAULT NULL COMMENT '',
  `term_liquidation_preference` varchar(16) DEFAULT NULL COMMENT '',
  `term_drag_along_right` varchar(16) DEFAULT NULL COMMENT '',
  `term_dar_illustrate` varchar(16) DEFAULT NULL COMMENT '',
  `term_warrant` varchar(16) DEFAULT NULL COMMENT '',
  `term_dividends_preference` varchar(16) DEFAULT NULL COMMENT '',
  `term_valuation_adjustment` varchar(1024) DEFAULT NULL COMMENT '',
  `term_spouse_consent` varchar(16) DEFAULT NULL COMMENT '',
  `term_longstop_date` varchar(16) DEFAULT NULL COMMENT '',
  `term_important_changes` varchar(1024) DEFAULT NULL COMMENT '',
  `term_good_item` varchar(1024) DEFAULT NULL COMMENT '',
  `arch_final_captalbe` varchar(256) DEFAULT NULL COMMENT '',
  `arch_final_word` varchar(8) DEFAULT NULL COMMENT '',
  `arch_closing_pdf` varchar(8) DEFAULT NULL COMMENT '',
  `arch_closing_original` varchar(8) DEFAULT NULL COMMENT '',
  `arch_overseas_stockcert` varchar(8) DEFAULT NULL COMMENT '境外股票证书',
  `arch_aic_registration` varchar(8) DEFAULT NULL COMMENT '境内工商',
  `pending_detail` varchar(1024) DEFAULT NULL COMMENT '',
  `work_memo` text DEFAULT NULL COMMENT '',
  `update_time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `system_logs`
--

DROP TABLE IF EXISTS `system_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_log` (
  `id` int(11) NOT NULL auto_increment,
  `operator_id` int(11) DEFAULT NULL,
  `operator_ip` varchar(16) DEFAULT NULL COMMENT '操作IP',
  `resource` varchar(16) DEFAULT NULL COMMENT '操作资源',
  `res_id` varchar(16) DEFAULT NULL COMMENT '资源ID',
  `action` varchar(16) DEFAULT NULL COMMENT '动作',
  `detail` text DEFAULT NULL COMMENT '操作内容',
  `create_time` int(11) DEFAULT NULL,
  Index `res_id_idx` (`res_id`),
  Index `operator_id_idx` (`operator_id`),
  Index `create_time_idx` (`create_time`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
