<?php

namespace ZfcUser\Service;

use Zend\Authentication\AuthenticationServiceInterface as AuthenticationService;
use Zend\Form\FormInterface as Form;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use ZfcBase\EventManager\EventProvider;
use ZfcUser\Mapper\HydratorInterface as Hydrator;
use ZfcUser\Mapper\UserInterface as UserMapper;
use ZfcUser\Options\UserServiceOptionsInterface as ServiceOptions;

class User extends EventProvider implements ServiceManagerAwareInterface
{
    /**
     * @var UserMapper
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
     * @var ServiceOptions
     */
    protected $options;

    /**
     * @var Hydrator
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
        $entityClass = $this->getOptions()->getUserEntityClass();
        $form        = $this->getRegisterForm();

        $form->setHydrator($this->getFormHydrator());
        $form->bind(new $entityClass);
        $form->setData($data);

        if ($form->isValid()) {
            $user   = $form->getData();
            $events = $this->getEventManager();

            $user->setPassword($this->getFormHydrator()->getCryptoService()->create($user->getPassword()));

            $events->trigger(__FUNCTION__, $this, compact('user', 'form'));
            $this->getUserMapper()->insert($user);
            $events->trigger(__FUNCTION__.'.post', $this, compact('user', 'form'));

            return $user;
        }
        return false;
    }

    /**
     * change the current users password
     *
     * @param array $data
     * @return boolean
     */
    public function changePassword(array $data)
    {
        if (!$user = $this->guardValidCredential($data['credential'])) {
            return false;
        }

        $user->setPassword($this->getFormHydrator()->getCryptoService()->create($data['newCredential']));

        $events = $this->getEventManager();

        $events->trigger(__FUNCTION__, $this, compact('user'));
        $this->getUserMapper()->update($user);
        $events->trigger(__FUNCTION__.'.post', $this, compact('user'));

        return true;
    }

    public function changeEmail(array $data)
    {
        if (!$user = $this->guardValidCredential($data['credential'])) {
            return false;
        }

        $user = $this->getFormHydrator()->hydrate(
            array('email' => trim($data['newIdentity'])),
            $user
        );
        $events = $this->getEventManager();

        $events->trigger(__FUNCTION__, $this, compact('user'));
        $this->getUserMapper()->update($user);
        $events->trigger(__FUNCTION__.'.post', $this, compact('user'));

        return true;
    }

    /**
     * Verify the credential
     *
     * @param  string $credential
     * @return bool|\ZfcUser\Entity\UserInterface
     */
    private function guardValidCredential($credential)
    {
        /* @var $currentUser \ZfcUser\Entity\UserInterface */
        if ($currentUser = $this->getAuthService()->getIdentity()) {
            $cryptoService = $this->getFormHydrator()->getCryptoService();
            if ($cryptoService->verify($credential, $currentUser->getPassword())) {
                return $currentUser;
            }
        }

        return false;
    }

    /**
     * getUserMapper
     *
     * @return UserMapper
     */
    public function getUserMapper()
    {
        if (null === $this->userMapper) {
            $this->setUserMapper($this->serviceManager->get('zfcuser_user_mapper'));
        }
        return $this->userMapper;
    }

    /**
     * setUserMapper
     *
     * @param UserMapperInterface $userMapper
     * @return User
     */
    public function setUserMapper(UserMapper $userMapper)
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
            $this->setAuthService($this->serviceManager->get('zfcuser_auth_service'));
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
            $this->setRegisterForm($this->serviceManager->get('zfcuser_register_form'));
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
            $this->setChangePasswordForm(
                $this->serviceManager->get('zfcuser_change_password_form')
            );
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
        if (!$this->options instanceof ServiceOptions) {
            $this->setOptions($this->serviceManager->get('zfcuser_module_options'));
        }
        return $this->options;
    }

    /**
     * set service options
     *
     * @param ServiceOptions $options
     */
    public function setOptions(ServiceOptions $options)
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
     * @return Hydrator
     */
    public function getFormHydrator()
    {
        if (!$this->formHydrator instanceof Hydrator) {
            $this->setFormHydrator(
                $this->serviceManager->get('zfcuser_user_hydrator')
            );
        }

        return $this->formHydrator;
    }

    /**
     * Set the Form Hydrator to use
     *
     * @param Hydrator $formHydrator
     * @return User
     */
    public function setFormHydrator(Hydrator $formHydrator)
    {
        $this->formHydrator = $formHydrator;
        return $this;
    }
}
