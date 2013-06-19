<?php

namespace ZfcUser\Service;

use Zend\ServiceManager\ServiceLocatorInterface;

class LoginServiceFactory extends AbstractServiceFactory
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @throws \InvalidArgumentException
     * @return LoginService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \ZfcUser\Options\ModuleOptions $options */
        $options = $serviceLocator->get('ZfcUser\Options\ModuleOptions');
        $service = new LoginService();

        foreach ($options->getLoginPlugins() as $plugin) {
            $service->registerPlugin($this->get($serviceLocator, $plugin));
        }

        $chain = $service->getAdapterChain();
        foreach ($options->getLoginAdapters() as $priority => $adapter) {
            $chain->addAdapter($this->get($serviceLocator, $adapter), $priority);
        }

        $service->setAuthenticationService($this->get($serviceLocator, $options->getAuthenticationService()));

        return $service;
    }
}