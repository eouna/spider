<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2020/3/4
 * Time: 21:16
 */

namespace Model\RedisModel;


use Model\BaseModel;
use Model\Link\LinkInfo;
use Tools\CPlus;

class QueueBaseModel extends BaseModel
{
    /**
     * @return string
     */
    public function getQueueTable (){
        return $this->modelTable[$this->type] . $this->linkQueueSuffix;
    }

    /**
     * @param LinkInfo $linkInfo
     */
    public function pushLinkQueue(LinkInfo $linkInfo){
        CPlus::redisLpush($this->getQueueTable(), $linkInfo->url);
    }

    /**
     * @return bool
     */
    public function isEmptyQueue(){
        $linkSize = CPlus::redisLlen($this->getQueueTable());
        return $linkSize <= 0;
    }

    /**
     * @return mixed
     */
    public function popLink(){
        return CPlus::redisLpop($this->getQueueTable());
    }
}
