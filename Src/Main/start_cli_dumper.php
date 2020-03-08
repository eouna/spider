<?php
//
//use Channel\Client;
//use Config\Enum\CChannelEventNameType;
//use Logic\ConfigLoader;
//use Model\SQLModel\ResourceCategory\ResourceCategory;
//use Workerman\Worker;
//use Workerman\Lib\Timer;
//
//$task = new Worker();
//$task->count = 1; //触发器 用于循环处理消息队列中的消息 也可以控制爬虫速度 以及监控当前系统资源情况
//$task->name = 'Cli Dumper';
//static $outputMessageCache = [];
//
//
//$task->onWorkerStart = function ($task) use (&$outputMessageCache){
//    require_once __DIR__ . "/Common/functions.php";
//    ConfigLoader::init();
//    Client::connect(CChannelEventNameType::CLI_DUMPER_CHANNEL_ADDR, CChannelEventNameType::CLI_DUMPER_CHANNEL_PORT);;
//    Client::on(CChannelEventNameType::CLI_DUMPER_CHANNEL, function ($channelData) use (&$outputMessageCache){
//        var_dump("Channel Message!");/*
//        array_map(function ($message){var_dump($message);}, $channelData);*/
//        array_push($outputMessageCache, $channelData);
//        var_dump($outputMessageCache);
//    });
//    //Timer::add(0.001, 'cliDumperTask', array(0), true);
//};
///**
// *  定时任务为串行任务，多服想同一时间执行需要分开进程维护
// * 任务时间间隔建议10分钟以上  加任务不要加23:50分以后  不然维护进程维护不到
// * @param int $sleep
// * @throws
// */
//function cliDumperTask($sleep){
//    global $outputMessageCache;
//    if(!empty($outputMessageCache)){
//        $fistMessage = array_shift($outputMessageCache);
//        !is_array($fistMessage) ? : array_map(function ($message){echo "<^::^>";var_dump($message);}, $fistMessage);
//        var_dump("Deal Channel Queue Message!");
//    }
//}
//// 如果不是在根目录启动，则运行runAll方法
//if (!defined('GLOBAL_START')) {
//    Worker::runAll();
//}
