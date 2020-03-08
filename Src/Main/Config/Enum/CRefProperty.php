<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2020/3/3
 * Time: 17:51
 */

namespace Config\Enum;


use ReflectionClass;

class CRefProperty
{
    /**
     * @var string $childClassName
     */
    public $childClassName;

    /**
     * @var ReflectionClass $childRefObj
     */
    public $childRefObj;

    /**
     * CRefProperty constructor.
     * @param $className
     * @throws \ReflectionException
     */
    public function __construct($className)
    {
        $this->childClassName = $className;
        $this->childRefObj = new \ReflectionClass($this->childClassName);
    }
}
