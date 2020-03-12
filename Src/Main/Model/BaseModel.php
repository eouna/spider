<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/9/9
 * Time: 9:58
 */

namespace Model;

use Model\Link\LinkInfo;
use Model\RedisModel\Resource\ResourceQueueModel;
use Tools\CPlus;

class BaseModel
{
    const _DEFAULT = '默认资源';
    const SITE_MAP = '站点地图';
    const LINK_LIST = '链接地址保存';
    const IMG_LIST = '图像地址保存';
    const VIDEO_LIST = '视频地址保存';
    const AUDIO_LIST = '音频地址保存';
    const SOURCE_LIST = '资源地址保存';
    const JS_DATA = 'js数据';
    const CSS_DATA = 'css数据';
    const HTML_ENTITY = 'html内容';
    const URL_RECORD = '链接地址记录';
    const FAILURE_GET_LOG = '加载错误日志';
    const PASSED_RECORD = '爬虫走过的链接';

    protected $modelTable = [
        self::SITE_MAP          => 'site_map',
        self::LINK_LIST         => 'link_list',
        self::IMG_LIST          => 'img_link',
        self::VIDEO_LIST        => 'video_link',
        self::AUDIO_LIST        => 'audio_link',
        self::SOURCE_LIST       => 'source_link',
        self::JS_DATA           => 'js_link',
        self::CSS_DATA          => 'css_link',
        self::HTML_ENTITY       => 'html_date',
        self::URL_RECORD        => 'url_history_record',
        self::FAILURE_GET_LOG   => 'failure_task_log',
        self::PASSED_RECORD     => 'spider_walker',
    ];

    private static $modelAlias = [
        self::LINK_LIST         => '链接',
        self::IMG_LIST          => '图片资源',
        self::VIDEO_LIST        => '视频资源',
        self::AUDIO_LIST        => '音频资源',
        self::JS_DATA           => 'JS脚本资源',
        self::CSS_DATA          => 'CSS样式资源',
        self::HTML_ENTITY       => 'HTML网页资源',
        self::_DEFAULT          => self::_DEFAULT,
    ];

    protected $tableChip = [
        self::LINK_LIST         => 1000,
        self::IMG_LIST          => 1000,
        self::VIDEO_LIST        => 1000,
        self::AUDIO_LIST        => 1000,
        self::SOURCE_LIST       => 1000,
        self::JS_DATA           => 1000,
        self::CSS_DATA          => 1000,
        self::HTML_ENTITY       => 1000,
        self::URL_RECORD        => 1000,
        self::FAILURE_GET_LOG   => 1000,
        self::PASSED_RECORD     => 10000,
    ];

    public $table;

    private $page_suffix = '_page';

    protected $lindCountSuffix = '_count';

    protected $linkQueueSuffix = '_queue';

    protected $rootUrl = '';

    protected $nativeUrl = '';

    protected $type;

    public function __construct($siteRootUrl = '')
    {
        if (!empty($siteRootUrl)) {
            $this->rootUrl = md5($siteRootUrl);
            $this->nativeUrl = $siteRootUrl;
            if (!CPlus::redisHexists($this->modelTable[self::URL_RECORD], $siteRootUrl))
                CPlus::redisHset($this->modelTable[self::URL_RECORD], $siteRootUrl, $this->rootUrl);
        }
    }

    /**
     * @param $key
     * @return $this
     */
    protected function table($key){
        $this->table = $this->rootUrl . bcmod((string)crc($key), ($this->tableChip[$this->type] ?? 1000));
        return $this;
    }

    /**
     * @param $key
     * @return string
     */
    private function getPassedTable($key){
        return $this->rootUrl . bcmod((string)crc($key), $this->tableChip[self::PASSED_RECORD]);
    }
        /**
     * @param string $type
     * @return mixed
     */
    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    protected function get(){
        return $this->table;
    }

    /**
     * @param LinkInfo $linkInfo
     * @return bool
     */
    public function saveLink(LinkInfo $linkInfo){
        $imageLinkTable = $this->table($linkInfo->url)->get();
        $passLinkTable = $this->getPassedTable($linkInfo->url);
        if(!CPlus::redisHexists($imageLinkTable, $linkInfo->url) && !CPlus::redisHexists($passLinkTable, $linkInfo->url)){
            CPlus::redisHset($imageLinkTable, $linkInfo->url, $linkInfo);
            CPlus::redisHset($passLinkTable, $linkInfo->url, $linkInfo->linkTitle);
            CPlus::redisIncr($this->rootUrl . $this->lindCountSuffix);
            $linkInfo->url = $this->nativeUrl . $linkInfo->url;
            $resourceQueueModel = (new ResourceQueueModel());
            $resourceQueueModel->setType($linkInfo->type)->pushLinkQueue($linkInfo);
            unset($resourceQueueModel);
            return true;
        }
        return false;
    }

    /**
     * @param LinkInfo $linkInfo
     * @return bool
     */
    public function linkExists(LinkInfo $linkInfo){
        $imageLinkTable = $this->table($linkInfo->url)->get();
        return CPlus::redisHexists($imageLinkTable, $linkInfo->url);
    }

    /**
     * @param string $type
     * @return string
     */
    public static function getTypeAlias(string $type): string
    {
        if(isset(self::$modelAlias[$type]))
            return self::$modelAlias[$type];
        dump_vars('未设置此类资源：' . $type . ' 的别名');
        return self::$modelAlias[self::_DEFAULT];
    }

}
