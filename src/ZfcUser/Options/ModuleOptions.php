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
     * Service manager name of the authentication service.
     *
     * @var string
     */
    protected $authenticationService = 'Zend\Authentication\AuthenticationService';

    /**
     * Plugins that are registered with the register service.
     *
     * @var array
     */
    protected $registerPlugins = array();

    /**
     * Plugins that are registered with the login service.
     *
     * @var array
     */
    protected $loginPlugins = array();

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
     * @param array $loginPlugins
     * @return ModuleOptions
     */
    public function setLoginPlugins($loginPlugins)
    {
        $this->loginPlugins = $loginPlugins;
        return $this;
    }

    /**
     * @return array
     */
    public function getLoginPlugins()
    {
        return $this->loginPlugins;
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

    /**
     * @param array $registerPlugins
     * @return ModuleOptions
     */
    public function setRegisterPlugins($registerPlugins)
    {
        $this->registerPlugins = $registerPlugins;
        return $this;
    }

    /**
     * @return array
     */
    public function getRegisterPlugins()
    {
        return $this->registerPlugins;
    }

    /**
     * @param string $authenticationService
     * @return ModuleOptions
     */
    public function setAuthenticationService($authenticationService)
    {
        $this->authenticationService = $authenticationService;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthenticationService()
    {
        return $this->authenticationService;
    }
}