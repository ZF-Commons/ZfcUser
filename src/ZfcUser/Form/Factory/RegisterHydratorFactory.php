<?php

namespace ZfcUser\Form\Factory;

use ZfcUser\Form\RegisterHydrator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RegisterHydratorFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return RegisterHydrator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new RegisterHydrator($serviceLocator->get('ZfcUser\Form\PasswordStrategy'));
    }
}