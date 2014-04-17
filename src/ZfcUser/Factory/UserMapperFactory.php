<?php
namespace ZfcUser\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use ZfcUser\Mapper\User as UserMapper;

class UserMapperFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get('zfcuser_module_options');
        $mapper = new UserMapper();
        $mapper->setDbAdapter($serviceLocator->get('zfcuser_zend_db_adapter'));
        $entityClass = $options->getUserEntityClass();
        $mapper->setEntityPrototype(new $entityClass);
        $mapper->setHydrator($serviceLocator->get('zfcuser_user_hydrator'));
        $mapper->setTableName($options->getTableName());
        return $mapper;
    }
}
