<?php

namespace ZfcUser\Controller;

use Zend\Form\Form;
use Zend\Mvc\Controller\ActionController;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\View\Model\ViewModel;
use ZfcUser\Service\User as UserService;
use ZfcUser\Form\LoginFilter;
use ZfcUser\Module as ZfcUser;

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
     * @var \ZfcUser\Form\RegisterFilter
     */
    protected $registerFilter;

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
        return new ViewModel();
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

        $form->setInputFilter(new LoginFilter());
        $form->setData($request->post());

        if (!$form->isValid()) {
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
        $service = $this->getUserService();
        $form = $service->getRegisterForm();

        if ($request->isPost() && ZfcUser::getOption('enable_registration')) {
            $data = $request->post()->toArray();
            try {
                $service->register($data);
                if (ZfcUser::getOption('login_after_registration')) {
                    $post = $request->post();
                    $post['identity']   = $post['email'];
                    $post['credential'] = $post['password'];
                    return $this->forward()->dispatch('zfcuser', array('action' => 'authenticate'));
                }
                return $this->redirect()->toRoute('zfcuser/login');
            } catch (\InvalidArgumentException $e) {
                // ignore exception, error messages are displayed in view
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

    public function getLoginForm()
    {
        return $this->loginForm;
    }

    public function setLoginForm(Form $loginForm)
    {
        $this->loginForm = $loginForm;
        $fm = $this->flashMessenger()->setNamespace('zfcuser-login-form')->getMessages();
        if (isset($fm[0])) {
            $this->loginForm->setMessages(
                array('identity' => array($fm[0]))
            );
        }
        return $this;
    }
}
