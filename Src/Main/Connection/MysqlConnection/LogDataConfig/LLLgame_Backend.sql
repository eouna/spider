/*
Ultimate v12.09 (64 bit)
MySQL - 5.6.28-cdb2016-log : Database - LLLgame_Backend_CE
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`LLLgame_Backend_CE` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `LLLgame_Backend_CE`;

/*Table structure for table `fa_action_log` */

DROP TABLE IF EXISTS `fa_action_log`;

CREATE TABLE `fa_action_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` varchar(12) DEFAULT NULL COMMENT '玩家ID',
  `user_name` varchar(45) DEFAULT NULL COMMENT '玩家姓名',
  `action_name` varchar(200) DEFAULT NULL COMMENT '玩家动作',
  `action_value` varchar(100) DEFAULT NULL COMMENT '动作的值',
  `operate` varchar(45) DEFAULT NULL COMMENT '操作名称',
  `path` varchar(200) DEFAULT NULL COMMENT '操作路径',
  `created_at` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `fa_card_collect_log` */

DROP TABLE IF EXISTS `fa_card_collect_log`;

CREATE TABLE `fa_card_collect_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` varchar(12) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家ID',
  `user_name` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩爱姓名',
  `card_id` int(12) DEFAULT NULL COMMENT '卡牌ID',
  `is_new` tinyint(4) DEFAULT NULL COMMENT '是否是新卡',
  `consume_currency` int(11) DEFAULT NULL COMMENT '消耗的金币',
  `quality` int(11) DEFAULT NULL COMMENT '质量',
  `role_name` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '角色名',
  `role_id` int(5) DEFAULT NULL COMMENT '角色ID',
  `operate` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作类型',
  `path` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作路径',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1126 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `fa_clock_log` */

DROP TABLE IF EXISTS `fa_clock_log`;

CREATE TABLE `fa_clock_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` varchar(12) DEFAULT NULL COMMENT '玩家ID',
  `user_name` varchar(45) DEFAULT NULL COMMENT '玩家名',
  `clock_op` tinyint(2) DEFAULT NULL COMMENT '操作类型',
  `is_open` tinyint(2) DEFAULT NULL COMMENT '是否打开',
  `clock_date` varchar(20) DEFAULT NULL COMMENT '闹钟日期',
  `clock_repeat` varchar(20) DEFAULT NULL COMMENT '是否重复',
  `title` varchar(45) DEFAULT NULL COMMENT '闹钟名称',
  `alert_voice` varchar(12) DEFAULT NULL COMMENT '提示语音',
  `operate` varchar(45) DEFAULT NULL COMMENT '操作类型',
  `path` varchar(100) DEFAULT NULL COMMENT '操作路径',
  `created_at` int(11) DEFAULT NULL COMMENT '创建日期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `fa_collect_log` */

DROP TABLE IF EXISTS `fa_collect_log`;

CREATE TABLE `fa_collect_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` varchar(12) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '玩家ID',
  `user_name` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '玩家姓名',
  `type_id` tinyint(4) DEFAULT NULL COMMENT '类型ID',
  `plot_id` int(11) DEFAULT NULL COMMENT '剧情ID',
  `memory_id` int(8) DEFAULT NULL COMMENT '回忆ID',
  `count_num_type` int(11) DEFAULT NULL COMMENT '类型对应的总数',
  `count_num` int(11) DEFAULT NULL COMMENT '回忆总数',
  `operate` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '操作类型',
  `path` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '操作路径',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=438 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `fa_confess_log` */

DROP TABLE IF EXISTS `fa_confess_log`;

CREATE TABLE `fa_confess_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` varchar(12) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家ID',
  `user_name` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家姓名',
  `consume_item_id` int(11) DEFAULT NULL COMMENT '消耗的道具ID',
  `count_heartbeat` int(11) DEFAULT NULL COMMENT '心动值变化',
  `new_num` int(11) DEFAULT NULL COMMENT '新的心动值',
  `new_rank` int(11) DEFAULT NULL COMMENT '新的排名',
  `role_name` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '角色名',
  `operate` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作类型',
  `path` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作路径',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=156 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `fa_contribute_log` */

DROP TABLE IF EXISTS `fa_contribute_log`;

