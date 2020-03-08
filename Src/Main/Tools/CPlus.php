<?php

namespace Tools;

use Config\Enum\CChannelEventNameType;
use Connection\RedisConnection\RedisConnect;
use Logic\ChannelUtils\ChannelManager;
use Logic\ChannelUtils\SocketPackage\ChannelPackageData;
use stdClass;

/**
 * 超级公共类
 * User: CCL
 * Date: 2016/7/22
 * Time: 15:56
 */
class CPlus {

    /*
     * 编码数据为json格式
     */
    public static function jsonE($cmd, $status, $info) {
        $message['cmd'] = $cmd;
        $message['body']['status'] = $status;
        $message['body']['info'] = $info;
        return json_encode($message);
    }

    /*
     * 编码数据为json格式
     */
    public static function error($cmd , $info){
        $message['cmd'] = $cmd;
        $message['status'] = false;
        $message['msg'] = $info;
        return json_encode($message);
    }

    /*
     * 编码数据为json格式
     */
    public static function success($cmd , $info){
        $message['cmd'] = $cmd;
        $message['status'] = true;
        $message['msg'] = $info;
        return json_encode($message);
    }

    /*
     * 解析json格式数据
     */
    public static function jsonD($value) {
        return json_decode($value, TRUE);
    }

    /**
     * redis 事务
     */
    public static function redisMulti() {
        //  --多操作的管道实例
        //  $pipe = PublicApi::redisMulti();
        // $pipe->exec();
        return RedisConnect::init()->multi();
    }

    /**
     * redis 查看redis当前连接的DB
     */
    public static function redisGetDb(){
        return RedisConnect::init()->getDB();
    }

    /**
     * redis 管道
     */
    public static function redisPipeline() {
        return RedisConnect::init()->pipeline();
    }

    /**
     * Hexists 判断键是否存在
     * 检测键是否存在
     * 1, 如果键存在。
     * 0, 如果键不存在。
     */
    public static function redisHexists($key, $key1) {
        return RedisConnect::init()->hexists($key, $key1);
    }

    /**
     * Exists 判断键是否存在
     * 检测键是否存在
     * 1, 如果键存在。
     * 0, 如果键不存在。
     */
    public static function redisExists($key) {
        return RedisConnect::init()->exists($key);
    }

    /**
     * Hset 写入数据
     * @param $key
     * @param $key1
     * @param $val
     * @return mixed
     */
    public static function redisHset($key, $key1, $val) {
        return RedisConnect::init()->hset($key, $key1, $val);
    }

    /**
     * Hget 读取数据
     * @param $key $key
     * @param $key1
     * @return mixed
     */
    public static function redisHget($key, $key1) {

        return RedisConnect::init()->hget($key, $key1);
    }
    /**
     * Lpush 写入数据
     * @param $key
     * @param $val
     * @return mixed
     */
    public static function redisLset($key, $index,$val) {
        return RedisConnect::init()->lSet($key,$index, $val);
    }
    /**
     * Lpush 写入数据
     * @param $key
     * @param $val
     * @return mixed
     */
    public static function redisLrem($key , $val , $count) {
        return RedisConnect::init()->lRem($key,$val, $count);
    }
    /**
     * Lpush 写入数据
     * @param $key
     * @param $val
     * @return mixed
     */
    public static function redisLindex($key , $index) {
        return RedisConnect::init()->lIndex($key,$index);
    }
    /**
     * Lpush 写入数据
     * @param $key
     * @param $val
     * @return mixed
     */
    public static function redisLpush($key, $val) {
        return RedisConnect::init()->lPush($key, $val);
    }
    /**
     * Lpush 写入数据
     * @param string $key
     * @param int $start
     * @param int $end
     * @return mixed
     */
    public static function redisLRange($key, $start , $end) {
        return RedisConnect::init()->lRange($key , $start , $end);
    }
    /**
     * Lpop 写入数据
     * @param $key
     * @param $key1
     * @param $val
     * @return mixed
     */
    public static function redisLpop($key) {
        return RedisConnect::init()->lPop($key);
    }
    /**
     * Rpop 写入数据
     * @param $key
     * @param $key1
     * @param $val
     * @return mixed
     */
    public static function redisRpop($key) {
        return RedisConnect::init()->rPop($key);
    }
    /**
     * Llen 写入数据
     * @param $key
     * @param $key1
     * @param $val
     * @return mixed
     */
    public static function redisLlen($key) {
        return RedisConnect::init()->lLen($key);
    }
    /**
     *  zCount获取总数
     * @param $key
     * @return mixed
     */
    public static function redisZcard($key) {
        return RedisConnect::init()->zCard($key);
    }
    /**
     *  zCount获取总数
     * @param $script
     * @param $output
     * @param $key
     * @return mixed
     */
    public static function redisEval($script , $output , $key) {
        return RedisConnect::init()->evaluate($script , $output , $key);
    }
    /**
     * 获取键值的类型
     * @param string $key
     * @return string
     * */
    public static function redisKeyType(string $key){
        return RedisConnect::init()->type($key);
    }
    /**
     * incr 自增
     * @param $key
     * @param $key1
     * @return mixed
     */
    public static function redisIncr($key) {
        return RedisConnect::init()->incr($key);
    }

