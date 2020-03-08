<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/8/12
 * Time: 17:26
 */

use Logic\ConfigLoader;
use Logic\Service\Link\DealLinkProvider;
use Logic\SpiderFetch;
use Model\BaseModel;
use Model\RedisModel\LinkQueueModel;
use Model\SQLModel\ResourceCategory\ResourceCategory;
use Src\ApplicationRegister;
use Workerman\Connection\TcpConnection;
use Workerman\Worker;

$task = new Worker('Text://0.0.0.0:12216');
$task->count = 50; //最多40个服务器 8核CPU
$task->name = 'Link Loader';
$task->reusePort = true;

$task->onConnect = function (TcpConnection $tcpConnection) {};

/**
 * 启动时加载配置
 */
$task->onWorkerStart = function () {
    require_once __DIR__ . "/Common/functions.php";
    ConfigLoader::init();
    $app = new ApplicationRegister();
    $app->runFacade();
};

/**
 * @param TcpConnection $connection
 * @param string $message
 * @return string
 */
$task->onMessage = function (TcpConnection $connection, string $message) {
    dump_vars('处理WORKER ID为: ' . $connection->id . ' 的任务');
    $sMemory = number_format((float)(memory_get_usage() / 1048576), 2);
    dump_vars("当前进程ID：\033[36m". posix_getpid() ." \033[m\033[255;255;255m占用内存：" . $sMemory . 'Mb');
    $jsonData = (array)json_decode($message);
    $recMsg = "ERROR";
    if (!empty($jsonData) && isset($jsonData['action'])) {
        switch ($jsonData['action']) {
            case 'doCollect':
                $jsonData['url'] = (new LinkQueueModel())->setType(BaseModel::LINK_LIST)->popLink();
                $sMemory = number_format((float)(memory_get_usage() / 1048576), 2);
                dump_vars("当前进程ID：\033[36m". posix_getpid() ." \033[m\033[255;255;255m占用内存：" . $sMemory . 'Mb');
                DealLinkProvider::doCollectTrigger($jsonData);
                $sMemory = number_format((float)(memory_get_usage() / 1048576), 2);
                dump_vars("当前进程ID：\033[36m". posix_getpid() ." \033[m\033[255;255;255m占用内存：" . $sMemory . 'Mb');
                $recMsg = 'SUCCESS!';
                break;
        }
    }
    $connection->send($recMsg);
};

// 如果不是在根目录启动，则运行runAll方法
if (!defined('GLOBAL_START')) {
    Worker::runAll();
}
