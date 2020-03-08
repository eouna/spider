<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/3/2
 * Time: 18:40
 */

namespace Src;

use Src\Facade\Facades;

class ApplicationRegister
{
    /**
     * 容器别名
     * @var array $container_alias
     * */
    protected $container_alias;

    /**
     * 绑定控制
     * */
    protected $access_bind = [
        "paginate" => "Src\Provider\PageManageProvider"
    ];

    /**
     * 当前注册的容器
     * */
    protected $register_facade;

    /**
     * 别名列表
     * */
    protected $alias_list = [];

    /**
     * 初始化为微框
     * */
    public function __construct()
    {
        // TODO 初始化数据
        $this->runFacade();
    }

    public function runFacade(){

        $this->registerFacade();
    }

    /**
     * 清理所有容器数据和绑定数据
     * */
    protected function flushAll(){

        $this->container_alias = [];
    }

    /**
     * 开始注册容器
     * */
    protected function registerFacade(){

        if(!isset($this->container_alias)){
            self::registerContainerAlias();
        }

        Facades::setFacade($this);
    }

    /**
     * 创建容器实例
     * @param string $facade_name
     * @return ApplicationRegister
     * */
    public function make(string $facade_name){

        if(!class_exists($facade_name))
            throw new \RuntimeException('Class NOT EXISTS！');

        $alias = basename(str_replace('\\', '/', $facade_name));
        array_push($this->container_alias, [$alias => $facade_name]);
        $this->register_facade = $facade_name;

        return $this;
    }

    /**
     * 给容器创建别名
     * @param string $alias_name
     * @return ApplicationRegister
     * */
    public function alias(string $alias_name){

        if(empty($this->register_facade))
            throw new \RuntimeException('Not Found Register Name! ');

        !isset($this->container_alias[$this->register_facade]) ?: $this->container_alias[$this->register_facade] = $alias_name;

        return $this;
    }

    /**
     * 注册容器的别名
     * */
    protected function registerContainerAlias(){
        $this->container_alias = [
            'paginate' => 'Src\Provider\PageManageProvider',
        ];
    }
}
