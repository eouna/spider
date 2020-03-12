<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2020/3/4
 * Time: 12:21
 */

namespace Logic\Service\Link;


use Logic\ConfigLoader;
use Logic\UrlDealFactoryMemoryLeak;
use Logic\UrlDealFactory;
use Tools\Validator;

class DealLinkProvider
{
    /**
     * @return LinkPackage
     */
    public static function formatLinkPackage(){
        $linkPackage = (new LinkPackage());
        $linkPackage->depth = 10;
        $linkPackage->filter = $message['filter'] = 100 * 1024;
        $linkPackage->interval = 5;
        $linkPackage->type = [
            UrlDealFactoryMemoryLeak::TAG_IMAGE => 1,
        ];
        return $linkPackage;
    }

    /**
     * @param array $jsonData
     */
    public static function doCollectTrigger(array $jsonData){
        if(Validator::make($jsonData, [
            'url' => 'exists',
            'filter' => 'exists|number',
        ])->fail())
            return;
        $sMemory = number_format((float)(memory_get_usage() / 1048576), 2);
        dump_vars("当前进程ID：\033[36m". posix_getpid() ." \033[m\033[255;255;255m占用内存：" . $sMemory . 'Mb');
        $urlDealFactory = new UrlDealFactory();
        $urlDealFactory->config($jsonData)->go();
        unset($urlDealFactory);
        $sMemory = number_format((float)(memory_get_usage() / 1048576), 2);
        dump_vars("当前进程ID：\033[36m". posix_getpid() ." \033[m\033[255;255;255m占用内存：" . $sMemory . 'Mb');
    }
}