    /**
     * incrby 增加
     * @param $key
     * @param $key1
     * @return mixed
     */
    public static function redisHincrby($key, $key1, $val) {
        return RedisConnect::init()->hincrby($key, $key1, $val);
    }

    /**
     * get
     * @param $key
     * @param $key1
     * @return mixed
     */
    public static function redisGet($key) {
        return RedisConnect::init()->get($key);
    }

    /**
     * set
     * @param $key
     * @param $key1
     * @return mixed
     */
    public static function redisSet($key, $val) {
        return RedisConnect::init()->set($key, $val);
    }

    /**
     * hdel  删除
     * @param $key
     * @param $key1
     * @return mixed
     */
    public static function redisHdel($key, $key1) {
        return RedisConnect::init()->hDel($key, $key1);
    }

    /**
     * del  删除 表
     * @param $key
     * @return mixed
     */
    public static function redisDel($key) {
        return RedisConnect::init()->del($key);
    }

    /**
     * hlen 查键长度
     */
    public static function redisHlen($key) {
        return RedisConnect::init()->hlen($key);
    }

    /**
     * hmget
     *  获取批量数据
     * @param string $key 表名
     * @param array $keyarray 键名  array('$key1','$key2',...)
     * @return array
     */
    public static function redisHmget($key, $keyarray) {
        return RedisConnect::init()->hmget($key, $keyarray);
    }

    /**
     * hmset
     * 写入批量数据
     * @param string $key 表名
     * @param array $keyarray 键名  array('$key1','$key2',...)
     * @return int
     */
    public static function redisHmset($key, $keyarray) {
        return RedisConnect::init()->hmset($key, $keyarray);
    }

    /**
     * Hgetall
     *  获取全部数据
     * @param string $key 表名
     * @return array
     */
    public static function redisHgetall($key) {

        return RedisConnect::init()->hgetall($key);
    }

    /**
     * hkeys 查健
     */
    public static function redisHkeys($key) {
        return RedisConnect::init()->hkeys($key);
    }

    /**
     * hvals 查内容
     */
    public static function redisHvals($key) {
        return RedisConnect::init()->hvals($key);
    }

    /**
     * 过期时间
     */
    public static function redisSetex($key, $time, $val) {
        return RedisConnect::init()->setex($key, $time, $val);
    }

    /**
     * 指定键的过期时间
     */
    public static function redisExpire($key, $time) {

        return RedisConnect::init()->expire($key, $time);
    }

    /**
     * redis 加入有序列表
     */
    public static function rediszAdd($key, $score, $val) {
        return RedisConnect::init()->zadd($key, $score, $val);
    }

    /**
     * redis 移除有序列表
     */
    public static function rediszRem($key, $val) {
        return RedisConnect::init()->zRem($key, $val);
    }

    /**
     * redis 范围搜索有序列表
     */
    public static function rediszRange($key, $start_num, $end_num, $type = false) {
        if ($type == TRUE) {

            return RedisConnect::init()->zrange($key, $start_num, $end_num, 'withscores');
        }
        return RedisConnect::init()->zrange($key, $start_num, $end_num);
    }

    /*
     * redis 增减有序列表中特定val的score值
     *       */

    public static function rediszIncrby($key, $score, $val) {
        return RedisConnect::init()->zincrby($key, $score, $val);
    }

