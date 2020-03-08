<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);
use Channel\Client;
use Config\Enum\CChannelEventNameType;
use Logic\SpiderFetch;
use \GatewayWorker\Lib\Gateway;
use Tools\CPlus;
/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events {

    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     *
     * @param int $client_id 连接id
     * @throws Exception
     */
    public static function onConnect($client_id) {
        echo "Client Connected....." . $client_id . "\n";
        Client::connect(CChannelEventNameType::CLI_DUMPER_CHANNEL_ADDR, CChannelEventNameType::CLI_DUMPER_CHANNEL_PORT);
        Client::on(CChannelEventNameType::CLI_DUMPER_CHANNEL, function ($channelData) use ($client_id){
            Gateway::sendToClient($client_id, CPlus::success(10001, $channelData));
        });
    }

    /**
     * 当客户端发来消息时触发
     * @throws
     * @param int $client_id 连接id
     * @param mixed $message 具体消息
     * @return string
     */
    public static function onMessage($client_id, $message) {
        $message = (array)json_decode($message);
        $response = '';
        if(isset($message['cmd']) && $message['cmd'] != 999 && isset($message['group'])){
            switch ($message['group']){
                case "c_b_ex":
                    $response = SpiderFetch::init($message, $client_id);
                    break;
            }
            if(!empty($response))
                return Gateway::sendToCurrentClient($response);
            return CPlus::error(-1, "服务器数据处理出错。。。");
        }
        return null;
    }

    /**
     * 当用户断开连接时触发
     * @param int $client_id 连接id
     */
    public static function onClose($client_id) {
    }

}
