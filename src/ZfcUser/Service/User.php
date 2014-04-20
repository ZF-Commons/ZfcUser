<?php

namespace ZfcUser\Service;

use Zend\Authentication\AuthenticationService;
use Zend\Form\FormInterface;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Crypt\Password\Bcrypt;
use ZfcBase\EventManager\EventProvider;
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
     * @var FormInterface
     */
    protected $changeEmailForm;

    /**
     * @var FormInterface
     */
    protected $changePasswordForm;

    /**
     * @var FormInterface
     */
    protected $registrationForm;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var UserServiceOptionsInterface
     */
    protected $options;

    /**
     * createFromForm
     *
     * @param array $data
     * @return \ZfcUser\Entity\UserInterface
     * @throws Exception\InvalidArgumentException
     */
    public function register(array $data)
    {
        $form = $this->getRegistrationForm();
        $form->setData($data);

        if (!$form->isValid()) {
            return false;
        }

        // TODO: Move to hydrator
        /* @var \ZfcUser\Entity\UserInterface $user */
        $bcrypt = new Bcrypt();
        $bcrypt->setCost($this->getOptions()->getPasswordCost());
        $user = $form->getData();
        $user->setPassword($bcrypt->create($user->getPassword()));

        // If user state is enabled, set the default state value
        if ($this->getOptions()->getEnableUserState() && $this->getOptions()->getDefaultUserState()) {
            // TODO: Possibly move to hydration
            $user->setState($this->getOptions()->getDefaultUserState());
        }

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $user));
        $this->getUserMapper()->insert($user);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('user' => $user));

        return $user;
    }

    /**
     * change the current users password
     *
     * @param array $data
     * @return boolean
     */
    public function changePassword(array $data)
    {
        $form = $this->getChangePasswordForm();
        $form->setData($data);

        if (!$form->isValid()) {
            return false;
        }

        // TODO: Move to form filter
        $bcrypt = new Bcrypt();
        $bcrypt->setCost($this->getOptions()->getPasswordCost());

        /* @var \ZfcUser\Entity\UserInterface $user */
        // TODO: Possibly move to hydration
        $user = $this->getAuthService()->getIdentity();
        $user->setPassword($bcrypt->create($data['newCredential']));

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $user));
        $this->getUserMapper()->update($user);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('user' => $user));

        return true;
    }

    public function changeEmail(array $data)
    {
        $form = $this->getChangeEmailForm();
        $form->setData($data);

        if (!$form->isValid()) {
            return false;
        }

        /* @var \ZfcUser\Entity\UserInterface $user */
        // TODO: Possibly move to hydration
        $user = $this->getAuthService()->getIdentity();
        $user->setEmail($data['newIdentity']);

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $user));
        $this->getUserMapper()->update($user);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('user' => $user));

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
     * @return FormInterface
     */
    public function getChangeEmailForm()
    {
        if (null === $this->changeEmailForm) {
            $fem = $this->getServiceManager()->get('FormElementManager');
            $this->setChangeEmailForm($fem->get('ZfcUser\Form\ChangeEmailForm'));
        }

        return $this->changeEmailForm;
    }

    /**
     * @param FormInterface $form
     */
    public function setChangeEmailForm(FormInterface $form)
    {
        $this->changeEmailForm = $form;
    }

    /**
     * @return FormInterface
     */
    public function getChangePasswordForm()
    {
        if (null === $this->changePasswordForm) {
            $fem = $this->getServiceManager()->get('FormElementManager');
            $this->setChangePasswordForm($fem->get('ZfcUser\Form\ChangePasswordForm'));
        }

        return $this->changePasswordForm;
    }

    /**
     * @param FormInterface $form
     */
    public function setChangePasswordForm(FormInterface $form)
    {
        $this->changePasswordForm = $form;
    }

    /**
     * @return FormInterface
     */
    public function getRegistrationForm()
    {
        if (null === $this->registrationForm) {
            $fem = $this->getServiceManager()->get('FormElementManager');
            $this->setRegistrationForm($fem->get('ZfcUser\Form\RegistrationForm'));
        }

        return $this->registrationForm;
    }

    /**
     * @param FormInterface $form
     */
    public function setRegistrationForm(FormInterface $form)
    {
        $this->registrationForm = $form;
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
}
