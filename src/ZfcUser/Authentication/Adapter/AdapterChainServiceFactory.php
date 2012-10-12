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

        $options = $serviceLocator->get('zfcuser_module_options');

        //iterate and attach multiple adapters
        foreach($options->getAuthAdapters() as $priority => $adapterName) {
            $adapter = $serviceLocator->get($adapterName);
            $chain->getEventManager()->attach('authenticate', array($adapter, 'authenticate'), $priority);
        }

        return $chain;
    }
}
