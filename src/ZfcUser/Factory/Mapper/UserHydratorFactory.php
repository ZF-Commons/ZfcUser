<?php
namespace ZfcUser\Factory;

use ZfcUser\Mapper;
use Zend\Crypt\Password\Bcrypt;

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
