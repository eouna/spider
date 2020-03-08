<?php


namespace Logic\ChannelUtils;


use Channel\Client;
use Logic\ChannelUtils\SocketPackage\ChannelPackageData;
use Logic\ChannelUtils\SocketPackage\PackageData;

class ChannelManager extends ChannelHandler
{

    /**
     * @var ChannelPackageData $socketDataPackage
     */
    protected $socketDataPackage;

    private $mesData;

    public function __construct(PackageData $channelSocketPackageData)
    {
        try {
            $this->setSocketInfo($channelSocketPackageData);
            Client::connect($this->socketDataPackage->addr, $this->socketDataPackage->port);
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getTraceAsString());
        }
    }

    /**
     * @param PackageData $channelSocketPackageData
     * @return self
     */
    public function setSocketInfo(PackageData $channelSocketPackageData)
    {
        // TODO: Implement setSocketAddr() method.
        $this->socketDataPackage = $channelSocketPackageData;
        $this->mesData = $this->socketDataPackage->msgData;
        return $this;
    }

    /**
     * @param string $msgData
     * @return self
     */
    public function setMsgData(string $msgData): ChannelHandler
    {
        // TODO: Implement getMsgData() method.
        $this->mesData = $msgData;
        return self::$instance;
    }

    /**
     * @param PackageData|null $channelSocketPackageData
     * @return self
     */
    public static function make(PackageData $channelSocketPackageData = null)
    {
        // TODO: Implement make() method.
        if(!(self::$instance instanceof self))
            self::$instance = new self($channelSocketPackageData);
        return self::$instance;
    }

    /**
     * @return mixed
     */
    public function toMsg()
    {
        // TODO: Implement toMsg() method.
        try {
            if(($this->socketDataPackage instanceof ChannelPackageData) && $this->socketDataPackage->channelBindEventName){
                Client::publish($this->socketDataPackage->channelBindEventName, $this->socketDataPackage->msgData);
            }
        } catch (\Exception $e) {
            throw new \RuntimeException('信道：' . $this->socketDataPackage->channelBindEventName . '已关闭！');
        }
    }

    /**
     * 发布消息
     */
    public function msgToChannel(){
        $this->toMsg();
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
}
