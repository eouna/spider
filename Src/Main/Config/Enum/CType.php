<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2020/3/3
 * Time: 16:19
 */

namespace Config\Enum;


abstract class CType
{

    protected static $instance;

    protected $type;

    /**
     * @var CRefProperty $instanceRef
     */
    private $instanceRef;
    /**
     * @param string $cType
     * @return self
     */
    abstract public static function enum(string $cType);

    private function getInstance(){
        try {
            $this->instanceRef = new CRefProperty(get_class(self::$instance));
        } catch (\ReflectionException $e) {
            dump_vars($e->getTraceAsString());
        }
    }

    /**
     * @return string
     */
    public function getValue(): string
    {

        $this->getInstance();
        try{
            $defaultValueProperty = $this->instanceRef->childRefObj->getProperty('defaultValue');
            $defaultValueProperty->setAccessible(true);
            $defaultValue = (array)$defaultValueProperty->getValue(new $this->instanceRef->childClassName);

            if (isset($defaultValue[$this->type]))
                return $defaultValue[$this->type];
            throw new \RuntimeException("Event Type Not Exists!");
        } catch (\ReflectionException $e) {
            dump_vars($e->getTraceAsString());
        }
    }
}
