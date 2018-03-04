-- MySQL dump 10.13  Distrib 5.5.35, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: aimeizhuyi
-- ------------------------------------------------------
-- Server version	5.5.35-0ubuntu0.12.04.1

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
-- Table structure for table `admins`
--
drop database if exists `aimeizhuyi`;
create database  `aimeizhuyi` default charset=utf8;
use `aimeizhuyi`;

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `gender` varchar(3) DEFAULT NULL,
  `department` varchar(32) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `valid` enum('valid','invalid') DEFAULT 'valid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

insert into `admin`(`name`,`password`) values 
('wp','b6ddd84a9cc636257258701ca934e763'),
('shadow','b6ddd84a9cc636257258701ca934e763')
;



drop table if exists `buyer_withdraw`;
create table `buyer_withdraw` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `buyer_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `create_time` int(11) default NULL,
  `update_time` int(11) default NULL,
  `account_type` enum('foreign','local') NOT NULL DEFAULT 'local' COMMENT '国外，国内',
  `account_no` varchar(32) default NULL comment '银行账号',
  `account_name` varchar(32) default NULL comment '收款人的人名，真名',
  `account_address` varchar(255) default NULL comment '收款人地址，国外账户需要',
  `account_bank` varchar(128) default NULL comment '银行名称',
  `account_swift` varchar(32) default NULL comment '国外银行swift，不懂啥意思',
  `account_routing` varchar(32) default NULL comment '国外银行routing，不懂啥意思',
  `account_country` varchar(32) default NULL comment '国外银行的国家',
  `account_city` varchar(32) default NULL comment '国外银行的城市',
  `amount` float(10,2) default NULL comment '金额',
  `note` text comment '卖家提款的备注',
  `log` text comment '相关订单id的json数组，备查',
  `admin_note` text comment '财务打款的备注',
  `status` enum('begin','finish') NOT NULL DEFAULT 'begin' COMMENT '', 
  Index `buyer_id_index` (`buyer_id`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


drop table if exists `talk`;
create table `talk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `buyer_id` int(11) not NULL,
  `user_id` int(11) not NULL,
  `stock_id` int(11) not NULL,
  `sender` int(1) not NULL comment '1代表buyer发的，0代表user发的',
  `create_time` int(11) not NULL,
  `msg` varchar(255) default NULL comment '消息内容',
  Index `buyer_id_index` (`buyer_id`,`stock_id`),
  Index `user_id_index` (`user_id`,`stock_id`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;





drop table if exists `buyer_account`;
create table `buyer_account` (
     `id` int(11) NOT NULL AUTO_INCREMENT,
     `buyer_id` int(11) DEFAULT NULL,
     `create_time` int(11) default NULL,
     `update_time` int(11) default NULL,

     `type` enum('foreign','local') NOT NULL DEFAULT 'local' COMMENT '国外，国内',
     `no` varchar(32) default NULL comment '银行账号',
     `name` varchar(32) default NULL comment '收款人的人名，真名',
     `address` varchar(255) default NULL comment '收款人地址，国外账户需要',
     `bank` varchar(128) default NULL comment '银行名称',
     `swift` varchar(32) default NULL comment '国外银行swift，不懂啥意思',
     `routing` varchar(32) default NULL comment '国外银行routing，不懂啥意思',
     `country` varchar(32) default NULL comment '国外银行的国家',
     `city` varchar(32) default NULL comment '国外银行的城市',
    
     Index `buyer_id_index` (`buyer_id`),
     PRIMARY KEY (`id`)
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
insert into buyer_account
(buyer_id,create_time,update_time,`type`,no,name,address,bank,swift,routing,country,city) 
values
(1,1401346160,1401346160,'local','123456','账户名1','火星','招行',"","","中国","北京");


DROP TABLE IF EXISTS `buyer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `buyer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) not NULL,
  `password` varchar(128) not NULL,
  `gender` varchar(3) DEFAULT NULL,
  `country` varchar(32) default NULL,
  `province` varchar(32) default NULL,
  `city` varchar(32) default NULL,
  `address` varchar(256) default NULL,
  `profession` varchar(256) DEFAULT NULL,
  `email` varchar(127) DEFAULT NULL,
  `phone` varchar(32) default NULL,
  `create_time` int(11) default NULL,
  `update_time` int(11) default NULL,
  `weixin` varchar(32) default NULL,
  `qq` varchar(32) default NULL,
  `real_name` varchar(32) default NULL,
  `favor_brands` varchar(512) DEFAULT NULL,
  `maxpay` int(11) DEFAULT '0',
  `birthday` int(11) default null,
  `resume` text COMMENT '简历',
  `id_pics` text  COMMENT '证件照片',
  `id_num` varchar(32) DEFAULT NULL COMMENT '护照号',
  `id_type` varchar(10) default null comment '证件类型，现在为空，因为所有证件都是“护照”',
  `head` varchar(256) DEFAULT NULL COMMENT '头像照片',
  `status` enum('notapply','apply','reject','be','disable') NOT NULL DEFAULT 'notapply' COMMENT '买手状态：申请中，是，已退出，驳回，封禁',
  `valid` enum('valid','invalid') NOT NULL DEFAULT 'valid',
  
  /*提款的时候，填的默认信息，上次提款的时候存下的，暂存于此表*/
  `account_type` varchar(255) default null comment '银行账号类型',
  `account` varchar(32) default null comment '银行账号',
  `account_name` varchar(32) default NULL comment '银行账号所属的人名，真名',
  
  `fee_rate` varchar(10) default null COMMENT '代购费率',
  `ship_percent` varchar(5) default '50%' comment '发货结算比例',
  `easemob_username` varchar(64) default null,
  `easemob_password` varchar(64)  default null,
  
  `picker` varchar(16) default NULL,
  
  `check_words` text,
  UNIQUE KEY `i_id_num` (`id_num`,`id_type`),
  UNIQUE KEY `i_name` (`name`),
  UNIQUE KEY `i_easemob_username` (`easemob_username`),
  UNIQUE KEY `i_email` (`email`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
insert into `buyer`
(`name`,`password`,`gender`,`country`,`province`,`city`,`address`,`profession`,`email`,`phone`,`create_time`,`update_time`,`weixin`,`favor_brands`,`maxpay`,`resume`,`status`,`valid`,`real_name`,`head`) values
('wp','b6ddd84a9cc636257258701ca934e763','男','中国','北京','北京','海淀区罗庄','计算机','wwwppp0801@qq.com','18610455401','0',0,'wwwppp0801','["apple"]',10000,'','be','valid','王芃','/winphp/metronic/media/image/avatar1_small.jpg'),
('const','b6ddd84a9cc636257258701ca934e763','男','中国','北京','北京','海淀区中关村','计算机','test@qq.com','15810001000','0',0,'wwwppp0801','["apple"]',10000,'','apply','valid','付恒','/winphp/metronic/media/image/avatar1_small.jpg')
;


--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `valid` enum('valid','invalid') DEFAULT 'valid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Table structure for table `liveApply`
--

DROP TABLE IF EXISTS `live_apply`; /*直播申请*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `live_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address` varchar(256) DEFAULT NULL,
  `name` varchar(256) DEFAULT NULL,
  `intro` varchar(1024) DEFAULT NULL,
  `brands` varchar(256) DEFAULT NULL,
  `text` text,
  `imgs` varchar(2048) DEFAULT NULL,
  `start_time` int(11) DEFAULT NULL,
  `end_time` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `checker_id` varchar(32) DEFAULT NULL,
  `check_time` int(11) DEFAULT NULL,
  `check_result` enum('unchecked','admit','reject') NOT NULL DEFAULT 'unchecked',
  `check_words` varchar(1024) DEFAULT NULL,
  `valid` enum('valid','invalid') DEFAULT 'valid',
  `product_type` varchar(64) DEFAULT NULL COMMENT '直播的产品类型',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `liveLogs`
--

DROP TABLE IF EXISTS `live_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `live_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `live_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `log` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;




DROP TABLE IF EXISTS `live`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `live` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) not NULL,
  `type` varchar(16) not NULL,
  `intro` text,
  `buyer_id` int(11) not NULL,/*TODO*/
  `country` varchar(32) DEFAULT NULL,
  `province` varchar(32) default NULL,
  `city` varchar(32) DEFAULT NULL,
  `address` varchar(256) DEFAULT NULL,
  `brands` varchar(1024) DEFAULT NULL,
  `start_time` int(11) not NULL,
  `end_time` int(11) not NULL,
  `create_time` int(11) NOT NULL,
  `valid` enum('valid','invalid') NOT NULL DEFAULT 'valid',
  `list_show` smallint NOT NULL DEFAULT 1 COMMENT '是否在列表展示 0-不展示；1-展示',
  `update_time` int(11) not NULL,
/*  `content` text,*/
  `status` enum('not_verify','verifying','verified','cancel') DEFAULT 'not_verify' COMMENT '未审核、正在审核、已审核、撤销',
  `imgs` text COMMENT '正常背景',
  `dim_imgs` text COMMENT '模糊背景',
  `product_type` varchar(64) DEFAULT NULL COMMENT '商品类型',
  `check_time` int(11) default null,
  `checker_id` int(11) default null,
  `check_words` varchar(1024) default null,
  `selector` varchar(20) DEFAULT null,
  `editor` varchar(20) DEFAULT null,
  `fee` varchar(10) default null COMMENT '直播费',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
insert into `live`(`name`,`intro`,`buyer_id`,address,brands,start_time,end_time,create_time,update_time,status,imgs,dim_imgs) values
('live from U.S.A G1','奢侈大牌',2,'地址','["Gucci","Tods","Parda","MIU MIU"]',1399300260,1407427200,1399306260,1399306260,'verified','["/winphp/metronic/media/image/live1.jpg","/winphp/metronic/media/image/live2.jpg"]','["/winphp/metronic/media/image/dim_back.jpg"]'),
('live from U.S.A G2','奢侈大牌',2,'地址','["Gucci","Tods","Parda","MIU MIU"]',1399300260,1407427200,1399306260,1399306260,'verified','["/winphp/metronic/media/image/live3.jpg","/winphp/metronic/media/image/live4.jpg"]','["/winphp/metronic/media/image/dim_back.jpg"]'),
('live from U.S.A G3','奢侈大牌',2,'地址','["Gucci","Tods","Parda","MIU MIU"]',1399300260,1407427200,1399306260,1399306260,'verified','["/winphp/metronic/media/image/live5.jpg","/winphp/metronic/media/image/live6.jpg"]','["/winphp/metronic/media/image/dim_back.jpg"]'),
('live from U.S.A G4','奢侈大牌',2,'地址','["Gucci","Tods","Parda","MIU MIU"]',1399300260,1407427200,1399306260,1399306260,'verified','["/winphp/metronic/media/image/live1.jpg","/winphp/metronic/media/image/live2.jpg"]','["/winphp/metronic/media/image/dim_back.jpg"]'),
('live from U.S.A G5','奢侈大牌',2,'地址','["Gucci","Tods","Parda","MIU MIU"]',1399300260,1407427200,1399306260,1399306260,'verified','["/winphp/metronic/media/image/live3.jpg","/winphp/metronic/media/image/live4.jpg"]','["/winphp/metronic/media/image/dim_back.jpg"]'),
('live from U.S.A G6','奢侈大牌',2,'地址','["Gucci","Tods","Parda","MIU MIU"]',1399300260,1407427200,1399306260,1399306260,'verified','["/winphp/metronic/media/image/live5.jpg","/winphp/metronic/media/image/live6.jpg"]','["/winphp/metronic/media/image/dim_back.jpg"]'),
('live from U.S.A G7','奢侈大牌',2,'地址','["Gucci","Tods","Parda","MIU MIU"]',1399300260,1407427200,1399306260,1399306260,'verified','["/winphp/metronic/media/image/live1.jpg","/winphp/metronic/media/image/live2.jpg"]','["/winphp/metronic/media/image/dim_back.jpg"]'),
('live from U.S.A G8','奢侈大牌',2,'地址','["Gucci","Tods","Parda","MIU MIU"]',1399300260,1407427200,1399306260,1399306260,'verified','["/winphp/metronic/media/image/live3.jpg","/winphp/metronic/media/image/live4.jpg"]','["/winphp/metronic/media/image/dim_back.jpg"]'),
('live from U.S.A G9','奢侈大牌',2,'地址','["Gucci","Tods","Parda","MIU MIU"]',1399300160,1399300260,1399306260,1399306260,'verified','["/winphp/metronic/media/image/live5.jpg","/winphp/metronic/media/image/live6.jpg"]','["/winphp/metronic/media/image/dim_back.jpg"]'),
('live from U.S.A G10','奢侈大牌',2,'地址','["Gucci","Tods","Parda","MIU MIU"]',1399300160,1399300260,1399306260,1399306260,'verified','["/winphp/metronic/media/image/live1.jpg","/winphp/metronic/media/image/live2.jpg"]','["/winphp/metronic/media/image/dim_back.jpg"]'),
('live from U.S.A G11','奢侈大牌',2,'地址','["Gucci","Tods","Parda","MIU MIU"]',1399300160,1399300260,1399306260,1399306260,'verified','["/winphp/metronic/media/image/live3.jpg","/winphp/metronic/media/image/live4.jpg"]','["/winphp/metronic/media/image/dim_back.jpg"]'),
('live from U.S.A G12','奢侈大牌',2,'地址','["Gucci","Tods","Parda","MIU MIU"]',1399300160,1399300260,1399306260,1399306260,'verified','["/winphp/metronic/media/image/live5.jpg","/winphp/metronic/media/image/live6.jpg"]','["/winphp/metronic/media/image/dim_back.jpg"]'),
('live from U.S.A G13','奢侈大牌',2,'地址','["Gucci","Tods","Parda","MIU MIU"]',1399300160,1399300260,1399306260,1399306260,'verified','["/winphp/metronic/media/image/live1.jpg","/winphp/metronic/media/image/live2.jpg"]','["/winphp/metronic/media/image/dim_back.jpg"]');
insert into `live`(`name`,`intro`,`buyer_id`,address,brands,imgs,start_time,end_time,create_time,update_time,status) values
('测试直播','介绍文字',1,'地址','["Gucci","Tods","Parda","MIU MIU"]','/upload/images/Premium-Outlets.jpg',1399300260,1399882220,1407427200,1396882220,'not_verify'),
('德国TK Maxx折扣购物商场','奢侈大牌',2,'地址','["Gucci","Tods","Parda","MIU MIU"]','/upload/images/article-2520488-19F6E97A00000578-16_634x573.jpg',1399300260,1407427200,1399306260,1399306260,'not_verify'),
('意大利GUCCI包包直播','奢侈大牌',2,'巴贝里诺名品奥特莱斯','["Gucci","Tods","Parda","MIU MIU"]','/upload/images/132220228.png',1399300260,1407427200,1399306260,1399306260,'not_verify'),
('加拿大伊顿购物中心','奢侈大牌',2,'伊顿购物中心(Eaton Centre)','["Gucci","Tods","Parda","MIU MIU"]','/upload/images/img201008300240010.jpg',1399300260,1407427200,1399306260,1399306260,'cancel'),
('live from U.S.A G13','奢侈大牌',2,'地址','["Gucci","Tods","Parda","MIU MIU"]',1399300160,1399300260,1399306260,1399306260,'verified','["/winphp/metronic/media/image/live1.jpg","/winphp/metronic/media/image/live2.jpg"]'),
('测试直播','介绍文字',1,'地址','["Gucci","Tods","Parda","MIU MIU"]',0,1399882220,1407427200,1396882220,'not_verify','["/winphp/metronic/media/image/live3.jpg","/winphp/metronic/media/image/live4.jpg"]')
;

insert into `live`(`name`,`intro`,`buyer_id`,address,brands,imgs,start_time,end_time,create_time,update_time,status) values
('测试直播','介绍文字',1,'地址','["Gucci","Tods","Parda","MIU MIU"]','/upload/images/Premium-Outlets.jpg',1399300260,1399882220,1407427200,1396882220,'not_verify'),
('德国TK Maxx折扣购物商场','奢侈大牌',1,'地址','["Gucci","Tods","Parda","MIU MIU"]','/upload/images/article-2520488-19F6E97A00000578-16_634x573.jpg',1399300260,1407427200,1399306260,1399306260,'not_verify'),
('意大利GUCCI包包直播','奢侈大牌',1,'巴贝里诺名品奥特莱斯','["Gucci","Tods","Parda","MIU MIU"]','/upload/images/132220228.png',1399300260,1407427200,1399306260,1399306260,'not_verify'),
('加拿大伊顿购物中心','奢侈大牌',1,'伊顿购物中心(Eaton Centre)','["Gucci","Tods","Parda","MIU MIU"]','/upload/images/img201008300240010.jpg',1399300260,1407427200,1399306260,1399306260,'cancel')
;

--
-- Table structure for table `logistics`
--

DROP TABLE IF EXISTS `logistic`; /*国内物流单*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logistic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `live_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `logistic_no` varchar(32) DEFAULT NULL,
  `logistic_provider` varchar(32) DEFAULT NULL,
  `logistic_price` float(10,2) DEFAULT NULL,
  `receiver_name` varchar(128) DEFAULT NULL,
  `receiver_addr` varchar(256) DEFAULT NULL,
  `receiver_phone` varchar(32) DEFAULT NULL,
  `receiver_email` varchar(128) DEFAULT NULL,
  `sender_name` varchar(128) DEFAULT NULL,
  `sender_addr` varchar(256) DEFAULT NULL,
  `sender_phone` varchar(128) DEFAULT NULL,
  `sender_email` varchar(128) DEFAULT NULL,
  `valid` enum('valid','invalid') NOT NULL DEFAULT 'valid',
  `create_time` int(11) DEFAULT NULL,
  `status` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
insert into logistic(order_id,logistic_no)values
(1,'dsajdklsa')
,(2,'dsajdklsa')
;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Table structure for table `orderGrades`
--

DROP TABLE IF EXISTS `order_grades`;/*订单评分？？*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_grades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `buyer_grade` int(1) DEFAULT NULL,
  `dilivery_grade` int(1) DEFAULT NULL,
  `speed_grade` int(1) DEFAULT NULL,
  `service_grade` int(1) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `shopper_grade` int(1) DEFAULT NULL,
  `marks` varchar(1024) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `orderLogs`
--

DROP TABLE IF EXISTS `order_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `log` varchar(256) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `operator` varchar(32) DEFAULT '',
  `operator_id` varchar(32) DEFAULT '',
  `create_time` int(11) DEFAULT NULL,
  `op_type` varchar(32) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
--

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `order`; /*订单*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order` (
  `id` int(11) not nULL AUTO_INCREMENT,
  `user_id` int(11) not NULL,
  `status` enum('wait_prepay'/*等待付定金*/,'prepayed'/*定金*/,'wait_pay'/*货已ok，等待付全款*/,'payed'/*全款*/,'packed'/*打包完毕*/,'wait_refund'/*买手确认采购失败，等待退款*/,'refund'/*已退款*/,'returned'/*未补全款，已退货*/,'fail'/*手工操作，退款了*/,'to_demostic'/*国外到国内*/,'demostic'/*国内入库*/,'to_user'/*国内到用户*/,'post_sale','success','canceled','timeout','full_refund') DEFAULT 'wait_prepay' COMMENT '订单状态',
/*  `type` enum('live','stock') DEFAULT NULL COMMENT '什么类型的订单，直播订单，商城订单',
*/
  `live_id` int(11) DEFAULT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `stock_id` int(11) DEFAULT NULL,
  `stock_amount_id` int(11) DEFAULT NULL,
  `num` int(11) DEFAULT NULL,
  `sum_price` float(10,2) DEFAULT NULL,
  `pre_payment_id` varchar(32) DEFAULT NULL,/*aimei_finance 库相关，代表一笔支付*/
  `payment_id` varchar(32) DEFAULT NULL,/*aimei_finance 库相关，代表一笔支付*/
  `logistic_id` int(11) DEFAULT NULL,
  `pack_id` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `note` text,
  `sys_note` text,
  `user_addr_id` int(11) DEFAULT NULL,
  `buyer_withdraw_id` int(11) DEFAULT NULL,/*如果这个订单的钱已被buyer提取，这个是被提取的id*/
  `country` varchar(64) DEFAULT NULL,
  `province` varchar(8) DEFAULT NULL,
  `city` varchar(8) DEFAULT NULL,
  `addr` varchar(256) NOT NULL,
  `postcode` varchar(16) DEFAULT NULL,
  `name` varchar(32) NOT NULL,
  `phone` varchar(32) DEFAULT NULL,
  `cellphone` varchar(32) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `source` varchar(256) DEFAULT NULL,
/*
  `logistic_price` float(10,2) DEFAULT NULL,
  `product_price` float(10,2) DEFAULT NULL,
  `cash_return` float(10,2) DEFAULT NULL,
  `coupon` float(10,2) DEFAULT NULL,
  `coupon_id` int(11) DEFAULT NULL,
*/
  Index `live_id_index` (`live_id`),
  Index `pack_id_index` (`pack_id`),
  Index `user_id_index` (`user_id`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10012 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
insert into `order`(user_id,status,live_id,stock_id,stock_amount_id,num,sum_price,payment_id,pre_payment_id,logistic_id,pack_id,create_time)values
(1,'to_user',1,4,1,1,3999,1,2,1,1,1400233696)
,(1,'to_user',1,2,2,1,4999,3,4,2,1,1400233696)

,(1,'prepayed',14,2,5,1,4999,null,null,null,null,1400233696)
,(1,'prepayed',14,2,5,1,4999,null,null,null,null,1400233696)
,(1,'prepayed',14,2,6,1,4999,null,null,null,null,1400233696)
,(1,'prepayed',14,2,6,1,4999,null,null,null,null,1400233696)

,(1,'prepayed',2,2,5,1,4999,null,null,null,null,1400233696)
,(1,'prepayed',2,2,5,1,4999,null,null,null,null,1400233696)
,(1,'prepayed',2,2,6,1,4999,null,null,null,null,1400233696)
,(1,'prepayed',2,2,6,1,4999,null,null,null,null,1400233696)

,(1,'prepayed',1,2,5,1,4999,null,null,null,null,1400233696)
,(1,'prepayed',1,2,5,1,4999,null,null,null,null,1400233696)
,(1,'prepayed',1,2,6,1,4999,null,null,null,null,1400233696)
,(1,'prepayed',1,2,6,1,4999,null,null,null,null,1400233696)
,(2,'prepayed',1,2,7,1,4999,null,null,null,null,1400233696)
,(2,'prepayed',1,2,7,1,4999,null,null,null,null,1400233696)
,(2,'prepayed',1,2,8,1,4999,null,null,null,null,1400233696)
,(2,'prepayed',1,2,8,1,4999,null,null,null,null,1400233696)
,(2,'wait_pay',1,2,5,1,4999,null,null,null,null,1400233696)
,(2,'wait_pay',1,2,5,1,4999,null,null,null,null,1400233696)
,(2,'payed',1,1,2,2,4999,null,null,null,null,1400233696)
,(3,'payed',1,1,2,2,4999,null,null,1,null,1400233696)
,(3,'prepayed',1,1,2,2,4999,null,null,1,null,1400233696)
,(2,'wait_prepay',1,1,2,1,4999,null,null,null,null,1400233696)
;

DROP TABLE IF EXISTS `payment`;
CREATE TABLE `payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `status` enum('wait_pay','payed') DEFAULT 'wait_pay',
  `order_id` int(11) DEFAULT NULL,
  `type` enum('prepay','pay') DEFAULT 'prepay' COMMENT '支付类型：定金，后期款',
  `amount` float(10,2) DEFAULT NULL,/*金额*/
  `refund_amount` float(10,2) DEFAULT NULL COMMENT '退款金额',/*金额*/
  `refund_memo` varchar(256) DEFAULT NULL  COMMENT '退款说明',
  `trade_no` varchar(64) DEFAULT NULL  COMMENT '订单号',
  `pay_account` varchar(64) DEFAULT '' COMMENT '支付账号',
  `platform_trade_no` varchar(64) DEFAULT '' COMMENT '支付平台订单号',
  `source` varchar(64) DEFAULT 'zfb' COMMENT '支付来源',
  `remark` varchar(1024) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trade_no_index` (`trade_no`),
  KEY `platform_trade_no_index` (`platform_trade_no`),
  KEY `create_time_index` (`create_time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
insert into payment(user_id,order_id,`type`,amount) values
(1,1,'prepay',100)
,(1,1,'pay',3899)
,(1,2,'prepay',100)
,(1,2,'pay',3899)
,(1,3,'prepay',100)
,(1,3,'pay',3899)
,(1,4,'prepay',100)
,(1,4,'pay',3899)
;


--
-- Table structure for table `securecode`
--

DROP TABLE IF EXISTS `securecode`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `securecode` (/*验证码之类的*/
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `code` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `operation` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `valid` enum('valid','invalid') NOT NULL DEFAULT 'valid',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `timeout_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stockAmount`
--

DROP TABLE IF EXISTS `stock_amount`;/*和stock是一对多的关系，购买产品的数量，子类别；如iphone的金色和黑色*/
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_amount` (
  `id` int(11) not NULL AUTO_INCREMENT COMMENT '自增长id',
  /*`user_id` int(11) DEFAULT NULL,*/
  `stock_id` int(11) DEFAULT NULL,
  `sku_value` text,/*黑色 16g*/
  `amount` int(11) unsigned DEFAULT '0' COMMENT '在库数量',
  `locked_amount` int(11) unsigned DEFAULT '0' COMMENT '被锁定数量，订单生成时，订单中的商品数量即被锁定',
  `sold_amount` int(11) unsigned DEFAULT '0' COMMENT '总共卖出多少件，商品出库时即增加该字段值',
  `create_time` int(11) DEFAULT NULL,
  `valid` enum('valid','invalid') DEFAULT 'valid' COMMENT '是否有效，invalid表示该商品类型已被删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

insert into stock_amount(stock_id,sku_value,amount)values
(6,"32G",10000),
(6,'64G',10000),
(6,'128G',10000),
(6,'256G', 10000),
(1,'红色	XL',10000),
(1,'黄色	L',10000),
(1,'白色	S',10000),
(1,'黑色	M',10000),
(2,'红色	XL',10000),
(2,'黄色	L',10000),
(2,'白色	S',10000),
(2,'黑色	M',10000),
(3,'红色	XL',10000),
(3,'黄色	L',10000),
(3,'白色	S',10000),
(3,'黑色	M',10000),
(4,'红色	XL',10000),
(4,'黄色	L',10000),
(4,'白色	S',10000),
(4,'黑色	M',10000),
(5,'红色	XL',10000),
(5,'黄色	L',10000),
(5,'白色	S',10000),
(5,'黑色	M',10000);
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `stockComments`
--

DROP TABLE IF EXISTS `stock_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_comment` (
  `id` int(11) noT NULL AUTO_INCREMENT,
  `stock_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` varchar(1024) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL COMMENT '一次反复回复的session_id，实际是首次发帖的id',
  `reply_id` int(11) DEFAULT NULL COMMENT '被回复的评论id',
  `reply_user_id` int(11) DEFAULT NULL COMMENT '被回复的人id',
  `create_time` int(11) DEFAULT NULL,
  `valid` enum('valid','invalid') DEFAULT 'valid',
  `status` enum('new','read') DEFAULT 'new',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stockGrades`
--

DROP TABLE IF EXISTS `stock_grade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_grade` (
  `id` int(11) not nULL AUTO_INCREMENT,
  `stock_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `stock_amount_id` int(11) DEFAULT NULL,
  `grade` int(1) defAULT NULL,
  `marks` varchar(1024) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `shopper_grade` int(1) DEFAULT NULL,
  primary key (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;




--
-- Table structure for table `stockLogs`
--

DROP TABLE IF EXISTS `stock_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_id` int(11) DEFAULT NULL COMMENT '商品id',
  `types` varchar(1024) DEFAULT NULL COMMENT '相关类型Id',
  `operation` enum('new','in','out','shelf','del','edit','check','delTypes','lock','unlock','sold') DEFAULT NULL COMMENT '操作类型',
  `changes` varchar(2048) DEFAULT NULL COMMENT '记录变动',
  `amount` int(11) DEFAULT NULL COMMENT '变动数量',
  `log_time` int(11) DEFAULT NULL COMMENT 'log时间',
  `user_id` int(11) DEFAULT NULL COMMENT '操作者',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stockPack`
--

DROP TABLE IF EXISTS `pack`;
--
--
--
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pack` (/*买手发货的商品*/
  `id` int(11) not null AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL ,
  `status` enum('not_send','send') DEFAULT 'not_send',
  `live_id` int(11) DEFAULT null,
  `live_ids` varchar(256) DEFAULT null COMMENT '直播ID列表',
  `buyer_id` int(11) not NULL,
  `logistic_provider` varchar(128) DEFAULT NULL,
  `logistic_no` varchar(128) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `valid` enum('valid','invalid') DEFAULT 'valid',
  `logistic_price` float(10,2) NOT NULL DEFAULT '0.00',
  `logistic_price_unit` varchar(10) NOT NULL DEFAULT 'CNY',
  `imgs` text,/*里面有快递单扫描、购物小票*/
  `logistic_imgs` text,/*里面有快递单扫描、购物小票*/
  `note` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
insert into pack(live_id,buyer_id,logistic_provider,logistic_no,logistic_price)values
(1,1,'顺丰','askdhjkshdsdf','100');

/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `stockSupply`
--
/*
drop table if exists `pack_item`;*//*一个pack，对应多个item*/
/*!40101 set @saved_cs_client     = @@character_set_client */;
/*!40101 set character_set_client = utf8 */;
/*
create table `pack_item` (
  `id` int(11) not null auto_increment,
  `pack_id` int(11) default null,
  `order_id` int(11) default null,
  `create_time` int(11) default null,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
insert into pack_item(pack_id,order_id)values
(1,1),(1,2);
*/
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stocks`
--



drop table if exists `stock`;
/*!40101 set @saved_cs_client     = @@character_set_client */;
/*!40101 set character_set_client = utf8 */;
create table `stock` (
  `id` int(11) not null auto_increment,
  `live_id` int(11) not null,
  `buyer_id` int(11) default null,
  `category_id` int(11) default null,
  `serial_num` varchar(128) default null,
  `model_num` varchar(64) default null,
  `name` varchar(128) default null,
  `brand` varchar(128) default null,
/*  `sell_type` enum('live','stock','cosign') default null comment '库存类型：库存，直播，寄卖',*/
  `pricein` float(10,2) unsigned DEFAULT '0.00' COMMENT '进货价格',
/*  `pricein_unit` enum('AUD','HKD','GBP','USD','JPY','EUR','CNY') CHARACTER SET utf8 DEFAULT 'CNY' COMMENT '进货价格货币单位',*/
  `pricein_unit` varchar(10) DEFAULT 'CNY' COMMENT '进货价格的货币单位',
  `priceout` float(10,2) unsigned DEFAULT '0.00' COMMENT '销售价格',
  `priceout_unit` varchar(10) DEFAULT 'CNY' COMMENT '销售价格的货币单位',
/*  `priceout_unit` enum('AUD','HKD','GBP','USD','JPY','EUR','CNY') CHARACTER SET utf8 DEFAULT 'CNY' COMMENT '销售价格的货币单位',*/
  `imgs` text,
  `create_time` int(11) default null,
  `update_time` int(11) default null,
  `flow_time` int(11) default null,
  `sell_time` int(11) default null,
  `valid` enum('valid','invalid') default 'valid',
  `sku_meta` text,
  `note` text,
  `status` enum('not_verify','verifying','verified','cancel') DEFAULT 'not_verify' COMMENT '未审核、正在审核、已审核、撤销',
  /*`check_result` enum('unchecked','admit','reject') default 'unchecked',*/
  `check_time` int(11) default null,
  `checker_id` int(11) default null,
  `check_words` varchar(1024) default null,
  `onshelf` int(1) DEFAULT '1' COMMENT '在架状态，上架为1，下架为0',
  `prepay` float(10,2) unsigned DEFAULT '0.00' COMMENT '定金，单位同销售价格',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*insert into `stock`(`live_id`,`buyer_id`,serial_num,model_num,name,brand,sku_meta) values("1","1","abcde","jhgfjk","surface pro2国行","微软",'{"容量":["32G","64G","128G","256G"]}');*/
insert into `stock`(`live_id`,`buyer_id`,serial_num,model_num,name,brand,sku_meta,`prepay`,`priceout`,`priceout_unit`,`pricein`,`pricein_unit`,`create_time`,`imgs`,`status`) values
("1","1","abcde","jhgfjk","LV 新款 2014M1","lv",'{"颜色":["红色","黄色","白色","黑色"],"尺码":["XL","L","S","M"]}',580,2546,'CNY',2800,'CNY',1399464365,'["/winphp/metronic/media/image/dress1.jpg","/winphp/metronic/media/image/dress2.jpg"]','verified'),
("1","1","abcde","jhgfjk","LV 新款 2014M2","lv",'{"颜色":["红色","黄色","白色","黑色"],"尺码":["XL","L","S","M"]}',580,2546,'CNY',2800,'CNY',1399464365,'["/winphp/metronic/media/image/dress1.jpg","/winphp/metronic/media/image/dress2.jpg"]','verified'),
("1","1","abcde","jhgfjk","LV 新款 2014M3","lv",'{"颜色":["红色","黄色","白色","黑色"],"尺码":["XL","L","S","M"]}',580,2546,'CNY',2800,'CNY',1399464365,'["/winphp/metronic/media/image/dress1.jpg","/winphp/metronic/media/image/dress2.jpg"]','verified'),
("1","1","abcde","jhgfjk","LV 新款 2014M4","lv",'{"颜色":["红色","黄色","白色","黑色"],"尺码":["XL","L","S","M"]}',580,2546,'CNY',2800,'CNY',1399464365,'["/winphp/metronic/media/image/dress1.jpg","/winphp/metronic/media/image/dress2.jpg"]','verified'),
("1","1","abcde","jhgfjk","LV 新款 2014M5","lv",'{"颜色":["红色","黄色","白色","黑色"],"尺码":["XL","L","S","M"]}',580,2546,'CNY',2800,'CNY',1399464365,'["/winphp/metronic/media/image/dress1.jpg","/winphp/metronic/media/image/dress2.jpg"]','verified'),
("1","1","abcde","jhgfjk","LV 新款 2014M6","lv",'{"颜色":["红色","黄色","白色","黑色"],"尺码":["XL","L","S","M"]}',580,2546,'CNY',2800,'CNY',1399464365,'["/winphp/metronic/media/image/dress1.jpg","/winphp/metronic/media/image/dress2.jpg"]','verified'),
("1","1","abcde","jhgfjk","LV 新款 2014M7","lv",'{"颜色":["红色","黄色","白色","黑色"],"尺码":["XL","L","S","M"]}',580,2546,'CNY',2800,'CNY',1399464365,'["/winphp/metronic/media/image/dress1.jpg","/winphp/metronic/media/image/dress2.jpg"]','verified'),
("1","1","abcde","jhgfjk","LV 新款 2014M8","lv",'{"颜色":["红色","黄色","白色","黑色"],"尺码":["XL","L","S","M"]}',580,2546,'CNY',2800,'CNY',1399464365,'["/winphp/metronic/media/image/dress1.jpg","/winphp/metronic/media/image/dress2.jpg"]','verified'),
("1","1","abcde","jhgfjk","LV 新款 2014M9","lv",'{"颜色":["红色","黄色","白色","黑色"],"尺码":["XL","L","S","M"]}',580,2546,'CNY',2800,'CNY',1399464365,'["/winphp/metronic/media/image/dress1.jpg","/winphp/metronic/media/image/dress2.jpg"]','verified'),
("1","1","abcde","jhgfjk","LV 新款 2014M10","lv",'{"颜色":["红色","黄色","白色","黑色"],"尺码":["XL","L","S","M"]}',580,2546,'CNY',2800,'CNY',1399464365,'["/winphp/metronic/media/image/dress1.jpg","/winphp/metronic/media/image/dress2.jpg"]','verified'),
("2","1","abcde","jhgfjk","surface pro2国行","微软",'{"容量":["32G","64G","128G","256G"]}',580,2546,'CNY',2800,'CNY',1399464365,'["/winphp/metronic/media/image/dress1.jpg","/winphp/metronic/media/image/dress2.jpg"]','verified'),
("2","1","abcde","jhgfjk","surface pro2国行","微软",'{"容量":["32G","64G","128G","256G"]}',580,2546,'CNY',2800,'CNY',1399464365,'["/winphp/metronic/media/image/dress1.jpg","/winphp/metronic/media/image/dress2.jpg"]','verified'),
("2","1","abcde","jhgfjk","surface pro2国行","微软",'{"容量":["32G","64G","128G","256G"]}',580,2546,'CNY',2800,'CNY',1399464365,'["/winphp/metronic/media/image/dress1.jpg","/winphp/metronic/media/image/dress2.jpg"]','verified'),
("2","1","abcde","jhgfjk","surface pro2国行","微软",'{"容量":["32G","64G","128G","256G"]}',580,2546,'CNY',2800,'CNY',1399464365,'["/winphp/metronic/media/image/dress1.jpg","/winphp/metronic/media/image/dress2.jpg"]','verified'),
("2","1","abcde","jhgfjk","surface pro2国行","微软",'{"容量":["32G","64G","128G","256G"]}',580,2546,'CNY',2800,'CNY',1399464365,'["/winphp/metronic/media/image/dress1.jpg","/winphp/metronic/media/image/dress2.jpg"]','verified'),
("2","1","abcde","jhgfjk","surface pro2国行","微软",'{"容量":["32G","64G","128G","256G"]}',580,2546,'CNY',2800,'CNY',1399464365,'["/winphp/metronic/media/image/dress1.jpg","/winphp/metronic/media/image/dress2.jpg"]','verified'),
("2","1","abcde","jhgfjk","surface pro2国行","微软",'{"容量":["32G","64G","128G","256G"]}',580,2546,'CNY',2800,'CNY',1399464365,'["/winphp/metronic/media/image/dress1.jpg","/winphp/metronic/media/image/dress2.jpg"]','verified'),
("2","1","abcde","jhgfjk","surface pro2国行","微软",'{"容量":["32G","64G","128G","256G"]}',580,2546,'CNY',2800,'CNY',1399464365,'["/winphp/metronic/media/image/dress1.jpg","/winphp/metronic/media/image/dress2.jpg"]','verified'),
("2","1","abcde","jhgfjk","surface pro2国行","微软",'{"容量":["32G","64G","128G","256G"]}',580,2546,'CNY',2800,'CNY',1399464365,'["/winphp/metronic/media/image/dress1.jpg","/winphp/metronic/media/image/dress2.jpg"]','verified'),
("2","1","abcde","jhgfjk","surface pro2国行","微软",'{"容量":["32G","64G","128G","256G"]}',580,2546,'CNY',2800,'CNY',1399464365,'["/winphp/metronic/media/image/dress1.jpg","/winphp/metronic/media/image/dress2.jpg"]','verified');

--
-- Dumping data for table `stocks`
--

--
-- Table structure for table `stocksLiked`
--

drop table if exists `stock_like`;
/*!40101 set @saved_cs_client     = @@character_set_client */;
/*!40101 set character_set_client = utf8 */;
create table `stock_like` (
  `id` int(11) not null auto_increment,
  `stock_id` int(11) default null,
  `user_id` int(11) default null,
  `create_time` int(11) default null,
  `valid` enum('valid','invalid') default 'valid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `system_logs`
--

DROP TABLE IF EXISTS `system_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_log` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) DEFAULT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `log` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Table structure for table `userAddr`
--

DROP TABLE IF EXISTS `user_addr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_addr` (
  `id` int(11) not null auto_increment,
  `user_id` int(11) not null,
  `country` varchar(64) default null,
  `province` varchar(8) default null,
  `city` varchar(8) default null,
  `addr` varchar(256) not null,
  `postcode` varchar(16) default null,
  `name` varchar(32) not null,
  `phone` varchar(32) default null,
  `cellphone` varchar(32) default null,
  `email` varchar(64) default null,
  `valid` enum('valid','invalid') default 'valid',
  `create_time` int(11) default null,
  `first_choice` int(1) default '0' comment '收获地址的优先选择，默认0不优先',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户收货地址表';
/*!40101 SET character_set_client = @saved_cs_client */;
insert into user_addr(`user_id`, `country`, `province`, `city`, `addr`, `postcode`, `name`, `phone`, `cellphone`, `create_time`)values
(4, '中国', '北京', '北京', '朝阳区酒仙桥路6号', '100100', '曹晴', '01012345', '15810540853', 1399948608),
(3, '中国', '北京', '北京', '海淀区中关村1号', '100100', '付恒', '01012345', '15810540001', 1399948608),
(3, '中国', '北京', '北京', '海淀区中关村2号', '100100', '付恒', '01012345', '15810540001', 1399948608),
(3, '中国', '北京', '北京', '海淀区中关村3号', '100100', '付恒', '01012345', '15810540001', 1399948608),
(3, '中国', '北京', '北京', '海淀区中关村4号', '100100', '付恒', '01012345', '15810540001', 1399948608),
(3, '中国', '北京', '北京', '海淀区中关村5号', '100100', '付恒', '01012345', '15810540001', 1399948608),
(3, '中国', '北京', '北京', '海淀区中关村6号', '100100', '付恒', '01012345', '15810540001', 1399948608),
(3, '中国', '北京', '北京', '海淀区中关村6号', '100100', '付恒', '01012345', '15810540001', 1399948608),
(3, '中国', '北京', '北京', '海淀区中关村7号', '100100', '付恒', '01012345', '15810540001', 1399948608),
(3, '中国', '北京', '北京', '海淀区中关村8号', '100100', '付恒', '01012345', '15810540001', 1399948608)
;

--
-- Table structure for table `userPointsLog`
--

drop table if exists `userpoint_log`;
/*!40101 set @saved_cs_client     = @@character_set_client */;
/*!40101 set character_set_client = utf8 */;
create table `userpoint_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) default null,
  `change` int(11) default null,
  `create_time` int(11) default null,
  `reason` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) not null aUTO_INCREMENT,
  `name` varchar(32) defaULT NULL,
  `phone` varchar(32) not NULL,
  `password` varchar(32) default NULL,
  `valid` enum('valid','invalid') NOT NULL DEFAULT 'valid' COMMENT '用户是否有效',
  `phone_verified` int(1) DEFAULT '0',
  `phone1` varchar(32) deFAULT NULL,
  `qq` varchar(32) defaulT NULL,
  `email_verified` int(1) DEFAULT '0',
  `email` varchar(32) defAULT NULL,
  `address` varchar(256) DEFAULT NULL,
  `nick` varchar(32) defaULT NULL,
  `city` varchar(32) defaULT NULL,
  `province` varchar(32) DEFAULT NULL,
  `country` varchar(32) dEFAULT NULL,
  `weixin_id` varchar(32) DEFAULT NULL,
  `weibo_id` varchar(32) DEFAULT NULL,
  `grade` int(11) not null DEFAULT '0' COMMENT '用户等级',
  `gender` enum('man','woman') NOT NULL DEFAULT 'woman' COMMENT '性别',
  `birthday` int(11) default null,
  `married` int(1) defaulT '0',
  `year_income` int(10) dEFAULT '0' COMMENT '年收入',
  `interests` varchar(256) DEFAULT NULL COMMENT '用户兴趣',
  `id_num` varchar(32) deFAULT NULL COMMENT '身份证号码',
  `create_time` int(11) dEFAULT NULL,
  `last_update_time` int(11) DEFAULT NULL,
  `avatar_url` varchar(512) DEFAULT 'users/noAvatar.jpg',
  `question` varchar(256) DEFAULT NULL,
  `answer` varchar(256) dEFAULT NULL,
  `points` int(11) not nuLL DEFAULT '0' COMMENT '用户积分',
  `wx_openid` varchar(64) DEFAULT NULL,
  `third_platform_type` enum('weixin','weibo','qq') DEFAULT 'weixin',
  `wx_accesstoken` varchar(64) DEFAULT NULL,
  `wx_createtime` int(11) DEFAULT NULL,
  `wx_refreshtoken` varchar(64) DEFAULT NULL,
  
  `easemob_username` varchar(64) default null,
  `easemob_password` varchar(64)  default null,

  `source` varchar(256) DEFAULT NULL COMMENT '注册来源，用来判断用户设备',
  
  PRIMARY KEY (`id`),
  UNIQUE KEY `i_name` (`name`),
  UNIQUE KEY `i_easemob_username` (`easemob_username`),
  UNIQUE KEY `i_phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
insert into user(`name`,`password`,`phone`,`phone_verified`) values 
('wp','b6ddd84a9cc636257258701ca934e763','18610455401','1')
,('shadow','b6ddd84a9cc636257258701ca934e763','13811284315','0')
,('caoqing','202cb962ac59075b964b07152d234b70','15810540853','1')
,('const','202cb962ac59075b964b07152d234b70','15810001000','1')
;

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feedback` (
  `id` int(11) not null auto_increment,
  `user_id` int(11) not null,
  `info` text COMMENT '反馈内容',
  `returnVisitInfo` text COMMENT '客服回访内容',
  `create_time` int(11) default null,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户反馈表';
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `easemob_anonymous`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `easemob_anonymous` (
  `id` int(11) not null auto_increment,
  `username` varchar(64) not null,
  `password` varchar(64)  not null,
  `session_id` varchar(64) not null,
  `create_time` int(11) default null,
  `update_time` int(11) default null,
  PRIMARY KEY (`id`),
  UNIQUE KEY `i_username` (`username`),
  UNIQUE KEY `i_session_id` (`session_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='easemob匿名用户表';
/*!40101 SET character_set_client = @saved_cs_client */;



DROP TABLE IF EXISTS `index_new`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `index_new` (
  `id` int(11) not null auto_increment,
  `type` varchar(20) not null,/*stock：商品页，live：直播页，buyer：买手页*/
  `model_id` varchar(64)  not null,/*（和type对应，stock_id、live_id、buyer_id）*/
  `title` varchar(250),
  `imgs` text,
  `order` int(11) default 0,
  `valid` enum('valid','invalid') default 'valid',
  `create_time` int(11) default null,
  `update_time` int(11) default null,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='首页推荐位';
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `user_reminder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_reminder` (
  `id` int(11) not null auto_increment,
  `model_type` varchar(20) not null,/*live：直播开始5分钟前提醒*/
  `model_id` varchar(64)  not null,/*（和type对应，stock_id、live_id、buyer_id）*/
  `user_id` int(11) not null,
  `push_id` varchar(64)  not null COMMENT '推送ID',
  `type` varchar(20),/* before5:直播开始前5分钟提醒*/
  `status` varchar(10),/* not_send:还没提醒过，send:已经提醒过*/
  `create_time` int(11) default null,
  `update_time` int(11) default null,
  `desc` text,
  PRIMARY KEY (`id`),
  KEY `index_push_id` (`push_id`),
  KEY `index_user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户定的提醒';

DROP TABLE IF EXISTS `live_forenotice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `live_forenotice` (
  `id` int(11) not null auto_increment,
  `live_id` int(11) not null,
  `title` varchar(250) default null,
  `content` mediumtext,
  `create_time` int(11) default null,
  `update_time` int(11) default null,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='直播预告';
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission` (
  `id` int(11) not null auto_increment,
  `name` varchar(32) not null COMMENT '权限名称',
  `description` text COMMENT '详细说明',
  `create_time` int(11) default null,
  PRIMARY KEY (`id`),
  UNIQUE KEY `i_name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='权限信息';
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group` (
  `id` int(11) not null auto_increment,
  `name` varchar(32) not null COMMENT '权限组名称, root代表最大权限管理员',
  `description` text COMMENT '详细说明',
  `create_time` int(11) default null,
  PRIMARY KEY (`id`),
  UNIQUE KEY `i_name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='权限组信息';
/*!40101 SET character_set_client = @saved_cs_client */;
insert into `group`(`name`, `description`)  values
("root", "管理员")
;

DROP TABLE IF EXISTS `role_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_permission` (
  `id` int(11) not null auto_increment,
  `group_id` text DEFAULT NULL COMMENT '权限组ID',
  `admin_id` text DEFAULT NULL COMMENT '运营者ID',
  `permission_id` text not null COMMENT '权限ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户  权限组-权限 多对多关系';
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `admin_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_group` (
  `id` int(11) not null auto_increment,
  `admin_id` int(11) not null COMMENT '管理员ID',
  `group_id` int(11) not null COMMENT '权限组ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户-权限组 多对多关系';
/*!40101 SET character_set_client = @saved_cs_client */;
insert into `admin_group`(`admin_id`, `group_id`)  values
(1, 1),
(3, 1)
;

DROP TABLE IF EXISTS `action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `action` (
  `id` int(11) not null auto_increment,
  `name` varchar(32) not null COMMENT '名称',
  `description` text COMMENT '详细说明',
  `permission_name` varchar(32) default null COMMENT '权限名称',
  `permission_id` int(11) DEFAULT NULL,
  `create_time` int(11) default null COMMENT '创建时间',
  `update_time` int(11) default null COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='ACTION权限管理';

DROP TABLE IF EXISTS `live_flow`;
CREATE TABLE `live_flow` (
  `id` int(11) not null auto_increment,
  `live_id` varchar(64) not null,
  `imgs` text not null,
  `content` text not null,
  `flow_time` int(11) not null,
  `status` smallint default 1 COMMENT '0 不显示；1 显示',
  `create_time` int(11) default null,
  `update_time` int(11) default null,
  PRIMARY KEY (`id`),
  KEY `live_id_index` (`live_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='直播内容流';

DROP TABLE IF EXISTS `storage`;
CREATE TABLE `storage` (
      `id` int(11) NOT NULL auto_increment COMMENT '自增ID',
      `order_id` int(11) NOT NULL COMMENT '订单ID',
      `buyer_id` int(11) NOT NULL COMMENT '买手ID',
      `user_id` int(11) NOT NULL COMMENT '买家ID',
      `pack_id` int(11) NOT NULL COMMENT '包裹ID',
      `logistic_id` int(11) NOT NULL COMMENT '国内物流ID',
      `location` varchar(16) NOT NULL COMMENT '货架号',
      `memo` text NOT NULL COMMENT '库存备忘',
      `status` varchar(16) DEFAULT 'waiting' COMMENT '库存状态, waiting-未到货；in-已收货；out-已发货；canceled-已取消',
      `stock_status` varchar(16) DEFAULT 'normal' COMMENT '商品状态，normal-正常件；pending-问题件',
      `create_time` int(11) DEFAULT NULL COMMENT '库存登记时间',
      `pending_time` int(11) DEFAULT NULL COMMENT '问题件登记时间',
      `cs_status` smallint DEFAULT '0' COMMENT '客服状态，0-未处理；1-买家接受；2-买家不接受',
      `cs_memo` varchar(64) DEFAULT '' COMMENT '客服备忘',
      `cs_time` int(11) DEFAULT NULL COMMENT '客服处理时间',
      `pu_status` smallint DEFAULT '0' COMMENT '采购状态，0-未处理；1-接受入库；2-退回买手；3-销毁；4-买手补发；5-不再补发',
      `pu_memo` varchar(64) DEFAULT '' COMMENT '采购备忘',
      `pu_time` int(11) DEFAULT NULL COMMENT '采购处理时间',
      `in_time` int(11) DEFAULT NULL COMMENT '收货时间',
      `out_time` int(11) DEFAULT NULL COMMENT '发货时间',
      `action_time` int(11) DEFAULT NULL COMMENT '处理时间',
      `send_time` int(11) DEFAULT NULL COMMENT '采购发货时间',
      PRIMARY KEY (`id`),
      KEY `order_id_index` (`order_id`),
      KEY `buyer_id_index` (`buyer_id`),
      KEY `user_id_index` (`user_id`),
      KEY `pack_id_index` (`pack_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='库存管理';

DROP TABLE IF EXISTS `sms_queue`;
CREATE TABLE `sms_queue` (
      `id` int(11) NOT NULL auto_increment COMMENT '自增ID',
      `phone` varchar(16) NOT NULL COMMENT '手机号',
      `content` varchar(256) NOT NULL COMMENT '短信内容',
      `order_id` int(11) NOT NULL COMMENT '订单ID',
      `status` smallint DEFAULT 0 COMMENT '状态，0-未发送；1-已发送',
      `create_time` int(11) DEFAULT NULL COMMENT '',
      `send_time` int(11) DEFAULT NULL COMMENT '处理时间',
      PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='短信发送队列';

DROP TABLE IF EXISTS `delivery_abroad`;
CREATE TABLE `delivery_abroad` (
    `id` int(11) NOT NULL auto_increment COMMENT '自增ID',
    `order_id` int(11) NOT NULL COMMENT '订单ID',
    `buyer_id` int(11) NOT NULL COMMENT '买手ID',
    `pack_id` int(11) NOT NULL COMMENT '包裹ID',
    `live_id` int(11) NOT NULL COMMENT '直播ID',
    `stock_id` int(11) NOT NULL COMMENT '直播ID',
    `sku_id` int(11) NOT NULL COMMENT 'SKU ID',
    `status` smallint NOT NULL COMMENT '结算标志, 0 未结算；1 已结算',
    `pay_time` int(11) DEFAULT NULL COMMENT '结算时间',
    `delivery_time` int(11) DEFAULT NULL COMMENT '海外发货时间',
    PRIMARY KEY (`id`),
    UNIQUE KEY `order_id_index` (`order_id`),
    KEY `buyer_id_index` (`buyer_id`),
    KEY `pack_id_index` (`pack_id`),
    KEY `live_id_index` (`live_id`),
    KEY `delivery_time_index` (`delivery_time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='海外发货列表';

-- 汇率记录表
DROP TABLE IF EXISTS `exchange_rate`;
CREATE TABLE `exchange_rate` (
    `id` int(11) NOT NULL auto_increment COMMENT '自增ID',
    `currency_short` char(5) NOT NULL COMMENT '货币简称',
    `currency_name` varchar(16) NOT NULL COMMENT '货币名称',
    `buy` varchar(10) NOT NULL COMMENT '汇买价',
    `cash_buy` varchar(10) NOT NULL COMMENT '钞买价',
    `sell` varchar(10) NOT NULL COMMENT '卖出价',
    `cash_sell` varchar(10) NOT NULL COMMENT '钞卖价',
    `pub_time` int(11) NOT NULL COMMENT '发布时间',
    `create_time` int(11) NOT NULL COMMENT '入库时间',
    PRIMARY KEY (`id`),
    UNIQUE KEY `pub_time_currency_short_index` (`pub_time`,`currency_short`),
    KEY `pub_time_index` (`pub_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='汇率记录表';

-- 环信消息表
DROP TABLE IF EXISTS `easemob_msg`;
CREATE TABLE `easemob_msg` (
    `id` int(11) NOT NULL auto_increment COMMENT '自增ID',
    `msg_id` varchar(32) NOT NULL COMMENT '环信msg_id',
    `from` varchar(40) NOT NULL COMMENT '环信username',
    `to` varchar(40) NOT NULL COMMENT '环信username',
    `msg_type` varchar(10) default NULL COMMENT '消息类型',
    `msg_text` varchar(256) default NULL COMMENT '消息内容',
    `send_time` int(11) DEFAULT NULL COMMENT '发消息时间',
    `rawdata` text COMMENT '消息',
    PRIMARY KEY (`id`),
    UNIQUE KEY `msg_id_index` (`msg_id`),
    KEY `from_index` (`from`),
    KEY `to_index` (`to`),
    KEY `send_time_index` (`send_time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='环信消息';

-- 用户退款表
DROP TABLE IF EXISTS `user_refund`;
CREATE TABLE `user_refund` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `status` smallint(6) NOT NULL DEFAULT '0' COMMENT '退款状态, 0-未处理；1-处理中；2-已完成',
  `type` smallint(6) NOT NULL DEFAULT '0' COMMENT '退款方式, 0-原路退；1-手工退；',
  `range` smallint(6) NOT NULL DEFAULT '0' COMMENT '退款范围, 0-全额；1-部分；',
  `amount` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '退款指定金额',
  `note` varchar(256) NOT NULL COMMENT '退款备注',
  `imgs` text COMMENT '问题拍照',
  `account` varchar(256) NOT NULL COMMENT '账户信息',
  `creator` varchar(32) DEFAULT 'system' COMMENT '创建人类型，system；admin; buyer',
  `creator_id` varchar(32) DEFAULT '0' COMMENT '创建人ID',
  `operator` varchar(32) DEFAULT 'admin' COMMENT '操作人',
  `operator_id` varchar(32) DEFAULT '0' COMMENT '操作人ID',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `reason` varchar(256) NOT NULL COMMENT '退款原因',
  PRIMARY KEY (`id`),
  KEY `create_time_index` (`create_time`)
) ENGINE=InnoDB AUTO_INCREMENT=1010001 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户退款表';

-- 推送任务表
DROP TABLE IF EXISTS `task_push`;
CREATE TABLE `task_push` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `status` smallint(6) NOT NULL DEFAULT '0' COMMENT '任务状态, 0-未处理；1-处理中；2-已完成',
  `content` varchar(256) NOT NULL COMMENT '推送内容',
  `creator_id` int(11) DEFAULT '0' COMMENT '创建人ID',
  `type` smallint(6) NOT NULL DEFAULT '0' COMMENT '推送范围, 0-全部；1-指定',
  `user_ids` text DEFAULT NULL COMMENT '创建人ID',
  `success` int(11) NOT NULL COMMENT '成功数',
  `fail` int(11) NOT NULL COMMENT '失败数',
  `create_time` int(11) NOT NULL COMMENT '任务创建时间',
  `push_time` int(11) NOT NULL COMMENT '开始推送时间',
  `end_time` int(11) NOT NULL COMMENT '推送完成时间',
  PRIMARY KEY (`id`),
  KEY `push_time_index` (`push_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='推送任务表';

-- 推广记录表（domob合作）
DROP TABLE IF EXISTS `promote_channel`;
CREATE TABLE `promote_channel` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `udid` varchar(64) DEFAULT NULL COMMENT '唯一设备标识',
  `mac` varchar(64) DEFAULT NULL COMMENT '唯一设备标识',
  `ifa` varchar(64) DEFAULT NULL COMMENT '唯一设备标识',
  `oid` varchar(64) DEFAULT NULL COMMENT '唯一设备标识',
  `appid` varchar(32) NOT NULL COMMENT 'appid',
  `source` varchar(32) DEFAULT NULL COMMENT '渠道来源',
  `click_ip` varchar(16) NOT NULL COMMENT '点击ip',
  `active_ip` varchar(16) DEFAULT NULL COMMENT '激活ip',
  `click_time` int(11) NOT NULL COMMENT '点击时间',
  `active_time` int(11) DEFAULT NULL COMMENT '激活时间',
  `ping_time` int(11) DEFAULT NULL COMMENT '回调时间',
  PRIMARY KEY (`id`),
  KEY `ifa_index` (`ifa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='推送任务表';

DROP TABLE IF EXISTS `stock_feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_feedback` (
  `id` int(11) not null auto_increment,
  `stock_id` int(11) not null,
  `live_id` int(11) not null,
  `buyer_id` int(11) not null,
  `user_id` int(11) not null,
  `type` varchar(30) default null COMMENT '如：与描述不符；高于市场价；其他。在前端都是固定选项',
  `info` text COMMENT '反馈内容',
  `create_time` int(11) default null,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户反馈表';
/*!40101 SET character_set_client = @saved_cs_client */;

-- 发货单打印记录 
DROP TABLE IF EXISTS `express_print`;
CREATE TABLE `express_print` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `storage_ids` varchar(1024) DEFAULT NULL COMMENT '订单号',
  `print_time` int(11) DEFAULT NULL COMMENT '打印时间',
  PRIMARY KEY (`id`),
  KEY `print_time_index` (`print_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='发货单打印记录';
