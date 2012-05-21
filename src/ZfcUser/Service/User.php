<?php

namespace ZfcUser\Service;

use Zend\Authentication\AuthenticationService,
    Zend\Form\Form,
    Zend\EventManager\ListenerAggregate,
    DateTime,
    ZfcUser\Util\Password,
    ZfcUser\Model\UserMapperInterface,
    ZfcUser\Model\UserMetaMapperInterface,
    ZfcUser\Module as ZfcUser,
    ZfcBase\EventManager\EventProvider;

class User extends EventProvider
{
    /**
     * @var UserMapperInterface
     */
    protected $userMapper;

    /**
     * @var UserMetaMapperInterface
     */
    protected $userMetaMapper;

    /**
     * @var mixed
     */
    protected $resolvedIdentity;

    /**
     * @var authService
     */
    protected $authService;

    public function updateMeta($key, $value)
    {
        $user = $this->getAuthService()->getIdentity();
        if (!$userMeta = $this->userMetaMapper->get($user->getUserId(), $key)) {
            $class = ZfcUser::getOption('usermeta_model_class');
            $userMeta = new $class;
            $userMeta->setUser($user);
            $userMeta->setMetaKey($key);
            $userMeta->setMeta($value);
            $this->userMetaMapper->add($userMeta);
        }
        if (!$userMeta->getUser()) {
            $userMeta->setUser($user);
        }
        $userMeta->setMeta($value);
        $this->userMetaMapper->update($userMeta);
    }

    /**
     * createFromForm
     *
     * @param Form $form
     * @return ZfcUser\Model\User
     */
    public function createFromForm(Form $form)
    {
        $class = ZfcUser::getOption('user_model_class');
        $user = new $class;

        $data = $form->getData();

        $user->setEmail($data['email'])
             ->setPassword(Password::hash($data['password']))
             ->setRegisterIp($_SERVER['REMOTE_ADDR'])
             ->setRegisterTime(new DateTime('now'))
             ->setEnabled(true);
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
     * @return ZfcUser\Model\User
     */
    public function getByUsername($username)
    {
        return $this->userMapper->findByUsername($username);
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
     * setUserMetaMapper
     *
     * @param UserMetaMapperInterface $userMetaMapper
     * @return User
     */
    public function setUserMetaMapper(UserMetaMapperInterface $userMetaMapper)
    {
        $this->userMetaMapper = $userMetaMapper;
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
}
