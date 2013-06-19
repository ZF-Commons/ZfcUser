<?php

namespace ZfcUser\Service;

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

abstract class AbstractPluginService
{
    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * A list of plugins that are allowed to be registered with this service.
     *
     * @var array
     */
    protected $allowedPluginInterfaces = array();

    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     * @return $this
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->setIdentifiers(array(__CLASS__, get_class($this)));
        $this->eventManager = $eventManager;
        return $this;
    }

    /**
     * Registers a plugin (listener) with the event manager.
     *
     * @param ListenerAggregateInterface $plugin
     * @throws Exception\InvalidPluginException
     * @return $this
     */
    public function registerPlugin(ListenerAggregateInterface $plugin)
    {
        $implements = class_implements($plugin);
        $allowed    = $this->allowedPluginInterfaces;

        if (!empty($allowed) && 0 === count(array_intersect($implements, $allowed))) {
            throw new Exception\InvalidPluginException(
                sprintf(
                    'Attempted to register invalid plugin %s, allowed: %s',
                    get_class($plugin),
                    implode(' ', $allowed)
                )
            );
        }

        $this->getEventManager()->attach($plugin);
        return $this;
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    protected function getEventManager()
    {
        if (!$this->eventManager instanceof EventManagerInterface) {
            $this->setEventManager(new EventManager());
        }
        return $this->eventManager;
    }
}