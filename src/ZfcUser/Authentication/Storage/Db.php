<?php

namespace EdpUser\Authentication\Storage;

use Zend\Authentication\Storage,
    EdpUser\Mapper\UserInterface as UserMapper;

class Db implements Storage
{
    /**
     * @var Storage
     */
    protected $storage;

    /**
     * @var UserMapper
     */
    protected $mapper;

    /**
     * @var mixed
     */
    protected $resolvedIdentity;

    /**
     * Returns true if and only if storage is empty
     *
     * @throws Zend\Authentication\Storage\Exception If it is impossible to determine whether storage is empty
     * @return boolean
     */
    public function isEmpty()
    {
        return $this->getStorage()->isEmpty();
    }

    /**
     * Returns the contents of storage
     *
     * Behavior is undefined when storage is empty.
     *
     * @throws Zend\Authentication\Storage\Exception If reading contents from storage is impossible
     * @return mixed
     */
    public function read()
    {
        if (null !== $this->resolvedIdentity) {
            return $this->resolvedIdentity;
        }

        $identity = $this->getStorage()->read();

        if (is_int($identity)) {
            $identity = $this->getMapper()->findById($identity);
        }

        if ($identity) {
            $this->resolvedIdentity = $identity;
        } else {
            $this->resolvedIdentity = null;
        }

        return $this->resolvedIdentity;
    }

    /**
     * Writes $contents to storage
     *
     * @param  mixed $contents
     * @throws Zend\Authentication\Storage\Exception If writing $contents to storage is impossible
     * @return void
     */
    public function write($contents)
    {
        $this->resolvedIdentity = null;
        $this->getStorage()->write($contents);
    }

    /**
     * Clears contents from storage
     *
     * @throws Zend\Authentication\Storage\Exception If clearing contents from storage is impossible
     * @return void
     */
    public function clear()
    {
        $this->resolvedIdentity = null;
        $this->getStorage()->clear();
    }

    /**
     * getStorage 
     * 
     * @return Storage
     */
    public function getStorage()
    {
        if (null === $this->storage) {
            $this->setStorage(new Storage\Session);
        }
        return $this->storage;
    }

    /**
     * setStorage
     * 
     * @param Storage $storage 
     * @access public
     * @return void
     */
    public function setStorage(Storage $storage)
    {
        $this->storage = $storage;
        return $this;
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
    public function setMapper(UserMapper $mapper)
    {
        $this->mapper = $mapper;
        return $this;
    }
}
