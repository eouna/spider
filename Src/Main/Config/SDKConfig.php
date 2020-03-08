<?php

namespace Config;

class SDKConfig{

    const COS_IMAGE     = "image-1253145602";

    /**
     * 可使用的存储桶列表
     * */
    private static $bucket_type = [
        self::COS_IMAGE  => '图像',
    ];

    private static $q_cloud_config;
    private static $iqy_config;
    private static $jpush_config;
    public static $instance;

    /**
     * @var int $qq_app_id QQ的App Id
     * */
    private static $qq_app_id = 101552597;

    public function __construct(){
        if(!(self::$instance instanceof self))
            self::$instance = new self();
        self::$instance->initAQYConfig();
    }

    /**
     * 获取腾讯云配置
     * */
    public static function getQCloudConfig(){

        return self::setQCloudConfig();
    }
    /**
     * 获取爱奇艺SDK的配置
     * */
    public static function getAQYConfig(){

        return self::initAQYConfig();
    }
    /**
     * 校验爱奇艺签名
     * @param \stdClass $sign
     * @return bool
     * */
    public static function checkoutSign(\stdClass $sign){

        $aqy = self::getAQYConfig();

        $local_sign = md5($sign->uid ."&". $sign->timestamp . "&" . $aqy->Loginkey);
        if(strcmp($local_sign , $sign->sign) === 0)
            return true;

        return false;
    }
    /**
     * 设置腾讯云配置
     * */
    private static function setQCloudConfig() : \stdClass {

        self::$q_cloud_config               = new \stdClass();

        self::$q_cloud_config->app_id       = 0;
        self::$q_cloud_config->app_key      = "";
        self::$q_cloud_config->templateId   = "";
        self::$q_cloud_config->smsSign      = "";

        return self::$q_cloud_config;
    }
    /**
     * 极光推送配置
     * */
    public static function jPushConfig(){
        self::$jpush_config = new \stdClass();
        self::$jpush_config->app_id = "";
        self::$jpush_config->app_key= "";
        return self::$jpush_config;
    }
    /**
     * 腾讯存储桶配置
     * */
    public static function QCloudBucketConfig(){
        $q_cloud_bucket_config = new \stdClass();
        $q_cloud_bucket_config->app_id = "";
        $q_cloud_bucket_config->app_key = "";
        return $q_cloud_bucket_config;
    }
    /**
     * 校验存储桶的正确性
     * @param string $bucket_name
     * @return bool
     * */
    public static function checkBucketExists(string $bucket_name): bool {

        return key_exists($bucket_name, self::$bucket_type);
    }

    /**
     * @return int
     */
    public static function getQqAppId(): int
    {
        return self::$qq_app_id;
    }
}
?>