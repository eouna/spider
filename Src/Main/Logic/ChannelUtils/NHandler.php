<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2020/3/3
 * Time: 14:31
 */

namespace Logic\ChannelUtils;


interface NHandler
{
    /**
     * @return self
     */
    public static function make();

    /**
     * @return mixed
     */
    public function toMsg();
}
