<?php

use Logic\ConfigLoader;
use Logic\Service\Dump\ResInfoDumper;
use Model\SQLModel\ResourceCategory\ResourceCategory;
use Workerman\Worker;
use Workerman\Lib\Timer;

$task = new Worker();
$task->count = 1; //触发器 用于循环处理消息队列中的消息 也可以控制爬虫速度 以及监控当前系统资源情况
$task->name = 'Crawl Trigger';

$task->onWorkerStart = function (Worker $task) {
    require_once __DIR__ . "/Common/functions.php";
    ConfigLoader::init();
    ResourceCategory::initTagsMap();
    ResInfoDumper::make()->setFrameTitle("触发器开始启动")->withOutPutData([
        "时间" => date("Y-m-d H:i:s"),
        "当前内存" => number_format((float)(memory_get_usage() / 1048576), 2) . "M",
        "当前进程ID" => posix_getpid(),
        "当前workerID" => $task->id,
    ])->setFrameRate(70)->cliPrint()->pushToClient();
    Timer::add(1, 'timingTask', array(0), true);
};

/**
 *  定时任务为串行任务，多服想同一时间执行需要分开进程维护
 * 任务时间间隔建议10分钟以上  加任务不要加23:50分以后  不然维护进程维护不到
 * @param int $sleep
 * @throws
 */
function timingTask($sleep){
    TaskCron\TaskFactory::dispatchTask();
}

// 如果不是在根目录启动，则运行runAll方法
if (!defined('GLOBAL_START')) {
    Worker::runAll();
}
