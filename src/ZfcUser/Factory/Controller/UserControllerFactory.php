<?php
namespace ZfcUser\Factory\Controller;

use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Authentication\Adapter;
use ZfcUser\Controller\UserController;

class UserControllerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var ControllerManager $controllerManager*/
        $serviceManager = $controllerManager->getServiceLocator();

        $redirectCallback = $serviceManager->get('zfcuser_redirect_callback');
        $controller = new UserController($redirectCallback);

        return $controller;
    }
}
