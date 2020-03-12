<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2020/3/12
 * Time: 9:47
 */

namespace Model\RedisModel\ResourceCategory;


use Config\Enum\ELock;
use Tools\CPlus;

class ResourceLock
{

    /**
     * @return bool
     */
    public static function getLockStatus(){
        return CPlus::redisGet(ELock::L_INSERT_CATEGORY_INCR) % 2 ? true : false;
    }

    /**
     * 目录插入时加锁
     */
    public static function updateLockStatus(){
        CPlus::redisIncr(ELock::L_INSERT_CATEGORY_INCR);
        CPlus::redisIncr(ELock::L_INSERT_CATEGORY_INCR);
    }

    /**
     * @param string $categoryName
     * @param string $domain
     * @return mixed
     */
    public static function insertCategoryInCache(string $categoryName, string $domain){
        return CPlus::redisHset(ELock::L_RESTORE_CATEGORY_CACHE, $domain . $categoryName, microtime(true));
    }

    /**
     * @param string $categoryName
     * @param string $domain
     * @return bool
     */
    public static function categoryWhetherInCache(string $categoryName, string $domain){
        return CPlus::redisHexists(ELock::L_RESTORE_CATEGORY_CACHE, $domain . $categoryName);
    }
}
