<?php

namespace ZfcUser\Authentication\Adapter;

use DateTime;
use Zend\Authentication\Result as AuthenticationResult;
use ZfcUser\Authentication\Adapter\AdapterChainEvent as AuthEvent;
use ZfcBase\Mapper\DataMapperInterface as UserMapper;
use ZfcUser\Module as ZfcUser;
use ZfcUser\Repository\UserInterface as UserRepositoryInterface;
use ZfcUser\Util\Password;

class Db extends AbstractAdapter
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

    /**
     * getMapper 
     * 
     * @return UserMapper
     */
    public function getMapper()
    {
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
     * @return UserRepositoryInterface
     */
    public function getRepository()
    {
        return $this->repository;
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
}
