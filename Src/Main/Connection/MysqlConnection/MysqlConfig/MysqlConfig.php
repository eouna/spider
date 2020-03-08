<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/4/2
 * Time: 10:09
 */

namespace Connection\MysqlConnection\MysqlConfig;

class MysqlConfig
{
    private static $config;                 //配置表数据
    private static $config_instance;        //配置表实例
    public static $default_section;         //默认连接

    /**
     * 初始化配置表
     * */
    public static function loadConfig(){

        self::$config = parse_ini_file(__DIR__ . "/../../../Config/Mysql.conf", true);

        foreach (self::$config as $section => $config){
            self::$config_instance[$section] = new MysqlConfigInstance(array_values($config));
        }
        self::$default_section = key(self::$config_instance);
    }

    /**
     * 获取配置表数据
     * @param string $section 配置组名
     * @return MysqlConfigInstance
     * */
    public static function confBySection($section){

        if(!isset(self::$config_instance[$section]))
            self::loadConfig();
        return self::$config_instance[$section];
    }

    /**
     * 获取连接的section
     * @return mixed
     */
    public static function getSections(){

        return array_keys(self::$config_instance);
    }

    /**
     * 获取原始数据配置
     * @return mixed
     * */
    public static function getConfig(){

        return self::$config;
    }
}
