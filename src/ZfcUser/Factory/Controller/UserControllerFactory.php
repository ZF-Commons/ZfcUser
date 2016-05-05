<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 5/6/2015
 * Time: 6:50 PM
 */

namespace ZfcUser\Factory\Controller;

use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Controller\RedirectCallback;
use ZfcUser\Controller\UserController;

class UserControllerFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $controllerManager
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /* @var ControllerManager $controllerManager*/
        $serviceManager = $controllerManager->getServiceLocator();

        /* @var RedirectCallback $redirectCallback */
        $redirectCallback = $serviceManager->get('zfcuser_redirect_callback');

        /* @var UserController $controller */
        $controller = new UserController($redirectCallback);
        $controller->setServiceLocator($serviceManager);

        $controller->setChangeEmailForm($this->serviceLocator->get('zfcuser_change_email_form'));
        $controller->setOptions($this->serviceLocator->get('zfcuser_module_options'));
        $controller->setChangePasswordForm($this->serviceLocator->get('zfcuser_change_password_form'));
        $controller->setLoginForm($this->serviceLocator->get('zfcuser_login_form'));
        $controller->setRegisterForm($this->serviceLocator->get('zfcuser_register_form'));
        $controller->setUserService($this->serviceLocator->get('zfcuser_user_service'));

        return $controller;
    }
}
