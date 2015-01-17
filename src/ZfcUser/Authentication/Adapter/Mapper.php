<?php

namespace ZfcUser\Authentication\Adapter;

use Zend\Authentication\Adapter\AbstractAdapter;
use ZfcUser\Entity\UserInterface as UserEntity;
use ZfcUser\Mapper\UserInterface as UserMapper;
use Zend\Crypt\Password\PasswordInterface;
use Zend\Authentication\Result;

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

    public function authenticate()
    {
        $userObject = call_user_func(array($this->mapper, $this->mapperMethod), $this->getIdentity());

        if (!$userObject instanceof UserEntity) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND,
                null,
                array('A record with the supplied identity could not be found.')
            );
        }

        if (!$this->credentialProcessor->verify($this->getCredential(), $userObject->getPassword())) {
            // Password does not match
            return new Result(
                Result::FAILURE_CREDENTIAL_INVALID,
                null,
                array('Supplied credential is invalid.')
            );
        }

        return new Result(
            Result::SUCCESS,
            $userObject,
            array('Authentication successful.')
        );
    }
}
