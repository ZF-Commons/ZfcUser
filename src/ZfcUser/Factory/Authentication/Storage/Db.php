<?php

namespace ZfcUser\Factory\Authentication\Storage;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Authentication\Storage\Db as DbStorage;

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
        $mapper = $serviceLocator->get('zfcuser_user_mapper');

        return new DbStorage(
            $mapper
        );
    }
}
