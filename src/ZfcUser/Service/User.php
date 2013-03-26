<?php

namespace ZfcUser\Service;

use Zend\Authentication\AuthenticationService;
use Zend\Form\Form;
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Crypt\Password\Bcrypt;
use Zend\Stdlib\Hydrator;
use Zend\View\Renderer\RendererInterface;
use ZfcBase\EventManager\EventProvider;
use ZfcUser\Entity\UserInterface;
use ZfcUser\Mapper\UserInterface as UserMapperInterface;
use ZfcUser\Options\UserServiceOptionsInterface;


class User extends EventProvider implements ServiceManagerAwareInterface
{

    /**
     * @var UserMapperInterface
     */
    protected $userMapper;

    /**
     * @var AuthenticationService
     */
    protected $authService;

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
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var UserServiceOptionsInterface
     */
    protected $options;

    /**
     * @var Hydrator\ClassMethods
     */
    protected $formHydrator;

    /**
     * createFromForm
     *
     * @param array $data
     * @return \ZfcUser\Entity\UserInterface
     * @throws Exception\InvalidArgumentException
     */
    public function register(array $data)
    {
        $class = $this->getOptions()->getUserEntityClass();
        $user  = new $class;
        $form  = $this->getRegisterForm();
        $form->setHydrator($this->getFormHydrator());
        $form->bind($user);
        $form->setData($data);
        if (!$form->isValid()) {
            return false;
        }

        $user = $form->getData();
        /* @var $user \ZfcUser\Entity\UserInterface */

        $bcrypt = new Bcrypt;
        $bcrypt->setCost($this->getOptions()->getPasswordCost());
        $user->setPassword($bcrypt->create($user->getPassword()));

        if ($this->getOptions()->getEnableUsername()) {
            $user->setUsername($data['username']);
        }
        if ($this->getOptions()->getEnableDisplayName()) {
            $user->setDisplayName($data['display_name']);
        }

        // If user state is enabled, set the default state value
        if ($this->getOptions()->getEnableUserState()) {
            if ($this->getOptions()->getDefaultUserState()) {
                $user->setState($this->getOptions()->getDefaultUserState());
            }
        }
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $user, 'form' => $form));
        $this->getUserMapper()->insert($user);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('user' => $user, 'form' => $form));
        return $user;
    }

    /**
     * Determine if a forgot token hash is valid and, if so, return the user it belongs to.
     *
     * @param string $token
     * @return null|\ZfcUser\Entity\UserInterface
     */
    public function getUserFromForgotToken($token)
    {
        $data = $this->base64UrlDecode($token);

        if (!$data || !isset($data['email']) || !isset($data['token'])) {
            return null;
        }

        $user = $this->getUserMapper()->findByEmail($data['email']);
        if (!$user) {
            return null;
        }

        if ($user->getForgotToken() == $data['token']) {
            return $user;
        }

        return null;
    }

    /**
     * send user a forgot password email
     *
     * @param array $data
     * @return boolean
     */
    public function sendForgotPassword($identity, TransportInterface $transport, RendererInterface $renderer)
    {
        $mapper = $this->getUserMapper();
        /** @var $user \ZfcUser\Entity\User */
        $user   = $mapper->findByEmail($identity);

        if (!$user) {
            return false;
        }

        $token   = substr(md5($user->getEmail() . microtime(true)), 0, 8);
        $encoded = array(
            'email' => $user->getEmail(),
            'token' => $token
        );
        $body  = $renderer->render(
            $this->getOptions()->getForgotMailTemplate(),
            array(
                'token'   => $this->base64UrlEncode($encoded),
                'user'    => $user,
                'options' => $this->getOptions()
            )
        );

        $user->setForgotTimestamp(new \DateTime('now'));
        $user->setForgotToken($token);
        $this->getUserMapper()->update($user);

        $message = new Message();
        $message->addTo($user->getEmail())
                ->addFrom($this->getOptions()->getMailFromAddress(), $this->getOptions()->getMailFromName())
                ->setSubject($this->getOptions()->getForgotMailSubject())
                ->setBody($body);

        $transport->send($message);
        return true;
    }

    /**
     * Change the users password. Defaults to current user if one is not given.
     *
     * @param array $data
     * @param boolean $verify
     * @param UserInterface|null $currentUser
     * @return boolean
     */
    public function changePassword(array $data, $verify = true, UserInterface $currentUser = null)
    {
        if (!$currentUser) {
            $currentUser = $this->getAuthService()->getIdentity();
        }

        $bcrypt = new Bcrypt;
        $bcrypt->setCost($this->getOptions()->getPasswordCost());

        if ($verify && !$bcrypt->verify($data['credential'], $currentUser->getPassword())) {
            return false;
        }

        $pass = $bcrypt->create($data['newCredential']);
        $currentUser->setPassword($pass);

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $currentUser));
        $this->getUserMapper()->update($currentUser);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('user' => $currentUser));

        return true;
    }

    public function changeEmail(array $data)
    {
        $currentUser = $this->getAuthService()->getIdentity();

        $bcrypt = new Bcrypt;
        $bcrypt->setCost($this->getOptions()->getPasswordCost());

        if (!$bcrypt->verify($data['credential'], $currentUser->getPassword())) {
            return false;
        }

        $currentUser->setEmail($data['newIdentity']);

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $currentUser));
        $this->getUserMapper()->update($currentUser);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('user' => $currentUser));

        return true;
    }

    /**
     * getUserMapper
     *
     * @return UserMapperInterface
     */
    public function getUserMapper()
    {
        if (null === $this->userMapper) {
            $this->userMapper = $this->getServiceManager()->get('zfcuser_user_mapper');
        }
        return $this->userMapper;
    }

    /**
     * setUserMapper
     *
     * @param UserMapperInterface $userMapper
     * @return User
     */
    public function setUserMapper(UserMapperInterface $userMapper)
    {
        $this->userMapper = $userMapper;
        return $this;
    }

    /**
     * getAuthService
     *
     * @return AuthenticationService
     */
    public function getAuthService()
    {
        if (null === $this->authService) {
            $this->authService = $this->getServiceManager()->get('zfcuser_auth_service');
        }
        return $this->authService;
    }

    /**
     * setAuthenticationService
     *
     * @param AuthenticationService $authService
     * @return User
     */
    public function setAuthService(AuthenticationService $authService)
    {
        $this->authService = $authService;
        return $this;
    }

    /**
     * @return Form
     */
    public function getRegisterForm()
    {
        if (null === $this->registerForm) {
            $this->registerForm = $this->getServiceManager()->get('zfcuser_register_form');
        }
        return $this->registerForm;
    }

    /**
     * @param Form $registerForm
     * @return User
     */
    public function setRegisterForm(Form $registerForm)
    {
        $this->registerForm = $registerForm;
        return $this;
    }

    /**
     * @return Form
     */
    public function getChangePasswordForm()
    {
        if (null === $this->changePasswordForm) {
            $this->changePasswordForm = $this->getServiceManager()->get('zfcuser_change_password_form');
        }
        return $this->changePasswordForm;
    }

    /**
     * @param Form $changePasswordForm
     * @return User
     */
    public function setChangePasswordForm(Form $changePasswordForm)
    {
        $this->changePasswordForm = $changePasswordForm;
        return $this;
    }

    /**
     * get service options
     *
     * @return UserServiceOptionsInterface
     */
    public function getOptions()
    {
        if (!$this->options instanceof UserServiceOptionsInterface) {
            $this->setOptions($this->getServiceManager()->get('zfcuser_module_options'));
        }
        return $this->options;
    }

    /**
     * set service options
     *
     * @param UserServiceOptionsInterface $options
     */
    public function setOptions(UserServiceOptionsInterface $options)
    {
        $this->options = $options;
    }

    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager instance
     *
     * @param ServiceManager $serviceManager
     * @return User
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     * Return the Form Hydrator
     *
     * @return \Zend\Stdlib\Hydrator\ClassMethods
     */
    public function getFormHydrator()
    {
        if (!$this->formHydrator instanceof Hydrator\ClassMethods) {
            $this->setFormHydrator($this->getServiceManager()->get('zfcuser_register_form_hydrator'));
        }

        return $this->formHydrator;
    }

    /**
     * Set the Form Hydrator to use
     *
     * @param Hydrator\ClassMethods $formHydrator
     * @return User
     */
    public function setFormHydrator(Hydrator\ClassMethods $formHydrator)
    {
        $this->formHydrator = $formHydrator;
        return $this;
    }

    /**
     * Encode to url-safe base64.
     *
     * @param array $input
     * @return string
     */
    protected function base64UrlEncode(array $input)
    {
        if (!is_array($input)) {
            throw new Exception\InvalidArgumentException(
                'base64UrlEncode requires an array input'
            );
        }
        return strtr(base64_encode(serialize($input)), '+/=', '-_.');
    }

    /**
     * Decode url-safe base64.
     *
     * @param string $input
     * @return null|array
     */
    protected function base64UrlDecode($input)
    {
        $input = base64_decode(strtr($input, '-_.', '+/='));
        if (!$input) {
            return null;
        }

        $input = unserialize($input);
        if (!$input) {
            return null;
        }
        return $input;
    }
}
