<?php

namespace ZfcUser\Factory\Controller;

use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Controller\RedirectCallback;
use ZfcUser\Controller\UserController;

class UserControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceManager, $requestedName, array $options = null)
    {
        /* @var RedirectCallback $redirectCallback */
        $redirectCallback = $serviceManager->get('zfcuser_redirect_callback');

        /* @var UserController $controller */
        $controller = new UserController($redirectCallback);
        $controller->setServiceLocator($serviceManager);

        $controller->setChangeEmailForm($serviceManager->get('zfcuser_change_email_form'));
        $controller->setOptions($serviceManager->get('zfcuser_module_options'));
        $controller->setChangePasswordForm($serviceManager->get('zfcuser_change_password_form'));
        $controller->setLoginForm($serviceManager->get('zfcuser_login_form'));
        $controller->setRegisterForm($serviceManager->get('zfcuser_register_form'));
        $controller->setUserService($serviceManager->get('zfcuser_user_service'));

        return $controller;
    }

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

        return $this->__invoke($serviceManager, null);
    }
}
