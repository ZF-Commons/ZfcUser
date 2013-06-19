<?php

namespace ZfcUserTest\Asset;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use ZfcUser\Plugin\LoginPluginInterface;

class LoginListener extends AbstractListenerAggregate implements LoginPluginInterface
{
    public function attach(EventManagerInterface $events)
    {
        $events->attach(LoginPluginInterface::EVENT_PREPARE_FORM, array($this, 'form'));
    }

    public function form(EventInterface $event)
    {
        $form = $event->getTarget();
        $form->add(array('name' => 'test'));
    }
}
