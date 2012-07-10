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
        $adapter = $serviceLocator->get('ZfcUser\Authentication\Adapter\Db');
        $chain->getEventManager()->attach('authenticate', array($adapter, 'authenticate'));
        return $chain;
    }
}
