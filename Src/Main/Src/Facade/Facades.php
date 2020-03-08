<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/3/2
 * Time: 18:39
 */

namespace Src\Facade;


use Src\ApplicationRegister;

abstract class Facades
{
    /**
     * 容器列表
     * @var array $facade
     * */
    protected static $facade;

    /**
     * 容器解析列表
     * @var array $resolveInstance
     * */
    protected static $resolveInstance;

    /**
     * 获取容器的别名
     * @return string
     * @throws \RuntimeException
     * */
    abstract protected static function getFacadeAccessor();

    /**
     * 设置容器
     * @param ApplicationRegister $facade 注册程序实例
     * */
    public static function setFacade(ApplicationRegister $facade){
        self::$facade = $facade;
    }

    /**
     * 获取容器路径
     * */
    protected static function getAccessorRoot(){
        return self::resolveFaced(self::getFacadeAccessor());
    }

    /**
     * 获取容器实例
     * @param mixed $name 实例名称
     * @return mixed
     * */
    protected static function resolveFaced($name){
        if(is_object($name)){
            return $name;
        }
        if(isset(static::$resolveInstance[$name])){
            return static::$resolveInstance[$name];
        }
        return static::$resolveInstance[$name] = static::$facade[$name];
    }

    /**
     * 清理指定的容器
     * @param string $name 容器名
     * */
    public static function clearResolvedInstance($name){
        unset(static::$resolveInstance[$name]);
    }

    /**
     * 清理所有解析容器列表
     *
     * @return void
     */
    public static function clearResolvedInstances()
    {
        static::$resolveInstance = [];
    }

    /**
     * 魔术方法获取成员静态方法
     * @param string $method 成员方法
     * @param mixed $arguments 成员函数参数
     * @throws \RuntimeException
     * @return mixed
     * */
    public static function __callStatic(string $method, $arguments)
    {
        // TODO: Implement __callStatic() method.
        $facade_instance = static::getAccessorRoot();

        if(empty($facade_instance)){
            throw new \RuntimeException("此容器未注册");
        }

        return $facade_instance->{$method}(...$arguments);
    }
}
