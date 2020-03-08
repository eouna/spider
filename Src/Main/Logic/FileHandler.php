<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/9/7
 * Time: 20:12
 */

namespace Logic;

use Config\Enum\Host;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Tools\Tools;

class FileHandler
{
    /**
     * 保存图片
     * @param string $download_url
     * @param array $path_name
     * @param string $suffix
     * @throws
     * @return string whole url
     * */
    public static function save(string $download_url, array $path_name, $suffix = 'jpg')
    {
        if(!is_dir($path_name[0])){
            var_dump($path_name[0]);
            mkdir($path_name[0],0755, true);
        }
        $file_path = $path_name[0] . $path_name[1];
        $file_exists = file_exists($file_path);
        if($file_exists){
            $file_size = filesize($file_path);
            if($file_size == 0)
                return self::downloadImgToLocal($download_url, $file_path);
            return Host::IMG_ADDRESS . $path_name[1];
        }
        if (!$file_exists)
            try{
                #图片下载method1：Guzzle下载
                $http_client = new Client();
                $http_client->get($download_url, ['save_to' => $file_path]);
                #图片下载method2：Curl下载
                if(!file_exists($file_path))
                    self::downloadImgToLocal($download_url, $file_path);
                else
                    Tools::uploadToQCloudCOS($file_path, date("Ymd") . "/resource/" . $path_name[3] . "/" . $path_name[1]);
                return Host::IMG_ADDRESS . $path_name[1];
            }catch(ConnectException $exception){
                if(!file_exists($file_path))
                    self::downloadImgToLocal($download_url, $file_path);
                return Host::IMG_ADDRESS . $path_name[1];
            }
        return Host::IMG_ADDRESS . $path_name[1];
    }

    /**
     * 通过CURL获取图片
     * @param string $url 要下载的连接
     * @param string $filename 保存的文件地址
     * @return bool
     * */
    private static function downloadImgToLocal($url = "", $filename = "")
    {
        if (!is_dir(basename($filename))) {
            echo "The Dir was not exits";
            return false;
        }
        $handler = curl_init();
        $fp = fopen($filename, 'wb');
        curl_setopt($handler, CURLOPT_URL, $url);
        curl_setopt($handler, CURLOPT_FILE, $fp);
        curl_setopt($handler, CURLOPT_HEADER, 0);
        curl_setopt($handler, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($handler, CURLOPT_TIMEOUT, 60);
        curl_exec($handler);
        curl_close($handler);
        fclose($fp);
        return true;
    }
}