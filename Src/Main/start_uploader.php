<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/8/12
 * Time: 17:26
 */

use Logic\ConfigLoader;
use Workerman\Worker;

$task = new Worker("Text://127.0.0.1:12217");
$task->count = 5; //最多40个服务器 8核CPU
$task->name = 'Uploader Resource Worker';
$task->onConnect = function ($worker){
    require_once __DIR__ . "/Common/functions.php";
    var_dump($worker);
};
$task->onMessage = function ($worker) {
    require_once __DIR__ . "/Common/functions.php";
    ConfigLoader::init();
    var_dump($worker);
};
// 如果不是在根目录启动，则运行runAll方法
if (!defined('GLOBAL_START')) {
    Worker::runAll();
}
