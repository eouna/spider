<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/4/23
 * Time: 21:53
 */

namespace TaskCron;

use Config\Enum\CSocketType;
use Logic\ChannelUtils\SocketManager;
use Logic\ChannelUtils\SocketPackage\SocketDataPackage;
use Logic\Service\Link\LinkPackage;
use Model\BaseModel;
use Model\RedisModel\LinkQueueModel;

class LoadLinkTask extends TaskInterface
{
    private static $timeRecorder;
    /**
     * 运行极光推送任务
     * @param mixed $param
     * @throws
     * @return mixed
     * */
    public static function run($param)
    {
        if($param instanceof LinkPackage && !(new LinkQueueModel())->setType(BaseModel::LINK_LIST)->isEmptyQueue()){

            $nowTime = time();
            if(empty(self::$timeRecorder)) self::$timeRecorder = $nowTime;

            if($nowTime - self::$timeRecorder >= $param->interval){
                echo "\r\n";
                dump_vars('开始处理异步处理队列中的链接和资源的请求。。。。');
                $sMemory = number_format((float)(memory_get_usage() / 1048576), 2);
                dump_vars("当前进程ID：\033[36m". posix_getpid() ." \033[m\033[255;255;255m占用内存：" . $sMemory . 'Mb');
                $socketObj = new SocketDataPackage();
                $socketObj->addr = CSocketType::S_LINK_LOADER_ADDR;
                $socketObj->msgData = json_encode(['action' => 'doCollect', 'type' => $param->type, 'filter' => $param->filter]);
                SocketManager::make()->setSocketInfo($socketObj)->toMsg();
                self::$timeRecorder = $nowTime;
            }
        }
    }
}
