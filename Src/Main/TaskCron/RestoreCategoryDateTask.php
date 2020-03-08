<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2020/3/7
 * Time: 20:39
 */

namespace TaskCron;


use Model\SQLModel\ResourceCategory\ResourceCategory;

class RestoreCategoryDateTask extends TaskInterface
{

    private static $timeRecorder;

    private static $interval = 10;

    /**
     * 运行任务
     * @param mixed $param
     * */
    public static function run($param)
    {
        $nowTime = time();
        if(empty(self::$timeRecorder)) self::$timeRecorder = $nowTime;
        if($nowTime - self::$timeRecorder >= self::$interval){
            ResourceCategory::saveTagsMap();
            self::$timeRecorder = $nowTime;
        }
    }
}
