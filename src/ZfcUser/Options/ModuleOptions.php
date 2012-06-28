<?php

namespace ZfcUser\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions implements
    AuthenticationOptionsInterface,
    RegistrationOptionsInterface,
    UserControllerOptionsInterface,
    UserServiceOptionsInterface
{
    protected $useRedirectParameterIfPresent = false;

    protected $loginFormTimeout = 300;

    protected $userFormTimeout = 300;

    protected $loginAfterRegistration = true;

    protected $authIdentityFields = array( 'email' );

    protected $userEntityClass = 'ZfcUser\Entity\User';

    protected $enableRegistration = true;

    protected $enableUsername = false;

    protected $enableDisplayName = false;

    protected $useRegistrationFormCaptcha;

    protected $passwordCost;

    public function setUseRedirectParameterIfPresent($useRedirectParameterIfPresent)
    {
        $this->useRedirectParameterIfPresent = $useRedirectParameterIfPresent;
    }

    public function getUseRedirectParameterIfPresent()
    {
        return $this->useRedirectParameterIfPresent;
    }

    public function setEnableRegistration($enableRegistration)
    {
        $this->enableRegistration = $enableRegistration;
    }

    public function getEnableRegistration()
    {
        return $this->enableRegistration;
    }

    public function setLoginFormTimeout($loginFormTimeout)
    {
        $this->loginFormTimeout = $loginFormTimeout;
    }

    public function getLoginFormTimeout()
    {
        return $this->loginFormTimeout;
    }

    public function setUserFormTimeout($userFormTimeout)
    {
        $this->userFormTimeout = $userFormTimeout;
    }

    public function getUserFormTimeout()
    {
        return $this->userFormTimeout;
    }

    public function setLoginAfterRegistration($loginAfterRegistration)
    {
        $this->loginAfterRegistration = $loginAfterRegistration;
    }

    public function getLoginAfterRegistration()
    {
        return $this->loginAfterRegistration;
    }

    public function setAuthIdentityFields($authIdentityFields)
    {
        $this->authIdentityFields = $authIdentityFields;
    }

    public function getAuthIdentityFields()
    {
        return $this->authIdentityFields;
    }

    /**
     * set enable username
     *
     * @param bool $flag
     * @return ModuleOptions
     */
    public function setEnableUsername($flag)
    {
        $this->enableUsername = (bool) $flag;
        return $this;
    }

    /**
     * get enable username
     *
     * @return bool
     */
    public function getEnableUsername()
    {
        return $this->enableUsername;
    }

    /**
     * set enable display name
     * @param bool $flag
     * @return ModuleOptions
     */
    public function setEnableDisplayName($flag)
    {
        $this->enableDisplayName = (bool) $flag;
        return $this;
    }

    /**
     * get enable display name
     *
     * @return bool
     */
    public function getEnableDisplayName()
    {
        return $this->enableDisplayName;
    }

    public function setUseRegistrationFormCaptcha($useRegistrationFormCaptcha)
    {
        $this->useRegistrationFormCaptcha = $useRegistrationFormCaptcha;
    }

    public function getUseRegistrationFormCaptcha()
    {
        return $this->useRegistrationFormCaptcha;
    }

    /**
     * set require activation
     *
     * @param bool $flag
     * @return ModuleOptions
     */
    public function setRequireActivation($flag)
    {
        $this->requireActivation = (bool) $flag;
        return $this;
    }

    /**
     * get require activation
     *
     * @return bool
     */
    public function getRequireActivation()
    {
        return $this->requireActivation;
    }

    public function setUserEntityClass($userEntityClass)
    {
        $this->userEntityClass = $userEntityClass;
    }

    public function getUserEntityClass()
    {
        return $this->userEntityClass;
    }

    public function setPasswordCost($passwordCost)
    {
        $this->passwordCost = $passwordCost;
    }

    public function getPasswordCost()
    {
        return $this->passwordCost;
    }
}
