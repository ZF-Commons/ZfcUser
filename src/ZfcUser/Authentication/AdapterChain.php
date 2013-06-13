<?php

namespace ZfcUser\Authentication;

use ZfcUser\Authentication\ChainEvent;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

class AdapterChain implements
    AdapterInterface,
    EventManagerAwareInterface
{
    const EVENT_AUTHENTICATE = 'authenticate';
    const EVENT_SETUP        = 'setup';
    const EVENT_TEARDOWN     = 'teardown';

    /**
     * @var array
     */
    protected $adapters = array();

    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * @var ChainEvent
     */
    protected $event;

    /**
     * @var array
     */
    protected $eventParams = array();

    /**
     * {@inhericDoc}
     */
    public function authenticate()
    {
        $event        = $this->getEvent();
        $eventManager = $this->getEventManager();

        $event->setParams($this->getEventParams());

        $eventManager->trigger(static::EVENT_SETUP, $event);
        $eventManager->trigger(static::EVENT_AUTHENTICATE, $event);

        $result = new Result(
            $event->getCode(),
            $event->getIdentity(),
            $event->getMessages()
        );

        $eventManager->trigger(static::EVENT_TEARDOWN, $event);

        return $result;
    }

    /**
     * {@inhericDoc}
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
        return $this;
    }

    /**
     * {@inhericDoc}
     */
    public function getEventManager()
    {
        if (!$this->eventManager instanceof EventManagerInterface) {
            $this->setEventManager(new EventManager());
        }
        return $this->eventManager;
    }

    /**
     * @param ChainEvent $event
     * @return AdapterChain
     */
    public function setEvent($event)
    {
        $this->event = $event;
        return $this;
    }

    /**
     * @return ChainEvent
     */
    public function getEvent()
    {
        if (!$this->event) {
            $this->event = new ChainEvent();
            $this->event->setAdapter($this);
        }
        return $this->event;
    }

    /**
     * @param ChainableAdapterInterface $adapter
     * @param int $priority
     * @return AdapterChain
     */
    public function addAdapter(ChainableAdapterInterface $adapter, $priority = 100)
    {
        $this->getEventManager()->attach($adapter, $priority);
        $this->adapters[] = $adapter;
        return $this;
    }

    /**
     * @param array $adapters
     * @return AdapterChain
     */
    public function setAdapters(array $adapters)
    {
        foreach ($adapters as $priority => $adapter) {
            if (is_int($priority)) {
                $this->addAdapter($adapter, $priority);
            } else {
                $this->addAdapter($adapter);
            }
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getAdapters()
    {
        return $this->adapters;
    }

    /**
     * @param array $eventParams
     * @return AdapterChain
     */
    public function setEventParams($eventParams)
    {
        $this->eventParams = $eventParams;
        return $this;
    }

    /**
     * @return array
     */
    public function getEventParams()
    {
        return $this->eventParams;
    }
}