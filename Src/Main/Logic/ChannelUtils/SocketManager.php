<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2020/3/3
 * Time: 14:15
 */

namespace Logic\ChannelUtils;


use Config\Enum\CSocketType;
use Exception;
use Logic\ChannelUtils\SocketPackage\PackageData;
use Logic\ChannelUtils\SocketPackage\SocketDataPackage;
use Workerman\Connection\AsyncTcpConnection;

class SocketManager extends ChannelHandler
{
    /**
     * @var SocketDataPackage $socketAddr
     */
    protected $socketDataPackage;

    private $msgData;

    /**
     * @var self $instance
     */
    public static $instance;

    /**
     * @return mixed
     */
    public function getSocketAddr()
    {
        return $this->socketDataPackage;
    }

    /**
     * @return self
     */
    public static function make()
    {
        // TODO: Implement make() method.
        if(!(self::$instance instanceof self))
            self::$instance = new self();
        return self::$instance;
    }


    /**
     * @return void
     * @throws Exception
     * @return string
     */
    public function toMsg()
    {
        // TODO: Implement toMsg() method.
        $syncTask = new AsyncTcpConnection(CSocketType::enum($this->socketDataPackage->addr)->getValue());
        $syncTask->onConnect = function(AsyncTcpConnection $connection) {
            dump_vars('连接的 WORKER ID为：' . $connection->id);
        };
        $syncTask->onError = function(AsyncTcpConnection $task_connection, $err_code, $err_msg) {
            echo "$err_code, $err_msg";
        };
        $syncTask->onMessage = function (AsyncTcpConnection $task_connection, $task_result) use (&$response){
            $response = $task_result;
            dump_vars('关闭异步连接 ASYNC_MSG: ' . $response);
            $task_connection->close();
            $task_connection->destroy();
        };
        $syncTask->send($this->socketDataPackage->msgData);
        $syncTask->connect();
        return $response;
    }

    /**
     * @param string $msgData
     * @return self
     */
    public function setMsgData(string $msgData): ChannelHandler
    {
        // TODO: Implement setMsgData() method.
        $this->msgData = $msgData;
        return self::$instance;
    }

    /**
     * @param PackageData $socketDataPackage
     * @return self
     */
    public function setSocketInfo(PackageData $socketDataPackage): ChannelHandler
    {
        $this->socketDataPackage = $socketDataPackage;
        $this->msgData = $this->socketDataPackage->msgData;
        return self::$instance;
    }

}
