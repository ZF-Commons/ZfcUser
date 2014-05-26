<?php
namespace ZfcUser\Factory\Mapper;

use Zend\Crypt\Password\Bcrypt;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Mapper;

class UserHydratorFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get('zfcuser_module_options');
        $crypto  = new Bcrypt;
        $crypto->setCost($options->getPasswordCost());
        return new Mapper\UserHydrator($crypto);
    }
}
