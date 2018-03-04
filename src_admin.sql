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

use `aimeizhuyi`;

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`; /*企业信息*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL COMMENT '企业名称',
  `bussiness` varchar(32) DEFAULT NULL COMMENT '所属行业',
  `admin_id` int(11) DEFAULT NULL COMMENT '创建人ID',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
--
-- Table structure for table `project`
--

DROP TABLE IF EXISTS `project`; /*项目表*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project` (
  `id` int(11) not NULL AUTO_INCREMENT COMMENT '项目ID',
  `item_status` varchar(32) DEFAULT NULL COMMENT '记录状态',
  `name` varchar(32) DEFAULT NULL COMMENT '名称',
  `code` varchar(32) DEFAULT NULL COMMENT '编号',
  `turn` varchar(16) DEFAULT NULL COMMENT '轮次',
  `turn_sub` varchar(16) DEFAULT NULL COMMENT '子轮次',
  `investment_type` varchar(16) DEFAULT NULL COMMENT '投退类型',
  `proj_status` varchar(16) DEFAULT NULL COMMENT '项目状态',
  `decision_date` int(11) DEFAULT NULL COMMENT '决策日期',
  `close_date` int(11) DEFAULT NULL COMMENT 'close日期',
  `owner_pre` varchar(16) DEFAULT NULL COMMENT '原负责人',
  `owner_now` varchar(16) DEFAULT NULL COMMENT '现负责人',
  `law_firm` varchar(32) DEFAULT NULL COMMENT '律所及律师',
  `legal_in` varchar(32) DEFAULT NULL COMMENT '法律接口人',
  `director_out` varchar(16) DEFAULT NULL COMMENT '境外董事',
  `director_in` varchar(16) DEFAULT NULL COMMENT '境内董事',
  `director_status` varchar(16) DEFAULT NULL COMMENT '境内董事工商办理',
  `observer` varchar(16) DEFAULT NULL COMMENT '观察员',
  `pre_money` varchar(16) DEFAULT NULL COMMENT '投前估值',
  `post_money` varchar(16) DEFAULT NULL COMMENT '投后估值',
  `stock_price` varchar(16) DEFAULT NULL COMMENT '股价',
  `financing_amount` varchar(16) DEFAULT NULL COMMENT '融资额度',
  `currency` varchar(8) DEFAULT NULL COMMENT '货币单位, RMB/USD',
  `investment_co` varchar(32) DEFAULT NULL COMMENT '投资主体',
  `period` varchar(8) DEFAULT NULL COMMENT '期数',
  `multi_currency` varchar(8) DEFAULT NULL COMMENT '是否多货币',
  `our_amount` varchar(16) DEFAULT NULL COMMENT '我方额度',
  `other_amount` varchar(32) DEFAULT NULL COMMENT '其他方面额度',
  `stock_trans` varchar(8) DEFAULT NULL COMMENT '老股转让否',
  `trans_detail` varchar(64) DEFAULT NULL COMMENT '老股转说明',
  `amount_memo` varchar(64) DEFAULT NULL COMMENT '金额备注',
  `loan` varchar(16) DEFAULT NULL COMMENT '借款',
  `shareholding` varchar(16) DEFAULT NULL COMMENT '初始持股比例',
  `shareholding_new` varchar(16) DEFAULT NULL COMMENT '最新持股比例',
  `shareholding_total` varchar(16) DEFAULT NULL COMMENT '各主体总持股比例',
  `shareholding_member` varchar(16) DEFAULT NULL COMMENT '团队持股比例',
  `shareholding_esop` varchar(16) DEFAULT NULL COMMENT 'esop比例',
  `mirror` varchar(16) DEFAULT NULL COMMENT '镜像持股',
  `entrustment` varchar(16) DEFAULT NULL COMMENT '代持',
  `stocknum_all` varchar(16) DEFAULT NULL COMMENT '公司总股数',
  `stocknum_turn` varchar(16) DEFAULT NULL COMMENT '公司本轮股数',
  `stocknum_total` varchar(16) DEFAULT NULL COMMENT '公司合计股数',
  `hold_value` varchar(16) DEFAULT NULL COMMENT '持有价值',
  `return_rate` varchar(16) DEFAULT NULL COMMENT '总回报率',
  `return_irr` varchar(16) DEFAULT NULL COMMENT 'IRR回报率',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `project_memo`; /*项目备忘记录*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_memo` (
  `id` int(11) not NULL AUTO_INCREMENT COMMENT '记录ID',
  `project_id` int(11) DEFAULT NULL COMMENT '项目ID',
  `admin_id` int(11) DEFAULT NULL COMMENT '创建人ID',
  `message` text DEFAULT '' COMMENT '工作说明',
  `memo` text DEFAULT '' COMMENT '工作记录',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
