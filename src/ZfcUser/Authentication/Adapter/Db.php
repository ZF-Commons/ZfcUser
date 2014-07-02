<?php

namespace ZfcUser\Authentication\Adapter;

use DateTime;
use InvalidArgumentException;
use Zend\Authentication\Result as AuthenticationResult;
use Zend\Crypt\Password\Bcrypt;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\Session\Container as SessionContainer;
use ZfcUser\Authentication\Adapter\AdapterChainEvent as AuthenticationEvent;
use ZfcUser\Entity\UserInterface as UserEntity;
use ZfcUser\Mapper\HydratorInterface as Hydrator;
use ZfcUser\Mapper\UserInterface as UserMapper;
use ZfcUser\Options\AuthenticationOptionsInterface as AuthenticationOptions;

class Db extends AbstractAdapter implements ServiceManagerAwareInterface
{
    /**
     * @var UserMapper
     */
    protected $mapper;

    /**
     * @var Hydrator
     */
    protected $hydrator;

    /**
     * @var callable
     */
    protected $credentialPreprocessor;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var AuthenticationOptions
     */
    protected $options;

    /**
     * Called when user id logged out
     */
    public function logout()
    {
        $this->getStorage()->clear();
    }

    public function authenticate(AuthenticationEvent $event)
    {
        if ($this->isSatisfied()) {
            $storage = $this->getStorage()->read();
            $event->setIdentity($storage['identity'])
                  ->setCode(AuthenticationResult::SUCCESS)
                  ->setMessages(array('Authentication successful.'));
            return;
        }

        $identity   = $event->getRequest()->getPost()->get('identity');
        $credential = $event->getRequest()->getPost()->get('credential');
        $userObject = null;

        // Cycle through the configured identity sources and test each
        $fields = $this->getOptions()->getAuthIdentityFields();
        while (!is_object($userObject) && count($fields) > 0) {
            $mode = array_shift($fields);
            switch ($mode) {
                case 'username':
                    $userObject = $this->getMapper()->findByUsername($identity);
                    break;
                case 'email':
                    $userObject = $this->getMapper()->findByEmail($identity);
                    break;
            }
        }

        if (!$userObject) {
            $event->setCode(AuthenticationResult::FAILURE_IDENTITY_NOT_FOUND)
                  ->setMessages(array('A record with the supplied identity could not be found.'));
            $this->setSatisfied(false);
            return false;
        }

        if ($this->getOptions()->getEnableUserState()) {
            // Don't allow user to login if state is not in allowed list
            if (!in_array($userObject->getState(), $this->getOptions()->getAllowedLoginStates())) {
                $event->setCode(AuthenticationResult::FAILURE_UNCATEGORIZED)
                      ->setMessages(array('A record with the supplied identity is not active.'));
                $this->setSatisfied(false);
                return false;
            }
        }

        $credential = $this->preProcessCredential($credential, $userObject);

        $cryptoService = $this->getHydrator()->getCryptoService();
        if (!$cryptoService->verify($credential, $userObject->getPassword())) {
            // Password does not match
            $event->setCode(AuthenticationResult::FAILURE_CREDENTIAL_INVALID)
                  ->setMessages(array('Supplied credential is invalid.'));
            $this->setSatisfied(false);
            return false;
        } elseif ($cryptoService instanceof Bcrypt) {
            // Update user's password hash if the cost parameter has changed
            $this->updateUserPasswordHash($userObject, $credential, $cryptoService);
        }

        // regen the id
        SessionContainer::getDefaultManager()->regenerateId();

        // Success!
        $event->setIdentity($userObject->getId());

        $this->setSatisfied(true);
        $storage = $this->getStorage()->read();
        $storage['identity'] = $event->getIdentity();
        $this->getStorage()->write($storage);
        $event->setCode(AuthenticationResult::SUCCESS)
              ->setMessages(array('Authentication successful.'));
    }

    protected function updateUserPasswordHash(UserEntity $user, $password, Bcrypt $bcrypt)
    {
        $hash = explode('$', $user->getPassword());
        if ($hash[2] === $bcrypt->getCost()) {
            return;
        }
        $user = $this->getHydrator()->hydrate(compact('password'), $user);
        $this->getMapper()->update($user);
    }

    public function preprocessCredential($credential, UserEntity $user)
    {
        if (is_callable($this->credentialPreprocessor)) {
            return call_user_func($this->credentialPreprocessor, $credential, $user);
        }
        return $credential;
    }

    /**
     * getMapper
     *
     * @return UserMapper
     */
    public function getMapper()
    {
        if (!$this->mapper instanceof UserMapper) {
            $this->setMapper($this->serviceManager->get('zfcuser_user_mapper'));
        }
        return $this->mapper;
    }

    /**
     * setMapper
     *
     * @param UserMapper $mapper
     * @return Db
     */
    public function setMapper(UserMapper $mapper)
    {
        $this->mapper = $mapper;
        return $this;
    }

    /**
     * Lazy-loads a hydrator from the service manager
     *
     * @return Hydrator
     */
    public function getHydrator()
    {
        if (!$this->hydrator instanceof Hydrator) {
            $this->setHydrator($this->serviceManager->get('zfcuser_user_hydrator'));
        }
        return $this->hydrator;
    }

    /**
     * Set the hydrator
     *
     * @param Hydrator $hydrator
     */
    public function setHydrator(Hydrator $hydrator)
    {
        $this->hydrator = $hydrator;
    }

    /**
     * Get credentialPreprocessor.
     *
     * @return callable
     */
    public function getCredentialPreprocessor()
    {
        return $this->credentialPreprocessor;
    }

    /**
     * Set credentialPreprocessor.
     *
     * @param  callable $credentialPreprocessor the value to be set
     * @throws InvalidArgumentException when argument is not callable
     */
    public function setCredentialPreprocessor($credentialPreprocessor)
    {
        if (!is_callable($credentialPreprocessor)) {
            $message = sprintf(
                "Credential Preprocessor must be callable, [%s] given",
                gettype($credentialPreprocessor)
            );
            throw new InvalidArgumentException($message);
        }
        $this->credentialPreprocessor = $credentialPreprocessor;
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

    /**
     * @param AuthenticationOptions $options
     */
    public function setOptions(AuthenticationOptions $options)
    {
        $this->options = $options;
    }

    /**
     * @return AuthenticationOptions
     */
    public function getOptions()
    {
        if (!$this->options instanceof AuthenticationOptions) {
            $this->setOptions($this->serviceManager->get('zfcuser_module_options'));
        }
        return $this->options;
    }
}
