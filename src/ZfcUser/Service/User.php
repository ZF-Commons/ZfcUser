<?php

namespace ZfcUser\Service;

use DateTime;
use Zend\Authentication\AuthenticationService;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use ZfcBase\Mapper\DataMapperInterface as UserMapper;
use ZfcUser\Mapper\UserMetaInterface as UserMetaMapper;
use ZfcUser\Module as ZfcUser;
use ZfcUser\Repository\UserInterface as UserRepositoryInterface;
use ZfcUser\Util\Password;

class User extends EventProvider implements ServiceManagerAwareInterface
{

    /**
     * @var UserMapper
     */
    protected $userMapper;

    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * @var UserMetaMapper
     */
    protected $userMetaMapper;

    /**
     * @var mixed
     */
    protected $resolvedIdentity;

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
     * @var ServiceManager
     */
    protected $serviceManager;

    public function updateMeta($key, $value)
    {
        $user = $this->getAuthService()->getIdentity();
        if (!$userMeta = $this->getUserMetaMapper()->get($user->getUserId(), $key)) {
            $class = $this->getUserRepository()->getClassName();
            $userMeta = new $class;
            $userMeta->setUser($user);
            $userMeta->setMetaKey($key);
            $userMeta->setMeta($value);
            $this->getUserMetaMapper()->persist($userMeta);
        }
        if (!$userMeta->getUser()) {
            $userMeta->setUser($user);
        }
        $userMeta->setMeta($value);
        $this->getUserMetaMapper()->persist($userMeta);
    }

    /**
     * createFromForm
     *
     * @param array $data
     * @return \ZfcUser\Model\UserInterface
     * @throws Exception\InvalidArgumentException
     */
    public function register(array $data)
    {
        $class = $this->getUserRepository()->getClassName();
        $user = new $class;

        $form = $this->getRegisterForm();
        $form->bind($user);
        $form->setData($data);
        if (!$form->isValid()) {
            throw new Exception\InvalidArgumentException('invalid data');
        }

        $user = $form->getData();
        /* @var $user \ZfcUser\Model\UserInterface */

        $user->setPassword(Password::hash($user->getPassword()));
        $user->setRegisterTime(new DateTime('now'));
        $user->setRegisterIp($_SERVER['REMOTE_ADDR']);
        $user->setEnabled(true);

        if (ZfcUser::getOption('require_activation')) {
            $user->setActive(false);
        } else {
            $user->setActive(true);
        }
        if (ZfcUser::getOption('enable_username')) {
            $user->setUsername($data['username']);
        }
        if (ZfcUser::getOption('enable_display_name')) {
            $user->setDisplayName($data['display_name']);
        }
        $this->events()->trigger(__FUNCTION__, $this, array('user' => $user, 'form' => $form));
        $this->getUserMapper()->persist($user);
        return $user;
    }

    /**
     * Get a user entity by their username
     *
     * @param string $username
     * @return \ZfcUser\Model\UserInterface
     */
    public function getByUsername($username)
    {
        return $this->userRepository->findByUsername($username);
    }

    /**
     * getUserRepository
     * 
     * @return UserRepositoryInterface
     */
    public function getUserRepository()
    {
        if (null === $this->userRepository) {
            $this->userRepository = $this->getServiceManager()->get('zfcuser_user_repository');
        }
        return $this->userRepository;
    }

    /**
     * setUserRepository
     *
     * @param UserRepositoryInterface $userMapper
     * @return User
     */
    public function setUserRepository(UserRepositoryInterface $userMapper)
    {
        $this->userRepository = $userMapper;
        return $this;
    }

    /**
     * getUserMetaMapper 
     * 
     * @return UserMetaMapper
     */
    public function getUserMetaMapper()
    {
        if (null === $this->userMetaMapper) {
            $this->userMetaMapper = $this->getServiceManager()->get('zfcuser_usermeta_mapper');
        }
        return $this->userMetaMapper;
    }

    /**
     * setUserMetaMapper
     *
     * @param UserMetaMapper $userMetaMapper
     * @return User
     */
    public function setUserMetaMapper(UserMetaMapper $userMetaMapper)
    {
        $this->userMetaMapper = $userMetaMapper;
        return $this;
    }
    
    /**
     * getUserMapper 
     * 
     * @return UserMapper
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
     * @param UserMapper $userMapper
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
     * @param ServiceManager $locator
     * @return void
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }
}
