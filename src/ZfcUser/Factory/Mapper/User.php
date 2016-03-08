<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 5/6/2015
 * Time: 6:43 PM
 */

namespace ZfcUser\Factory\Mapper;

use Zend\Db\Adapter\Adapter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Mapper;
use ZfcUser\Options\ModuleOptions;

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
        /** @var ModuleOptions $options */
        $options = $serviceLocator->get('zfcuser_module_options');
        $mapper = new Mapper\User();
        /** @var Adapter $dbAdapter */
        $dbAdapter = $serviceLocator->get('zfcuser_zend_db_adapter');
        $mapper->setDbAdapter($dbAdapter);
        $entityClass = $options->getUserEntityClass();
        $mapper->setEntityPrototype(new $entityClass);
        $mapper->setHydrator(new Mapper\UserHydrator());
        $mapper->setTableName($options->getTableName());
        return $mapper;
    }
}
