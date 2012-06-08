<?php

namespace ZfcUser\Service;

use DateTime;
use Zend\Authentication\AuthenticationService;
use Zend\Form\Form;
use ZfcBase\EventManager\EventProvider;
use ZfcBase\Mapper\DataMapperInterface as UserMapper;
use ZfcUser\Mapper\UserMetaInterface as UserMetaMapper;
use ZfcUser\Module as ZfcUser;
use ZfcUser\Repository\UserInterface as UserRepositoryInterface;
use ZfcUser\Util\Password;

class User extends EventProvider
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


    public function updateMeta($key, $value)
    {
        $user = $this->getAuthService()->getIdentity();
        if (!$userMeta = $this->userMetaMapper->get($user->getUserId(), $key)) {
            $class = $this->userRepository->getClassName();
            $userMeta = new $class;
            $userMeta->setUser($user);
            $userMeta->setMetaKey($key);
            $userMeta->setMeta($value);
            $this->userMetaMapper->persist($userMeta);
        }
        if (!$userMeta->getUser()) {
            $userMeta->setUser($user);
        }
        $userMeta->setMeta($value);
        $this->userMetaMapper->persist($userMeta);
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
        $class = $this->userRepository->getClassName();
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
        $this->userMapper->persist($user);
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
     * setUserMapper
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
            $this->authService = new AuthenticationService;
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
    public function getRegisterForm()
    {
        return $this->registerForm;
    }


}
