<?php

namespace ZfcUser\Controller;

use Zend\Form\Form;
use Zend\Mvc\Controller\ActionController;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\View\Model\ViewModel;
use ZfcUser\Service\User as UserService;
use ZfcUser\Options\UserControllerOptionsInterface;

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
     * @var UserControllerOptionsInterface
     */
    protected $options;

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
        $this->getServiceLocator()->get('zfcuser_user_mapper');
        $request = $this->getRequest();
        $form    = $this->getLoginForm();

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->query()->get('redirect')) {
            $redirect = $request->query()->get('redirect');
        } else {
            $redirect = false;
        }

        if (!$request->isPost()) {
            return array(
                'loginForm' => $form,
                'redirect'  => $redirect,
            );
        }

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

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->post()->get('redirect')) {
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

        if ($request->isPost() && $service->getOptions()->getEnableRegistration()) {
            $data = $request->post()->toArray();
            $user = $service->register($data);
            if (!$user) {
                // todo: PRG
                return array(
                    'registerForm' => $form,
                    'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                );
            }
            if ($service->getOptions()->getLoginAfterRegistration()) {
                $post = $request->post();
                $identityFields = $service->getOptions()->getAuthIdentityFields();
                if (in_array('email', $identityFields)) {
                    $post['identity'] = $user->getEmail();
                } elseif(in_array('username', $identityFields)) {
                    $post['identity'] = $user->getUsername();
                }
                $post['credential'] = $post['password'];
                return $this->forward()->dispatch('zfcuser', array('action' => 'authenticate'));
            }
            return $this->redirect()->toRoute('zfcuser/login');
        }
        return array(
            'registerForm' => $form,
            'enableRegistration' => $this->getOptions()->getEnableRegistration(),
        );
    }

    /**
     * Getters/setters for DI stuff
     */

    public function getUserService()
    {
        if (!$this->userService) {
            $this->userService = $this->getServiceLocator()->get('zfcuser_user_service');
        }
        return $this->userService;
    }

    public function setUserService(UserService $userService)
    {
        $this->userService = $userService;
        return $this;
    }

    public function getLoginForm()
    {
        if (!$this->loginForm) {
            $this->loginForm = $this->getServiceLocator()->get('zfcuser_login_form');
        }
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

    /**
     * set options
     *
     * @param UserControllerOptionsInterface $options
     * @return UserController
     */
    public function setOptions(UserControllerOptionsInterface $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * get options
     *
     * @return UserControllerOptionsInterface
     */
    public function getOptions()
    {
        if (!$this->options instanceof UserControllerOptionsInterface) {
            $this->setOptions($this->getServiceLocator()->get('zfcuser_module_options'));
        }
        return $this->options;
    }
}
