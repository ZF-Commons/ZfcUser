<?php

namespace EdpUser\Controller;

use Zend\Mvc\Controller\ActionController,
    Zend\Form\Form,
    Zend\Stdlib\ResponseDescription as Response,
    EdpUser\Service\User as UserService,
    EdpUser\Module as EdpUser;

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
        if (!$this->edpUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute('edpuser/login'); 
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
            $this->flashMessenger()->setNamespace('edpuser-login-form')->addMessage($this->failedLoginMessage);
            return $this->redirect()->toRoute('edpuser/login'); 
        }

        return $this->forward()->dispatch('edpuser', array('action' => 'authenticate'));
    }

    /**
     * Logout and clear the identity 
     */
    public function logoutAction()
    {
        if (!$this->edpUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute('edpuser/login');
        }

        $this->edpUserAuthentication()->getAuthService()->clearIdentity();

        return $this->redirect()->toRoute('edpuser/login');
    }

    /**
     * General-purpose authentication action 
     */
    public function authenticateAction()
    {
        $adapter = $this->edpUserAuthentication()->getAuthAdapter();

        $result = $adapter->prepareForAuthentication($this->getRequest());

        // Return early if an adapter returned a response
        if ($result instanceof Response) {
            return $result;
        }

        $auth = $this->edpUserAuthentication()->getAuthService()->authenticate($adapter);

        if (!$auth->isValid()) {
            $this->flashMessenger()->setNamespace('edpuser-login-form')->addMessage($this->failedLoginMessage);
            return $this->redirect()->toRoute('edpuser/login');
        }

        if (EdpUser::getOption('use_redirect_parameter_if_present') && $request->post()->get('redirect')) {
            return $this->redirect()->toUrl($request->post()->get('redirect'));
        }

        return $this->redirect()->toRoute('edpuser');
    }

    /**
     * Register new user 
     */
    public function registerAction()
    {
        if ($this->edpUserAuthentication()->getAuthService()->hasIdentity()) {
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
                if (EdpUser::getOption('login_after_registration')) {
                    $post = $request->post();
                    $post['identity']   = $post['email'];
                    $post['credential'] = $post['password'];
                    return $this->forward()->dispatch('edpuser', array('action' => 'authenticate'));
                }
                return $this->redirect()->toRoute('edpuser/login');
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
}
