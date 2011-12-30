<?php

namespace EdpUser\Service;

use EdpUser\Authentication\AuthenticationService,
    Zend\Form\Form,
    DateTime,
    EdpUser\Util\Password,
    EdpUser\Mapper\UserInterface as UserMapper,
    EdpUser\Mapper\UserMetaInterface as UserMetaMapper,
    EdpUser\Module,
    EdpCommon\EventManager\EventProvider;

class User extends EventProvider
{
    /**
     * userMapper 
     * 
     * @var UserMapper
     */
    protected $userMapper;

    /**
     * userMetaMapper 
     * 
     * @var UserMetaMapper
     */
    protected $userMetaMapper;

    public function updateMeta($key, $value)
    {
        $user = $this->getAuthService()->getIdentity();
        if (!$userMeta = $this->userMetaMapper->get($user->getUserId(), $key)) {
            $class = Module::getOption('usermeta_model_class');
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
     * @return EdpUser\Entity\User
     */
    public function createFromForm(Form $form)
    {
        $class = Module::getOption('user_model_class');
        $user = new $class;
        $user->setEmail($form->getValue('email'))
             ->setPassword($this->hashPassword($form->getValue('password')))
             ->setRegisterIp($_SERVER['REMOTE_ADDR'])
             ->setRegisterTime(new DateTime('now'))
             ->setEnabled(true);
        if (Module::getOption('require_activation')) {
            $user->setActive(false);
        } else {
            $user->setActive(true);
        }
        if (Module::getOption('enable_username')) {
            $user->setUsername($form->getValue('username'));
        }
        if (Module::getOption('enable_display_name')) {
            $user->setDisplayName($form->getValue('display_name'));
        }
        $this->events()->trigger(__FUNCTION__, $this, array('user' => $user, 'form' => $form));
        $this->userMapper->persist($user);
        return $user;
    }

    protected function hashPassword($password, $salt = false)
    {
        return Password::hash($password, $salt ?: $this->getNewSalt());
    }

    protected function getNewSalt()
    {
        $algorithm = strtolower(Module::getOption('password_hash_algorithm'));
        switch ($algorithm) {
            case 'blowfish':
                $cost = Module::getOption('blowfish_cost');
                break;
            case 'sha512':
                $cost = Module::getOption('sha512_rounds');
                break;
            case 'sha256':
                $cost = Module::getOption('sha256_rounds');
                break;
            default:
                throw new \Exception(sprintf(
                    'Unsupported hashing algorithm: %s',
                    $algorithm
                ));
                break;
        }
        return Password::getSalt($algorithm, (int) $cost);
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

    public function getAuthService()
    {
        if (null === $this->authService) {
            die('asd');
            $this->authService = new AuthenticationService;
        }
        return $this->authService;
    }

    /**
     * setAuthenticationService 
     * 
     * @param mixed $authService 
     * @return User
     */
    public function setAuthService($authService)
    {
        $this->authService = $authService;
        return $this;
    }
}
