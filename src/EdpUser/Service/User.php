<?php

namespace EdpUser\Service;

use Zend\Authentication\AuthenticationService,
    Zend\Form\Form,
    DateTime,
    EdpUser\Mapper\UserInterface as UserMapper,
    EdpUser\Module,
    Zend\EventManager\EventCollection,
    Zend\EventManager\EventManager,
    EdpUser\Util\Password;

class User
{
    /**
     * @var Zend\Authentication\AuthenticationService
     */
    protected $authService;

    /**
     * userMapper 
     * 
     * @var UserMapper
     */
    protected $userMapper;

    /**
     * @var EventCollection
     */
    protected $events;

    /**
     * authenticate 
     * 
     * @param string $identity 
     * @param string $credential 
     * @return bool
     */
    public function authenticate($identity, $credential)
    {
        $authService = $this->getAuthService();
        // Auth by email
        $userEntity = $this->userMapper->findByEmail($identity);
        if ($userEntity !== null) {
            $credentialHash = $this->hashPassword($credential, $userEntity->getPassword());
            $adapter        = $this->userMapper->getAuthAdapter($identity, $credentialHash, 'email');
            $result         = $authService->authenticate($adapter);
            if ($result->isValid()) {
                $this->events()->trigger(__FUNCTION__ . '.success', $this, array('user' => $userEntity));
                $this->updateUserLastLogin($userEntity);
                $authService->getStorage()->write($userEntity);
                $this->updateUserPasswordHash($userEntity, $password);
                return true;
            }
        }
        if (Module::getOption('enable_username')) {
            $userEntity = $this->userMapper->findByUsername($identity);
            if ($userEntity !== null) {
                $credentialHash = $this->hashPassword($credential, $userEntity->getPassword());
                $adapter        = $this->userMapper->getAuthAdapter($identity, $credentialHash, 'username');
                $result         = $authService->authenticate($adapter);
                if ($result->isValid()) {
                    $this->events()->trigger(__FUNCTION__ . '.success', $this, array('user' => $userEntity));
                    $this->updateUserLastLogin($userEntity);
                    $authService->getStorage()->write($userEntity);
                    $this->updateUserPasswordHash($userEntity, $password);
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * updateUserLastLogin 
     * 
     * @param EdpUser\Entity\User $user 
     * @return void
     */
    protected function updateUserLastLogin($user)
    {
        $user->setLastLogin(new DateTime('now'));
        $user->setLastIp($_SERVER['REMOTE_ADDR']);
        $this->userMapper->persist($user);
    }

    protected function updateUserPasswordHash($userEntity, $password)
    {
        $newHash = $this->hashPassword($password);
        if ($newHash === $userEntity->getPassword()) {
            return $this;
        }
        $userEntity->setPassword($newHash);
        $this->userMapper->persist($userEntity);
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
     * getAuthService 
     * 
     * @return mixed
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
     * @param mixed $authService 
     * @return User
     */
    public function setAuthService($authService)
    {
        $this->authService = $authService;
        return $this;
    }

    /**
     * Set the event manager instance used by this context
     * 
     * @param  EventCollection $events 
     * @return mixed
     */
    public function setEventManager(EventCollection $events)
    {
        $this->events = $events;
        return $this;
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     * 
     * @return EventCollection
     */
    public function events()
    {
        if (!$this->events instanceof EventCollection) {
            $identifiers = array(__CLASS__, get_class($this));
            if (isset($this->eventIdentifier)) {
                if ((is_string($this->eventIdentifier))
                    || (is_array($this->eventIdentifier))
                    || ($this->eventIdentifier instanceof Traversable)
                ) {
                    $identifiers = array_unique($identifiers + (array) $this->eventIdentifier);
                } elseif (is_object($this->eventIdentifier)) {
                    $identifiers[] = $this->eventIdentifier;
                }
                // silently ignore invalid eventIdentifier types
            }
            $this->setEventManager(new EventManager($identifiers));
        }
        return $this->events;
    }
}
