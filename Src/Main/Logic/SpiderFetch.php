<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/9/7
 * Time: 15:27
 */
namespace Logic;

use GatewayWorker\Lib\Gateway;
use Tools\CPlus;
use Tools\Validator;

class SpiderFetch extends ApiBase
{

    public static function init(array $message, string $client_id)
    {
        return parent::init($message, $client_id); // TODO: Change the autogenerated stub
    }

    /**
     * 处理客户端信息
     * @param array $message
     * @return string
     * */
    public static function dealClientMsg(array $message){

        if(Validator::make($message, [
            'url' => 'required|url',
            'depth' => 'exists|number',
            'filter' => 'exists|number',
            'interval' => 'exists|number',
        ])->fail())
            return CPlus::error(10001, "数据验证出错！");

        $message['depth'] <= 10 ? ($message['depth'] < 100 ?: $message['depth'] = 100) :  ($message['depth'] = ($message['depth'] <= 0) ? 1 : 10);
        $message['filter'] <= 0 ? $message['filter'] = 50 * 1024 : ($message['filter'] >= 10240 ? $message['filter'] = 10240 * 1024 : $message['filter'] *= 1024);
        $message['interval'] >= 0 ? ($message['interval'] < 1000 ?: $message['interval'] = 1000) : $message['interval'] = 0;
        empty($message['type']) ?: !is_array($message['type']) ?: $message['type'] = array_flip($message['type']);

        UrlDealFactory::make(ConfigLoader::IMG_SITE_GROUP)->config($message)->go();

        return CPlus::success(10001,'开始采集');
    }
}