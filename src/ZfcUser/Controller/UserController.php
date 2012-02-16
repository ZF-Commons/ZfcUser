<?php

namespace ZfcUser\Controller;

use Zend\Mvc\Controller\ActionController,
    Zend\Form\Form,
    Zend\Stdlib\ResponseDescription as Response,
    ZfcUser\Service\User as UserService,
    ZfcUser\Module as ZfcUser;

class UserController extends ActionController
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var Form
     */
    protected $loginForm;

    /**
     * @var Form
     */
    protected $registerForm;

    /**
     * @todo Make this dynamic / translation-friendly
     * @var string
     */
    protected $failedLoginMessage = 'Authentication failed. Please try again.';

    /**
     * User page 
     */
    public function indexAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute('zfcuser/login'); 
        }
    }

    /**
     * Login form 
     */
    public function loginAction()
    {
        $request = $this->getRequest();
        $form    = $this->getLoginForm();

        if (!$request->isPost()) {
            return array(
                'loginForm' => $form,
            );
        }

        if (!$form->isValid($request->post()->toArray())) {
            $this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($this->failedLoginMessage);
            return $this->redirect()->toRoute('zfcuser/login'); 
        }
        // clear adapters

        return $this->forward()->dispatch('zfcuser', array('action' => 'authenticate'));
    }

    /**
     * Logout and clear the identity 
     */
    public function logoutAction()
    {
        $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute('zfcuser/login');
        }

        $this->zfcUserAuthentication()->getAuthService()->clearIdentity();

        return $this->redirect()->toRoute('zfcuser/login');
    }

    /**
     * General-purpose authentication action 
     */
    public function authenticateAction()
    {
        if ($this->zfcUserAuthentication()->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('zfcuser');
        }
        $request = $this->getRequest();
        $adapter = $this->zfcUserAuthentication()->getAuthAdapter();

        $result = $adapter->prepareForAuthentication($request);

        // Return early if an adapter returned a response
        if ($result instanceof Response) {
            return $result;
        }

        $auth = $this->zfcUserAuthentication()->getAuthService()->authenticate($adapter);

        if (!$auth->isValid()) {
            $this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($this->failedLoginMessage);
            $adapter->resetAdapters();
            return $this->redirect()->toRoute('zfcuser/login');
        }

        if (ZfcUser::getOption('use_redirect_parameter_if_present') && $request->post()->get('redirect')) {
            return $this->redirect()->toUrl($request->post()->get('redirect'));
        }

        return $this->redirect()->toRoute('zfcuser');
    }

    /**
     * Register new user 
     */
    public function registerAction()
    {
        if ($this->zfcUserAuthentication()->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('zfcuser');
        }
        
        $request = $this->getRequest();
        $form    = $this->getRegisterForm();
        if ($request->isPost() && ZfcUser::getOption('enable_registration')) {
            if (false === $form->isValid($request->post()->toArray())) {
                $this->flashMessenger()->setNamespace('zfcuser-register-form')->addMessage($request->post()->toArray());
                return $this->redirect()->toRoute('zfcuser/register');
            } else {
                $this->getUserService()->createFromForm($form);
                if (ZfcUser::getOption('login_after_registration')) {
                    $post = $request->post();
                    $post['identity']   = $post['email'];
                    $post['credential'] = $post['password'];
                    return $this->forward()->dispatch('zfcuser', array('action' => 'authenticate'));
                }
                return $this->redirect()->toRoute('zfcuser/login');
            }
        }
        return array(
            'registerForm' => $form,
        );
    }

    /**
     * Getters/setters for DI stuff
     */

    public function getUserService()
    {
        return $this->userService;
    }

    public function setUserService(UserService $userService)
    {
        $this->userService = $userService;
        return $this;
    }

    public function getRegisterForm()
    {
        return $this->registerForm;
    }

    public function setRegisterForm(Form $registerForm)
    {
        $this->registerForm = $registerForm;
        $fm = $this->flashMessenger()->setNamespace('zfcuser-register-form')->getMessages();
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
        $fm = $this->flashMessenger()->setNamespace('zfcuser-login-form')->getMessages();
        if (isset($fm[0])) {
            $this->loginForm->addErrorMessage($fm[0]);
        }
        return $this;
    }
}
