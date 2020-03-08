<?php


namespace Config\Enum;


class CChannelEventNameType extends CType
{

    const CLI_DUMPER_CHANNEL = 'CliDumperChannel';
    const CLI_DUMPER_SEND_CHANNEL = 'CliDumperSendChannel';
    const CLI_DUMPER_CHANNEL_ADDR = '0.0.0.0';
    const CLI_DUMPER_CHANNEL_PORT = '2206';

    private $defaultValue = [];

    /**
     * @param string $cType
     * @return self
     */
    public static function enum(string $cType)
    {
        // TODO: Implement enum() method.
        if(!(self::$instance instanceof self))
            self::$instance = new self();
        self::$instance->type = $cType;
        return self::$instance;
    }

    public function bind(\Closure $func){
        if(!isset($this->defaultValue[$this->type])){
            $this->defaultValue[$this->type] = $func;
        }
    }

    /**
     * @param array $params
     */
    public function exec(...$params){
        if($this->defaultValue[$this->type] instanceof \Closure){
            call_user_func($this->defaultValue[$this->type], $params);
        }
    }
}
