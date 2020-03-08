<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2020/3/4
 * Time: 22:33
 */

namespace Model\SQLModel\Resource;


use Model\IMode;

class IResource implements IMode
{
    public $id;
    public $native_url;
    public $cos_url;
    public $local_url;
    public $category_ids;
    public $file_type;
    public $file_size;
    public $file_name;
    public $query_count;
    public $is_available;
    public $created_at;
    public $updated_at;

    /**
     * @return string
     */
    public function toJson(): string
    {
        // TODO: Implement toJson() method.
        return json_encode($this->toArray());
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        // TODO: Implement toArray() method.
        $config['native_url'] = $this->native_url ?? '';
        $config['cos_url'] = $this->cos_url ?? '';
        $config['local_url'] = $this->local_url ?? '';
        $config['category_ids'] = $this->category_ids ?? '';
        $config['file_type'] = $this->file_type ?? '';
        $config['file_size'] = $this->file_size ?? '';
        $config['file_name'] = $this->file_name ?? '';
        $config['query_count'] = $this->query_count ?? '';
        $config['is_available'] = $this->is_available ?? '';
        $config['created_at'] = $this->created_at ?? '';
        $config['updated_at'] = $this->updated_at ?? '';
        return $config;
    }
}
