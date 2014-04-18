<?php
namespace ZfcUser\Factory;

use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthenticationServiceFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new AuthenticationService(
            $serviceLocator->get('ZfcUser\Authentication\Storage\Db'),
            $serviceLocator->get('ZfcUser\Authentication\Adapter\AdapterChain')
        );
    }
}
