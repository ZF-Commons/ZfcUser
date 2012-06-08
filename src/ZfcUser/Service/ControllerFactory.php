<?php

namespace ZfcUser\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Controller\UserController;

class ControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sm)
    {
        $userService  = $sm->get('zfcuser_user_service');
        $loginEvents  = $sm->get('EventManager');
        $loginForm    = $sm->get('ZfcUser\Form\Login');
        $loginForm->setEventManager($loginEvents);

        $controller = new UserController();
        $controller->setUserService($userService);
        $controller->setLoginForm($loginForm);
        $controller->setServiceLocator($sm);
        return $controller;
    }
}
