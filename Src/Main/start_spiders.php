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
//此进程用于逻辑处理 获取html中的节点
// task worker，使用Text协议
$task_worker = new Worker('Text://0.0.0.0:12345');
// task进程数可以根据需要多开一些
$task_worker->count = 5;
$task_worker->name = 'SYNCTaskWorker';
//只有php7才支持task->reusePort，可以让每个task进程均衡的接收任务
//$task->reusePort = true;
$task_worker->onMessage = function(TcpConnection $connection, $task_data)
{
    require_once __DIR__ . "/Common/functions.php";
    // 假设发来的是json数据
    $task_data = json_decode($task_data, true);
    switch ($task_data['action']){
        case 'dl_lk':
            try{
                $client = new Client();
                $response = $client->get($task_data['request_url']);
                $html_string = $response->getBody()->getContents();
                // 发送结果
                $connection->send(json_encode(['html_str' => transToUTF8($html_string)]));
            }catch (ClientException $exception){
                $client = new Client();
                $response = $client->get($task_data['request_url']);
                $html_string = $response->getBody()->getContents();
                // 发送结果
                $connection->send(json_encode(['html_str' => transToUTF8($html_string)]));
            }
            unset($task_data, $response);
            break;
        case 'dl_img':
            list($source_path, $local_path) = $task_data['msg'];
            FileHandler::save($source_path, $local_path);
            $connection->send(json_encode(['res' => 'finish']));
            break;
    }
};
// 如果不是在根目录启动，则运行runAll方法
if (!defined('GLOBAL_START')) {
    Worker::runAll();
}
