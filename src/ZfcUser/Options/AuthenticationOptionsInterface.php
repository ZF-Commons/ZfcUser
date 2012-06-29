<?php

namespace ZfcUser\Options;

interface AuthenticationOptionsInterface extends PasswordOptionsInterface
{
    /**
     * set login after registration
     *
     * @param bool $loginAfterRegistration
     */
    public function setLoginAfterRegistration($loginAfterRegistration);

    /**
     * get login after registration
     *
     * @return bool
     */
    public function getLoginAfterRegistration();

    /**
     * set login form timeout in seconds
     *
     * @param int $loginFormTimeout
     */
    public function setLoginFormTimeout($loginFormTimeout);

    /**
     * set login form timeout in seconds
     *
     * @param int $loginFormTimeout
     */
    public function getLoginFormTimeout();

    /**
     * set auth identity fields
     *
     * @param array $authIdentityFields
     * @return ModuleOptions
     */
    public function setAuthIdentityFields($authIdentityFields);

    /**
     * get auth identity fields
     *
     * @return array
     */
    public function getAuthIdentityFields();
}
