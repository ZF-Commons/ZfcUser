<?php

namespace ZfcUser\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Service\User as UserService;

class User implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $userMapper = $serviceLocator->get('zfcuser_user_mapper');
        $authService = $serviceLocator->get('zfcuser_auth_service');
        $registerForm = $serviceLocator->get('zfcuser_register_form');
        $options = $serviceLocator->get('zfcuser_module_options');
        $formHydrator = $serviceLocator->get('zfcuser_user_hydrator');

        return new UserService(
            $userMapper,
            $authService,
            $registerForm,
            $options,
            $formHydrator
        );
    }
}
