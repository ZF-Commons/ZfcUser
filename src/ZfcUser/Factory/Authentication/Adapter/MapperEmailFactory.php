<?php
namespace ZfcUser\Factory\Authentication\Adapter;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Authentication\Adapter\Mapper;

class MapperEmailFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Mapper(
            $serviceLocator->get('zfcuser_user_mapper'),
            'findByEmail',
            $serviceLocator->get('zfcuser_authentication_credentialprocessor')
        );
    }
}
