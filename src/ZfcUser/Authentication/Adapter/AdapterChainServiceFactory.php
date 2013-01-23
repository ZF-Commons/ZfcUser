<?php
namespace ZfcUser\Authentication\Adapter;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Authentication\Adapter\AdapterChain;

class AdapterChainServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $chain = new AdapterChain;
        /* @var $moduleOptions \ZfcUser\Options\ModuleOptions */
        $moduleOptions = $serviceLocator->get('zfcuser_module_options');
        $enabledAuthMethods = $moduleOptions->getEnabledAuthMethods();
        $availableAuthMethods = $moduleOptions->getAvailableAuthMethods();
        $i = count($availableAuthMethods);
        foreach ($enabledAuthMethods as $authMethod) {
            $adapter = $serviceLocator->get($availableAuthMethods[$authMethod]);
            $chain->getEventManager()->attach('authenticate', array($adapter, 'authenticate'), $i--);
        }
        return $chain;
    }
}