    /**
     * redis 范围搜索有序列表中 指定$val的score值
     */
    public static function rediszScore($key, $val) {
        return RedisConnect::init()->zscore($key, $val);
    }
    /**
     * redis 范围获取有序列表中排名
     */
    public static function rediszRank($key, $val) {
        return RedisConnect::init()->zrank($key, $val);
    }
    /**
     * redis 范围获取有序列表中排名
     */
    public static function rediszRevRank($key, $val) {
        return RedisConnect::init()->zRevRank($key, $val);
    }
    /**
     * redis 获取有序列表中score介于某个范围内的$val列表
     */
    public static function rediszRangeByScore($key, $s_num, $e_num, $type = false) {
        if ($type == TRUE) {

            return RedisConnect::init()->zrangebyscore($key, $s_num, $e_num, ['withscores' => TRUE]);
        }
        return RedisConnect::init()->zrangebyscore($key, $s_num, $e_num);
    }

    /**
     * redis 删除有序列表中score介于某个范围内的$val列表
     */
    public static function rediszRemRangeByScore($key, $s_num, $e_num) {
        return RedisConnect::init()->zremrangebyscore($key, $s_num, $e_num);
    }

    /**
     * redis 删除有序列表中下标介于某个范围内的$val列表
     */
    public static function rediszRemRangeByRank($key, $s_num, $e_num) {
        return RedisConnect::init()->zremrangebyrank($key, $s_num, $e_num);
    }

    /**
     * redis 获取有序列表按 score 值递减(从大到小)来排列
     * @return array
     */
    public static function rediszRevRange($key, $s_num, $e_num, $type = false) {
        if ($type == TRUE) {
            return RedisConnect::init()->zrevrange($key, $s_num, $e_num, 'withscores');
        }
        return RedisConnect::init()->zrevrange($key, $s_num, $e_num);
    }

    /**
     * redis 添加地理位置
     */
    public static function redisGeoAdd($key, $val) {
        return RedisConnect::init()->geoadd($key, $val);
    }

    /**
     * redis 返回地理位置之间的距离
     */
    public static function redisGeodist($key, $members) {
        return RedisConnect::init()->geodist($key, $members);
    }

    /**
     * redis 返回经纬度地理位置半径中的所有元素（包含本身）
     */
    public static function redisGeoRadius($key, $val) {
        return RedisConnect::init()->georadius($key, $val);
    }

    /**
     * redis 返回元素地理位置半径中所有的元素（包含本身）
     */
    public static function redisGeoRadiusByMember($key, $val) {
        return RedisConnect::init()->georadiusbymember($key, $val);
    }
    /**
     * redis 返回匹配的键值
     */
    public static function redisKeys($key) {
        return RedisConnect::init()->keys($key);
    }

    /**
     * redis 全清
     */
    public static function redisFlushAll() {
        return RedisConnect::init()->flushAll();
    }
    /**
     * redis 更名
     * @param string $old_key
     * @param string $new_key
     * @return string
     * */
    public static function redisRename($old_key, $new_key){
        return RedisConnect::init()->rename($old_key, $new_key);
    }

    public static function array2object($array) {

        if (is_array($array)) {
            $obj = new StdClass();

            foreach ($array as $key => $val) {
                $obj->$key = $val;
            }
        } else {
            $obj = $array;
        }

        return $obj;
    }

    public static function object2array($object) {
        if (is_object($object)) {
            foreach ($object as $key => $value) {
                $array[$key] = $value;
            }
        } else {
            $array = $object;
        }
        return $array;
    }

    /**
     * crc 循环校验码
     * @param int $crc_str
     * @param int $mod
     * @return int
     */
    public static function crc(int $crc_str , int $mod){
        $crc_num    = crc32($crc_str);                 //玩家在redis中的ID
        $table      = sprintf("%u" , $crc_num);     //格式化表ID
        return $table % $mod;
    }

