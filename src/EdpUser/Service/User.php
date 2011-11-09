<?php

namespace EdpUser\Service;

use Zend\Authentication\AuthenticationService,
    Zend\Form\Form,
    DateTime,
    EdpUser\Mapper\UserInterface as UserMapper,
    Zend\EventManager\EventCollection,
    Zend\EventManager\EventManager;

class User
{
    /**
     * @var string
     */
    protected static $userModelClass = 'EdpUser\Model\User';

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
        // Auth by email
        $userEntity = $this->userMapper->findByEmail($identity);
        if ($userEntity !== null) {
            $credentialHash = $this->hashPassword($credential, $userEntity->getSalt());
            $adapter     = $this->userMapper->getAuthAdapter($identity, $credentialHash, 'email');
            $authService = $this->getAuthService();
            $result      = $authService->authenticate($adapter);
            if ($result->isValid()) {
                $this->updateUserLastLogin($userEntity);
                $authService->getStorage()->write($userEntity);
                return true;
            }
        }
        // @TODO: Check if enableUsernameAuth setting is on 
        $userEntity = $this->userMapper->findByUsername($identity);
        if ($userEntity !== null) {
            $credentialHash = $this->hashPassword($credential, $userEntity->getSalt());
            $adapter = $this->userMapper->getAuthAdapter($identity, $credentialHash, 'username'); 
            $result  = $authService->authenticate($adapter);
            if ($result->isValid()) {
                $this->updateUserLastLogin($userEntity);
                $authService->getStorage()->write($userEntity);
                return true;
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
        $user = new static::$userModelClass;
        $user->setEmail($form->getValue('email'))
             ->setUsername($form->getValue('username'))
             ->setDisplayName($form->getValue('display_name'))
             ->setSalt($this->randomBytes(16))
             ->setPassword($this->hashPassword($form->getValue('password'), $user->getSalt()))
             ->setRegisterIp($_SERVER['REMOTE_ADDR'])
             ->setRegisterTime(new DateTime('now'));
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

    public static function setUserModelClass($userModelClass)
    {
        static::$userModelClass = $userModelClass;
    }

    public static function getUserModelClass()
    {
        return static::$userModelClass;
    }

    /**
     * hashPassword
     *
     * @param string $password
     * @param string $salt
     * @return string
     */
    public function hashPassword($password, $salt)
    {
        return hash('sha512', $password.$salt);
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
