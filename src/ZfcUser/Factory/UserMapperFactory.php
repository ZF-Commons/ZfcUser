<?php
namespace ZfcUser\Factory;

use Zend\Db;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator;
use ZfcUser\Options;

class UserMapperFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $options Options\ModuleOptions */
        $options = $serviceLocator->get('zfcuser_module_options');

        /* @var $dbAdapter Db\Adapter\Adapter */
        $dbAdapter = $serviceLocator->get('zfcuser_zend_db_adapter');

        $mapperClass = $options->getUserMapperClass();
        $mapper = new $mapperClass;
        $mapper->setDbAdapter($dbAdapter);

        $entityClass = $options->getUserEntityClass();

        /* @var $hydrator Hydrator\HydratorInterface */
        $hydrator = $serviceLocator->get('zfcuser_user_hydrator');

        $mapper
            ->setEntityPrototype(new $entityClass)
            ->setHydrator($hydrator)
            ->setTableName($options->getTableName())
        ;

        return $mapper;
    }
}
