<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2020/3/4
 * Time: 20:55
 */

namespace Model\RedisModel;


interface QueueModel
{
    /**
     * @return mixed
     */
    public function getQueueTable ();

    /**
     * @param string $type
     * @return QueueBaseModel
     */
    public function setType(string $type);
}
