<?php

namespace ZfcUser\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * The name of the user entity class.
     *
     * @var string
     */
    protected $entityClass;

    /**
     * Listeners that are registered with the login service. Useful for preparing
     * adapters prior to authentication..
     *
     * @var array
     */
    protected $loginListeners = array();

    /**
     * Adapters that are added to the login chain.
     *
     * @var array
     */
    protected $loginAdapters = array();

    /**
     * The hydrator to use for registration.
     *
     * @var string
     */
    protected $registerHydrator = 'ZfcUser\Form\RegisterHydrator';

    /**
     * Cost for bcrypt.
     *
     * @var int
     */
    protected $passwordCost = 14;

    /**
     * The salt for bcrypt.
     *
     * @var string
     */
    protected $passwordSalt = 'change_the_default_salt!';

    /**
     * @param string $registerHydrator
     * @return ModuleOptions
     */
    public function setRegisterHydrator($registerHydrator)
    {
        $this->registerHydrator = $registerHydrator;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegisterHydrator()
    {
        return $this->registerHydrator;
    }

    /**
     * @param string $entityClass
     * @return ModuleOptions
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * @param array $loginListeners
     * @return ModuleOptions
     */
    public function setLoginListeners($loginListeners)
    {
        $this->loginListeners = $loginListeners;
        return $this;
    }

    /**
     * @return array
     */
    public function getLoginListeners()
    {
        return $this->loginListeners;
    }

    /**
     * @param array $loginAdapters
     * @return ModuleOptions
     */
    public function setLoginAdapters($loginAdapters)
    {
        $this->loginAdapters = $loginAdapters;
        return $this;
    }

    /**
     * @return array
     */
    public function getLoginAdapters()
    {
        return $this->loginAdapters;
    }

    /**
     * @param int $passwordCost
     * @return ModuleOptions
     */
    public function setPasswordCost($passwordCost)
    {
        $this->passwordCost = $passwordCost;
        return $this;
    }

    /**
     * @return int
     */
    public function getPasswordCost()
    {
        return $this->passwordCost;
    }

    /**
     * @param string $passwordSalt
     * @return ModuleOptions
     */
    public function setPasswordSalt($passwordSalt)
    {
        $this->passwordSalt = $passwordSalt;
        return $this;
    }

    /**
     * @return string
     */
    public function getPasswordSalt()
    {
        return $this->passwordSalt;
    }
}