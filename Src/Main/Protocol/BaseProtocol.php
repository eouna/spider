<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/9/11
 * Time: 10:40
 */

namespace Protocol;

use BinaryStream\BinaryReader;
use BinaryStream\BinaryWriter;

abstract class BaseProtocol
{

    const OBFUSCATION_PREFIX = "FKAS_YOYO";

    public function __construct()
    {
    }

    /**
     * get message id
     * @return int
     */
    abstract public static function getMsgId();

    /**
     * 写入数据
     * @param BinaryWriter $buffer
     * @return string
     */
    abstract public function write(BinaryWriter $buffer);

    /**
     * 读取数据
     * @param BinaryReader $buffer
     */
    abstract public function read(BinaryReader $buffer);

    /**
     * 访问未知属性时抛出配置表异常
     * @param mixed $name
     * */
    public function __isset(string $name)
    {
        // TODO: Implement __isset() method.
        throw new \RuntimeException("·This Attribute：{$name} Not Found In Configure·");
    }
}