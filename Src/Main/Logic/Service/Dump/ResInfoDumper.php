<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2020/3/5
 * Time: 21:53
 */

namespace Logic\Service\Dump;

use Model\BaseModel;
use Model\Link\ResourceLinkInfo;
use Tools\CPlus;
use function GuzzleHttp\Psr7\str;

class ResInfoDumper
{

    private static $feedLineStr = "\r\n";

    private static $HT = "\t";

    /**
     * @var float $frameRate
     */
    private $frameRate = 0.5;

    /**
     * @var self $instance
     */
    protected static $instance;

    /**
     * @var int $screenWidth
     */
    protected $screenWidth = 100;

    /**
     * @var array $outPutStr
     */
    protected $outPutStr = '';

    /**
     * @var bool $withFrame
     */
    protected $withFrame = true;

    /**
     * @var array $outputData;
     */
    protected $outputData;

    /**
     * @var string $frameTitle
     */
    protected $frameTitle = "信息提示框";

    /**
     * ResInfoDumper constructor.
     */
    public function __construct()
    {
        $this->initialScreenWidth();
    }

    /**
     * @return ResInfoDumper
     */
    public static function make(){
        if(!(self::$instance instanceof self))
            self::$instance = new self;
        self::$instance->outputData = [];
        return self::$instance;
    }

    /**
     * @param ResourceLinkInfo $resourceLinkInfo
     * @return string
     */
    public static function ResourceCliDumper(ResourceLinkInfo $resourceLinkInfo){
        return dump_cli(
            "\r\n+-------------------------资源信息----------------------------------------------------------".
            "\r\n|    资源地址\t：" . $resourceLinkInfo->resourceUrl .
            "\r\n|    资源类型\t：" . BaseModel::getTypeAlias($resourceLinkInfo->type) .
            "\r\n|    资源大小\t: " . CPlus::getFileSizeDesc($resourceLinkInfo->resourceSize) .
            "\r\n|    资源名称\t：" . $resourceLinkInfo->linkTitle .
            "\r\n|    本地地址\t: " . $resourceLinkInfo->localUrl .
            "\r\n+-------------------------------------------------------------------------------------------");
    }

    /**
     * @return ResInfoDumper
     */
    private function initialScreenWidth(){
        preg_match('/(\d{0,5}).+?/i',shell_exec("stty size|awk '{print $2}'"), $screenWidth);
        if(!empty($screenWidth))
            $this->screenWidth = $screenWidth[0] - 1;
        return self::$instance;
    }

    /**
     * @param bool $withFrame
     * @return ResInfoDumper
     */
    public function setWhetherFrame(bool $withFrame = true){
        $this->withFrame = $withFrame;
        return self::$instance;
    }

    /**
     * @param array $outPutData
     * @return self
     */
    public function withOutPutData(array $outPutData){
        $this->outputData = $outPutData;
        return self::$instance;
    }

    /**
     * @param string $frameTitle
     * @return ResInfoDumper
     */
    public function setFrameTitle(string $frameTitle)
    {
        $this->frameTitle = $frameTitle;
        return self::$instance;
    }

    /**
     * @param int $frameRate
     * @return ResInfoDumper
     */
    public function setFrameRate(int $frameRate)
    {
        $this->frameRate = $frameRate * 0.01;
        return self::$instance;
    }

    /**
     * 输出数据
     */
    public function cliPrint(){
        if(!empty($this->outputData)){
            $dataOutputStr = '';
            $maxLengthKVStr = $maxLengthKeyStr = -1;
            array_walk($this->outputData, function ($value, $key) use (&$maxLengthKeyStr){
                $keyLen = CPlus::countStrLen($key);
                return $keyLen <= $maxLengthKeyStr ?: $maxLengthKeyStr = $keyLen;
            });
            foreach ($this->outputData as $keyStr => $value){
                if(!is_object($value) || !is_array($value)){
                    $keyStrLen = CPlus::countStrLen($keyStr);
                    $keyStrSpaceNum = $maxLengthKeyStr - $keyStrLen;
                    $valueStr = self::$feedLineStr . "|    ". $keyStr . str_pad('', $keyStrSpaceNum) . " ：" . $value;
                    CPlus::countStrLen($valueStr) <= $maxLengthKVStr ?: $maxLengthKVStr = CPlus::countStrLen($valueStr);
                    $dataOutputStr .= $valueStr;
                }
            }
            if(!empty($dataOutputStr)){
                $titleLen = CPlus::countStrLen($this->frameTitle);
                $this->screenWidth = ceil($this->screenWidth * ($maxLengthKVStr / $this->screenWidth + 0.1));
                $startLinePreLen = ceil(($this->screenWidth - $titleLen) * $this->frameRate);
                $startLinePre = self::$feedLineStr . "+" . str_pad("", $startLinePreLen, "-");
                $startLineNxt = str_pad("", ($this->screenWidth - $startLinePreLen - $titleLen), "-");
                $endLine = self::$feedLineStr . "+" . str_pad("", $this->screenWidth, "-");
                $outputStr = $startLinePre .
                    $this->frameTitle .
                    $startLineNxt .
                    $dataOutputStr .
                    $endLine;
                $this->outPutStr = $outputStr;
                var_dump($this->outPutStr);
            }
        }
        return self::$instance;
    }

    /**
     * 将消息推送的客户端
     */
    public function pushToClient(){
        CPlus::broadcastToOnlineClient($this->outPutStr);
    }
}
