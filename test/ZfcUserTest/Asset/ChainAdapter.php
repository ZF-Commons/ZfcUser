<?php

namespace ZfcUserTest\Asset;

use Zend\Authentication\Result;
use Zend\EventManager\EventManagerInterface;
use ZfcUser\Authentication\AdapterChain;
use ZfcUser\Authentication\ChainableAdapterInterface;
use ZfcUser\Authentication\ChainEvent;

class ChainAdapter implements ChainableAdapterInterface
{
    protected $result    = null;
    protected $listeners = array();

    public function authenticate(ChainEvent $e)
    {
        if ($this->result) {
            $e->setCode(Result::SUCCESS);
            $e->setIdentity('foo');
            $e->clearMessages();
        } else {
            $e->setCode(Result::FAILURE);
            $e->setIdentity(null);
            $e->addMessages(array('failure'));
        }
    }

    public function setup(ChainEvent $e)
    {
        $this->result = $e->getParam('result');
    }

    public function attach(EventManagerInterface $events, $priority = 100)
    {
        $this->listeners[] = $events->attach('test', array($this, 'test'), $priority);
        $this->listeners[] = $events->attach(AdapterChain::EVENT_SETUP, array($this, 'setup'), $priority);
        $this->listeners[] = $events->attach(AdapterChain::EVENT_AUTHENTICATE, array($this, 'authenticate'), $priority);
    }

    public function detach(EventManagerInterface $events)
    {
    }

    public function test()
    {

    }

    public function getListeners()
    {
        return $this->listeners;
    }
}