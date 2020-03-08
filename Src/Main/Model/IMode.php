<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2020/3/4
 * Time: 22:37
 */

namespace Model;


interface IMode
{
    /**
     * @return array
     */
    public function toArray():array ;

    /**
     * @return string
     */
    public function toJson():string ;
}
