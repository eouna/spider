<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/9/9
 * Time: 10:20
 */

namespace Logic;

use GatewayWorker\Lib\Gateway;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Logic\Service\Dump\ResInfoDumper;
use Model\BaseModel;
use Model\Link\LinkInfo;
use Model\Link\ResourceLinkInfo;
use Model\RedisModel\Resource\ImageLinkModel;
use Model\RedisModel\ResourceCategory\ResourceCategory;
use Model\RedisModel\SiteCollectModel;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use Qcloud\Cos\Exception\CurlException;
use Tools\CPlus;
use Tools\Guzzle;

class UrlDealFactoryMemoryLeak
{
    const TAG_AUDIO = 'audio';
    const TAG_VIDEO = 'video';
    const TAG_IMAGE = 'image';
    const TAG_LINK = 'link';

    private $url_list = [];
    private $img_list = [];
    private $source_list = [];
    private $siteName = '';
    private $config = [];
    private $site_info = [];

    /**
     * 当前主站URL
     * @var string $linkRootUrl
     */
    private $linkRootUrl;

    /**
     * 当前主站URL域名
     * @var string $linkRootUrl
     */
    private $linkRootUrlDomain;

    /**
     * 是否开启无限制链接爬取
     * @var bool
     */
    private $isOpenNoLimit = false;

    /**
     * @var Dom $dom
     * */
    private $dom;

    /**
     * @var Client $client
     * */
    private $client;

    private static $instance;

    public function __construct($section_name = ConfigLoader::IMG_SITE_GROUP)
    {
        require_once __DIR__ . "/../../../vendor/eouna/pack-binary/autoload.php";
        $this->config = ConfigLoader::configBySectionName($section_name);
        $this->client = new Client();
        $this->dom = new Dom();
        $this->site_info = parse_url($this->config['url']);
    }

    /**
     * @param string $section_name
     * @return UrlDealFactoryMemoryLeak
     * */
    public static function make($section_name = ConfigLoader::IMG_SITE_GROUP)
    {
        if (!(self::$instance instanceof self))
            self::$instance = new self($section_name);
        self::$instance->url_list = [];
        return self::$instance;
    }

    /**
     * @param bool $isOpenNoLimit
     */
    public function setIsOpenNoLimit(bool $isOpenNoLimit)
    {
        $this->isOpenNoLimit = $isOpenNoLimit;
    }

    /**
     * 配置表
     * @param array $options
     * @return UrlDealFactoryMemoryLeak
     * */
    public function config($options = [])
    {
        if (!empty($options))
            $this->config = array_merge($this->config, $options);
        return $this;
    }

    /**
     * @param int $depth
     * *@throws
     */
    public function go(int $depth = 0)
    {
        array_push($this->url_list, [$this->config['url'], 'base_url']);
        $parseUrl = parse_url($this->config['url']);
        $this->linkRootUrl = $parseUrl['scheme'] . "://" . $parseUrl['host'];
        $this->linkRootUrlDomain = $parseUrl['host'];
        $sMicroTime = microtime(true);
        $sMemory = number_format((float)(memory_get_usage() / 1048576), 2);
        dump_vars("当前进程ID：\033[36m". posix_getpid() ." \033[m\033[255;255;255m占用内存：" . $sMemory . 'Mb');
        $this->doCollection($depth, $this->url_list);
        $eMicroTime = microtime(true);
        $eMemory = number_format((float)(memory_get_usage() / 1048576), 2);
        $tookMemory = $eMemory - $sMemory;
        $tookTime = number_format((float)($eMicroTime - $sMicroTime), 2);
        $tookMemoryMessage = $tookMemory < 0 ? "\033[m\033[255;255;255m@\033[38释放\033[m\033[255;255;255m" : "\033[m\033[255;255;255m消耗";
        dump_vars("当前进程ID：\033[32m". posix_getpid() . " {$tookMemoryMessage}内存：" . abs($tookMemory) . 'Mb 当前占用内存：' . $eMemory . 'Mb 共耗时：' . $tookTime . 's');
        $this->finished();
        return;
    }

    /**
     * 收集html中的链接和指定的资源
     * @param int $depth
     * @param $url_list
     * @throws
     * */
    private function doCollection(int $depth, $url_list)
    {
        if ($depth > $this->config['depth'] || empty($url_list)) return;
        foreach ($url_list as $link) {
            list($request_url, $url_title) = $link;
            try{
                $response = transToUTF8($this->client->get($request_url, ['verify' => false])->getBody()->getContents());
                $sMemory = number_format((float)(memory_get_usage() / 1048576), 2);
                dump_vars("Cur Process ID：\033[36m". posix_getpid() ." \033[m\033[255;255;255mOccupation Memory：" . $sMemory . 'Mb');
                $dom = $this->dom->loadStr($response);
                $sMemory = number_format((float)(memory_get_usage() / 1048576), 2);
                dump_vars("Cur Process ID：\033[36m". posix_getpid() ." \033[m\033[255;255;255mOccupation Memory：" . $sMemory . 'Mb');
                $siteTitle = getTitle($response);
                !empty($this->siteName) ?: $this->siteName = mb_substr($siteTitle, (int)(strripos($siteTitle, '_')));
                $this->dealLink($dom, $siteTitle);
                $this->resourceDispatcher($dom, $siteTitle);
                unset($dom);
                $sMemory = number_format((float)(memory_get_usage() / 1048576), 2);
                dump_vars("Cur Process ID：\033[36m". posix_getpid() ." \033[m\033[255;255;255mOccupation Memory：" . $sMemory . 'Mb');
            }catch (RequestException $exception){
                dump_vars($exception->getMessage());
                continue 1;
            }
        }
        unset($url_list);
    }

