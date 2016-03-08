<?php

namespace ZfcUser\Factory\Authentication\Storage;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Authentication\Storage\Db as DbStorage;
use ZfcUser\Mapper\UserInterface;

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
        return new DbStorage(
            $mapper
        );
    }
}
