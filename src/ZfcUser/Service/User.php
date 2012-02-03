<?php

namespace ZfcUser\Service;

use Zend\Authentication\AuthenticationService,
    Zend\Form\Form,
    Zend\EventManager\ListenerAggregate,
    DateTime,
    ZfcUser\Util\Password,
    ZfcUser\Model\Mapper\User as UserMapper,
    ZfcUser\Model\Mapper\UserMeta as UserMetaMapper,
    ZfcUser\Module as ZfcUser,
    ZfcBase\EventManager\EventProvider;

class User extends EventProvider
{
    /**
     * @var UserMapper
     */
    protected $userMapper;

    /**
     * @var UserMetaMapper
     */
    protected $userMetaMapper;

    /**
     * @var mixed
     */
    protected $resolvedIdentity;


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
        $user->setEmail($form->getValue('email'))
             ->setPassword(Password::hash($form->getValue('password')))
             ->setRegisterIp($_SERVER['REMOTE_ADDR'])
             ->setRegisterTime(new DateTime('now'))
             ->setEnabled(true);
        if (ZfcUser::getOption('require_activation')) {
            $user->setActive(false);
        } else {
            $user->setActive(true);
        }
        if (ZfcUser::getOption('enable_username')) {
            $user->setUsername($form->getValue('username'));
        }
        if (ZfcUser::getOption('enable_display_name')) {
            $user->setDisplayName($form->getValue('display_name'));
        }

        //trigger pre save event and return the modified user
        $returnUser = $this->events()->trigger('register-pre-save', $this, array('user' => $user, 'form' => $form))->last();
        $user = $returnUser ? $returnUser : $user;
        $this->userMapper->persist($user);
        $this->events()->trigger('register-post-save', $this, array('user' => $user, 'form' => $form));

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
     * @param UserMapper $userMapper 
     * @return User
     */
    public function setUserMapper(UserMapper $userMapper)
    {
        $this->userMapper = $userMapper;
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
