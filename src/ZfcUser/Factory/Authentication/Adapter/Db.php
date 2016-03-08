<?php

namespace ZfcUser\Factory\Authentication\Adapter;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Authentication\Adapter\Db as DbAdapter;
use ZfcUser\Mapper\UserInterface;
use ZfcUser\Options\AuthenticationOptionsInterface;
use ZfcUser\Options\ModuleOptions;

class Db implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var UserInterface $mapper */
        $mapper = $serviceLocator->get('zfcuser_user_mapper');
        /** @var AuthenticationOptionsInterface $options */
        $options = $serviceLocator->get('zfcuser_module_options');
        return new DbAdapter(
            $mapper,
            $options
        );
    }
}
