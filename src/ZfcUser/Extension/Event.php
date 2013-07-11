<?php

namespace ZfcUser\Extension;

use Zend\EventManager;

class Event extends EventManager\Event
{
    const EXTENSION_LOAD = 'extension.load';

    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @param \ZfcUser\Extension\Manager $manager
     * @return $this
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
        return $this;
    }

    /**
     * @return \ZfcUser\Extension\Manager
     */
    public function getManager()
    {
        return $this->manager;
    }
}