CREATE TABLE `fa_contribute_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` varchar(12) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家ID',
  `user_name` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '用户名',
  `gift_id` int(6) DEFAULT NULL COMMENT '礼物ID',
  `count_num` int(11) DEFAULT NULL COMMENT '贡献值',
  `new_num` int(11) DEFAULT NULL COMMENT '新的贡献值',
  `new_week_num` int(11) DEFAULT NULL COMMENT '新的周贡献值',
  `new_total_num` int(11) DEFAULT NULL COMMENT '新的总贡献值',
  `role_id` int(8) DEFAULT NULL COMMENT '角色ID',
  `operate` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作类型',
  `path` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作路径',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `fa_currency_log` */

DROP TABLE IF EXISTS `fa_currency_log`;

CREATE TABLE `fa_currency_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家ID',
  `user_name` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家姓名',
  `op_type` tinyint(4) DEFAULT NULL COMMENT '操作类型',
  `money_id` int(4) DEFAULT NULL COMMENT '钱币ID',
  `old_num` int(11) DEFAULT NULL COMMENT '旧的金币数量',
  `count_num` int(11) DEFAULT NULL COMMENT '操作的数量',
  `new_num` int(11) DEFAULT NULL COMMENT '新的金币数量',
  `reason` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '二级原因',
  `reason_param` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '二级原因参数',
  `operate` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作类型',
  `path` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作路径',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1435 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `fa_hot_log` */

DROP TABLE IF EXISTS `fa_hot_log`;

CREATE TABLE `fa_hot_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` varchar(12) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家ID',
  `user_name` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家姓名',
  `role_id` tinyint(4) DEFAULT NULL COMMENT '角色ID',
  `gift_id` tinyint(4) DEFAULT NULL COMMENT '礼物ID',
  `count_hot` int(11) DEFAULT NULL COMMENT '总热度值',
  `new_hot_num` int(11) DEFAULT NULL COMMENT '新的热度总值',
  `new_week_hot` int(11) DEFAULT NULL COMMENT '新的周热度值',
  `new_total_hot` int(11) DEFAULT NULL COMMENT '新的总热度值',
  `operate` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作类型',
  `path` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作路径',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `fa_intimacy_log` */

DROP TABLE IF EXISTS `fa_intimacy_log`;

CREATE TABLE `fa_intimacy_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` varchar(12) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家ID',
  `user_name` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家姓名',
  `role_id` tinyint(4) DEFAULT NULL COMMENT '角色ID',
  `role_name` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '角色名字',
  `task_id` tinyint(4) DEFAULT NULL COMMENT '任务ID',
  `gift_id` int(11) DEFAULT NULL COMMENT '礼物ID',
  `intimacy_change` int(4) DEFAULT NULL COMMENT '亲密度变化值',
  `new_intimacy_level` int(4) DEFAULT NULL COMMENT '新的亲密度等级',
  `operate` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作类型',
  `path` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作路径',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=451 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `fa_item_log` */

DROP TABLE IF EXISTS `fa_item_log`;

CREATE TABLE `fa_item_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家ID',
  `user_name` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家姓名',
  `item_type` tinyint(4) DEFAULT NULL COMMENT '消耗类型',
  `item_name` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '道具名称',
  `item_old_num` int(11) DEFAULT NULL COMMENT '旧的数量',
  `item_id` int(11) DEFAULT NULL COMMENT '道具ID',
  `item_num` int(11) DEFAULT NULL COMMENT '数量',
  `item_new_num` int(11) DEFAULT NULL COMMENT '新的数量',
  `is_cost_ticket` tinyint(4) DEFAULT NULL COMMENT '是否花费金币',
  `operate` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作名称',
  `path` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作路径',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3913 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `fa_login_log` */

DROP TABLE IF EXISTS `fa_login_log`;

CREATE TABLE `fa_login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家ID',
  `user_name` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家姓名',
  `login_ip` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '登陆IP',
  `login_channel` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '登陆渠道',
  `hardware_type` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '登陆机型',
  `device_version` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '设备型号',
  `device_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '设备ID',
  `qiyidevid` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '爱奇艺设备ID',
  `login_duration` int(10) DEFAULT NULL COMMENT '登录时长',
  `operate` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作详情',
  `path` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作路径',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7411 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `fa_logout_log` */

DROP TABLE IF EXISTS `fa_logout_log`;

CREATE TABLE `fa_logout_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` varchar(11) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家ID',
  `user_name` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家姓名',
  `login_channel` varchar(12) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '登陆渠道',
  `hardware_type` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '登陆机型',
  `device_version` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '设备版本',
  `device_id` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '设备ID',
  `login_time` int(11) DEFAULT NULL COMMENT '登陆时间',
  `online_time` int(11) DEFAULT NULL COMMENT '在线时长',
  `operate` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作别名',
  `path` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作路径',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16011 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `fa_plot_log` */

