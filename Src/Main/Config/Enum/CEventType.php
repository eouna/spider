<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2020/3/2
 * Time: 20:39
 */

namespace Config\Enum;

class CEventType extends CType
{
    const E_FINISHED_DOWNLOAD_LINK = "完成";

    private $defaultValue = [
        self::E_FINISHED_DOWNLOAD_LINK => 1,
    ];

    /**
     * @param string $cType
     * @return CType
     */
    public static function enum(string $cType)
    {
        // TODO: Implement make() method.
        if(!(self::$instance instanceof self))
            self::$instance = new self();
        self::$instance->type = $cType;
        return self::$instance;
    }
}
