<?php

namespace ZfcUser\Extension;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthenticationFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Authentication($serviceLocator->get('Zend\Authentication\AuthenticationService'));
    }
}