<?php

namespace EdpUser\Controller;

use Zend\Mvc\Controller\ActionController,
    Zend\Controller\Action\Helper\FlashMessenger,
    Zend\Form\Form,
    Zend\Stdlib\ResponseDescription as Response,
    EdpUser\Service\User as UserService,
    EdpUser\Authentication\AuthenticationService,
    EdpUser\Authentication\Adapter,
    EdpUser\Module;

class UserController extends ActionController
{
    protected $registerForm;
    protected $userService;
    protected $loginForm;
    protected $authService;
    protected $authAdapter;

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
        $request = $this->getRequest();
        $form    = $this->getLoginForm();

        /**
         * @TODO: Make this dynamic / translation-friendly 
         */
        $failedLoginMessage = 'Authentication failed. Please try again.';
        
        if ($request->isPost()) {

            // Validate form
            if (!$form->isValid($request->post()->toArray())) {
                $this->flashMessenger()->setNamespace('edpuser-login-form')->addMessage($failedLoginMessage);
                return $this->redirect()->toRoute('edpuser/login'); 
            }

            $result = $this->getAuthService()
                           ->add($this->getDbAuthAdapter())
                           ->clearAdapterStorage()
                           ->authenticate($request);

            // Return early if an adapter returned a response
            if ($result instanceof Response) {
                return $result;
            }

            if (!$this->getAuthService()->hasIdentity()) {
                $this->flashMessenger()->setNamespace('edpuser-login-form')->addMessage($failedLoginMessage);
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
        $request = $this->getRequest();
        $form    = $this->getRegisterForm();
        if ($request->isPost()) {
            if (false === $form->isValid($request->post()->toArray())) {
                $this->flashMessenger()->setNamespace('edpuser-register-form')->addMessage($request->post()->toArray());
                return $this->redirect()->toRoute('edpuser/register');
            } else {
                $this->getUserService()->createFromForm($form);
                if (Module::getOption('login_after_registration')) {
                    $result = $this->getAuthService()->add($this->getDbAuthAdapter())->authenticate($request);

                    // Return early if an adapter returned a response
                    if ($result instanceof Response) {
                        return $result;
                    }

                    if ($this->getAuthService()->hasIdentity()) {
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

    public function getUserService()
    {
        if (null === $this->userService) {
            $this->userService = $this->getLocator()->get('edpuser_user_service');
        }
        return $this->userService;
    }

    public function getRegisterForm()
    {
        return $this->registerForm;
    }

    public function setRegisterForm(Form $registerForm)
    {
        $this->registerForm = $registerForm;
        $fm = $this->flashMessenger()->setNamespace('edpuser-register-form')->getMessages();
        if (isset($fm[0])) {
            $this->registerForm->isValid($fm[0]);
        }
        return $this;
    }

    public function getLoginForm()
    {
        return $this->loginForm;
    }

    public function setLoginForm(Form $loginForm)
    {
        $this->loginForm = $loginForm;
        $fm = $this->flashMessenger()->setNamespace('edpuser-login-form')->getMessages();
        if (isset($fm[0])) {
            $this->loginForm->addErrorMessage($fm[0]);
        }
        return $this;
    }

    public function getDbAuthAdapter()
    {
        return $this->authAdapter;
    }

    public function setDbAuthAdaper(Adapter $authAdapter)
    {
        $this->authAdapter = $authAdapter;
    }

    public function getAuthService()
    {
        if (null === $this->authService) {
            $this->authService = new AuthenticationService;
        }
        return $this->authService;
    }

    public function setAuthSerivce(AuthenticationService $authService)
    {
        $this->authService = $authService;
        return $this;
    }
}
