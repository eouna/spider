<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/4/3
 * Time: 17:51
 */

namespace Model\SQLModel;

use Connection\MysqlConnection\LogDataConfig\LogDistributeCenter;
use Connection\MysqlConnection\MysqlConfig\MysqlConfig;
use Connection\MysqlConnection\MysqlConfig\MysqlConnect;
use stdClass;
use Tools\CPlus;
use Workerman\MySQL\Connection;

abstract class SqlBaseModel
{
    /**
     * 数据库名
     * */
    protected $database;
    /**
     * 要操作的表名
     * */
    protected $table;
    /**
     * 分表的键
     * */
    protected $key;
    /**
     * 是否更新时间戳
     * */
    protected $timestamp = true;
    /**
     * 查询对象
     * @var Connection
     * */
    protected $query;

    /** @var */
    private $query_params;

    /**
     * 单例对象
     * @var SqlBaseModel
     * */
    public static $instance;

    /**
     * 更新的时间表名
     * */
    protected $created_at = "created_at";
    protected $updated_at = "updated_at";

    /**
     * SqlBaseModel constructor.
     * @param string $database
     */
    public function __construct($database = ''){

        $this->database = empty($database) ? MysqlConfig::$default_section : $database;
        !empty($this->table) ?: $this->table = MysqlConfig::confBySection($this->database)->prefix . CPlus::transModeName(get_class($this));

        if(LogDistributeCenter::exists($this->table))
            $this->table = LogDistributeCenter::table($this->table)->key($this->key)->get();
        $this->query_params = new stdClass();

        $this->query = MysqlConnect::connect($this->database);
    }

    /**
     * 获取单个变量
     * @param string $name
     * @return mixed
     * */
    protected function getOffset(string $name){

        return $this->query_params->{$name};
    }

    /**
     * 设置单个变量
     * @param string $name
     * @param mixed $value
     * @return mixed
     * */
    protected function setOffset(string $name, $value){

        return $this->query_params->{$name} = $value;
    }

    /**
     * 删除单个变量
     * @param string $name
     * */
    protected function unsetOffset(string $name){
        if(isset($this->query_params->{$name}))
            unset($this->query_params->{$name});
        else
            throw new \RuntimeException("The Key Not Found! ");
    }

    /**
     * 获取所有变量
     * @return array
     * */
    protected function all(){
        return (array)$this->query_params;
    }

    public function __isset($name)
    {
        // TODO: Implement __isset() method.
        return isset($this->query_params->{$name});
    }

    public function __set($name, $value)
    {
        // TODO: Implement __set() method.
        $this->query_params->{$name} = $value;
    }

    public function __get($name)
    {
        // TODO: Implement __get() method.
        return $this->query_params->{$name};
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
        $this->query_params = null;
        $this->query = null;
        dump_vars('释放MYSQL数据库实例！');
    }
}