    /**
     * 获取IP地址
     * @return string
     * */
    public static function getIp()
    {

        if(!empty($_SERVER["HTTP_CLIENT_IP"]))
        {
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        }
        else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
        {
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        else if(!empty($_SERVER["REMOTE_ADDR"]))
        {
            $cip = $_SERVER["REMOTE_ADDR"];
        }
        else
        {
            $cip = '';
        }
        preg_match("/[\d\.]{7,15}/", $cip, $cips);
        $cip = isset($cips[0]) ? $cips[0] : 'unknown';
        unset($cips);

        return $cip;
    }

    /**
     * 校验文件
     * @param mixed $validate
     * @return mixed
     * */
    public static function validator($validate){

        $validator_provider = [
            'password'  => '/^[a-z0-9_-]{3,16}$/',
            'email'     => '/^[a-z\d]+(\.[a-z\d]+)*@([\da-z](-[\da-z])?)+(\.{1,2}[a-z]+)+$/',
            'ip'        => '/((2[0-4]\d|25[0-5]|[01]?\d\d?)\.){3}(2[0-4]\d|25[0-5]|[01]?\d\d?)/',
            'phone'     => '/^[1](([3][0-9])|([4][5-9])|([5][0-3,5-9])|([6][5,6])|([7][0-8])|([8][0-9])|([9][1,8,9]))[0-9]{8}$/',
            'exists'    => ''
        ];

        if(is_string($validate))
            return empty($validate) ? false : true;

        if(!is_array($validate))
            return false;

        foreach ($validate as $param => $rules){

            if(!empty($rules) && strpos("|" , (string)$rules)){
                foreach (explode("|" , $rules) as $rule){
                    if(!key_exists($rule , $validator_provider))
                        continue;

                    $res = preg_grep($validator_provider[$rule], [$param]);

                    if(empty($res))
                        return false;
                }
            }
            if(!empty($param) && !is_numeric($rules) && empty($rules))
                return false;
        }
        return true;
    }

    /**
     * 将大写字母转成小写并在中间加_！
     * @param string $class_name
     * @return string 新的MODE NAME
     * */
    public static function transModeName($class_name){
        $i = 0;
        $class_name = preg_replace("/.*\\\\([\w]+)$/i" , '$1' , $class_name);
        do{
            if(isset($class_name[$i]) && strtoupper($class_name[$i]) === $class_name[$i]){
                $class_name[$i] = strtolower($class_name[$i]);
                if($i !=  0)
                    $class_name = substr($class_name , 0 , $i) . "_" . substr($class_name , $i , strlen($class_name) - 1);
            }
        }while(isset($class_name[$i++]));
        return $class_name;
    }

    /**
     * js escape
     * @param string $string
     * @param string $in_encoding
     * @param string $out_encoding
     * @return string
     * */
    public static function escape($string, $in_encoding = 'UTF-8',$out_encoding = 'UCS-2') {
        $return = '';
        if (function_exists('mb_get_info')) {
            for($x = 0; $x < mb_strlen ( $string, $in_encoding ); $x ++) {
                $str = mb_substr ( $string, $x, 1, $in_encoding );
                if (strlen ( $str ) > 1) { // 多字节字符
                    $return .= '%u' . strtoupper ( bin2hex ( mb_convert_encoding ( $str, $out_encoding, $in_encoding ) ) );
                } else {
                    $return .= '%' . strtoupper ( bin2hex ( $str ) );
                }
            }
        }
        return $return;
    }

    /**
     * 判断是否为json字符串
     * @param string $json_string
     * @return mixed
     * */
    public static function safeJsonDecode(string $json_string){
        $json = json_decode($json_string);

        if(json_last_error() != 0 || self::json_last_error_msg() != 'No error'){
            self::dump(self::json_last_error_msg());
            return json_last_error();
        }

        return $json;
    }

    /*
     * 打印数据
     * */
    public static function dump($message , $track_depth = 2){
        if(!DEBUG) return;
        $debug_trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS , $track_depth);
        if(isset($debug_trace[$track_depth - 1]['class']) && isset($debug_trace[$track_depth - 1]['function']))
            if(is_array($message) || is_object($message)){
                var_dump(date("m-d H:i:s") . "  " . $debug_trace[$track_depth - 1]['class']."::".$debug_trace[$track_depth - 1]['function']."：\033[m\033[1;38;5;208m".$debug_trace[$track_depth - 2]['line']."    \033[m\033[255;255;255mDEBUG=============> ");
                var_dump($message);
            }else{
                var_dump(date("m-d H:i:s") . "  " . $debug_trace[$track_depth - 1]['class']."::".$debug_trace[$track_depth - 1]['function']."：\033[m\033[1;38;5;208m".$debug_trace[$track_depth - 2]['line']."    \033[m\033[255;255;255mDEBUG=============> ".$message);
            }
    }

