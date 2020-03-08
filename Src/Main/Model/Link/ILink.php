<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2020/3/4
 * Time: 18:20
 */

namespace Model\Link;


interface ILink
{
    /**
     * @return string
     */
    public function formatJson();

    /**
     * @return mixed
     */
    public function formatData();
}
