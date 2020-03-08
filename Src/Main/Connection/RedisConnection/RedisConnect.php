<?php
/**
 * Created by PhpStorm.
 * User: jhj
 * Date: 2015/12/10
 * Time: 9:22
 */

namespace Connection\RedisConnection;
use Redis;
class RedisConnect
{
    /**
     * Redis的ip
     * @var string
     */
    const REDISHOSTNAME = "127.0.0.1";
     /* Redis的port
    //     * @var int
     */
    const REDISPORT = 6379;

    /**
     * Redis的超时时间
     * @var int
     */
    const REDISTIMEOUT = 0;

    /**
     * Redis的password
     * @var string
     */
    const REDISPASSWORD = "";

    /**
     * Redis的DBname
     * @var int
     */
    const REDISDBNAME = 1;

    /**
     * 类单例
     * @var object
     */
    private static $instance;

    /**
     * Redis的连接句柄
     * @var object
     */
    private $redis;

    /**
     * 私有化构造函数，防止类外实例化
     * @param int $db_idx
     */
    private function __construct(int $db_idx = 0)
    {
        // 单例数据库
        $this->redis = new \Redis();
        $this->redis->pconnect(self::REDISHOSTNAME, self::REDISPORT, self::REDISTIMEOUT);
        $this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
        //$this->redis->auth(self::REDISPASSWORD);//正式上线需打开
        /*集群*/
//        $this->redis = new \RedisCluster(NULL, self::REDISHOSTNAME);
//        $this->redis->setOption(\RedisCluster::OPT_SERIALIZER, \RedisCluster::SERIALIZER_PHP);
        //     $this->redis->auth('mkzq@2016Cluster#.fuck');
        //  $this->redis->setOption(\RedisCluster::OPT_SERIALIZER, \RedisCluster::SERIALIZER_NONE);

    }

    /**
     * 私有化克隆函数，防止类外克隆对象
     */
    private function __clone()
    {
    }

    /**
     * 类的唯一公开静态方法，获取类单例的唯一入口
     * @return Redis
     */
    public static function init()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance->redis;
    }


    /**
     * 获取redis的连接实例
     * @return \Redis
     */
    public function getRedisConn()
    {
        return $this->redis;
    }


    /**
     * 需要在单例切换的时候做清理工作
     */
    public function __destruct()
    {
        self::$instance->redis->close();
        self::$instance = NULL;
    }
}
