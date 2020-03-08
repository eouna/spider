<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/9/11
 * Time: 10:30
 */

namespace Protocol;
use BinaryStream\BinaryReader;
use BinaryStream\BinaryWriter;
use GatewayWorker\Lib\Gateway;

class NoticeProtocol extends BaseProtocol
{

    const MSG_ID = 10001;
    /**
     * 时间
     * @var string $time
     */
    public $time;
    /**
     * 消息
     * @var double $msg
     */
    public $msg;

    /**
     * 协议
     * @var double $proto
     */
    public $proto;

    public function __construct()
    {
        parent::__construct();
        $this->time = 0;
        $this->msg = '';
    }
    /**
     * return message ID
     * @return int
     */
    public static function getMsgID(){
        return self::MSG_ID;
    }
    /**
     * return message ID
     * @return int
     */
    public static function msgID(){
        return self::MSG_ID;
    }
    /**
     * @param string $msg
     * 发送通知消息到客户端
     * */
    public static function sendNotice($msg){
        $buffer = new BinaryWriter();
        $self = new self();
        $self->time = time();
        $self->msg = $msg;
        Gateway::sendToCurrentClient($self->write($buffer));
    }
    /**
     * 写入数据
     * @throws
     * @param BinaryWriter $buffer
     * @return string
     */
    public function write(BinaryWriter $buffer)
    {
        // TODO: Implement write() method.
        $buffer->writeUTFString(self::OBFUSCATION_PREFIX);
        $buffer->writeInt32(self::MSG_ID);
        $buffer->writeInt64($this->time);
        $buffer->writeUTFString($this->msg);
        return $buffer->getWriteStream();
    }

    /**
     * 读取数据
     * @param BinaryReader $buffer
     */
    public function read(BinaryReader $buffer)
    {
        // TODO: Implement read() method.
        $this->time = $buffer->readInt32();
        $this->PlayerID = $buffer->readUTFString();
    }
}