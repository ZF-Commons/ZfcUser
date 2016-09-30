<?php

namespace ZfcUser\Authentication\Storage;

use Zend\Authentication\Storage\StorageInterface;
use ZfcUser\Mapper\UserInterface as UserMapper;
use ZfcUser\Entity\UserInterface as UserEntity;

/**
 * Zend\Authentication Storage decorator which converts user identifier
 * stored in the session container to a User entity on read
 */
class Mapper implements StorageInterface
{
    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * @var UserMapper
     */
    protected $mapper;

    /**
     * @var UserEntity|null
     */
    protected $resolvedIdentity;

    /**
     * @param UserMapper $mapper Mapper to load user entity from
     * @param StorageInterface $storage Decorated storage object
     */
    public function __construct(UserMapper $mapper, StorageInterface $storage)
    {
        $this->mapper = $mapper;
        $this->storage = $storage;
    }
    
    /**
     * Returns true if and only if storage is empty
     *
     * @throws \Zend\Authentication\Exception\InvalidArgumentException If it is impossible to determine whether
     * storage is empty or not
     * @return boolean
     */
    public function isEmpty()
    {
        if ($this->storage->isEmpty()) {
            return true;
        }
        $identity = $this->read();
        if ($identity === null) {
            $this->clear();
            return true;
        }

        return false;
    }

    /**
     * Returns the contents of storage as a User entity or null if the
     * identifier in storage does not map to an existing user account. 
     *
     * @throws \Zend\Authentication\Exception\InvalidArgumentException If reading contents from storage is impossible
     * @return UserEntity|null
     */
    public function read()
    {
        if (null !== $this->resolvedIdentity) {
            return $this->resolvedIdentity;
        }

        $identity = $this->storage->read();

        if (is_int($identity) || is_scalar($identity)) {
            $identity = $this->mapper->findById($identity);
        }

        $this->resolvedIdentity = $identity instanceof UserEntity
                ? $identity
                : null;

        return $this->resolvedIdentity;
    }

    /**
     * Writes $contents to storage
     *
     * @param  mixed $contents
     * @throws \Zend\Authentication\Exception\InvalidArgumentException If writing $contents to storage is impossible
     * @return void
     */
    public function write($contents)
    {
        $this->resolvedIdentity = null;
        $this->storage->write($contents);
    }

    /**
     * Clears contents from storage
     *
     * @throws \Zend\Authentication\Exception\InvalidArgumentException If clearing contents from storage is impossible
     * @return void
     */
    public function clear()
    {
        $this->resolvedIdentity = null;
        $this->storage->clear();
    }
}
