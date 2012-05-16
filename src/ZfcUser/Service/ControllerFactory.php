<?php

namespace ZfcUser\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Controller\UserController;

class ControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sm)
    {
        $userService  = $sm->get('ZfcUser\Service\User');
        $loginEvents  = $sm->get('EventManager');
        $loginForm    = $sm->get('ZfcUser\Form\Login');
        $loginForm->setEventManager($loginEvents);
        // this is causing problems currently -- I think due to SM 
        // canonicalization of the classname.
        // $registerForm = $sm->get('ZfcUser\Form\Register');

        $controller = new UserController();
        $controller->setUserService($userService);
        $controller->setLoginForm($loginForm);
        // $controller->setRegisterForm($registerForm);
        return $controller;
    }
}
