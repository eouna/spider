<?php
use \Workerman\Worker;
use \Workerman\WebServer;
use \GatewayWorker\Gateway;
use \GatewayWorker\BusinessWorker;
use \Workerman\Autoloader;

require_once __DIR__ . '/../../vendor/autoload.php';

/*$context = [
    'ssl' => [
        'local_cert'  => '/gameserver/Applications/pp/Config/dsgame.iqiyi.com.pem', // 也可以是crt文件
        'local_pk'    => '/gameserver/Applications/pp/Config/dsgame.iqiyi.com.key',
        'verify_peer' => false,
    ]
];
// WebServer
$web = new WebServer("https://[::]:443" ,$context);
// WebServer进程数量
$web->count = 2;
$web->transport = 'ssl';*/
$web = new WebServer("http://[::]:81");
// WebServer进程数量
$web->count = 2;
// 设置站点根目录
$web->addRoot('spider.eouna.com', __DIR__.'/Web/my-app/build/');

// 如果不是在根目录启动，则运行runAll方法
if(!defined('GLOBAL_START'))
{
    Worker::runAll();
}
