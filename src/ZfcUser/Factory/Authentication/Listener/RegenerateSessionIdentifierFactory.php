<?php
namespace ZfcUser\Factory\Authentication\Listener;

use Zend\ServiceManager\DelegatorFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Authentication\Listener\RegenerateSessionIdentifier;
use Zend\Session\Container;

class RegenerateSessionIdentifierFactory implements DelegatorFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createDelegatorWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName, $callback)
    {
        $sessionStorage = call_user_func($callback);

        $sem = $serviceLocator->get('SharedEventManager');
        
        $manager = $serviceLocator->has('Zend\Session\ManagerInterface')
            ? $serviceLocator->get('Zend\Session\ManagerInterface')
            : Container::getDefaultManager();
        
        // Attach listener to regenerate SID before authentication occurs
        $sem->attach(
            'ZfcUser\Authentication\Adapter\AdapterChain',
            'authenticate.pre',
            new RegenerateSessionIdentifier($manager)
        );
        
        return $sessionStorage;
    }
}
