<?php

namespace ZfcUser\Service\Factory;

use ZfcUser\Service\RegisterService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RegisterServiceFactory implements FactoryInterface
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
        if (!$serviceLocator->has('ZfcUser\Storage')) {
            // todo: throw exception
            echo 'no storage adapter has been set, have you installed a storage provider?';
            exit;
        }

        /** @var \ZfcUser\Options\ModuleOptions $options */
        $options = $serviceLocator->get('ZfcUser\Options\ModuleOptions');

        /** @var \ZfcUser\Storage\AdapterInterface $storage */
        $storage = $serviceLocator->get('ZfcUser\Storage');

        /** @var \ZfcUser\Form\RegisterForm $form */
        $form = $serviceLocator->get('ZfcUser\Form\RegisterForm');

        return new RegisterService($form, $storage, $options);
    }
}