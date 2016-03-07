<?php

namespace ZfcUser\Factory\Authentication\Adapter;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

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
        if (!$serviceLocator instanceof ServiceManager) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        $mapper = $serviceLocator->get('zfcuser_user_mapper');
        $hydrator = $serviceLocator->get('zfcuser_user_hydrator');
        $options = $serviceLocator->get('zfcuser_module_options');

        return new Db(
            $mapper,
            $hydrator,
            $options
        );
    }
}