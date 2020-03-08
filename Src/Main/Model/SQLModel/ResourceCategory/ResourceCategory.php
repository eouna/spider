<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2020/3/7
 * Time: 17:48
 */

namespace Model\SQLModel\ResourceCategory;

use Model\SQLModel\SqlBaseModel;
use \Model\RedisModel\ResourceCategory\ResourceCategory as RResourceCategory;
use Tools\CPlus;

class ResourceCategory extends SqlBaseModel
{
    /**
     * 标签MAP
     * @var array
     */
    private static $tagsMap = [];

    /**
     * 新增标签MAP
     * @var array
     */
    private static $newAddTags = [];

    /**
     * @return mixed
     */
    public function getAllTags(){
        return $this->query->select(['id','cur_num','category_name', 'domain'])
            ->from($this->table)->query();
    }

    /**
     * @return array
     */
    public static function initTagsMap(): array
    {
        if(empty(self::$tagsMap)){
            $sqlData = (new ResourceCategory())->getAllTags();
            foreach ($sqlData as $idx => $data){
                $categoryData = ['cur_num' => $data['cur_num'], 'id' => $data['id']];
                self::$tagsMap[$data['domain']][$data['category_name']] = $categoryData;
                (new RResourceCategory())->updateCategoryMap($data['domain'], $data['category_name'], array_values($categoryData));
            }
        }
        return self::$tagsMap;
    }

    private static function updateNewAddMap(){
        $allCategoryData = (new RResourceCategory())->getAllCategoryInfo();
        foreach ($allCategoryData as $key => $iResourceCategory){
            if($iResourceCategory instanceof  IResourceCategory){
                if(empty($iResourceCategory->category_name)) continue;
                $data = ['cur_num' => $iResourceCategory->cur_num, 'id' => $iResourceCategory->id];
                if(!isset(self::$tagsMap[$iResourceCategory->domain][$iResourceCategory->category_name])){
                    self::$newAddTags[$iResourceCategory->domain][$iResourceCategory->category_name] = $data;
                }
                self::$tagsMap[$iResourceCategory->domain][$iResourceCategory->category_name] = $data;
            }
        }
    }

    /**
     * 回存标签数据
     */
    public static function saveTagsMap(){
        #处理新增数据
        $resourceCategoryInstance = new ResourceCategory();
        $iResourceCategoryInstance = new IResourceCategory();
        self::updateNewAddMap();
        if(!empty(self::$newAddTags)){
            $insertString = 'INSERT INTO ' . $resourceCategoryInstance->table;
            $insertNameStr = '( ' . str_replace("`id`,", '', $iResourceCategoryInstance->getTableNameString()) . ' )';
            $valueStr = " VALUES ";
            $values = '';
            foreach (self::$newAddTags as $domain => $value){
                foreach ($value as $categoryName => $data){
                    $time = time();
                    $curNum = self::$tagsMap[$domain][$categoryName]['cur_num'];
                    $values .= "('{$categoryName}',{$time},{$time},'{$domain}',0,{$curNum}),";
                }
            }
            $values = substr($values, 0, strlen($values) - 1);
            $sqlStr = $insertString . $insertNameStr . $valueStr . $values;
            $resourceCategoryInstance->query->query($sqlStr);
            self::$newAddTags = null;
            self::initTagsMap();
        }
        if(!empty(self::$tagsMap)){
            $insertString = 'UPDATE ' . $resourceCategoryInstance->table;
            $setCurNumValues = '
SET cur_num = CASE id
';
            $setUpdatedAtValues = 'updated_at = CASE id
';
            $whenIDThenCurNum = $whenIDThenUpdatedAt = '';
            $where = 'END
WHERE id IN(';
            $ids = '';
            foreach (self::$tagsMap as $domain => $value){
                foreach ($value as $categoryName => $data){
                    $ids .= $data['id'] . ',';
                    $whenIDThenCurNum .= "WHEN {$data['id']} THEN {$data['cur_num']}
";
                    $time = time();
                    $whenIDThenUpdatedAt .= "WHEN {$data['id']} THEN '{$time}'
";
                }
            }
            $ids = substr($ids, 0, strlen($ids) - 1) . ')';
            $sqlStr = $insertString . $setCurNumValues . $whenIDThenCurNum . "END,
" .
                $setUpdatedAtValues . $whenIDThenUpdatedAt . $where . $ids;
            $resourceCategoryInstance->query->query($sqlStr);
        }
    }
}
