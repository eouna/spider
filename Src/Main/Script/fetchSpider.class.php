<?php
namespace lib\spider;
require_once("common.fun.php");
abstract class spider_func{
    public  $ch;
    private $curl_deepth    = 0;        //当前抓取深度

    private $deep_max       = 8;        //最大深度

    public  $curl_array     = Array();  //curl设置数组

    public  $get_type       = '';        //设置抓取内容\

    public  $curl_url       = '';

    public  $url_host       = '';       //当前查询域名信息

    public  $url_path       = '';       //当前查询路径

    public  $url_query      = '';       //当前查询参数 

    public  $url_scheme     = '';       //当前查询参数    
    /*
     * @return resource
     * */
    abstract function getSinglePage($str);
    abstract function curlInit();
}
class spider extends spider_func{
    
    function __construct(){
        $this->ch = $this->curlInit();
    }
    public function curlInit(){
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);//将资源返回为string
        curl_setopt($curl,CURLOPT_CRLF,true);// 将Unix的换行符转换成回车换行符
        curl_setopt($curl, CURLOPT_FORBID_REUSE, true);//连接后强制断开
        curl_setopt($curl, CURLOPT_HEADER, false);//输出头文件信息
        //curl_setopt($curl, CURLOPT_POSTFIELDS, $data);//post数据发送
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//规避ssl的证书检查。
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);// 跳过host验证
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

        $this->curl_url = isset($_GET['url']) ? $_GET['url'] : $_GET['urls'];
        $this->getUrlInfo($this->curl_url);//初始化URL信息
        return $curl;
    }
    public function getCurlInfo($ch){
        $curlInfo[] = curl_getinfo($ch);
        //$curlInfo[] = curl_multi_info_read($ch);
        $curlInfo[] = curl_version();
        dump($curlInfo);
    }
    public function pathParase($baseUrl="",$strList){
        $str        = "/dsadsalkd/djas.jpg";
        $realList   = array();
        if(is_array($strList)){
            foreach ($strList as $key => $value)
                if(preg_match("/^(http:|https:)\/\//i", $value))
                    $realList[] = $value;
                else
                    if(preg_match("/^\/.+/i", $value)){
                        $base_info = parse_url(empty($_GET['url']) ? $_GET['urls'] : $_GET['url']);
                        $realList[] = $base_info['scheme'] . "://" . $base_info['host'] . $value;
                    }
        }
        return $realList;
    }
    public function getError($ch){
        $errorInfo[] = "last error number in curl:".curl_errno($ch);
        $errorinfo[] = "last error string in curl".curl_error($ch);
        $errorInfo[] = curl_strerror($ch);
        dump($errorInfo);
    }
    public function curl_trace($ch){
        curl_setopt($ch,CURLINFO_HEADER_OUT,true);
        return $ch;
    }
    public function getImg($dirname,$str){
        //$pattern = "/\"(http|https):\/\/.+\.(gif|jpg|jpeg|bmp|png)\"/i";
        //$pattern = "/Src=\"((http|https):\/\/.+(gif|jpg|jpeg|bmp|png))\"/i";
        //匹配src类型图片
        $pattern = "/Src=\"(.+?)\"/i";
        $listImg = array();
        preg_match_all($pattern,$str,$listImg);
        //匹配data-original类型图片
        $pattern1 = "/data-original=\"(.+?\.(gif|jpg|jpeg|bmp|png))\"/i";
        $listImg1 = array();
        preg_match_all($pattern1,$str,$listImg1);
        //匹配url()类型
        $pattern2 = "/url\((\"|\')(.+?)(\"|\')\)/i";
        $listImg2 = array();
        preg_match_all($pattern2,$str,$listImg2);
        if(isset($listImg1[1]))
            $arrImg = array_merge($listImg[1],$listImg1[1]);
        else
            $arrImg = $listImg[1];
        $arrImg=$this->pathParase($dirname,$arrImg);
        foreach ($arrImg as $key => $value) {//处理src中可能包含JS的情况
            if(preg_match("/.+\.js/", $value))
                unset($arrImg[$key]);
        }
        return $arrImg;
    }
    //给当前页面经行分类
    public function getTitle($str){
        $pattern = "/<title>(.+?)<\/title>/i";
        $match = array();
        preg_match($pattern,$str,$match);
        @$arr = explode('-', $match[1]);
        if(!isset($arr[1]))
            $arr = @explode('/', $match[1]);
        @$dir = $arr[1]."/".$arr[0]."/";
        $code = mb_detect_encoding($dir, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
        $dir = iconv($code, "GBK", $dir);
        return $dir;
    }
    public function showImg($arr){
        foreach ($arr as $key => $value) {
            echo "<img style='width:400px;height:400px;padding:10px;' Src={$value}></img>";
        }
    }
    public function downFile($url,$suf_name="image",$rename_rules="time",$imgSize=30,$kind=""){
        //$imgSize *=1024;
        $dir = dirname(__FILE__)."/../fileLoad/".date("Y-m-d")."/image/";
        $dir = strlen($kind) == 0 ? $dir : $dir.$kind;
        if($rename_rules=="time")
            $filename=$dir.$suf_name.date("y-m-d-H-i-s");
        if(!is_dir($dir)&&strlen($dir)>0){
            $dirRes = mkdir($dir,0777,true);
        }
        if(is_array($url)){
            foreach ($url as $key => $value) {
                $value = str_replace('"',"",$value);
                $pathInfo = pathinfo($value);
                $pathInfo['extension'] = isset($pathInfo['extension'])?$pathInfo['extension']:"jpg";
                $tempName = $filename.uniqid().".".$pathInfo['extension'];
                $len = $this->getDocLen($this->ch,$value);
                //$this->dumpa($value);
                if($len>$imgSize){
                    var_dump($imgSize);
                    $strData=file_get_contents($value);
                    file_put_contents($tempName,$strData,LOCK_EX);
                }
            }
        }
    }
    public function getSinglePage($str,$imgSize=30)
    {
        curl_setopt($this->ch, CURLOPT_URL, $str);
        curl_setopt($this->ch, CURLOPT_NOBODY, 0);
        curl_setopt($this->ch,CURLOPT_HTTPHEADER,  array('Content-type: text/plain'));
        $pageInfo=curl_exec($this->ch);
        $imgInfo=$this->getImg($this->url_scheme.$this->url_host,$pageInfo);
        echo "<br>";
        $title = $this->getTitle($pageInfo);
        //$this->showImg($imgInfo);
        $this->downFile([$str]);
        $this->downFile($imgInfo,"image","time",$imgSize,$title);
        //$this->saveHtml($str);
    }
    public function saveHtml($url,$dir=""){
        if(strlen($dir)!=0){
            $dir = iconv("UTF-8", "GBK", $dir);
        }else{
            $dir = dirname(__FILE__)."/../fileLoad/".date("Y-m-d");
        }if(!file_exists($dir)){
            mkdir($dir,0777,true);
        }
        $urlArry = pathinfo($url);
        $filename = strlen($urlArry['basename'])==0?$urlArry['dirname']:$urlArry['basename'];
        str_replace("/", "-", $filename);
        $filename.= ".html";
        $filename = $dir."/".$filename;
        var_dump($filename);
        //$fp      = @fopen($filename, "x+");
        $strData=file_get_contents($url);
        file_put_contents($filename, $strData);
    }
    //获取url信息
    private function getUrlInfo($url){
        $_parse = parse_url($url);
        $this->url_host = $_parse['host'];
        $this->url_scheme = $_parse['scheme']."://";
        $this->url_query = @$_parse['query'];
        $this->url_path = @$_parse['path'];
        unset($_parse);
    }
    public function getDocLen($ch,$url){
        //复制副本
        $curl = $ch;
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl,CURLOPT_HTTPHEADER,  array('Content-type: text/plain'));
        //不输出网页主体
        curl_setopt($curl, CURLOPT_NOBODY, 1);
        curl_setopt($curl, CURLOPT_HEADER, true);
        $res = curl_exec($curl);
        $regex = '/Content-Length:\s+(.+)?/';  
        preg_match($regex, $res, $matches);
        return @$matches[1];
    }
}
?>