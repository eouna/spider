<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/3/2
 * Time: 19:13
 */

namespace Src\Facade;

use Src\Provider\PageManageProvider;

/**
 * @method PageManageProvider table(string $table)
 * @method int offset(int $offset)
 * @method int limit(int $limit)
 * @method void checkTableName()
 * @method int getTotalPage()
 * @method int count()
 * @method int getCurPage()
 * @method array nextPage()
 * @method array get()
 * @see PageManageProvider
 */

class Page  extends Facades
{
    /**
     * 获取注册容器别名
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'paginate';
    }
}
