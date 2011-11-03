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
    protected $userService;
    protected $authService;

    public function indexAction()
    {
        if (!$this->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('default', array(
                'controller' => 'user',
                'action'     => 'login',
            )); 
        }
        return array('user' => $this->getAuthService()->getIdentity());
    }

    public function loginAction()
    {
        if ($this->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('default', array(
                'controller' => 'user',
                'action'     => 'index',
            )); 
        }
        $request    = $this->getRequest();
        $form       = $this->getLoginForm();
        if ($request->isPost()) {
            $auth = $this->getUserService()->authenticate(
                $request->post()->get('email'),
                $request->post()->get('password')
            );
            if ($auth) {
                return $this->redirect()->toRoute('default', array(
                    'controller' => 'user',
                    'action'     => 'index',
                )); 
            } else {
                return $this->redirect()->toRoute('default', array(
                    'controller' => 'user',
                    'action'     => 'login',
                )); 
            }
        }
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

    public function getUserService()
    {
        if (null === $this->userService) {
            $this->userService = $this->getLocator()->get('edpuser-user-service');
        }
        return $this->userService;
    }

    public function getAuthService()
    {
        if (null === $this->authService) {
            $this->authService = $this->getUserService()->getAuthService();
        }
        return $this->authService;
    }
}
