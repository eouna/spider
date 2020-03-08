<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2020/3/7
 * Time: 18:02
 */

namespace Model\SQLModel\ResourceCategory;


use Model\IMode;

class IResourceCategory implements IMode
{
    public $id;
    public $category_name;
    public $created_at;
    public $updated_at;
    public $domain;
    public $click_times;
    public $cur_num;
    private $tableNameString = "`id`,`category_name`,`created_at`,`updated_at`,`domain`,`click_times`,`cur_num`";

    /**
     * @return string
     */
    public function getTableNameString(): string
    {
        return $this->tableNameString;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        // TODO: Implement toArray() method.
        $config['id'] = $this->id ?? '';
        $config['category_name'] = $this->category_name ?? '';
        $config['created_at'] = $this->created_at ?? '';
        $config['updated_at'] = $this->updated_at ?? '';
        $config['domain'] = $this->domain ?? '';
        $config['click_times'] = $this->click_times ?? '';
        $config['cur_num'] = $this->cur_num ?? '';
        return  $config;
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        // TODO: Implement toJson() method.
        return json_encode($this->toArray());
    }
}
