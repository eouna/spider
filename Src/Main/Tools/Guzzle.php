<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/7/19
 * Time: 10:41
 */

namespace Tools;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use function GuzzleHttp\Promise\unwrap;

class Guzzle {

    private static $guzzle = [];

    /**
     * 释放资源
     */
    public static function closeAllConns(){
        if( count(self::$guzzle) === 0 ){
            return true;
        }
        foreach (self::$guzzle as $conn){
            $conn = null;
        }
    }

    /**
     * 实例化guzzle(单例)
     * @param $base_uri
     * @return bool
     */
    private static function init($base_uri) {

        if( !isset($guzzle[$base_uri]) ){

            if( !isset(self::$guzzle[$base_uri]) ){
                if(empty($base_uri)){
                    return false;
                }
                self::$guzzle[$base_uri] = new Client([
                    // Base URI is used with relative requests
                    'base_uri' => $base_uri,
                    // You can set any number of default request options.
                    'timeout'  => 10.0,
                    // https请求
                    'verify' => false
                ]);
            }
        }
        return true;
    }

    /**
     * 获取guzzle实例
     * @param $base_uri
     * @return bool|mixed
     */
    public static function getGuzzle($base_uri) {
        $ret = Guzzle::init($base_uri);
        if($ret == false){
            return false;
        }
        return self::$guzzle[$base_uri];
    }

    /**
     * post请求
     * @param string $base_uri   设置uri
     * @param string $api   请求api
     * @param array $post_data   请求数据
     * @param array $headers  请求头
     * @param string $type   请求类型 json
     * @param string $cookie  请求cookies
     * @return mixed
     * @throws \Exception
     */
    public static function guzzle_post($base_uri, $api, $post_data = [], $headers = [], $type = 'json', $cookie = '') {
        $guzzle_ins = Guzzle::getGuzzle($base_uri);
        try{
            if($type === 'json'){
                $data = [
                    'headers' => $headers,
                    'json' => $post_data,
                    'cookies' => $cookie,
                ];
            }else{
                $data = [
                    'headers' => $headers,
                    'body' => $post_data,
                    'cookies' => $cookie,
                ];
            }
            $response = $guzzle_ins->post($api, $data);
            $response_code = $response->getStatusCode();
            $ret = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
            return $ret;
        }catch (RequestException $e){
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * post请求(异步)
     * @param string $base_uri   设置uri
     * @param string $api  请求api
     * @param array $post_data  请求数据
     * @param array $headers  请求头
     * @param string $type  请求类型 json
     * @param string $cookie   请求cookies
     * @return array|mixed
     * @throws \Exception | \Throwable
     */
    public static function guzzle_postAsync($base_uri, $api, $post_data = [], $headers = [], $type = 'json', $cookie = '') {
        $guzzle_ins = Guzzle::getGuzzle($base_uri);
        $info = [];
        try{
            if($type === 'json'){
                $data = [
                    'headers' => $headers,
                    'json' => $post_data,
                    'cookies' => $cookie,
                ];
            }else{
                $data = [
                    'headers' => $headers,
                    'body' => $post_data,
                    'cookies' => $cookie,
                ];
            }
            $promises = [$guzzle_ins->postAsync($api, $data)];
            $ret = unwrap($promises);
            foreach ($ret as $k => $v){
                $info =  \GuzzleHttp\json_decode($v->getBody()->getContents(), true);   //获取server端返回值
            }
            return $info;
        }catch (RequestException $e){
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * get请求
     * @param string $base_uri   设置uri
     * @param string $api 请求api
     * @param array $headers 请求头
     * @return mixed
     * @throws \Exception
     */
    public static function guzzle_get($base_uri, $api, $headers = []) {
        $guzzle_ins = Guzzle::getGuzzle($base_uri);
        try{
            $data = [
                'headers' => $headers,
            ];
            $response = $guzzle_ins->get($api, $data);
            $response_code = $response->getStatusCode();
            $ret = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
            return $ret;
        }catch (RequestException $e){
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * get请求(异步)
     * @param string $base_uri   设置uri
     * @param string $api 请求api
     * @param array $headers 请求头
     * @return mixed
     * @throws \Exception | \Throwable
     */
    public static function guzzle_getAsync($base_uri, $api, $headers = []){
        $guzzle_ins = Guzzle::getGuzzle($base_uri);
        $info = [];
        try{
            $data = [
                'headers' => $headers,
            ];
            $promises = [$guzzle_ins->getAsync($api, $data)];
            $ret = unwrap($promises);

            foreach ($ret as $k => $v){
                $info =  \GuzzleHttp\json_decode($v->getBody()->getContents(), true);   //获取server端返回值
            }
            return $info;
        }catch (RequestException $e){
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * get请求(并行异步)
     * @param string $base_uri   设置uri
     * @param array $api 请求api
     * @return mixed
     * @throws \Exception | \Throwable
     */
    public static function guzzle_getAsyncAll($base_uri, Array $api){
        $guzzle_ins = Guzzle::getGuzzle($base_uri);
        try{
            $promises = [];
            $info = [];
            if(!empty($api)){
                foreach ($api as $value){
                    array_push($promises, $guzzle_ins->getAsync($value));
                }
            }
            $ret = unwrap($promises);   //客户端发起请求并等待所有的请求结束再返回结果
            foreach ($ret as $k => $v){
                $info[] =  \GuzzleHttp\json_decode($v->getBody()->getContents(), true);   //获取server端返回值
            }
            return $info;
        }catch (RequestException $e){
            throw new \Exception($e->getMessage());
        }
    }

    public static function getHeader($source_path){

        $client = new Client();
        try {
            return $client->get($source_path, [
                CURLOPT_HTTPHEADER => ['Content-type: text/plain'],
                CURLOPT_NOBODY => 1,
                CURLOPT_HEADER => true,
            ]);
        } catch (ConnectException $exception) {
            return $client->get($source_path, [
                CURLOPT_HTTPHEADER => ['Content-type: text/plain'],
                CURLOPT_NOBODY => 1,
                CURLOPT_HEADER => true,
            ]);
        }catch (RequestException $requestException){
            throw new \RuntimeException($requestException->getMessage());
        }
    }
}
