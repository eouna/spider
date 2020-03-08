<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/4/2
 * Time: 10:40
 */

namespace Connection\MysqlConnection\MysqlConfig;

use Workerman\MySQL\Connection;

class MysqlConnect
{
    const SPIDER = "MYSQL_SPIDER";

    /**
     * 连接池
     * */
    private static $db_pool;

    /**
     * 单个数据库连接对象上限
     * */
    public static $connected_max = 10;

    /**
     * workerman 数据库连接实例
     * @var Connection $db;
     * */
    protected static $db;

    /**
     * 初始化mysql连接
     * */
    public static function init(){

        $sql_conf = MysqlConfig::getSections();

        foreach ($sql_conf as $section){

            $sql_conf_instance = MysqlConfig::confBySection($section);
            self::$db_pool[$section] = new Connection(
                $sql_conf_instance->host, $sql_conf_instance->port, $sql_conf_instance->username,
                $sql_conf_instance->password, $sql_conf_instance->database
            );
        }
    }

    /**
     * 获取某个连接实例
     * @param string $section
     * @return Connection
     * */
    public static function connect(string $section = ''){

        !empty($section) ?: $section = MysqlConfig::$default_section;
        if(!isset(self::$db_pool[$section])) self::init();
        if(!(self::$db_pool[$section] instanceof Connection) || !self::PDOPing(self::$db_pool[$section])){
            $sql_conf_instance = MysqlConfig::confBySection($section);
            self::closeConnect($section);
            self::$db_pool[$section] = new Connection(
                $sql_conf_instance->host, $sql_conf_instance->port, $sql_conf_instance->username,
                $sql_conf_instance->password, $sql_conf_instance->database
            );
        }
        return self::$db = self::$db_pool[$section];
    }

    /**
     * 检查连接是否断开
     * @param Connection $db
     * @return bool
     * */
    protected static function PDOPing(Connection $db){

        try{
            $db->getPDOAttribute(\PDO::ATTR_SERVER_INFO);
        }catch (\PDOException $exception){
            $exception_code = $exception->getCode();
            if(!$exception_code || $exception_code == "HY000" || $exception_code == 2006 || $exception_code == 2013)
                return false;
        }catch (\Exception $exception){
            $exception_code = $exception->getCode();
            if(!$exception_code || $exception_code == "HY000" || $exception_code == 2006 || $exception_code == 2013)
                return false;
        }
        return true;
    }

    /**
     * 断开连接
     * @param string $section
     * */
    private static function closeConnect(string $section){

        unset(self::$db_pool[$section]);
    }
}
