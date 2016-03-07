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
use ZfcUser\Form\ChangeEmail;
use ZfcUser\Form\ChangePassword;
use ZfcUser\Form\Login;
use ZfcUser\Form\Register;
use ZfcUser\Options\ModuleOptions;
use ZfcUser\Service\User;

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
        /* @var ControllerManager $controllerManager */
        $serviceManager = $controllerManager->getServiceLocator();

        /* @var RedirectCallback $redirectCallback */
        $redirectCallback = $serviceManager->get('zfcuser_redirect_callback');

        /** @var User $userService */
        $userService = $serviceManager->get('zfcuser_user_service');

        /** @var Login $loginForm */
        $loginForm = $serviceManager->get('zfcuser_login_form');

        /** @var Register $registerForm */
        $registerForm = $serviceManager->get('zfcuser_register_form');

        /** @var ChangePassword $changePasswordForm */
        $changePasswordForm = $serviceManager->get('zfcuser_change_password_form');

        /** @var ChangeEmail $changeEmailForm */
        $changeEmailForm = $serviceManager->get('zfcuser_change_email_form');

        /** @var ModuleOptions $options */
        $options = $serviceManager->get('zfcuser_module_options');

        /* @var UserController $controller */
        $controller = new UserController($userService, $loginForm, $registerForm, $changePasswordForm, $changeEmailForm, $options, $redirectCallback);

        return $controller;
    }
}
