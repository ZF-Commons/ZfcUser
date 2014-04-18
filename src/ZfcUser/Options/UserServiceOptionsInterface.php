<?php

namespace ZfcUser\Options;

interface UserServiceOptionsInterface extends
 RegistrationOptionsInterface,
 AuthenticationOptionsInterface
{
    /**
     * set user entity class name
     *
     * @param  string        $userEntityClass
     * @return ModuleOptions
     */
    public function setUserEntityClass($userEntityClass);

    /**
     * get user entity class name
     *
     * @return string
     */
    public function getUserEntityClass();
}
