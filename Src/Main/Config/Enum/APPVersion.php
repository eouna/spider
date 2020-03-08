<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/9/7
 * Time: 18:23
 */

namespace Config\Enum;


class APPVersion
{
    private static $version = "v1";

    public static function getAppVersion(){
        return self::$version;
    }
}