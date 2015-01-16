<?php

namespace ZfcUser\Authentication\Adapter;

use Zend\Authentication\Result as AuthenticationResult;
use ZfcUser\Authentication\Adapter\AdapterChainEvent as AuthenticationEvent;
use ZfcUser\Entity\UserInterface as UserEntity;
use ZfcUser\Mapper\UserInterface as UserMapper;
use Zend\Crypt\Password\PasswordInterface;

class Mapper extends AbstractAdapter
{
    /**
     * @var UserMapper
     */
    protected $mapper;
    
    /**
     * @var string
     */
    protected $mapperMethod;

    /**
     * @var PasswordInterface
     */
    protected $credentialProcessor;
    
    public function __construct(UserMapper $mapper, $mapperMethod, PasswordInterface $validator)
    {
        $this->mapper = $mapper;
        $this->mapperMethod = $mapperMethod;
        $this->credentialProcessor = $validator;
    }

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

        $userObject = call_user_func(array($this->mapper, $this->mapperMethod), $event->getIdentity());

        if (!$userObject instanceof UserEntity) {
            $event->setCode(AuthenticationResult::FAILURE_IDENTITY_NOT_FOUND)
                  ->setMessages(array('A record with the supplied identity could not be found.'));
            $this->setSatisfied(false);
            return false;
        }

        if (!$this->credentialProcessor->verify($event->getCredential(), $userObject->getPassword())) {
            // Password does not match
            $event->setCode(AuthenticationResult::FAILURE_CREDENTIAL_INVALID)
                  ->setMessages(array('Supplied credential is invalid.'));
            $this->setSatisfied(false);
            return false;
        }

        // Success!
        $event->setIdentity($userObject->getId());

        $this->setSatisfied(true);
        $storage = $this->getStorage()->read();
        $storage['identity'] = $event->getIdentity();
        $this->getStorage()->write($storage);
        $event->setCode(AuthenticationResult::SUCCESS)
              ->setMessages(array('Authentication successful.'));
    }
}