    /**
     * @param $message
     * @param int $track_depth
     */
    public static function dumpByChannel($message , $track_depth = 2){
        if(!DEBUG) return;
        $debug_trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS , $track_depth);
        $outputData = null;
        if(isset($debug_trace[$track_depth - 1]['class']) && isset($debug_trace[$track_depth - 1]['function'])){
            if(is_array($message) || is_object($message)){
                $outputData[0] = date("m-d H:i:s") . "  " . $debug_trace[$track_depth - 1]['class']."::".$debug_trace[$track_depth - 1]['function']."：\033[m\033[1;38;5;208m".$debug_trace[$track_depth - 2]['line']."    \033[m\033[255;255;255mDEBUG=============> ";
                $outputData[1] = $message;
                var_dump($outputData[0]);
                var_dump($outputData[1]);
            }else{
                $outputData[0] = date("m-d H:i:s") . "  " . $debug_trace[$track_depth - 1]['class']."::".$debug_trace[$track_depth - 1]['function']."：\033[m\033[1;38;5;208m".$debug_trace[$track_depth - 2]['line']."    \033[m\033[255;255;255mDEBUG=============> ".$message;
                var_dump($outputData[0]);
            }
            $channelPackageData = new ChannelPackageData();
            $channelPackageData->addr = CChannelEventNameType::CLI_DUMPER_CHANNEL_ADDR;
            $channelPackageData->port = CChannelEventNameType::CLI_DUMPER_CHANNEL_PORT;
            $channelPackageData->msgData = $message;
            $channelPackageData->channelBindEventName = CChannelEventNameType::CLI_DUMPER_CHANNEL;
            !$outputData ?: ChannelManager::make($channelPackageData)->setSocketInfo($channelPackageData)->msgToChannel();
        }
    }

    /**
     * get json last error
     * */
    public static function json_last_error_msg() {

        static $ERRORS = [
            JSON_ERROR_NONE => 'No error',
            JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
            JSON_ERROR_STATE_MISMATCH => 'State mismatch (invalid or malformed JSON)',
            JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
            JSON_ERROR_SYNTAX => 'Syntax error',
            JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded'
        ];

        $error = json_last_error();
        return isset($ERRORS[$error]) ? $ERRORS[$error] : 'Unknown error';
    }

    /**
     * 万能解析
     * @param $content
     * @param $format
     * @return null
     */
    public static function unPackTools($content, $format)
    {

        if (!empty($content) && !empty($format)) {
            $to_len = 0;
            $len = 0;
            $format_len = 0;
            foreach ($format as $val) {
                $pformat = substr($val, 0, 1);
                $nformat = substr($val, 1);

                switch ($pformat) {
                    case 'C':
                        $format_len = 1;
                        break;
                    case 'V':
                    case 'N':
                    case 'L':
                    case 'l':
                        $format_len = 4;
                        break;
                    case 'v':
                    case 'n':
                        $format_len = 2;
                        break;
                    case 'a':
                        $format_len = $len;
                        break;
                }
                if ($nformat == 'Len') {
                    $unpack_data = unpack($pformat, substr($content, $to_len, $to_len + $format_len));
                    $len = $unpack_data[1];
                } else {
                    if ($pformat == 'a') {
                        $unpack_data = unpack($pformat . $len, substr($content, $to_len, $to_len + $format_len));
                        $back_data[$nformat] = $unpack_data[1];
                    } else {
                        $unpack_data = unpack($pformat, substr($content, $to_len, ($to_len + $format_len)));
                        $back_data[$nformat] = $unpack_data[1];
                    }

                }

                $to_len += $format_len;

            }

            return $back_data;

        }

        return null;
    }

    /**
     * 内容替换操作
     * @param string $content
     * @param array $replace_argument
     * @return string
     * */
    public static function contentReplaceByQuote(string $content, array $replace_argument){
        return preg_replace("/(\{.*\})/Us", implode('', $replace_argument), $content);
    }

    /**
     * @param $size
     * @param int $mod 默认为byte大小
     * @return string
     */
    public static function getFileSizeDesc($size, $mod = 0){
        static $modDecorator = ['Byte', 'Kb', 'Mb', 'Gb', 'Tb'];
        while($size > 1024){$size = $size / 1024 + (($mod++) * 0);}
        return floor($size) . ($mod < count($modDecorator) ? $modDecorator[$mod] : '');
    }
}
