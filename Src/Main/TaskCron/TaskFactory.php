<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/3/8
 * Time: 18:11
 */

namespace TaskCron;

use Logic\Service\Link\DealLinkProvider;

class TaskFactory
{

    const T_LOAD_LINK = '获取链接';
    const T_RESTORE_CATEGORY_DATE = '回存分类数据';

    private static $DEF = [
        self::T_LOAD_LINK => LoadLinkTask::class,
        self::T_RESTORE_CATEGORY_DATE => RestoreCategoryDateTask::class,
    ];

    private static $taskExecInterval = [
        self::T_RESTORE_CATEGORY_DATE => 10,
    ];

    private static $taskEventParams = [];

    private static $taskExecTimeRec = [];

    /**
     * 运行指定的任务
     * @param string $taskName
     * @param string $param
     * */
    public static function taskRun(string $taskName, $param = ''){
        $method = "run";
        !isset(self::$DEF[$taskName]) ?: self::$DEF[$taskName]::$method($param);
    }

    /**
     * 分发任务每分钟执行一次
     * */
    public static function dispatchTask(){

        self::taskRun(self::T_LOAD_LINK, DealLinkProvider::formatLinkPackage());
        self::taskRun(self::T_RESTORE_CATEGORY_DATE);
        /*$microTime = microtime(true);
        foreach (self::$DEF as $alias => $classInstance){
            if(isset(self::$taskExecTimeRec[$alias]) && isset(self::$taskExecInterval[$alias])){
                if(($microTime - self::$taskExecTimeRec[$alias]) > self::$taskExecInterval[$alias]){
                    self::taskRun(self::T_RESTORE_CATEGORY_DATE);
                    self::$taskExecTimeRec[$alias] = $microTime;
                }
            }else{
                self::$taskExecTimeRec[$alias] = $microTime;
            }
        }*/
    }

    /**
     * @param string $taskName
     * @param $classInstance
     * @param int $interval
     * @param mixed ...$params
     */
    public static function addTimerEvent(string $taskName, $classInstance, int $interval, ...$params){
        if(isset(self::$DEF)) return;
        self::$DEF[$taskName] = $classInstance;
        self::$taskExecInterval[$taskName] = $interval;
        self::$taskEventParams[$taskName] = $params;
        self::$taskExecTimeRec = microtime(true);
    }
}
