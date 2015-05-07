<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 5/6/2015
 * Time: 6:37 PM
 */

namespace ZfcUser\Factory\Controller;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RedirectCallback implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var RouteInterface $router */
        $router = $serviceLocator->get('Router');

        /* @var Application $application */
        $application = $serviceLocator->get('Application');

        /* @var ModuleOptions $options */
        $options = $serviceLocator->get('zfcuser_module_options');

        return new RedirectCallback($application, $router, $options);
    }
}
