<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/4/3
 * Time: 9:38
 */

namespace Connection\MysqlConnection\LogDataConfig;

use Connection\MysqlConnection\MysqlConfig\MysqlConnect;
use Tools\CPlus;

class LogDistributeCenter
{

    const LOG_REGISTER         = 'register_log';
    const LOG_LOGIN            = 'login_log';
    const LOG_LOGOUT           = 'logout_log';
    const LOG_ITEM             = 'item_log';
    const LOG_RECHARGE         = 'recharge_log';
    const LOG_CURRENCY         = 'currency_log';
    const LOG_INTIMACY         = 'intimacy_log';
    const LOG_HOT              = 'hot_log';
    const LOG_CONTRIBUTE       = 'contribute_log';
    const LOG_PLOT             = 'plot_log';
    const LOG_COLLECT          = 'collect_log';
    const LOG_CARD_COLLECT     = 'card_collect_log';
    const LOG_CONFESS          = 'confess_log';
    const LOG_SHARE            = 'share_log';
    const LOG_SIGN             = 'sign_log';
    const LOG_SCHEDULE         = 'schedule_log';
    const LOG_CLOCK            = 'clock_log';
    const LOG_ACTION           = 'action_log';
    const LOG_GUIDANCE         = 'novice_guidance_log';
    const LOG_BIND_PHONE       = 'bind_phone';
    const LOG_VALUE            = 'value_log';

    /**
     * 程序分片数量 按照数量分
     * */
    private static $mod_conf = [
        self::LOG_ACTION => 1,
        self::LOG_REGISTER      => 1,
        self::LOG_LOGIN         => 3,
        self::LOG_LOGOUT        => 3,
        self::LOG_ITEM          => 3,
        self::LOG_RECHARGE      => 1,
        self::LOG_CURRENCY      => 3,
        self::LOG_INTIMACY      => 3,
        self::LOG_HOT           => 3,
        self::LOG_CONTRIBUTE    => 3,
        self::LOG_PLOT          => 3,
        self::LOG_COLLECT       => 3,
        self::LOG_CARD_COLLECT  => 3,
        self::LOG_CONFESS       => 3,
        self::LOG_SHARE         => 3,
        self::LOG_SIGN          => 1,
        self::LOG_SCHEDULE      => 1,
        self::LOG_CLOCK         => 1,
        self::LOG_GUIDANCE      => 1,
        self::LOG_BIND_PHONE    => 1,
        self::LOG_VALUE         => 1,
    ];

