<?php
namespace ZfcUser\Factory\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Controller\RedirectCallback;

class RedirectCallbackFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $router = $serviceLocator->get('Router');
        $response = $serviceLocator->get('Response');
        $request = $serviceLocator->get('Request');
        $options = $serviceLocator->get('zfcuser_module_options');

        /** @var MvcEvent $mvcEvent */
        $mvcEvent   = $serviceLocator->get('Application')->getMvcEvent();
        $routeMatch = $mvcEvent->getRouteMatch();

        return new RedirectCallback($routeMatch, $router, $response, $request, $options);
    }
}
