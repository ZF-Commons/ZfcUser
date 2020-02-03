<?php

namespace ZfcUser\Factory\Mapper;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Mapper;
use ZfcUser\Options\ModuleOptions;

class User implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceLocator, $requestedName, array $options = null)
    {
        /** @var ModuleOptions $options */
        $options = $serviceLocator->get('zfcuser_module_options');
        $dbAdapter = $serviceLocator->get('zfcuser_zend_db_adapter');

        $entityClass = $options->getUserEntityClass();
        $tableName = $options->getTableName();

        $mapper = new Mapper\User();
        $mapper->setDbAdapter($dbAdapter);
        $mapper->setTableName($tableName);
        $mapper->setEntityPrototype(new $entityClass);
        $mapper->setHydrator(new Mapper\UserHydrator());

        return $mapper;
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this->__invoke($serviceLocator, null);
    }
}
