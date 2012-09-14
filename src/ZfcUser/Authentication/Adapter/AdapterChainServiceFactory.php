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
        $session = new \Zend\Session\Container('zfcuser');
        $adapter = (\ZfcUser\Service\RememberMe::getCookie() && !$session->offsetGet('forceRelogin')) ? 'ZfcUser\Authentication\Adapter\Cookie' : 'ZfcUser\Authentication\Adapter\Db';
        $adapter = $serviceLocator->get($adapter);
        $chain->getEventManager()->attach('authenticate', array($adapter, 'authenticate'));
        return $chain;
    }
}
