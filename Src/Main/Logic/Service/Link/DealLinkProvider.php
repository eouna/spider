<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2020/3/4
 * Time: 12:21
 */

namespace Logic\Service\Link;


use Logic\ConfigLoader;
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
            UrlDealFactory::TAG_IMAGE => 1,
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
        UrlDealFactory::make(ConfigLoader::IMG_SITE_GROUP)->config($jsonData)->go();
    }
}
