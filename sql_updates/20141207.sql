CREATE TABLE `live_stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '关系自增主键',
  `live_id` int(11) NOT NULL COMMENT '直播id',
  `stock_id` int(11) NOT NULL COMMENT '商品id',
  `status` enum('not_verify','verifying','verified','cancel') DEFAULT 'not_verify' COMMENT '未审核、正在审核、已审核、撤销',
  `stock_type` int(11) DEFAULT '1' COMMENT '1 商品;2 状态',
  `flow_time` int(11) DEFAULT NULL COMMENT '流时间',
  `sell_time` int(11) DEFAULT NULL COMMENT '销售时间',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `checker_id` int(11) DEFAULT NULL COMMENT '审核人员id',
  `check_words` varchar(1024) DEFAULT NULL COMMENT '评语',
  PRIMARY KEY (`id`),
  KEY `idx_live_id` (`live_id`),
  KEY `idx_liveId_status_flow_time` (`live_id`,`status`,`flow_time`),
  KEY `idx_stockid_liveid_flowtime` (`stock_id`,`live_id`,`flow_time`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='直播与商品的关系表';

CREATE TABLE `buyer_pic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `buyer_id` int(11) DEFAULT NULL COMMENT '买手id',
  `note` varchar(1024) DEFAULT NULL COMMENT '状态描述',
  `imgs` text COMMENT '照片列表',
  `location` varchar(200) DEFAULT NULL COMMENT '图片的地理位置信息',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `liked` int(11) DEFAULT NULL COMMENT '喜欢数目',
  `commented` int(11) DEFAULT NULL COMMENT '评论数',
  `status` tinyint(4) DEFAULT '1' COMMENT '0，删除；1，有效',
  PRIMARY KEY (`id`),
  KEY `idx_buyerId_status` (`buyer_id`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='买手图片状态表';

CREATE TABLE `state_rank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `buyer_id` int(11) DEFAULT NULL COMMENT '状态id',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '类型 1，商品；2，状态',
  `state_id` int(11) NOT NULL COMMENT '外部状态或商品id',
  `status` tinyint(4) DEFAULT '1' COMMENT '0，删除；1，有效',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_userId_status_updated` (`buyer_id`,`status`,`update_time`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='状态商品排序表';

CREATE TABLE `favor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `favor_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 商品，2 买手状态，3 买手',
  `favor_id` int(11) NOT NULL DEFAULT '0' COMMENT '被喜欢的实体id',
  `notify_id` int(11) NOT NULL DEFAULT '0' COMMENT '被通知者id',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '点赞者id',
  `valid` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 有效；0 删除',
  `read` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 未读；1 未读',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_userId_type` (`user_id`,`favor_type`,`valid`),
  KEY `idx_favorid_userid_type` (`favor_id`,`user_id`,`favor_type`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='买家喜欢';

alter table `stock` add column `liked` int(11) not null default '0' comment '被喜欢数';
alter table `stock` add column `commented` int(11) not null default '0' comment '被评论数';

alter table `buyer` add column `category` varchar(200) default null comment '买手的分类标签';
alter table `buyer` add column `signature` varchar(280) default null comment '买手签名档';
alter table `buyer` add column `fans` int(11) not null default '0' comment '粉丝数';
alter table `buyer` add column `level` int(11) not null default '0' comment '买手等级';
alter table `buyer` add column `desc` varchar(500) default null comment '买手的描述tag';


CREATE TABLE `buyer_rank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `buyer_id` int(11) NOT NULL COMMENT '买手id',
  `type` tinyint(4) NOT NULL COMMENT '排序的测试 1，运营推荐；2，最近更新商品',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `selector_id` int(11) DEFAULT '0' COMMENT '选款师id',
  `comment` varchar(500) DEFAULT NULL COMMENT '评论',
  `soso_comment` varchar(100) DEFAULT NULL COMMENT '买手最近上传的商品以及状态，保存3-5条',
  PRIMARY KEY (`id`),
  KEY `idx_updated` (`update_time`),
  KEY `idx_buyerId_type` (`buyer_id`,`type`)
) ENGINE=InnoDB AUTO_INCREMENT=292 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='买手的排序表';


CREATE TABLE `buyer_statistic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `buyer_id` int(11) NOT NULL COMMENT '买手id',
  `stock_statistic` varchar(500) DEFAULT NULL COMMENT '商品统计表',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_buyerId` (`buyer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='买手统计';


CREATE TABLE `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ci_user_type` tinyint(4) NOT NULL COMMENT '评论提交者的类型：1，买家；2，买手',
  `ci_user_id` int(11) NOT NULL COMMENT '评论提交者的用户id',
  `comment` varchar(280) DEFAULT NULL COMMENT '评论的内容',
  `state_type` tinyint(4) NOT NULL COMMENT '被评论事物的类型：1，商品；2，状态',
  `state_id` int(11) NOT NULL COMMENT '被评论事物的id',
  `owner_type` tinyint(4) NOT NULL COMMENT '被评论事物的所有者的用户类型：1，买家；2，买手',
  `owner_id` int(11) NOT NULL COMMENT '被评论事物的所有者的用户id',
  `comment_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '评论的类型：1，评论;2，回复；3，引用',
  `reply_id` int(11) DEFAULT NULL COMMENT '被回复的评论',
  `reply_user_type` tinyint(4) DEFAULT NULL COMMENT '被回复引用的用户的类型：1，买家；2，买手',
  `reply_user_id` int(11) DEFAULT NULL COMMENT '被回复引用的用户的id',
  `status` tinyint(4) DEFAULT '1' COMMENT '评论状态：1，有效；0，删除',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_stateId_type` (`state_id`,`state_type`),
  KEY `idx_ciUserId_ciUserType` (`ci_user_id`,`ci_user_type`),
  KEY `idx_replyedUserId_replyedUserType` (`reply_user_id`,`reply_user_type`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='评论表';


CREATE TABLE `stock_book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` tinyint(4) NOT NULL DEFAULT '1' COMMENT '图墙分类 1,打折村;2,美容;3,服装;4,鞋包;5,母婴;6,珠宝配饰;7,生活保健;8,特色',
  `stock_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `selector_id` int(11) NOT NULL DEFAULT '0' COMMENT '选款师',
  `comment` varchar(200) DEFAULT NULL COMMENT '买手推荐评论',
  `status` tinyint(4) DEFAULT '1' COMMENT '是否有效 1，有效；0，下线',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_stockId` (`stock_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品图墙';


CREATE TABLE `trade_rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL COMMENT '订单id',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '买家用户id',
  `stock_id` int(11) DEFAULT NULL COMMENT '商品id',
  `buyer_id` int(11) NOT NULL DEFAULT '0' COMMENT '买手用户id',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '评分',
  `stock_desc` varchar(200) DEFAULT NULL COMMENT '商品的评价',
  `buyer_desc` varchar(200) DEFAULT NULL COMMENT '买手的评价',
  `status` tinyint(4) DEFAULT NULL COMMENT '状态：1，有效；2，删除',
  `comment` varchar(280) DEFAULT NULL COMMENT '评论',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_orderId` (`order_id`),
  KEY `idx_userId` (`user_id`),
  KEY `idx_buyerId` (`buyer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='交易评价';

alter table `buyer` change column `category` `background_pic` varchar(256) DEFAULT '' COMMENT '买手页自定义图片大图';
alter table `buyer` add column `background_pic_small` varchar(256) DEFAULT '' COMMENT '买手页自定义图片小图' after `background_pic`;
alter table `buyer` change column `desc` `desc` varchar(1024) DEFAULT '' COMMENT '买家评论tag';
alter table `stock` add column `score` tinyint(4)  default '0' comment '商品的总和评分(0-50)，需要除以10';
alter table `stock` add column `rate_tags` varchar(200) default '' comment '商品评价的描述';

update buyer set signature='我是你的眼，带你发现更多美’;

alter table `live` add index `idx_buyer_id_valid`(`buyer_id`, `valid`, `start_time`);

alter table `index_new` add column `channel` tinyint(4) not null default '1' comment 'banner频道：1，直播频道；2，买手频道';

alter table `stock` add column `original_price` float(10,2) unsigned DEFAULT '0.00' COMMENT '国内售价' after `priceout_unit`;
alter table `stock` add column `score` tinyint(4)  default '0' comment '商品的总和评分(0-50)，需要除以10';
alter table `stock` add column `rate_tags` varchar(200) default '' comment '商品评价的描述';
alter table `stock` add column `tags` varchar(256) DEFAULT '' COMMENT '商品标签' after `note`;

alter table `state_rank` add index `idx_state_id_type`(`state_id`, `type`);

insert into `permission` (name, description, create_time) values('buyerrank', '买手推荐读', 1418096179),('buyerrank_w', '买手推荐写', 1418096179);
insert into `action` (name, description, permission_id, create_time, update_time) select 'buyerrank_index', '', id, 1418096179 , 1418096179 from permission where name='buyerrank';
insert into `action` (name, description, permission_id, create_time, update_time) select 'buyerrank_read', '', id, 1418096179 , 1418096179 from permission where name='buyerrank';
insert into `action` (name, description, permission_id, create_time, update_time) select 'buyerrank_search', '', id, 1418096179 , 1418096179 from permission where name='buyerrank';
insert into `action` (name, description, permission_id, create_time, update_time) select 'buyerrank_select', '', id, 1418096179 , 1418096179 from permission where name='buyerrank';
insert into `action` (name, description, permission_id, create_time, update_time) select 'buyerrank_select_search', '', id, 1418096179 , 1418096179 from permission where name='buyerrank';
insert into `action` (name, description, permission_id, create_time, update_time) select 'buyerrank_create', '', id, 1418096179 , 1418096179 from permission where name='buyerrank_w';
insert into `action` (name, description, permission_id, create_time, update_time) select 'buyerrank_update', '', id, 1418096179 , 1418096179 from permission where name='buyerrank_w';
insert into `action` (name, description, permission_id, create_time, update_time) select 'buyerrank_delete', '', id, 1418096179 , 1418096179 from permission where name='buyerrank_w';

insert into `permission` (name, description, create_time) values('stockbook', '图墙推荐', 1418096179),('stockbook_w', '图墙推荐写', 1418096179);
insert into `action` (name, description, permission_id, create_time, update_time) select 'stockbook_index', '', id, 1418096179 , 1418096179 from permission where name='stockbook';
insert into `action` (name, description, permission_id, create_time, update_time) select 'stockbook_read', '', id, 1418096179 , 1418096179 from permission where name='stockbook';
insert into `action` (name, description, permission_id, create_time, update_time) select 'stockbook_search', '', id, 1418096179 , 1418096179 from permission where name='stockbook';
insert into `action` (name, description, permission_id, create_time, update_time) select 'stockbook_select', '', id, 1418096179 , 1418096179 from permission where name='stockbook';
insert into `action` (name, description, permission_id, create_time, update_time) select 'stockbook_select_search', '', id, 1418096179 , 1418096179 from permission where name='stockbook';
insert into `action` (name, description, permission_id, create_time, update_time) select 'stockbook_create', '', id, 1418096179 , 1418096179 from permission where name='stockbook_w';
insert into `action` (name, description, permission_id, create_time, update_time) select 'stockbook_update', '', id, 1418096179 , 1418096179 from permission where name='stockbook_w';
insert into `action` (name, description, permission_id, create_time, update_time) select 'stockbook_delete', '', id, 1418096179 , 1418096179 from permission where name='stockbook_w';




