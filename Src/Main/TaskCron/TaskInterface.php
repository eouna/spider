<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/3/8
 * Time: 18:22
 */

namespace TaskCron;


use GatewayWorker\Lib\Gateway;
use Logic\Model\PlayerModel;
use Logic\PublicApi;

abstract class TaskInterface
{

    protected static $task_rule;

    /**
     * 获取定时时间规则
     * @return mixed
     */
    public static function getTaskRule()
    {
        throw new \RuntimeException("not overload this method");
    }

    /**
     * 运行任务
     * @param mixed $param
     * */
    abstract public static function run($param);

}
