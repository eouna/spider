<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2020/3/3
 * Time: 14:33
 */

namespace Logic\ChannelUtils;

use Logic\ChannelUtils\SocketPackage\PackageData;
use Logic\ChannelUtils\SocketPackage\SocketDataPackage;

abstract class ChannelHandler implements NHandler
{

    private $msgData;

    /**
     * @var SocketDataPackage $socketAddr
     */
    protected $socketDataPackage;

    /**
     * @var self $instance
     */
    public static $instance;

    /**
     * @param PackageData $socketAddr
     * @return self
     */
    abstract public function setSocketInfo(PackageData $socketAddr);


    /**
     * @param string $msgData
     * @return self
     */
    abstract public function setMsgData(string $msgData): self;
}
