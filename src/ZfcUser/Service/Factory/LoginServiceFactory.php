<?php

namespace ZfcUser\Service\Factory;

use ZfcUser\Service\LoginService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoginServiceFactory implements FactoryInterface
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

        /** @var \ZfcUser\Form\LoginForm $form */
        $form = $serviceLocator->get('ZfcUser\Form\LoginForm');

        $service = new LoginService($form, $options);

        foreach ($options->getLoginListeners() as $listener) {
            if (is_string($listener) && $serviceLocator->has($listener)) {
                $listener = $serviceLocator->get($listener);
            }
            $service->getEventManager()->attach($listener);
        }

        $chain = $service->getAdapterChain();

        foreach ($options->getLoginAdapters() as $priority => $adapter) {
            if (is_string($adapter) && $serviceLocator->has($adapter)) {
                $adapter = $serviceLocator->get($adapter);
            }
            $chain->addAdapter($adapter, $priority);
        }

        return $service;
    }
}