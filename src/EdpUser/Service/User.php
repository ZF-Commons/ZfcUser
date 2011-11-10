<?php

namespace EdpUser\Service;

use Zend\Authentication\AuthenticationService,
    Zend\Form\Form,
    DateTime,
    EdpUser\Mapper\UserInterface as UserMapper,
    EdpUser\Module,
    Zend\EventManager\EventCollection,
    Zend\EventManager\EventManager;

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
            $credentialHash = $this->hashPassword($credential, $userEntity->getSalt(), $userEntity->getHashAlgorithm());
            $adapter     = $this->userMapper->getAuthAdapter($identity, $credentialHash, 'email');
            $result      = $authService->authenticate($adapter);
            if ($result->isValid()) {
                $this->events()->trigger(__FUNCTION__ . '.success', $this, array('user' => $userEntity));
                $this->updateUserLastLogin($userEntity);
                $authService->getStorage()->write($userEntity);
                return true;
            }
        }
        if (Module::getOption('enable_username')) {
            $userEntity = $this->userMapper->findByUsername($identity);
            if ($userEntity !== null) {
                $credentialHash = $this->hashPassword($credential, $userEntity->getSalt(), $userEntity->getHashAlgorithm());
                $adapter = $this->userMapper->getAuthAdapter($identity, $credentialHash, 'username'); 
                $result  = $authService->authenticate($adapter);
                if ($result->isValid()) {
                    $this->events()->trigger(__FUNCTION__ . '.success', $this, array('user' => $userEntity));
                    $this->updateUserLastLogin($userEntity);
                    $authService->getStorage()->write($userEntity);
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
             ->setSalt($this->randomBytes(16))
             ->setPassword($this->hashPassword($form->getValue('password'), $user->getSalt(), Module::getOption('password_hash_algorithm')))
             ->setRegisterIp($_SERVER['REMOTE_ADDR'])
             ->setRegisterTime(new DateTime('now'))
             ->setHashAlgorithm(Module::getOption('password_hash_algorithm'))
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
     * hashPassword
     *
     * @param string $password
     * @param string $salt
     * @param mixed $algorithm
     * @return string
     */
    public function hashPassword($password, $salt, $algorithm)
    {
        if (is_string($algorithm) && in_array($algorithm, hash_algos())) {
            return hash($algorithm, $password.$salt);
        }
        if (is_callable($algorithm, false, $callableName)) {
            return $callableName($password.$salt);
        }
        throw new RuntimeException(sprintf(
            'failed to call algorithm function %s(), does it exist?',
            $callableName
        ));
    }

    /**
     * randomBytes
     *
     * returns X random raw binary bytes
     *
     * @param int $byteLength
     * @return string
     */
    public function randomBytes($byteLength)
    {
        if (function_exists('openssl_random_pseudo_bytes')) {
            $data = openssl_random_pseudo_bytes($byteLength);
        } elseif (is_readable('/dev/urandom')) {
            $fp = fopen('/dev/urandom','rb');
            if ($fp !== false) {
                $data = fread($fp, $byteLength);
                fclose($fp);
            }
        } elseif(function_exists('mcrypt_create_iv') && version_compare(PHP_VERSION, '5.3.0', '>=')) {
            $data = mcrypt_create_iv($byteLength, MCRYPT_DEV_URANDOM);
        } elseif (class_exists('COM')) {
            try {
                $capi = new \COM('CAPICOM.Utilities.1');
                $data = $capi->GetRandom($btyeLength,0);
            } catch (\Exception $ex) {} // Fail silently
        }
        if(empty($data)) {
            throw new \Exception(
                'Unable to find a secure method for generating random bytes.'
            );
        }
        return $data;
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
