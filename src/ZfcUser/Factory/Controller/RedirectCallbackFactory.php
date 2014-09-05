<?php
namespace ZfcUser\Factory\Controller;

use Zend\Mvc\Application;
use Zend\Mvc\Router\RouteInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Controller\RedirectCallback;
use ZfcUser\Options\ModuleOptions;

class RedirectCallbackFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
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
