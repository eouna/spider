<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/7/30
 * Time: 11:37
 */

namespace Logic;

use RuntimeException;
use Tools\CPlus;

abstract class ApiBase
{

    protected static $route_register_list = [
        #---小蜘蛛
        SpiderFetch::class => [
            /** @see SpiderFetch @method string dealClientMsg()*/
            10001 => 'dealClientMsg',    #处理客户端消息
        ],
    ];

    /**
     * 路由
     * @throws
     * @param array $message
     * @param string $client_id
     * @return mixed
     * */
    public static function init(array $message, string $client_id){

        date_default_timezone_set('PRC');

        if(empty($message['cmd']))
            throw new RuntimeException('CMD Not Set');

        if(!isset(self::$route_register_list[static::class]))
            throw new RuntimeException('Illegal Request, Class Name Not Found!');

        if(!isset(self::$route_register_list[static::class][$message['cmd']])){
            return CPlus::error(65000, "不在路由表中。。。");
        }

        $method = self::$route_register_list[static::class][$message['cmd']];
        $message['client_id'] = $client_id;

        return (static::class)::$method($message);
    }

    /**
     * 处理未找到的静态方法
     * @param string $name
     * @param string $arguments
     * */
    public static function __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.
    }
}