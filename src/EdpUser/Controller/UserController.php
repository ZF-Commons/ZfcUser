<?php

namespace EdpUser\Controller;

use Zend\Mvc\Controller\ActionController,
    Zend\Mvc\Router\RouteStack,
    Zend\Session\SessionManager,
    Zend\Session\Container,
    EdpUser\Service\User as UserService,
    EdpUser\Module,
    EdpUser\Util\Password;

class UserController extends ActionController
{
    protected $registerForm;
    protected $loginForm;
    protected $userService;
    protected $authService;
    protected $session;

    public function indexAction()
    {
        if (!$this->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('edpuser/login'); 
        }
        return array('user' => $this->getAuthService()->getIdentity());
    }

    public function loginAction()
    {
        if ($this->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('edpuser'); 
        }
        $request    = $this->getRequest();
        $form       = $this->getLoginForm();

        /**
         * @TODO: Make this dynamic / translation-friendly 
         */
        $failedLoginMessage = 'Authentication failed. Please try again.';
        
        if ($request->isPost()) {
            if (false === $form->isValid($request->post()->toArray())) {
                $this->getSession()->edpuser_login_form = $failedLoginMessage;
                return $this->redirect()->toRoute('edpuser/login'); 
            }
            $auth = $this->getUserService()->authenticate($request->post()->get('email'), $request->post()->get('password'));
            if (false === $auth) {
                $this->getSession()->edpuser_login_form = $failedLoginMessage;
                return $this->redirect()->toRoute('edpuser/login');
            }
            if (Module::getOption('use_redirect_parameter_if_present')
                && $request->post()->get('redirect')
            ) {
                return $this->redirect()->toUrl($request->post()->get('redirect'));
            }
            return $this->redirect()->toRoute('edpuser');
        }
        return array(
            'loginForm' => $form,
        );
    }

    public function logoutAction()
    {
        if (!$this->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('edpuser/login');
        }

        $this->getAuthService()->clearIdentity();

        return $this->redirect()->toRoute('edpuser/login');
    }

    public function registerAction()
    {
        if ($this->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('edpuser');
        }
        $request    = $this->getRequest();
        $form       = $this->getRegisterForm();
        if ($request->isPost()) {
            if (false === $form->isValid($request->post()->toArray())) {
                $this->getSession()->edpuser_register_form = $request->post()->toArray();
                return $this->redirect()->toRoute('edpuser/register');
            } else {
                $this->getUserService()->createFromForm($form);
                if (Module::getOption('login_after_registration')) {
                    $auth = $this->getUserService()->authenticate($request->post()->get('email'), $request->post()->get('password'));
                    if (false !== $auth) {
                        return $this->redirect()->toRoute('edpuser');
                    }
                }
                return $this->redirect()->toRoute('edpuser/login');
            }
        }
        return array(
            'registerForm' => $form,
        );
    }

    public function getRegisterForm()
    {
        if (null === $this->registerForm) {
            $this->registerForm = $this->getLocator()->get('edpuser_register_form');
            $session = $this->getSession()->edpuser_register_form;
            if (isset($session)) {
                $this->registerForm->isValid($session);
            }
        }
        return $this->registerForm;
    }

    public function getLoginForm()
    {
        if (null === $this->loginForm) {
            $this->loginForm = $this->getLocator()->get('edpuser_login_form');
            $session = $this->getSession()->edpuser_login_form;
            if (isset($session)) {
                $this->loginForm->addErrorMessage($session);
            }
        }
        return $this->loginForm;
    }

    public function getUserService()
    {
        if (null === $this->userService) {
            $this->userService = $this->getLocator()->get('edpuser_user_service');
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
    
    protected function getSession()
    {
        if (null === $this->session) {
            $manager = new SessionManager;
            
            $this->session = new Container(__NAMESPACE__, $manager);
            $this->session->setExpirationHops(1);
        }
        
        return $this->session;
    }
}
