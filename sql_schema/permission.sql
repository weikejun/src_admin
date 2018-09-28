-- MySQL dump 10.13  Distrib 5.5.37, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: aimeizhuyi
-- ------------------------------------------------------
-- Server version	5.5.37-0ubuntu0.12.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `role_permission`
--

DROP TABLE IF EXISTS `role_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` varchar(16) COMMENT '权限组ID',
  `admin_id` varchar(16) COMMENT '系统用户ID',
  `permission_id` varchar(16) DEFAULT NULL COMMENT '权限ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_key` (`group_id`,`permission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户  权限组-权限 多对多关系';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permission`
--

LOCK TABLES `role_permission` WRITE;
/*!40000 ALTER TABLE `role_permission` DISABLE KEYS */;
INSERT INTO `role_permission` VALUES (1,'2',NULL,'11'),(2,'2',NULL,'17'),(3,'2',NULL,'9'),(4,'2',NULL,'10'),(5,'2',NULL,'3'),(8,'3',NULL,'14'),(9,'3',NULL,'15'),(10,'3',NULL,'17'),(11,'3',NULL,'21'),(15,'3',NULL,'6'),(16,'3',NULL,'14'),(17,'3',NULL,'7'),(18,'3',NULL,'15'),(19,'3',NULL,'11'),(20,'3',NULL,'17'),(21,'3',NULL,'3'),(22,'3',NULL,'21'),(30,'3',NULL,'6'),(31,'3',NULL,'14'),(32,'3',NULL,'7'),(33,'3',NULL,'15'),(34,'3',NULL,'11'),(35,'3',NULL,'17'),(36,'3',NULL,'10'),(37,'3',NULL,'3'),(38,'3',NULL,'21'),(39,'3',NULL,'20'),(45,'4',NULL,'7'),(46,'4',NULL,'15'),(47,'4',NULL,'11'),(48,'4',NULL,'10'),(49,'4',NULL,'3'),(50,'4',NULL,'21'),(51,'4',NULL,'20'),(52,'5',NULL,'7'),(53,'5',NULL,'15'),(54,'5',NULL,'11'),(55,'5',NULL,'10'),(56,'5',NULL,'3'),(57,'5',NULL,'21'),(58,'5',NULL,'20'),(59,'6',NULL,'6'),(60,'6',NULL,'14'),(62,'7','','11'),(63,'7','','17'),(64,'7','','10'),(65,'7','','3'),(66,'2','','2'),(68,'2','','16'),(69,'8','','10'),(70,'8','','11'),(71,'7','','8'),(72,'9','','11'),(73,'9','','10'),(74,'9','','3');
/*!40000 ALTER TABLE `role_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission`
--

DROP TABLE IF EXISTS `permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL COMMENT '权限名称',
  `description` text COMMENT '详细说明',
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='权限信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission`
--

