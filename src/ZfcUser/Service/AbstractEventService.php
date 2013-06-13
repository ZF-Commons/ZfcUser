<?php

namespace ZfcUser\Service;

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

abstract class AbstractEventService implements EventManagerAwareInterface
{
    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * {@inheritDoc}
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->setIdentifiers(array(__CLASS__, get_class($this)));
        $this->eventManager = $eventManager;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getEventManager()
    {
        if (!$this->eventManager instanceof EventManagerInterface) {
            $this->setEventManager(new EventManager());
        }
        return $this->eventManager;
    }

    /**
     * Registers a plugin (listener) with the event manager.
     *
     * @param ListenerAggregateInterface $plugin
     * @return $this
     */
    public function registerPlugin(ListenerAggregateInterface $plugin)
    {
        $this->getEventManager()->attach($plugin);
        return $this;
    }
}