    private function resourceDispatcher(Dom $dom, string $title){
        if(!empty($this->config))

            foreach ($this->config['type'] as $type => $typeValue){
                switch ($type){
                    case self::TAG_IMAGE:
                        $this->dealImage($dom, $title);
                        break;
                    case self::TAG_VIDEO:
                    case self::TAG_AUDIO:
                        $this->dealSource($dom, $title, $type);
                        break;
                }
            }
    }

    /**
     * @param Dom $dom
     * @param string $siteTitle
     * @throws ChildNotFoundException
     * @throws NotLoadedException
     */
    public function dealLink(Dom $dom, string $siteTitle)
    {
        try {
            $urlCollect = [];
            $linkModel = new SiteCollectModel($this->linkRootUrl);
            $sMemory = number_format((float)(memory_get_usage() / 1048576), 2);
            dump_vars("当前进程ID：\033[36m". posix_getpid() ." \033[m\033[255;255;255m占用内存：" . $sMemory . 'Mb');
            $dom->find("a")->each(function (Dom\AbstractNode $node) use ($siteTitle, &$urlCollect, $linkModel) {
                $linkTitle = trim($node->text());
                $linkInfo = new LinkInfo();
                $linkInfo->linkTitle = empty($linkTitle) ? $siteTitle : $linkTitle;
                $linkNodAttr = $node->getTag()->getAttribute("href");
                $linkStr = trim($linkNodAttr['value']);
                if(!isset($linkStr[0]) || $linkStr == 'javascript:;')
                    return;
                $parseLink = parse_url($linkNodAttr['value']);
                if(!isset($parseLink['host']) || !isset($parseLink['path']) || $parseLink['host'] != $this->linkRootUrlDomain)
                    return;
                $linkInfo->url = $parseLink['path'] . ($parseLink['query'] ?? '') . ($parseLink['fragment'] ?? '');
                if(isset($urlCollect[$linkInfo->url]))
                    return;
                $urlCollect[$linkInfo->url] = md5($linkInfo->url);
                $linkInfo->type = BaseModel::LINK_LIST;
                $linkModel->saveLink($linkInfo);
                unset($linkInfo);
                //Gateway::sendToCurrentClient(CPlus::success(10002, 'Dealing Link：' . $linkInfo->url));
            });
            unset($linkModel);
            dump_vars('解析完页面为：' . $this->config['url'] . ' 的链接........');
            $sMemory = number_format((float)(memory_get_usage() / 1048576), 2);
            dump_vars("当前进程ID：\033[36m". posix_getpid() ." \033[m\033[255;255;255m占用内存：" . $sMemory . 'Mb');
        } catch (StrictException $exception) {
            dump_vars($exception->getMessage());
        } catch (CurlException $exception) {
            dump_vars($exception->getMessage());
        }
    }

