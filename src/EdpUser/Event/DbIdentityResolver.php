<?php

namespace EdpUser\Event;

use EdpUser\Authentication\AuthEvent,
    EdpUser\Mapper\UserInterface as UserMapper,
    EdpUser\Model\UserInterface as UserModel,
    Zend\EventManager\EventCollection,
    Zend\EventManager\ListenerAggregate;

class DbIdentityResolver implements ListenerAggregate
{
    /**
     * @var UserMapper
     */
    protected $mapper;

    /**
     * @var array
     */
    protected $listeners = array();

    /**
     * Attach one or more listeners
     *
     * @param EventCollection $events
     * @return DbIdentityResolver
     */
    public function attach(EventCollection $events)
    {
        $this->listeners[] = $events->attach('getIdentity.resolve', array($this, 'resolveIdentity'));
        return $this;
    }

    /**
     * Detach all previously attached listeners
     *
     * @param EventCollection $events
     * @return DbIdentityResolver
     */
    public function detach(EventCollection $events)
    {
        foreach ($this->listeners as $key => $listener) {
            $events->detach($listener);
            unset($this->listeners[$key]);
        }
        $this->listeners = array();
        return $this;
    }

    /**
     * resolveIdentity 
     * 
     * @param AuthEvent $e 
     * @return void
     */
    public function resolveIdentity(AuthEvent $e)
    {
        if (!is_int($e->getIdentity())) {
            return;
        }

        $identity = $this->getMapper()->findById($e->getIdentity());

        if ($identity instanceof UserModel) {
            $e->setIdentity($identity);
        }
    }

    /**
     * getMapper 
     * 
     * @return UserMapper
     */
    public function getMapper()
    {
        return $this->mapper;
    }

    /**
     * setMapper 
     * 
     * @param UserMapper $mapper 
     * @return DbIdentityResolver
     */
    public function setMapper(UserMapper $mapper)
    {
        $this->mapper = $mapper;
        return $this;
    }
}
