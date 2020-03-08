<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/9/7
 * Time: 18:16
 */
if(!function_exists('str_random')){
    /**
     * @param int $length
     * @throws Exception
     * @return string
     * */
    function str_random(int $length){
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;

            $bytes = random_bytes($size);

            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }
}

if(!function_exists('is_timestamp')){
    /**
     * 校验是否是时间戳
     * @param string $timestamp 时间戳
     * @return bool
     * */
    function is_timestamp(string $timestamp): bool{
        date_default_timezone_set('PRC');

        return strtotime(date('Y-m-d H:i:s',$timestamp)) === (int)$timestamp;
    }
}

if(!function_exists("crc")){
    /**
     * 计算crc32的值
     * @param string $key
     * @return string
     * */
    function crc(string $key){
        return sprintf("%u" , crc32($key));
    }
}


if(!function_exists("dump_vars")){
    /**
     * 打印
     * @param $message
     * @return string
     * */
    function dump_vars($message){
        \Tools\CPlus::dump($message, 3);
    }
}

if(!function_exists("dump_cli")){
    /**
     * 打印
     * @param $message
     * @return string
     * */
    function dump_cli($message){
        \Tools\CPlus::dumpByChannel($message, 3);
    }
}


if(!function_exists("isAbsolutePath")){
    /**
     * 打印
     * @param string $file_path
     * @return string
     * */
    function isAbsolutePath(string $file_path){
        return preg_match("/^([hH][tT]{2}[pP]:\/\/|[hH][tT]{2}[pP][sS]:\/\/).*/i", $file_path);
    }
}

if(!function_exists("isEmptyLink")){
    /**
     * 打印
     * @param string $file_path
     * @return string
     * */
    function isEmptyLink(string $file_path){
        return preg_match("/^(javascript).*/i", $file_path);
    }
}


if(!function_exists("getTitle")){
    /**
     * 打印
     * @param string $html_string
     * @return mixed
     * */
    function getTitle(string $html_string){
        $match = [];
        preg_match("/<title>(.+?)<\/title>/i", $html_string, $match);
        return isset($match[1]) ? $match[1] : '';
    }
}

if(!function_exists("transToUTF8")){
    /**
     * 打印
     * @param string $char_str
     * @return mixed
     * */
    function transToUTF8(string $char_str){
        $encode = mb_detect_encoding($char_str, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
        return mb_convert_encoding($char_str, 'UTF-8', $encode);
    }
}
