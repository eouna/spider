<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2020/3/4
 * Time: 11:38
 */

namespace Model\Link;


class LinkInfo implements ILink
{
    public $resourceId;
    public $url;
    public $linkTitle;
    public $name;
    public $type;
    protected $linkData;

    /**
     * @param mixed $linkData
     */
    public function setLinkData($linkData)
    {
        $this->linkData = $linkData;
    }

    /**
     * @return mixed
     */
    public function getLinkData()
    {
        return $this->linkData;
    }


    /**
     * @return string
     */
    public function formatJson()
    {
        // TODO: Implement formatJson() method.
    }

    /**
     * @return mixed
     */
    public function formatData()
    {
        // TODO: Implement formatData() method.
    }
}
