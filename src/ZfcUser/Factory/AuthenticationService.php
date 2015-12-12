<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 5/6/2015
 * Time: 6:40 PM
 */

namespace ZfcUser\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthenticationService implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new \Zend\Authentication\AuthenticationService(
            $serviceLocator->get('ZfcUser\Authentication\Storage\Db'),
            $serviceLocator->get('ZfcUser\Authentication\Adapter\AdapterChain')
        );
    }
}
