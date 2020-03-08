<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/9/12
 * Time: 18:12
 */

namespace Logic\Statistics;


class SocketSTT
{
    /**
     * 获取实时socket监控信息
     * */
    public static function getStatistics(){
        $socket_statistics = [];
        exec("ss -s", $socket_statistics);
        return join("\r\n", $socket_statistics);
    }
}