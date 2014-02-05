<?php
namespace ZfcUser\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Mapper\User;
use ZfcUser\Mapper\UserHydrator;

class UserMapperFactory implements FactoryInterface
{

	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$options = $serviceLocator->get('zfcuser_module_options');
        $mapper = new User();
        $mapper->setDbAdapter($serviceLocator->get('zfcuser_zend_db_adapter'));
        $entityClass = $options->getUserEntityClass();
        $mapper->setEntityPrototype(new $entityClass);
        $mapper->setHydrator(new UserHydrator());
        $mapper->setTableName($options->getTableName());
        return $mapper;
	}

}