<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2020/3/4
 * Time: 22:28
 */

namespace Model\SQLModel\Resource;

use Model\SQLModel\SqlBaseModel;

class Resource extends SqlBaseModel
{
    /**
     * @param IResource $IResource
     * @return mixed
     */
    public function saveResource(IResource $IResource){
        return $this->query->insert($this->table)
            ->cols($IResource->toArray())
            ->query();
    }
}
