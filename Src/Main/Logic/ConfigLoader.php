<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/9/9
 * Time: 11:15
 */
namespace Logic;
use Connection\MysqlConnection\MysqlConfig\MysqlConfig;
use Connection\MysqlConnection\MysqlConfig\MysqlConnect;
use Model\SQLModel\ResourceCategory\ResourceCategory;

class ConfigLoader
{
    const BLOG_SITE_GROUP = "blog_site_list";
    const IMG_SITE_GROUP = "img_site_list";
    const INFORMATION_SITE_GROUP = "information_site_list";

    /**
     * 配置表
     * */
    private static $config_map = [];

    public static function init(){
        self::$config_map = parse_ini_file(__DIR__ . "/../Config/Site.conf", true);
        MysqlConfig::loadConfig();
        MysqlConnect::init();
    }

    /**
     * 获取配置文件
     * @param  $section_name
     * @return array
     * */
    public static function configBySectionName($section_name){
        return isset(self::$config_map[$section_name]) ? self::$config_map[$section_name] : [];
    }
}
