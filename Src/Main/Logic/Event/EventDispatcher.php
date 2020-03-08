<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/2/19
 * Time: 11:04
 */

namespace Logic\Event;

use SplFixedArray;

class EventDispatcher
{

    private $eventList;

    public static $instance;

    public function __construct()
    {
        $this->eventList = new SplFixedArray();
    }

    public static function Make(){
        if(!(self::$instance instanceof self))
            self::$instance = new self;
        return self::$instance;
    }

    /**
     * @param int $eventType
     * @param mixed $callBackObj
     * @param string $callBackFun
     * @param array $args
     * @throws \ReflectionException
     */
    public function Bind(int $eventType, $callBackObj,string $callBackFun, ...$args){

        if ($callBackFun == null)
        {
            var_dump("CEventDispatcher：Bind：添加事件回调为null");
            return;
        }

        if ($callBackObj == null)
        {
            var_dump("CEventDispatcher：Bind：添加对象为null");
            return;
        }

        if($this->eventList->offsetExists($eventType)){
            var_dump("CEventDispatcher：Bind：事件已注册");
            return;
        }

        $callableObj = new CallableObj();
        $callableObj->m_callBackParam = $args;
        if($callBackObj instanceof \Closure){
            $this->eventList[$eventType] = new \stdClass();
            try{
                $r = new \ReflectionClass($this->eventList[$eventType]);
                $callBackObj->bindTo($r, $callBackFun);
                if(!class_exists($callBackFun, false))
                    $r->name = $callBackFun;
                $callableObj->m_callbackFunc = $callBackFun;
                $callableObj->m_callbackObj = $r;
                $this->eventList[$eventType] = $callableObj;
            }catch (\ReflectionException $exception){
                dump_vars($exception->getTrace());
            }
        }

        if (\is_object($callBackObj) || !\is_callable($callBackObj)) {
            if(class_exists($callBackFun)){
                $r = new \ReflectionClass($callBackObj);
                $reflectionMethod = $r->getMethod($callBackFun);
                if($reflectionMethod == null)
                    throw new \RuntimeException("Class Function Not Exists!");
                $callableObj->m_callbackFunc = $callBackFun;
                $callableObj->m_callbackObj = $callBackObj;
                $this->eventList[$eventType] = $callableObj;
            }
        }

        $this->__call($callBackObj, [$eventType, $callBackFun]);
    }

    /**
     * @param int $eventId
     */
    public function remove(int $eventId){

        if($this->eventList->offsetExists($eventId)){
            $this->eventList->offsetUnset($eventId);
            return;
        }
    }

    /**
     * @param $callObj
     */
    public function removeByObj($callObj){

        if($callObj == null){
            var_dump("CEventDispatcher：Bind：添加对象为null");
            return;
        }

        $callableObj = $this->eventList->current();
        while($callableObj && ($callableObj instanceof CallableObj)){
            if($callObj instanceof $callableObj->m_callbackObj)
                $this->eventList->offsetUnset($this->eventList->key());
            $this->eventList->next();
        }
    }

    /**
     * @param int $eventId
     * @return bool
     */
    public function IsRegister(int $eventId){
        return $this->eventList->offsetExists($eventId);
    }

    /**
     * @param int $eventType
     */
    public function EventDispatcher(int $eventType){


        if($eventType == null){
            var_dump("CEventDispatcher：EventDispatcher：事件ID为空");
            return;
        }

        if(!$this->eventList->offsetExists($eventType)){
            var_dump("CEventDispatcher：EventDispatcher：事件未注册");
            return;
        }

        if(\is_object($this->eventList[$eventType])){
            try{
                $callableObj = $this->eventList[$eventType];
                if($callableObj instanceof CallableObj){
                    $r = new \ReflectionClass($callableObj->m_callbackObj);
                    $rMethod = $r->getMethod($callableObj->m_callbackFunc);
                    if($rMethod instanceof \ReflectionMethod){
                        if($rMethod->isPrivate() || $rMethod->isProtected())
                            $rMethod->setAccessible(true);
                        $rMethod->isStatic() ?
                            $callableObj->m_callbackObj::{$callableObj->m_callbackFunc}($callableObj->m_callBackParam) :
                        $callableObj->m_callbackObj->{$callableObj->m_callbackFunc}($callableObj->m_callBackParam);
                    }
                    else
                        throw new \ReflectionException();
                }
            }catch (\ReflectionException $exception){
                throw new \RuntimeException("Class Function Not Exists!");
            }
        }
    }

    public function clear(){
        $this->eventList = new SplFixedArray();
    }

    /**
     * @param int $eventId
     * @return string|void
     */
    public function getObjSerialize(int $eventId){
        if($this->IsRegister($eventId)){
            $callableObj = $this->eventList->offsetGet($eventId);
            if($callableObj instanceof CallableObj){
                try {
                    if($callableObj->m_callbackObj == null)
                        return;
                    $r = new \ReflectionClass($callableObj->m_callbackObj);
                    return serialize($r);
                } catch (\ReflectionException $e) {
                    dump_vars($e->getTraceAsString());
                }
            }
        }
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }
}
