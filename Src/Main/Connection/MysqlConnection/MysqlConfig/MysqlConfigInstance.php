<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/4/2
 * Time: 10:13
 */

namespace Connection\MysqlConnection\MysqlConfig;


class MysqlConfigInstance
{
    /**
     * 连接数据库类型
     * */
    public $type;
    /**
     * 连接地址
     * */
    public $host;
    /**
     * 连接端口
     * */
    public $port;
    /**
     * 连接的数据库
     * */
    public $database;
    /**
     * 连接账号
     * */
    public $username;
    /**
     * 连接的密码
     * */
    public $password;
    /**
     * 数据库前缀
     */
    public $prefix;

    public function __construct($args){
        $this->type = $args[0];
        $this->host = $args[1];
        $this->port = $args[2];
        $this->database = $args[3];
        $this->username = $args[4];
        $this->password = $args[5];
        $this->prefix = $args[6];
        return $this;
    }
}
