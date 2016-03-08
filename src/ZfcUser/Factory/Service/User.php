<?php

namespace ZfcUser\Factory\Service;

use Zend\Authentication\AuthenticationService;
use Zend\Hydrator\HydratorInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Form\Register;
use ZfcUser\Mapper\UserInterface;
use ZfcUser\Options\UserServiceOptionsInterface;
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
        /** @var UserInterface $userMapper */
        $userMapper = $serviceLocator->get('zfcuser_user_mapper');
        /** @var AuthenticationService $authService */
        $authService = $serviceLocator->get('zfcuser_auth_service');
        /** @var Register $registerForm */
        $registerForm = $serviceLocator->get('FormElementManager')->get('zfcuser_register_form');
        /** @var UserServiceOptionsInterface $options */
        $options = $serviceLocator->get('zfcuser_module_options');
        /** @var HydratorInterface $formHydrator */
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
