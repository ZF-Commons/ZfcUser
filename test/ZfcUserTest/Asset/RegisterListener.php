<?php

namespace ZfcUserTest\Asset;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use ZfcUser\Plugin\RegisterPluginInterface;

class RegisterListener extends AbstractListenerAggregate implements RegisterPluginInterface
{
    public function attach(EventManagerInterface $events)
    {
    }
}