DROP TABLE IF EXISTS `fa_plot_log`;

CREATE TABLE `fa_plot_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` varchar(12) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家ID',
  `user_name` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家姓名',
  `plot_id` int(11) DEFAULT NULL COMMENT '剧情ID',
  `paragraph_id` int(11) DEFAULT NULL COMMENT '段落ID',
  `operate` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作类型',
  `path` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作路径',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3119 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `fa_recharge_log` */

DROP TABLE IF EXISTS `fa_recharge_log`;

CREATE TABLE `fa_recharge_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '玩家ID',
  `user_name` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '玩家姓名',
  `goods_id` int(4) DEFAULT NULL COMMENT '商品ID',
  `order_id` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '订单ID',
  `charge_num` int(10) DEFAULT NULL COMMENT '充值货币个数',
  `new_currency` int(11) DEFAULT NULL COMMENT '新的货币个数',
  `give_count` int(10) DEFAULT NULL COMMENT '额外加成',
  `channel` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '渠道ID',
  `device_id` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '设备ID',
  `qiyidevid` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '爱奇艺设备ID',
  `amount` int(10) DEFAULT NULL COMMENT '充值金额',
  `channel_id` int(4) DEFAULT NULL COMMENT '游戏渠道ID',
  `operate` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '操作名称',
  `path` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '操作路径',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2011 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `fa_register_log` */

DROP TABLE IF EXISTS `fa_register_log`;

CREATE TABLE `fa_register_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` varchar(15) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家ID',
  `user_name` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家姓名',
  `register_ip` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '注册地址',
  `register_channel` tinyint(4) DEFAULT NULL COMMENT '注册渠道',
  `os_version` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '移动终端系统版本',
  `device_brand` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '设备制造商',
  `device_version` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '设备型号',
  `device_id` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '设备ID',
  `qiyidevid` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '爱奇艺设备ID',
  `gold_pay` int(10) DEFAULT '0' COMMENT '充值钻石余量',
  `gold_bonus` int(10) DEFAULT '0' COMMENT '赠送钻石余量',
  `operate` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作名称',
  `path` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作路径',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6272 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `fa_schedule_log` */

DROP TABLE IF EXISTS `fa_schedule_log`;

CREATE TABLE `fa_schedule_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` varchar(12) DEFAULT NULL COMMENT '玩家ID',
  `user_name` varchar(45) DEFAULT NULL COMMENT '用户名',
  `schedule_op` tinyint(4) DEFAULT NULL COMMENT '日程操作',
  `role_id` int(4) DEFAULT NULL COMMENT '角色ID',
  `schedule_type` tinyint(2) DEFAULT NULL COMMENT '日程类型',
  `alert_date` int(11) DEFAULT NULL COMMENT '提醒日期',
  `clock_need` tinyint(2) DEFAULT NULL COMMENT '是否有闹钟',
  `operate` varchar(45) DEFAULT NULL COMMENT '操作类型',
  `path` varchar(100) DEFAULT NULL COMMENT '操作路径',
  `created_at` int(11) DEFAULT NULL COMMENT '创建日期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `fa_share_log` */

DROP TABLE IF EXISTS `fa_share_log`;

CREATE TABLE `fa_share_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` varchar(12) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家ID',
  `user_name` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家姓名',
  `share_url` text COLLATE utf8mb4_general_ci COMMENT '分享链接',
  `share_method` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '分享方法',
  `operate` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '操作类型',
  `path` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '操作路径',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `fa_sign_log` */

DROP TABLE IF EXISTS `fa_sign_log`;

CREATE TABLE `fa_sign_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` varchar(12) DEFAULT NULL COMMENT '玩家ID',
  `user_name` varchar(45) DEFAULT NULL COMMENT '玩家姓名',
  `sign_type` tinyint(1) DEFAULT NULL COMMENT '签到类型',
  `sign_num` int(4) DEFAULT NULL COMMENT '签到次数',
  `serial_day` int(6) DEFAULT NULL COMMENT '连续签到天数',
  `accumulate_day` int(6) DEFAULT NULL COMMENT '累计签到天数',
  `role_id` int(6) DEFAULT NULL COMMENT '角色ID',
  `operate` varchar(45) DEFAULT NULL COMMENT '操作类型',
  `path` varchar(100) DEFAULT NULL COMMENT '操作路径',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
