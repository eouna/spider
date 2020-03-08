<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/9/7
 * Time: 20:12
 */

use GuzzleHttp\Client;
use Workerman\Worker;
use GuzzleHttp\Exception\ClientException;
use Workerman\Connection\TcpConnection;
use Logic\FileHandler;

// task worker，使用Text协议
$task_worker = new Worker('Text://127.0.0.1:12215');
// task进程数可以根据需要多开一些
$task_worker->count = 40;
$task_worker->name = 'SYNCFileDownLoaderWorker';
//只有php7才支持task->reusePort，可以让每个task进程均衡的接收任务
//$task->reusePort = true;

$task_worker->onMessage = function(TcpConnection $connection, $task_data)
{
    require_once __DIR__ . "/Common/functions.php";
    echo "进程ID：" . $connection->id;
};
if(!defined('GLOBAL_START'))
{
    Worker::runAll();
}
