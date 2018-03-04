DROP TABLE IF EXISTS `permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission` (
  `id` int(11) not null auto_increment,
  `name` varchar(32) not null COMMENT '权限名称',
  `description` text COMMENT '详细说明',
  `create_time` int(11) default null,
  PRIMARY KEY (`id`)
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
  PRIMARY KEY (`id`)
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
  `group_id` int(11) COMMENT '权限组ID',
  `admin_id` int(11) COMMENT '运营者ID',
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
