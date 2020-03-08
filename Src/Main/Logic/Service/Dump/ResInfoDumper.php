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

class ResInfoDumper
{

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
}
