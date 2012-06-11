<?php

namespace ZfcUser\Mapper;

use DateTime;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class DefaultUserListener implements ListenerAggregateInterface
{
    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     * @param null|int $priority Optional priority "hint" to use when attaching listeners
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('persist.pre', array($this, 'onPrePersist'));
    }

    /**
     * Detach all previously attached listeners
     *
     * @param EventManagerInterface $events
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    public function onPrePersist(EventInterface $e)
    {
        $data = $e->getParam('data');
        foreach (array('last_login', 'register_time') as $key) {
            if (isset($data[$key]) && $data[$key] instanceof DateTime) {
                $data[$key] = $data[$key]->format('Y-m-d H:i:s');
            }
        }
        $e->setParam('data', $data);
        return $e;
    }

}