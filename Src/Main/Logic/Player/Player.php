<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2020/3/2
 * Time: 20:32
 */

namespace Logic\Player;

use Logic\Event\EventDispatcher;

class Player
{
    /** @var EventDispatcher $eventDispatcher*/
    private $eventDispatcher;

    public function __construct()
    {

    }

    private function init(){
        $this->eventDispatcher = new EventDispatcher();
    }

    /**
     * @return EventDispatcher
     */
    public function getEventDispatcher(): EventDispatcher
    {
        return $this->eventDispatcher;
    }

}
