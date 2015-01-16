<?php
namespace ZfcUser\Factory\Authentication\Storage;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Authentication\Storage\Mapper;

class MapperFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get('zfcuser_module_options');
        
        return new Mapper(
            $serviceLocator->get('zfcuser_user_mapper'),
            $serviceLocator->get('zfcuser_authentication_storage_backend')
        );
    }
}
