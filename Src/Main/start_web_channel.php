<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/8/12
 * Time: 17:26
 */

use Channel\Client;
use Workerman\Worker;

$task = new Worker();
$task->count = 1; //最多40个服务器 8核CPU
$task->name = 'Channel_Worker';

$task->onWorkerStart = function ($worker) {
    Client::connect('127.0.0.1', 2206);
    Client::on('web', function ($event_data) use ($worker){
        echo "Message Published！";
    });
};
