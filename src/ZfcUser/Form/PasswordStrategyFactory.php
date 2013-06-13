<?php

namespace ZfcUser\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PasswordStrategyFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return PasswordStrategy
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new PasswordStrategy($serviceLocator->get('ZfcUser\Options\ModuleOptions'));
    }
}