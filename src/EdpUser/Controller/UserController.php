<?php

namespace EdpUser\Controller;

use Zend\Mvc\Controller\ActionController,
    Zend\Mvc\Router\RouteStack,
    EdpUser\Service\User as UserService,
    Zend\Controller\Action\Helper\FlashMessenger;

class UserController extends ActionController
{
    protected $registerForm;
    protected $loginForm;

    public function loginAction()
    {
        $form       = $this->getLoginForm();
        return array(
            'loginForm' => $form,
        );
    }

    public function registerAction()
    {
        $request    = $this->getRequest();
        $form       = $this->getRegisterForm();
        return array(
            'registerForm' => $form,
        );
    }

    public function getRegisterForm()
    {
        if (null === $this->registerForm) {
            $this->registerForm = $this->getLocator()->get('edpuser-register-form');
        }
        return $this->registerForm;
    }

    public function getLoginForm()
    {
        if (null === $this->loginForm) {
            $this->loginForm = $this->getLocator()->get('edpuser-login-form');
        }
        return $this->loginForm;
    }
}
