<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/1/27
 * Time: 16:31
 */

namespace Tools;

use Config\Enum\APPVersion;
use Config\Enum\EyRsaKey;
use Config\SDKConfig;
use Qcloud\Cos\Client;
use Qcloud\Cos\Exception\CurlException;
use Qcloud\Cos\Exception\ServiceResponseException;

class Tools
{

    /**
     * 初始化表
     * @throws
     * @param  int $_N 获取的字母长度
     * @return string
     * */
    private static function generateCode(int $_N){

        $string = '';

        while (($len = strlen($string)) < $_N) {
            $size = $_N - $len;

            $bytes = random_bytes($size);

            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return strtoupper($string);
    }

    /**
     * 手机号码模拟器
     * generate a fake legal phone number
     * @return string
     * */
    public static function phoneGenerator(){

        $table_prefix_step = 'phone_number_prefix_pos';
        $table_number_body = 'phone_number_body_gen';

        $legal_number_prefix = [
            139, 138, 137, 136, 135, 134, 178, 170, 188, 187, 183, 182, 159, 158, 157, 152, 199, 166,
            150, 147, 139, 186, 185, 170, 156, 155, 130, 131, 132, 189, 180, 170, 153, 133, 198];

        $body = CPlus::redisIncr($table_number_body) + 10000000;
        $pos = CPlus::redisGet($table_prefix_step);

        if(!CPlus::redisExists($table_prefix_step) || $body == 99999999)
            $pos = CPlus::redisIncr($table_prefix_step);

        return $legal_number_prefix[$pos] . $body;
    }

    /**
     * 上传存储桶公用方法
     * @throws \Exception
     * @param string $local_path 文件的本地路径
     * @param string $dst_path_name 存储桶中的名字没有则为本地名
     * @param string $bucket_name 存储桶的名字
     * @return string|object 新的COS链接
     * */
    public static function uploadToQCloudCOS(string $local_path, string $dst_path_name = '', string $bucket_name = SDKConfig::COS_IMAGE){

        if(!SDKConfig::checkBucketExists($bucket_name))
            return false;

        if(!file_exists($local_path))
            return false;

        $sdk_config = SDKConfig::QCloudBucketConfig();

        $secretId = $sdk_config->app_id;                        //"云 API 密钥 SecretId";
        $secretKey = $sdk_config->app_key;                      //"云 API 密钥 SecretKey";
        $region = "ap-shanghai";                                //设置一个默认的存储桶地域
        try{

            $cosClient = new Client([
                'region' => $region,
                'schema' => 'https', //协议头部，默认为http
                'credentials'=> [
                    'secretId'  => $secretId ,
                    'secretKey' => $secretKey
                ]
            ]);

            $cloud_path = APPVersion::getAppVersion() . "/" . (!empty($dst_path_name) ? $dst_path_name : $dst_path_name = basename($local_path));
            $response = $cosClient->upload($bucket_name, $cloud_path, fopen($local_path, 'rb'));
        }catch (ServiceResponseException $serviceResponseException){
            return null;
        }

        return $response;
    }

    /**
     * 解析加密数据
     * @param string $rsa_key 加密过的AES KEY
     * @param string $aes_data AES加密过的数据
     * @return string
     * */
    public static function decodeAesData($rsa_key, $aes_data){

        $aes_key = EyRsa::priDecrypt($rsa_key, EyRsaKey::getPrimaryKey());

        $aes = new EyAes($aes_key);
        $response_data = $aes->decrypt($aes_data);

        return $response_data;
    }

    /**
     * 加密数据
     * @throws
     * @param string$encrypt_data
     * @return array
     * */
    public static function encodeDataByAes(string $encrypt_data){

        $aes_key = str_random(16);
        $aes = new EyAes($aes_key);

        $response_data['token_info'] = $aes->encrypt($encrypt_data);
        $response_data['token_key'] = EyRsa::priEncrypt($aes_key, EyRsaKey::getPrimaryKey());

        return $response_data;
    }


    /**
     * 计算当前时间与凌晨时间的分钟数差
     * @param string $time
     * @return int
     * */
    public static function mineMinuteOf12Pm($time = ''){
        $time = empty($time) ? time() : $time;
        $_start_time = mktime(0,0,0, date("m", $time), date("d", $time), date("Y", $time));
        return !$_start_time ? -1 : floor((($time - $_start_time) / 60));
    }
}