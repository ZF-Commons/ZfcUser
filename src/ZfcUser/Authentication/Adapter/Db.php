<?php

namespace ZfcUser\Authentication\Adapter;

use DateTime;
use Zend\Authentication\Result as AuthenticationResult;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcUser\Authentication\Adapter\AdapterChainEvent as AuthEvent;
use ZfcBase\Mapper\DataMapperInterface as UserMapper;
use ZfcUser\Module as ZfcUser;
use ZfcUser\Repository\UserInterface as UserRepositoryInterface;
use ZfcUser\Util\Password;

class Db extends AbstractAdapter implements ServiceManagerAwareInterface
{
    /**
     * @var UserMapper
     */
    protected $mapper;

    /**
     * @var UserRepositoryInterface
     */
    protected $repository;

    /**
     * @var closure / invokable object
     */
    protected $credentialPreprocessor;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    public function authenticate(AuthEvent $e)
    {
        if ($this->isSatisfied()) {
            $storage = $this->getStorage()->read();
            $e->setIdentity($storage['identity'])
              ->setCode(AuthenticationResult::SUCCESS)
              ->setMessages(array('Authentication successful.'));
            return;
        }

        $identity   = $e->getRequest()->post()->get('identity');
        $credential = $e->getRequest()->post()->get('credential');
        $credential = $this->preProcessCredential($credential);
        $userObject = NULL;

        // Cycle through the configured identity sources and test each
        $fields = ZfcUser::getOption('auth_identity_fields');
        while ( !is_object($userObject) && count($fields) > 0 ) {
            $mode = array_shift($fields);
            switch ($mode) {
                case 'username':
                    $userObject = $this->getRepository()->findByUsername($identity);
                    break;
                case 'email':
                    $userObject = $this->getRepository()->findByEmail($identity);
                    break;
            }
        }

        if (!$userObject) {
            $e->setCode(AuthenticationResult::FAILURE_IDENTITY_NOT_FOUND)
              ->setMessages(array('A record with the supplied identity could not be found.'));
            $this->setSatisfied(false);
            return false;
        }

        $credentialHash = Password::hash($credential, $userObject->getPassword());

        if ($credentialHash !== $userObject->getPassword()) {
            // Password does not match
            $e->setCode(AuthenticationResult::FAILURE_CREDENTIAL_INVALID)
              ->setMessages(array('Supplied credential is invalid.'));
            $this->setSatisfied(false);
            return false;
        }

        // Success!
        $e->setIdentity($userObject->getUserId());
        $this->updateUserLastLogin($userObject)
             ->updateUserPasswordHash($userObject, $credential)
             ->setSatisfied(true);
        $storage = $this->getStorage()->read();
        $storage['identity'] = $e->getIdentity();
        $this->getStorage()->write($storage);
        $e->setCode(AuthenticationResult::SUCCESS)
          ->setMessages(array('Authentication successful.'));
    }

    protected function updateUserPasswordHash($userObject, $password)
    {
        $newHash = Password::hash($password);
        if ($newHash === $userObject->getPassword()) return $this;

        $userObject->setPassword($newHash);

        $this->getMapper()->persist($userObject);
        return $this;
    }

    protected function updateUserLastLogin($userObject)
    {
        $userObject->setLastLogin(new DateTime('now'))
                   ->setLastIp($_SERVER['REMOTE_ADDR']);

        $this->getMapper()->persist($userObject);
        return $this;
    }

    public function preprocessCredential($credential)
    {
        $processor = $this->getCredentialPreprocessor();
        if (is_callable($processor)) {
            return $processor($credential);
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
        if (null === $this->mapper) {
            $this->mapper = $this->getServiceManager()->get('zfcuser_user_mapper');
        }
        return $this->mapper;
    }

    /**
     * setMapper
     * 
     * @param UserMapper $mapper
     * @return Db
     */
    public function setMapper(UserMapper$mapper)
    {
        $this->mapper = $mapper;
        return $this;
    }

    /**
     * getUserRepository
     * 
     * @return UserRepositoryInterface
     */
    public function getRepository()
    {
        if (null === $this->repository) {
            $this->repository = $this->getServiceManager()->get('zfcuser_user_repository');
        }
        return $this->repository;
    }

    /**
     * Set repository
     *
     * @param UserRepositoryInterface $repository
     * @return Db
     */
    public function setRepository(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
        return $this;
    }

    /**
     * Get credentialPreprocessor.
     *
     * @return \callable
     */
    public function getCredentialPreprocessor()
    {
        return $this->credentialPreprocessor;
    }
 
    /**
     * Set credentialPreprocessor.
     *
     * @param $credentialPreprocessor the value to be set
     */
    public function setCredentialPreprocessor($credentialPreprocessor)
    {
        $this->credentialPreprocessor = $credentialPreprocessor;
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
