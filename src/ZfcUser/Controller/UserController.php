<?php

namespace ZfcUser\Controller;

use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Parameters;
use Zend\View\Model\ViewModel;
use ZfcUser\Service\User as UserService;
use ZfcUser\Options\UserControllerOptionsInterface;

class UserController extends AbstractActionController
{
    const ROUTE_CHANGEPASSWD = 'zfcuser/changepassword';
    const ROUTE_LOGIN        = 'zfcuser/login';
    const ROUTE_REGISTER     = 'zfcuser/register';
    const ROUTE_CHANGEEMAIL  = 'zfcuser/changeemail';

    const CONTROLLER_NAME    = 'zfcuser';

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
     * @var Form
     */
    protected $changePasswordForm;

    /**
     * @var Form
     */
    protected $changeEmailForm;

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
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
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

        $redirect = $this->redirectUrl();

        if (!$request->isPost()) {
            return array(
                'loginForm' => $form,
                'redirect'  => $redirect,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
            );
        }

        $form->setData($request->getPost());

        if (!$form->isValid()) {
            $this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($this->failedLoginMessage);
            return $this->toRouteWithRedirect(static::ROUTE_LOGIN, $redirect);
        }

        // clear adapters
        $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
        $this->zfcUserAuthentication()->getAuthService()->clearIdentity();