LOCK TABLES `permission` WRITE;
/*!40000 ALTER TABLE `permission` DISABLE KEYS */;
INSERT INTO `permission` VALUES (1,'auth','权限模块读',1407393743),(2,'logistic','国内物流读',1407393743),(3,'stockamount',NULL,1407393743),(4,'user',NULL,1407393743),(5,'systemlog','系统日志读',1407393743),(6,'buyer',NULL,1407393743),(7,'live',NULL,1407393743),(8,'payment',NULL,1407393743),(9,'pack',NULL,1407393743),(10,'stock',NULL,1407393743),(11,'order',NULL,1407393743),(13,'auth_w',NULL,1407393743),(14,'buyer_w',NULL,1407393743),(15,'live_w',NULL,1407393743),(16,'logistic_w','国内物流写',1407393743),(17,'order_w',NULL,1407393743),(18,'pack_w',NULL,1407393743),(19,'payment_w',NULL,1407393743),(20,'stock_w',NULL,1407393743),(21,'stockamount_w',NULL,1407393743),(22,'systemlog_w','系统日志写',1407393743),(23,'user_w',NULL,1407393743),(28,'useraddr','买家收货地址',1407990240),(29,'useraddr_w','买家收货地址_写',1407990329),(30,'orderlog','订单日志读',1409764402),(31,'orderlog_w','订单日志写',1409764414);
/*!40000 ALTER TABLE `permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group`
--

DROP TABLE IF EXISTS `group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL COMMENT '权限组名称, root代表最大权限管理员',
  `description` text COMMENT '详细说明',
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='权限组信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group`
--

LOCK TABLES `group` WRITE;
/*!40000 ALTER TABLE `group` DISABLE KEYS */;
INSERT INTO `group` VALUES (1,'root','超级管理员',1406176386),(2,'仓库管理','',1407385735),(3,'运营管理','',1407394828),(4,'买手运营_内容','',1407394947),(5,'买手运营_选款','',1407394956),(6,'买手运营_审核','',1407394973),(7,'销售客服','',1407812278),(8,'财务管理','',1407852448),(9,'销售客服_售后','',1408590489);
/*!40000 ALTER TABLE `group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `action`
--

DROP TABLE IF EXISTS `action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL COMMENT '名称',
  `description` text COMMENT '详细说明',
  `permission_id` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=145 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='ACTION权限管理';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `action`
--

LOCK TABLES `action` WRITE;
/*!40000 ALTER TABLE `action` DISABLE KEYS */;
INSERT INTO `action` VALUES (1,'logistic_index',NULL,2,1406125305,1406125305),(2,'logistic_select_search',NULL,2,1406125305,1406125305),(3,'logistic_update',NULL,16,1406125305,1406125305),(4,'logistic_search',NULL,2,1406125305,1406125305),(5,'logistic_delete',NULL,16,1406125305,1406125305),(6,'logistic_read',NULL,2,1406125305,1406125305),(7,'logistic_select',NULL,2,1406125305,1406125305),(8,'logistic_create',NULL,16,1406125305,1406125305),(9,'stockamount_index',NULL,3,1406125305,1406125305),(10,'stockamount_select_search',NULL,3,1406125305,1406125305),(11,'stockamount_update',NULL,21,1406125305,1406125305),(12,'stockamount_search',NULL,3,1406125305,1406125305),(13,'stockamount_delete',NULL,21,1406125305,1406125305),(14,'stockamount_read',NULL,3,1406125305,1406125305),(15,'stockamount_select',NULL,3,1406125305,1406125305),(16,'stockamount_create',NULL,21,1406125305,1406125305),(17,'user_index',NULL,4,1406125305,1406125305),(18,'user_select_search',NULL,4,1406125305,1406125305),(19,'user_update',NULL,23,1406125305,1406125305),(20,'user_search',NULL,4,1406125305,1406125305),(21,'user_delete',NULL,23,1406125305,1406125305),(22,'user_read',NULL,4,1406125305,1406125305),(23,'user_select',NULL,4,1406125305,1406125305),(24,'user_create',NULL,23,1406125305,1406125305),(25,'systemlog_index',NULL,5,1406125305,1406125305),(26,'systemlog_select_search',NULL,5,1406125305,1406125305),(27,'systemlog_update',NULL,22,1406125305,1406125305),(28,'systemlog_search',NULL,5,1406125305,1406125305),(29,'systemlog_delete',NULL,22,1406125305,1406125305),(30,'systemlog_read',NULL,5,1406125305,1406125305),(31,'systemlog_select',NULL,5,1406125305,1406125305),(32,'systemlog_create',NULL,22,1406125305,1406125305),(33,'buyer_index',NULL,6,1406125305,1406125305),(34,'buyer_select_search',NULL,6,1406125305,1406125305),(35,'buyer_update',NULL,14,1406125305,1406125305),(36,'buyer_search',NULL,6,1406125305,1406125305),(37,'buyer_delete',NULL,14,1406125305,1406125305),(38,'buyer_read',NULL,6,1406125305,1406125305),(39,'buyer_select',NULL,6,1406125305,1406125305),(40,'buyer_create',NULL,14,1406125305,1406125305),(41,'live_index',NULL,7,1406125305,1406125305),(42,'live_select_search',NULL,7,1406125305,1406125305),(43,'live_update',NULL,15,1406125305,1406125305),(44,'live_search',NULL,7,1406125305,1406125305),(45,'live_delete',NULL,15,1406125305,1406125305),(46,'live_read',NULL,7,1406125305,1406125305),(47,'live_select',NULL,7,1406125305,1406125305),(48,'live_create',NULL,15,1406125305,1406125305),(49,'payment_index',NULL,8,1406125305,1406125305),(50,'payment_select_search',NULL,8,1406125305,1406125305),(51,'payment_update',NULL,19,1406125305,1406125305),(52,'payment_search',NULL,8,1406125305,1406125305),(53,'payment_delete',NULL,19,1406125305,1406125305),(54,'payment_read',NULL,8,1406125305,1406125305),(55,'payment_select',NULL,8,1406125305,1406125305),(56,'payment_create',NULL,19,1406125305,1406125305),(57,'pack_index',NULL,9,1406125305,1406125305),(58,'pack_select_search',NULL,9,1406125305,1406125305),(59,'pack_update',NULL,18,1406125305,1406125305),(60,'pack_search',NULL,9,1406125305,1406125305),(61,'pack_delete',NULL,18,1406125305,1406125305),(62,'pack_read',NULL,9,1406125305,1406125305),(63,'pack_select',NULL,9,1406125305,1406125305),(64,'pack_create',NULL,18,1406125305,1406125305),(65,'stock_index',NULL,10,1406125305,1406125305),(66,'stock_select_search',NULL,10,1406125305,1406125305),(67,'stock_update',NULL,20,1406125305,1406125305),(68,'stock_search',NULL,10,1406125305,1406125305),(69,'stock_delete',NULL,20,1406125305,1406125305),(70,'stock_read',NULL,10,1406125305,1406125305),(71,'stock_select',NULL,10,1406125305,1406125305),(72,'stock_create',NULL,20,1406125305,1406125305),(73,'order_index',NULL,11,1406125305,1406125305),(74,'order_select_search',NULL,11,1406125305,1406125305),(75,'order_update',NULL,17,1406125305,1406125305),(76,'order_search',NULL,11,1406125305,1406125305),(77,'order_delete',NULL,17,1406125305,1406125305),(78,'order_read',NULL,11,1406125305,1406125305),(79,'order_select',NULL,11,1406125305,1406125305),(80,'order_create',NULL,17,1406125305,1406125305),(81,'admin_index',NULL,1,1406125305,1406125305),(82,'admin_select_search',NULL,1,1406125305,1406125305),(83,'admin_update',NULL,13,1406125305,1406125305),(84,'admin_search',NULL,1,1406125305,1406125305),(85,'admin_delete',NULL,13,1406125305,1406125305),(86,'admin_read',NULL,1,1406125305,1406125305),(87,'admin_select',NULL,1,1406125305,1406125305),(88,'admin_create',NULL,13,1406125305,1406125305),(89,'permission_index',NULL,1,1406125305,1406125305),(90,'permission_select_search',NULL,1,1406125305,1406125305),(91,'permission_update',NULL,13,1406125305,1406125305),(92,'permission_search',NULL,1,1406125305,1406125305),(93,'permission_delete',NULL,13,1406125305,1406125305),(94,'permission_read',NULL,1,1406125305,1406125305),(95,'permission_select',NULL,1,1406125305,1406125305),(96,'permission_create',NULL,13,1406125305,1406125305),(97,'group_index',NULL,1,1406125305,1406125305),(98,'group_select_search',NULL,1,1406125305,1406125305),(99,'group_update',NULL,13,1406125305,1406125305),(100,'group_search',NULL,1,1406125305,1406125305),(101,'group_delete',NULL,13,1406125305,1406125305),(102,'group_read',NULL,1,1406125305,1406125305),(103,'group_select',NULL,1,1406125305,1406125305),(104,'group_create',NULL,13,1406125305,1406125305),(105,'rolepermission_index',NULL,1,1406125305,1406125305),(106,'rolepermission_select_search',NULL,1,1406125305,1406125305),(107,'rolepermission_update',NULL,13,1406125305,1406125305),(108,'rolepermission_search',NULL,1,1406125305,1406125305),(109,'rolepermission_delete',NULL,13,1406125305,1406125305),(110,'rolepermission_read',NULL,1,1406125305,1406125305),(111,'rolepermission_select',NULL,1,1406125305,1406125305),(112,'rolepermission_create',NULL,13,1406125305,1406125305),(113,'admingroup_index',NULL,1,1406125305,1406125305),(114,'admingroup_select_search',NULL,1,1406125305,1406125305),(115,'admingroup_update',NULL,13,1406125305,1406125305),(116,'admingroup_search',NULL,1,1406125305,1406125305),(117,'admingroup_delete',NULL,13,1406125305,1406125305),(118,'admingroup_read',NULL,1,1406125305,1406125305),(119,'admingroup_select',NULL,1,1406125305,1406125305),(120,'admingroup_create',NULL,13,1406125305,1406125305),(121,'action_index',NULL,1,1406125305,1406125305),(122,'action_select_search',NULL,1,1406125305,1406125305),(123,'action_update',NULL,13,1406125305,1406125305),(124,'action_search',NULL,1,1406125305,1406125305),(125,'action_delete',NULL,13,1406125305,1406125305),(126,'action_read',NULL,1,1406125305,1406125305),(127,'action_select',NULL,1,1406125305,1406125305),(128,'action_create',NULL,13,1406125305,1406125305),(129,'useraddr_index','',28,1407990433,1407990449),(130,'useraddr_select_search','',28,1407990461,1407990474),(131,'useraddr_update','',29,1407990484,1407990492),(132,'useraddr_search','',28,1407990499,1407990510),(133,'useraddr_delete','',29,1407990600,1407990610),(134,'useraddr_read','',28,1407990616,1407990623),(135,'useraddr_select','',28,1407990642,1407990656),(136,'useraddr_create','',29,1407990660,1407990669),(137,'orderlog_index','订单日志_列表',30,1409764549,1409764574),(138,'orderlog_select_search','订单日志_搜索选择',30,1409764602,1409764633),(139,'orderlog_update','订单日志_修改',31,1409764640,1409764666),(140,'orderlog_search','订单日志_搜索',30,1409764677,1409764699),(141,'orderlog_delete','订单日志_删除',31,1409764715,1409764734),(142,'orderlog_read','订单日志_详情',30,1409764762,1409764775),(143,'orderlog_select','订单日志_筛选',30,1409764781,1409764800),(144,'orderlog_create','订单日志_创建',31,1409764807,1409764824);
/*!40000 ALTER TABLE `action` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-09-15 14:57:29

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL,
  `real_name` varchar(32) DEFAULT NULL,
  `email` varchar(32) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `gender` varchar(3) DEFAULT NULL,
  `department` varchar(32) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `valid` enum('valid','invalid') DEFAULT 'valid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `permission_action`
--

DROP TABLE IF EXISTS `permission_action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_id` varchar(11) NOT NULL,
  `action_id` varchar(11) NOT NULL,
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `permission_id_action_id_index` (`permission_id`,`action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='权限动作管理';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `item_permission`
--

DROP TABLE IF EXISTS `item_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` varchar(16) DEFAULT NULL,
  `company_id` varchar(16) DEFAULT NULL,
  `project_id` varchar(16) DEFAULT NULL,
  `operator_id` varchar(16) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_uni` (`admin_id`,`company_id`, `project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
