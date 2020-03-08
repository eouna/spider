<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2020/3/7
 * Time: 19:31
 */

namespace Model\RedisModel\ResourceCategory;


use Model\BaseModel;
use Model\SQLModel\ResourceCategory\IResourceCategory;
use Model\SQLModel\ResourceCategory\ResourceCategory as MResourceCategory;
use Tools\CPlus;

class ResourceCategory extends BaseModel
{
    private $categoryMapKey = 'categoryMap';

    /**
     * @param string $domain
     * @param string $categoryName
     * @param array $categoryData
     */
    public function updateCategoryMap(string $domain, string $categoryName, $categoryData = []){

        $categoryInfo = [];
        if(CPlus::redisHexists($this->categoryMapKey, $domain))
            $categoryInfo = CPlus::redisHget($this->categoryMapKey, $domain);

        if(isset($categoryInfo[$categoryName]) && $categoryInfo[$categoryName][2] == -1 && isset($categoryData[1])){
            $categoryInfo[$categoryName][2] = $categoryData[1];
            CPlus::redisHset($this->categoryMapKey, $domain, $categoryInfo);
        }

        if(!isset($categoryInfo[$categoryName])){
            $categoryInfo[$categoryName][0] = "category_incr_" . md5($domain . $categoryName);
            $categoryInfo[$categoryName][1] = $categoryData[0] ?? 0;            //initial num
            $categoryInfo[$categoryName][2] = $categoryData[1] ?? -1;           //mysql id
            CPlus::redisHset($this->categoryMapKey, $domain, $categoryInfo);
            MResourceCategory::saveTagsMap();
        }

        CPlus::redisIncr($categoryInfo[$categoryName][0]);
    }

    /**
     * 获取分类ID
     * @param string $domain
     * @param string $categoryName
     * @return int|null
     */
    public function getCategoryMysqlID(string $domain, string $categoryName){
        if(CPlus::redisHexists($this->categoryMapKey, $domain)){
            $categoryInfo = CPlus::redisHget($this->categoryMapKey, $domain);
            if(isset($categoryInfo[$categoryName][2]))
                return $categoryInfo[$categoryName][2];
        }
        return null;
    }

    /**
     * @return array
     */
    public function getAllCategoryInfo(){
        $categoryInfoMap = [];
        $categoryInfoData = CPlus::redisHgetall($this->categoryMapKey);
        foreach ($categoryInfoData as $domain => $categoryInfo){
            foreach ($categoryInfo as $categoryName => $categoryData){
                list($curNumKey, $curNum, $mysqlID) = $categoryData;
                $iResourceCategory = new IResourceCategory();
                $iResourceCategory->domain = $domain;
                $iResourceCategory->cur_num = CPlus::redisGet($curNumKey) + $curNum;
                $iResourceCategory->id = $mysqlID;
                $iResourceCategory->category_name = $categoryName;
                $categoryInfoMap[] = $iResourceCategory;
            }
        }
        return $categoryInfoMap;
    }
}
