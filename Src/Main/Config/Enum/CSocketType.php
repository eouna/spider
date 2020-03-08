<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2020/3/3
 * Time: 16:17
 */

namespace Config\Enum;


class CSocketType extends CType
{
    const S_ASYNC_ADDR = '逻辑进程';
    const S_RESOURCE_LOADER_ADDR = '资源下载进程';
    const S_LINK_LOADER_ADDR = '链接下载进程';
    const S_CLOUD_UPLOADER_ADDR = '上传BUCKET进程';

    protected $defaultValue = [
        self::S_ASYNC_ADDR => 'Text://127.0.0.1:12345',
        self::S_RESOURCE_LOADER_ADDR => 'Text://127.0.0.1:12215',
        self::S_LINK_LOADER_ADDR => 'Text://127.0.0.1:12216',
        self::S_CLOUD_UPLOADER_ADDR => 'Text://127.0.0.1:12217',
    ];

    /**
     * @param string $cType
     * @return CType
     */
    public static function enum(string $cType)
    {
        // TODO: Implement enum() method.
        if(!(self::$instance instanceof self))
            self::$instance = new self();
        self::$instance->type = $cType;
        return self::$instance;
    }
}
