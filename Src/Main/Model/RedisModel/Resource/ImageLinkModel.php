<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2020/3/4
 * Time: 17:57
 */

namespace Model\RedisModel\Resource;

use Model\BaseModel;
use Model\Link\ResourceLinkInfo;
use Model\RedisModel\ResourceCategory\ResourceCategory;
use Model\SQLModel\Resource\IResource;
use Model\SQLModel\Resource\Resource;

class ImageLinkModel extends BaseModel
{

    /**
     * @param ResourceLinkInfo $linkInfo
     * @return mixed
     */
    public function saveDataIntoMysql(ResourceLinkInfo $linkInfo){
        $time = time();
        $IResource = new IResource();
        $IResource->file_name = $linkInfo->linkTitle;
        $IResource->file_size = $linkInfo->resourceSize;
        $IResource->created_at = $time;
        $IResource->file_type = $linkInfo->fileType;
        $IResource->is_available = 0;
        $IResource->local_url = $linkInfo->localUrl;
        $IResource->native_url = $linkInfo->resourceUrl;
        $IResource->updated_at = $time;
        $IResource->query_count = 0;
        $IResource->category_ids =  (new ResourceCategory())->getCategoryMysqlID($linkInfo->domain, $linkInfo->categoryName);
        $res = (new Resource())->saveResource($IResource);
        if(is_int($res) && $res != 0)
            return $res;
        return 0;
    }
}
