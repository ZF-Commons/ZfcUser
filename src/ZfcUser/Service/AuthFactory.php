<?php
namespace ZfcUser\Service;

use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class AuthFactory implements FactoryInterface
{

	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new AuthenticationService(
            $serviceLocator->get('ZfcUser\Authentication\Storage\Db'),
            $serviceLocator->get('ZfcUser\Authentication\Adapter\AdapterChain')
        );
	}
}