    /**
     * 程序分片 按照日期分
     * */
    public static $division_by_date = [
        self::LOG_LOGIN         => true,
    ];
    /**
     * 数据库数据
     * */
    private static $database = [
        self::LOG_CARD_COLLECT => "CREATE TABLE IF NOT EXISTS `fa_card_collect_log` (
            `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
            `uid` varchar(12) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家ID',
            `user_name` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩爱姓名',
            `card_id` int(12) DEFAULT NULL COMMENT '卡牌ID',
            `is_new` tinyint(4) DEFAULT NULL COMMENT '是否是新卡',
            `consume_currency` int(11) DEFAULT NULL COMMENT '消耗的金币',
            `quality` int(11) DEFAULT NULL COMMENT '质量',
            `operate` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作类型',
            `path` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作路径',
            `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
            `channels` int(6) DEFAULT 0 COMMENT '渠道',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
        self::LOG_COLLECT => "CREATE TABLE IF NOT EXISTS `fa_collect_log` (
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
            `channels` int(6) DEFAULT 0 COMMENT '渠道',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
        self::LOG_CONFESS => "CREATE TABLE IF NOT EXISTS `fa_confess_log` (
            `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
            `uid` varchar(12) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家ID',
            `user_name` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家姓名',
            `consume_item_id` int(11) DEFAULT NULL COMMENT '消耗的道具ID',
            `count_heartbeat` int(11) DEFAULT NULL COMMENT '心动值变化',
            `new_num` int(11) DEFAULT NULL COMMENT '新的心动值',
            `role_name` int(45) DEFAULT NULL COMMENT '角色名',
            `new_rank` int(11) DEFAULT NULL COMMENT '新的排名',
            `operate` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作类型',
            `path` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作路径',
            `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
            `channels` int(6) DEFAULT 0 COMMENT '渠道',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
        self::LOG_CONTRIBUTE => "CREATE TABLE IF NOT EXISTS `fa_contribute_log` (
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
            `channels` int(6) DEFAULT 0 COMMENT '渠道',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
        self::LOG_CURRENCY => "CREATE TABLE IF NOT EXISTS `fa_currency_log` (
            `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
            `uid` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家ID',
            `user_name` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家姓名',
            `op_type` tinyint(4) DEFAULT NULL COMMENT '操作类型',
            `money_id` int(4) DEFAULT NULL COMMENT '钱币ID',
            `old_num` int(11) DEFAULT NULL COMMENT '旧的金币数量',
            `count_num` int(11) DEFAULT NULL COMMENT '操作的数量',
            `new_num` int(11) DEFAULT NULL COMMENT '新的金币数量',
            `reason` varchar(200) DEFAULT NULL COMMENT '二级原因',
            `reason_param` varchar(200) DEFAULT NULL COMMENT '二级原因参数',
            `operate` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作类型',
            `path` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作路径',
            `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
            `channels` int(6) DEFAULT 0 COMMENT '渠道',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
        self::LOG_HOT => "CREATE TABLE IF NOT EXISTS `fa_hot_log` (
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
            `channels` int(6) DEFAULT 0 COMMENT '渠道',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
        self::LOG_INTIMACY => "CREATE TABLE IF NOT EXISTS `fa_intimacy_log` (
            `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
            `uid` varchar(12) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家ID',
            `user_name` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家姓名',
            `role_id` tinyint(4) DEFAULT NULL COMMENT '角色ID',
            `role_name` varchar(20) DEFAULT NULL COMMENT '角色名字',
            `task_id` tinyint(4) DEFAULT NULL COMMENT '任务ID',
            `gift_id` int(11) DEFAULT NULL COMMENT '礼物ID',
            `intimacy_change` int(4) DEFAULT NULL COMMENT '亲密度变化值',
            `new_intimacy_level` int(4) DEFAULT NULL COMMENT '新的亲密度等级',
            `operate` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作类型',
            `path` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作路径',
            `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
            `channels` int(6) DEFAULT 0 COMMENT '渠道',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
        self::LOG_ITEM => "CREATE TABLE IF NOT EXISTS `fa_item_log` (
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
            `channels` int(6) DEFAULT 0 COMMENT '渠道',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
        self::LOG_LOGIN => "CREATE TABLE IF NOT EXISTS `fa_login_log` (
            `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
            `uid` varchar(11) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家ID',
            `user_name` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家姓名',
            `login_ip` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '登陆IP',
            `login_channel` varchar(12) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '登陆渠道',
            `hardware_type` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '登陆机型',
            `device_version` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '设备型号',
            `device_id` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '设备ID',
            `qiyidevid` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '爱奇艺设备ID',
            `login_duration` int(10) COLLATE utf8mb4_general_ci DEFAULT 0 COMMENT '登录时长',
            `login_type` int(4) COLLATE utf8mb4_general_ci DEFAULT 0 COMMENT 'SDK登陆类型',
            `operate` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作详情',
            `path` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作路径',
            `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
            `channels` int(6) DEFAULT 0 COMMENT '渠道',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
        self::LOG_LOGOUT => "CREATE TABLE IF NOT EXISTS `fa_logout_log` (
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
            `channels` int(6) DEFAULT 0 COMMENT '渠道',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
        self::LOG_PLOT => "CREATE TABLE IF NOT EXISTS `fa_plot_log` (
            `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
            `uid` varchar(12) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家ID',
            `user_name` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家姓名',
            `plot_id` int(11) DEFAULT NULL COMMENT '剧情ID',
            `paragraph_id` int(11) DEFAULT NULL COMMENT '段落ID',
            `operate` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作类型',
            `path` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作路径',
            `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
            `channels` int(6) DEFAULT 0 COMMENT '渠道',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
        self::LOG_RECHARGE => "CREATE TABLE IF NOT EXISTS `fa_recharge_log` (
            `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
            `uid` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '玩家ID',
            `user_name` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '玩家姓名',
            `goods_id` int(4) DEFAULT NULL COMMENT '商品ID',
            `order_id` varchar(35) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '订单ID',
            `charge_num` int(10) DEFAULT NULL COMMENT '充值货币个数',
            `new_currency` int(11) DEFAULT NULL COMMENT '新的货币个数',
            `give_count` int(10) DEFAULT NULL COMMENT '额外加成',
            `channel` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '渠道ID',
            `channel_id` int(4) DEFAULT NULL COMMENT '游戏渠道ID',
            `device_id` char(100) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '设备ID',
            `qiyidevid` char(100) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '爱奇艺设备ID',
            `amount` int(10) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '充值金额',
            `operate` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '操作名称',
            `path` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '操作路径',
            `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
            `channels` int(6) DEFAULT 0 COMMENT '渠道',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
        self::LOG_REGISTER => "CREATE TABLE IF NOT EXISTS `fa_register_log` (
            `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
            `uid` varchar(15) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家ID',
            `user_name` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家姓名',
            `register_ip` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '注册地址',
            `register_channel` tinyint(4) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '注册渠道',
            `os_version` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '移动终端系统版本',
            `device_brand` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '设备制造商',
            `device_version` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '设备型号',
            `device_id` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '设备ID',
            `qiyidevid` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '爱奇艺设备ID',
            `gold_pay` int(10) COLLATE utf8mb4_general_ci DEFAULT 0 COMMENT '充值钻石余量',
            `gold_bonus` int(10) COLLATE utf8mb4_general_ci DEFAULT 0 COMMENT '赠送钻石余量',
            `operate` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作名称',
            `path` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作路径',
            `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
            `channels` int(6) DEFAULT 0 COMMENT '渠道',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
        self::LOG_SHARE => "CREATE TABLE IF NOT EXISTS `fa_share_log` (
            `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
            `uid` varchar(12) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家ID',
            `user_name` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家姓名',
            `share_url` text COLLATE utf8mb4_general_ci COMMENT '分享链接',
            `share_method` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '分享方法',
            `extra` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '扩展(剧情id)',
            `operate` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '操作类型',
            `path` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '操作路径',
            `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
            `channels` int(6) DEFAULT 0 COMMENT '渠道',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
        self::LOG_SIGN => "CREATE TABLE IF NOT EXISTS `fa_sign_log` (
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
            `createtime` int(11) NOT NULL COMMENT '创建时间',
            `channels` int(6) DEFAULT 0 COMMENT '渠道',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
        self::LOG_SCHEDULE => "CREATE TABLE IF NOT EXISTS `fa_schedule_log` (
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
            `createtime` int(11) DEFAULT NULL COMMENT '创建日期',
            `channels` int(6) DEFAULT 0 COMMENT '渠道',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
        self::LOG_CLOCK => "CREATE TABLE IF NOT EXISTS `fa_clock_log`(
            `id` INT(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
            `uid` VARCHAR(12) COMMENT '玩家ID',
            `user_name` VARCHAR(45) COMMENT '玩家名',
            `clock_op` TINYINT(2) COMMENT '操作类型',
            `is_open` TINYINT(2) COMMENT '是否打开',
            `clock_date` VARCHAR(20) COMMENT '闹钟日期',
            `clock_repeat` VARCHAR(20) COMMENT '是否重复',
            `title` VARCHAR(45) COMMENT '闹钟名称',
            `alert_voice` VARCHAR(12) COMMENT '提示语音',
            `operate` VARCHAR(45) COMMENT '操作类型',
            `path` VARCHAR(100) COMMENT '操作路径',
            `createtime` INT(11) COMMENT '创建日期',
            `channels` int(6) DEFAULT 0 COMMENT '渠道',
            PRIMARY KEY (`id`)
        ) ENGINE=INNODB CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
        self::LOG_ACTION => "CREATE TABLE IF NOT EXISTS `fa_action_log` (
            `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
            `uid` varchar(12) DEFAULT NULL COMMENT '玩家ID',
            `user_name` varchar(45) DEFAULT NULL COMMENT '玩家姓名',
            `action_name` varchar(200) DEFAULT NULL COMMENT '玩家动作',
            `action_value` varchar(100) DEFAULT NULL COMMENT '动作的值',
            `action_code` int(6) DEFAULT NULL COMMENT '索引代码',
            `operate` varchar(45) DEFAULT NULL COMMENT '操作名称',
            `path` varchar(200) DEFAULT NULL COMMENT '操作路径',
            `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
            `channels` int(6) DEFAULT 0 COMMENT '渠道',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
        self::LOG_GUIDANCE => "CREATE TABLE IF NOT EXISTS `fa_novice_guidance_log` (
          `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
          `uid` varchar(12) DEFAULT NULL COMMENT '玩家ID',
          `user_name` varchar(45) DEFAULT NULL COMMENT '用户姓名',
          `operate` varchar(200) DEFAULT NULL COMMENT '操作类型',
          `path` varchar(100) DEFAULT NULL COMMENT '操作名称',
          `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
          `channels` int(4) DEFAULT NULL COMMENT '渠道',
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
        self::LOG_BIND_PHONE => "CREATE TABLE IF NOT EXISTS `fa_bind_phone` (
          `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
          `uid` varchar(12) DEFAULT NULL COMMENT '玩家ID',
          `user_name` varchar(45) DEFAULT NULL COMMENT '用户姓名',
          `operate` varchar(200) DEFAULT NULL COMMENT '操作类型',
          `type` int(4) DEFAULT NULL COMMENT '绑定入口',
          `path` varchar(100) DEFAULT NULL COMMENT '操作名称',
          `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
          `channels` int(4) DEFAULT NULL COMMENT '渠道',
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
        self::LOG_VALUE => "CREATE TABLE IF NOT EXISTS `fa_value_log`(  
          `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
          `uid` VARCHAR(12) COMMENT '玩家ID',
          `user_name` VARCHAR(45) COMMENT '玩家姓名',
          `value_description` VARCHAR(200) COMMENT '参数描述',
          `value` VARCHAR(500) COMMENT '参数',
          `operate` VARCHAR(45) COMMENT '操作类型',
          `path` VARCHAR(100) COMMENT '操作路径',
          `createtime` INT(11) COMMENT '创建时间',
          `channels` INT(6) DEFAULT 0 COMMENT '渠道',
          PRIMARY KEY (`id`)
        ) ENGINE=INNODB CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
    ];

    private static $table;
    public static $instance;
    private static $key;

    /**
     * 程序启动检查日志数据库是否初始化
     * @throws
     * */
    public static function initialDatabase(){

        foreach (self::$mod_conf as $table_name => $mod){

            if(isset(self::$division_by_date[$table_name]) && self::$division_by_date[$table_name]){
                $sql = str_replace($table_name, $table_name . date('Ym', time()), self::$database[$table_name]);
                MysqlConnect::connect(MysqlConnect::BACKEND)->query($sql);
            }else
                while($mod--){
                    $sql = str_replace($table_name, $table_name.($mod == 0 ? '' : $mod), self::$database[$table_name]);
                    MysqlConnect::connect(MysqlConnect::BACKEND)->query($sql);
                }
        }
    }

    /**
     * 数据库自销，清档时需要
     * @throws
     * */
    public static function dropDatabase(){
        $drop_sql = 'DROP TABLE IF EXISTS `@`;';
        foreach (self::$mod_conf as $table_name => $mod){
            while($mod--){
                $sql = str_replace('@' , 'fa_' . $table_name.($mod == 0 ? '' : $mod), $drop_sql);
                MysqlConnect::connect(MysqlConnect::BACKEND)->query($sql);
            }
        }
    }

    /**
     * 清空数据库，清档时需要
     * @throws
     * */
    public static function truncateDatabase()
    {
        $drop_sql = 'TRUNCATE `@`;';
        foreach (self::$mod_conf as $table_name => $mod) {
            if (isset(self::$division_by_date[$table_name]) && self::$division_by_date[$table_name]) {
                $sql = str_replace('@', 'fa_' . $table_name . date('Ym', time()), $drop_sql);
                dump_vars($sql);
                MysqlConnect::connect(MysqlConnect::BACKEND)->query($sql);
            } else
                while ($mod--) {
                    $sql = str_replace('@', 'fa_' . $table_name . ($mod == 0 ? '' : $mod), $drop_sql);
                    dump_vars($sql);
                    MysqlConnect::connect(MysqlConnect::BACKEND)->query($sql);
                }
            $mod = 0;
        }
    }

    /**
     * 清空数据库，清档时需要
     * @param string $column_name
     * @throws
     * */
    public static function multiAddColumn(string $column_name)
    {
        $alter_sql = "ALTER TABLE `LLLgame_Backend_CE`.`fa_@`
                      ADD COLUMN `{$column_name}` INT(6) DEFAULT 0   COMMENT '渠道' AFTER `createtime`;";
        //$alter_sql = "ALTER TABLE `LLLgame_Backend_CE`.`fa_@`
        //              DROP COLUMN `{$column_name}`;";
        foreach (self::$mod_conf as $table_name => $mod) {
            if (isset(self::$division_by_date[$table_name]) && self::$division_by_date[$table_name]) {
                $sql = str_replace('@', $table_name . date('Ym', time()), $alter_sql);
                dump_vars($sql);
                MysqlConnect::connect(MysqlConnect::BACKEND)->query($sql);
            } else
                while ($mod--) {
                    $sql = str_replace('@', $table_name . ($mod == 0 ? '' : $mod), $alter_sql);
                    dump_vars($sql);
                    MysqlConnect::connect(MysqlConnect::BACKEND)->query($sql);
                }
        }
    }

    /**
     * 分放数据
     * @param string $table
     * @return LogDistributeCenter
     * */
    public static function table(string $table){

        if(!self::exists($table))
            throw new \RuntimeException('table not allowed');

        self::$table = $table;
        if(!(self::$instance instanceof self))
            self::$instance = new self();

        return self::$instance;
    }

    /**
     * 检查是否存在
     * @param string $table
     * @return bool
     * */
    public static function exists(string $table){

        $check_table = preg_replace('|[0-9]+|', '', $table);

        if(!key_exists($check_table , self::$mod_conf))
            return false;

        return true;
    }

    /**
     * @param string $key
     * @return LogDistributeCenter
     */
    public static function key(string $key){

        self::$key = $key;
        return self::$instance;
    }

    /**
     * 获取表名
     * @return string 表名
     * */
    public static function get(){

        if(isset(self::$division_by_date[self::$table]) && self::$division_by_date[self::$table]){
            $date = date('Ym', time());
            $table_name = self::$table . $date;
        }else{
            $table_mod = CPlus::crc(self::$key, self::$mod_conf[self::$table]);
            $table_name = self::$table . ($table_mod == 0 ? '' : $table_mod);
        }
        return $table_name;
    }
}