    /**
     * @param Dom $dom
     * @param string $url_title
     * @throws
     * */
    public function dealImage(Dom $dom, string $url_title)
    {
        try {

            //dump_vars('开始处理URL：' . $this->linkRootUrl . ' 的图像资源........');
            $imageModel = new ImageLinkModel($this->linkRootUrl);

            $dom->find('img')->each(function (Dom\AbstractNode $node) use ($url_title, $imageModel) {

                $linkInfo = new ResourceLinkInfo();

                $source_path = $node->getTag()->getAttribute('src');
                $sourceTitle = $node->getTag()->getAttribute('title');
                if (empty($source_path) || !isset($source_path['value']) || isset($this->img_list[md5($source_path['value'])]) || strpos($source_path['value'], ';base64,'))
                    return;

                $linkInfo->linkTitle = empty($sourceTitle['value']) ? $url_title : $sourceTitle['value'];
                $linkInfo->type = BaseModel::IMG_LIST;

                $source_path = isAbsolutePath($source_path['value']) ? $source_path['value'] : $this->site_info['scheme'] . "://" . $this->site_info['host'] . $source_path['value'];
                $linkInfo->url = $linkInfo->resourceUrl = $source_path;
                if($imageModel->linkExists($linkInfo))
                   return;

                $response = Guzzle::getHeader($source_path);
                $image_size = $response->getHeader("Content-Length");
                if (($this->config['filter'] && isset($image_size[0]) && $image_size[0] >= $this->config['filter']) || $this->config['filter'] == 0) {

                    $img_flag = md5($source_path);
                    $date = date('Ymd');
                    $file_info = pathinfo($source_path);

                    if (!isset($file_info['extension']) || !preg_match("/(jpg|png|webp|exif|svg|tif|icn|gif)/i", $file_info['extension']))
                        return null;

                    $linkInfo->fileType = $file_info['extension'];
                    $linkInfo->relativeUrl = 'img/' . $this->siteName . '/' . $date . '/' . $img_flag . "." . $file_info['extension'];
                    $linkInfo->localUrl = __RESOURCE__ . $linkInfo->relativeUrl;

                    $explodeSiteNameArr = explode("-", $this->siteName);
                    $linkInfo->categoryName =  $explodeSiteNameArr[1] ?? $explodeSiteNameArr[0];
                    $linkInfo->domain = parse_url($linkInfo->resourceUrl)['host'];

                    (new ResourceCategory())->updateCategoryMap($linkInfo->domain, $linkInfo->categoryName);
                    $linkInfo->resourceSize = $image_size[0];
                    //ResInfoDumper::ResourceCliDumper($linkInfo);
                    $linkInfo->resourceId = $imageModel->saveDataIntoMysql($linkInfo);

                    $imageModel->saveLink($linkInfo);
                }
            });
            //dump_vars('图片资源处理结束........');
            unset($dom);
        } catch (StrictException $exception) {
            dump_vars($exception->getMessage());
        } catch (ClientException $exception) {
            dump_vars($exception->getMessage());
        }
    }

    /**
     * @param Dom $dom
     * @param string $url_title
     * @param string $tag
     * @throws
     * */
    public function dealSource(Dom $dom, string $url_title, $tag = 'audio')
    {
        try {
            dump_vars("start deal source");
            $dom->find($tag)->each(function (Dom\AbstractNode $node) use ($url_title) {
                $source_path = $node->getTag()->getAttribute('src');
                if (empty($source_path) || isset($this->audio_list[md5($source_path['value'])]))
                    return null;
                $source_path = isAbsolutePath($source_path['value']) ? $source_path['value'] : $this->site_info['scheme'] . "://" . $this->site_info['host'] . $source_path['value'];
                $client = new Client();
                try {
                    $response = $client->get($source_path, [
                        CURLOPT_HTTPHEADER => ['Content-type: text/plain'],
                        CURLOPT_NOBODY => 1,
                        CURLOPT_HEADER => true,
                    ]);
                } catch (ConnectException $exception) {
                    $response = $client->get($source_path, [
                        CURLOPT_HTTPHEADER => ['Content-type: text/plain'],
                        CURLOPT_NOBODY => 1,
                        CURLOPT_HEADER => true,
                    ]);
                }catch (RequestException $requestException){
                    return null;
                }
                $file_size = $response->getHeader("Content-Length");
                if (($this->config['filter'] && isset($file_size[0]) && $file_size[0] >= $this->config['filter']) || $this->config['filter'] == 0) {
                    $save_flag = md5($source_path);
                    $date = date("Ymd");
                    $file_info = pathinfo($source_path);
                    if (!isset($file_info['extension']))
                        return null;
                    $local_path = [
                        __DIR__ . $this->config['save_path'] . 'audio/' . $date . "/",
                        $date . "-" . $save_flag . "." . $file_info['extension'],
                        $date . "/" . $date . "-" . $save_flag . "." . $file_info['extension'],
                        $file_info['extension']
                    ];
                    $this->source_list[$save_flag] = [$source_path, empty($source_title) ? $url_title : $source_title['value'], $local_path[0] . $local_path[1]];
                    dump_vars("\r\n正在拉取资源：" . $local_path[1] . ": " . floor($file_size[0] / 1024) . "Kb  名称：" . $this->source_list[$save_flag][1]);
                    Gateway::sendToCurrentClient(CPlus::success(10002,
                        "\r\n正在拉取资源：" . $local_path[1] . ": " . floor($file_size[0] / 1024) . "Kb  名称：" . $this->source_list[$save_flag][1]
                    ));
                    //FileHandler::save($source_path, $local_path);
                }
            });
            dump_vars("end deal image");
            unset($dom);
        } catch (StrictException $exception) {
            dump_vars($exception->getMessage());
        } catch (CurlException $exception) {
            dump_vars($exception->getMessage());
        }
    }

    /**
     * @return void
     */
    private function finished(){
        $this->dom = null;
        $this->client = null;
        self::$instance = null;
        dump_vars('释放处理工厂实例！');
        $sMemory = number_format((float)(memory_get_usage() / 1048576), 2);
        dump_vars("当前进程ID：\033[36m". posix_getpid() ." \033[m\033[255;255;255m占用内存：" . $sMemory . 'Mb');
    }
}
