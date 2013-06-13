<?php

namespace ZfcUser\Service;

use Zend\ServiceManager\ServiceLocatorInterface;

class RegisterServiceFactory extends AbstractServiceFactory
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @throws \InvalidArgumentException
     * @return RegisterService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \ZfcUser\Options\ModuleOptions $options */
        $options = $serviceLocator->get('ZfcUser\Options\ModuleOptions');

        /** @var \ZfcUser\Form\RegisterForm $form */
        $form = $serviceLocator->get('ZfcUser\Form\RegisterForm');

        $service = new RegisterService($form, $options);

        foreach ($options->getRegisterPlugins() as $plugin) {
            $service->registerPlugin($this->get($serviceLocator, $plugin));
        }

        return $service;
    }
}