        return $this->forward()->dispatch(static::CONTROLLER_NAME, array('action' => 'authenticate'));
    }

    /**
     * Logout and clear the identity
     */
    public function logoutAction()
    {
        $this->logout();

        $redirect = $this->redirectUrl();

        if ($redirect) {
            return $this->redirect()->toUrl($redirect);
        }

        return $this->redirect()->toRoute($this->getOptions()->getLogoutRedirectRoute());
    }

    /**
     * General-purpose authentication action
     */
    public function authenticateAction()
    {
        if ($this->zfcUserAuthentication()->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $post = $this->getRequest()->getPost();

        $result = $this->login(
            $post->get('identity'),
            $post->get('credential')
        );

        if ($result instanceof Response) {
            return $result;
        }

        $redirect = $this->redirectUrl();

        if (!$result->isValid()) {
            $this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($this->failedLoginMessage);

            $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();

            return $this->toRouteWithRedirect(static::ROUTE_LOGIN, $redirect);
        }

        if ($redirect) {
            return $this->redirect()->toUrl($redirect);
        }

        return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
    }

    /**
     * Register new user
     */
    public function registerAction()
    {
        // if the user is logged in, we don't need to register
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            // redirect to the login redirect route
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
        // if registration is disabled
        if (!$this->getOptions()->getEnableRegistration()) {
            return array('enableRegistration' => false);
        }

        $request = $this->getRequest();
        $service = $this->getUserService();
        $form = $this->getRegisterForm();
        $form->setHydrator($this->getFormHydrator());

        $redirect = $this->redirectUrl();

        $viewParams = array(
            'registerForm'       => $form,
            'enableRegistration' => $this->getOptions()->getEnableRegistration(),
            'redirect'           => $redirect,
        );
        $redirectUrl = $this->urlWithRedirect(static::ROUTE_REGISTER, $redirect);

        $prg = $this->runPrg($redirectUrl, $viewParams);
        if (!is_array($prg)) {
            return $prg;
        }

        $class = $this->getOptions()->getUserEntityClass();
        $user  = new $class;
        $form->bind($user);

        $form->setData($prg);
        if (!$form->isValid()) {
            return $viewParams;
        }

        $redirect = isset($prg['redirect']) ? $prg['redirect'] : null;

        if (!$service->register($user)) {
            return $viewParams;
        }

        if (!$service->getOptions()->getLoginAfterRegistration()) {
            return $this->toRouteWithRedirect(static::ROUTE_LOGIN, $redirect);
        }

        // @todo Add a getIdentity() method to the UserInterface?
        $identityFields = $service->getOptions()->getAuthIdentityFields();

        if (in_array('email', $identityFields)) {
            $identity = $user->getEmail();
        } elseif (in_array('username', $identityFields)) {
            $identity = $user->getUsername();
        }

        // @todo Add getIdentity() & getCredential() use Register form
        $request->setPost(new Parameters(array(
            'identity' => $identity,
            'credential' => $form->get('password')->getValue(),
            'redirect' => $redirect,
        )));

        // @todo Extract the authentication part out of authenticateAction and have it called
        // by authenticateAction, loginAction and here to avoid the pointless rewriting of this
        // post parameters.
        return $this->forward()->dispatch(static::CONTROLLER_NAME, array('action' => 'authenticate'));
    }

    /**
     * Change the users password
     */
    public function changepasswordAction()
    {
        // if the user isn't logged in, we can't change password
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            // redirect to the login redirect route
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $form = $this->getChangePasswordForm();

        $fm = $this->flashMessenger()->setNamespace('change-password')->getMessages();
        $status = (isset($fm[0])) ? $fm[0] : null;

        $prg = $this->runPrg(
            $this->url()->fromRoute(static::ROUTE_CHANGEPASSWD),
            array(
                'status' => $status,
                'changePasswordForm' => $form,
            )
        );
        if (!is_array($prg)) {
            return $prg;
        }

        $form->setData($prg);

        if (!$form->isValid()) {
            return array(
                'status' => false,
                'changePasswordForm' => $form,
            );
        }

        if (!$this->getUserService()->changePassword($form->getData())) {
            return array(
                'status' => false,
                'changePasswordForm' => $form,
            );
        }

        $this->flashMessenger()->setNamespace('change-password')->addMessage(true);

        return $this->redirect()->toRoute(static::ROUTE_CHANGEPASSWD);
    }

    public function changeEmailAction()
    {
        // if the user isn't logged in, we can't change email
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            // redirect to the login redirect route
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $form = $this->getChangeEmailForm();
        $request = $this->getRequest();
        $request->getPost()->set('identity', $this->getUserService()->getAuthService()->getIdentity()->getEmail());

        $fm = $this->flashMessenger()->setNamespace('change-email')->getMessages();
        $status = (isset($fm[0])) ? $fm[0] : null;

        $prg = $this->runPrg(
            $this->url()->fromRoute(static::ROUTE_CHANGEEMAIL),
            array(
                'status' => $status,
                'changeEmailForm' => $form,
            )
        );
        if (!is_array($prg)) {
            return $prg;
        }

        $form->setData($prg);

        if (!$form->isValid()) {
            return array(
                'status' => false,
                'changeEmailForm' => $form,
            );
        }

        $change = $this->getUserService()->changeEmail($prg);

        if (!$change) {
            $this->flashMessenger()->setNamespace('change-email')->addMessage(false);
            return array(
                'status' => false,
                'changeEmailForm' => $form,
            );
        }

        $this->flashMessenger()->setNamespace('change-email')->addMessage(true);

        return $this->redirect()->toRoute(static::ROUTE_CHANGEEMAIL);
    }

    /*
     * Internal methods
     * @todo Refactor these out of the controller as appropriate.
     */

    /**
     * 
     * @param  string $redirectUrl
     * @param  array  $firstTimeParams
     * @return Response|ViewModel|array
     */
    protected function runPrg($redirectUrl, array $firstTimeParams)
    {
        $prg = $this->prg($redirectUrl, true);

        if ($prg === false) {
            // By returning ViewModel instead on an array allow a simple
            // single call to is_array() on the return value
            return new ViewModel($firstTimeParams);
        }

        return $prg;
    }

    /**
     * Returns the redirect url to be used.
     *
     * @return string|false
     */
    protected function redirectUrl()
    {
        if (!$this->getOptions()->getUseRedirectParameterIfPresent()) {
            return false;
        }

        return $this->params()->fromPost('redirect', $this->params()->fromQuery('redirect', false));
    }

    /**
     * Returns a response to redirect to the given route and and pass on the redirect GET var.
     *
     * @param  string $route
     * @param  string $redirect
     * @return Response
     */
    protected function toRouteWithRedirect($route, $redirect)
    {
        return $this->redirect()->toUrl(
            $this->urlWithRedirect($route, $redirect)
        );
    }

    /**
     *  Returns the url to given route and and pass on the redirect GET var.
     *
     * @param  string $route
     * @param  string $redirect
     * @return Response
     */
    protected function urlWithRedirect($route, $redirect)
    {
        return $this->url()->fromRoute($route)
            . ($redirect ? '?redirect='.$redirect : '');
    }
    /**
     * Logs a user in
     *
     * @todo Move this to a authentication service
     *
     * @param  string $identity
     * @param  string $credential
     * @return \Zend\Authentication\Result|Response
     */
    protected function login($identity, $credential)
    {
        $adapter = $this->zfcUserAuthentication()->getAuthAdapter();

        $result = $adapter->prepareForAuthentication($identity, $credential);

        // Return early if an adapter returned a response
        if ($result instanceof Response) {
            return $result;
        }

        return $this->zfcUserAuthentication()->getAuthService()->authenticate($adapter);
    }

    /**
     * Clears the current identity.
     *
     * @todo Move this to a authentication service
     */
    protected function logout()
    {
        $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
        $this->zfcUserAuthentication()->getAuthAdapter()->logoutAdapters();
        $this->zfcUserAuthentication()->getAuthService()->clearIdentity();
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

    public function getRegisterForm()
    {
        if (!$this->registerForm) {
            $this->setRegisterForm($this->getServiceLocator()->get('zfcuser_register_form'));
        }
        return $this->registerForm;
    }

    public function setRegisterForm(Form $registerForm)
    {
        $this->registerForm = $registerForm;
    }

    public function getLoginForm()
    {
        if (!$this->loginForm) {
            $this->setLoginForm($this->getServiceLocator()->get('zfcuser_login_form'));
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

    public function getChangePasswordForm()
    {
        if (!$this->changePasswordForm) {
            $this->setChangePasswordForm($this->getServiceLocator()->get('zfcuser_change_password_form'));
        }
        return $this->changePasswordForm;
    }

    public function setChangePasswordForm(Form $changePasswordForm)
    {
        $this->changePasswordForm = $changePasswordForm;
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

    /**
     * Get changeEmailForm.
     *
     * @return changeEmailForm.
     */
    public function getChangeEmailForm()
    {
        if (!$this->changeEmailForm) {
            $this->setChangeEmailForm($this->getServiceLocator()->get('zfcuser_change_email_form'));
        }
        return $this->changeEmailForm;
    }

    /**
     * Set changeEmailForm.
     *
     * @param changeEmailForm the value to set.
     */
    public function setChangeEmailForm($changeEmailForm)
    {
        $this->changeEmailForm = $changeEmailForm;
        return $this;
    }

    /**
     * Return the Form Hydrator
     *
     * @return \Zend\Stdlib\Hydrator\ClassMethods
     */
    public function getFormHydrator()
    {
        if (!$this->formHydrator) {
            $this->setFormHydrator($this->getServiceLocator()->get('zfcuser_register_form_hydrator'));
        }

        return $this->formHydrator;
    }

    /**
     * Set the Form Hydrator to use
     *
     * @param ClassMethods $formHydrator
     * @return User
     */
    public function setFormHydrator(ClassMethods $formHydrator)
    {
        $this->formHydrator = $formHydrator;
        return $this;
    }
}
