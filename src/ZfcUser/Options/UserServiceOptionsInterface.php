<?php

namespace ZfcUser\Options;

use ZfcUser\Options\RegistrationOptionsInterface;

interface UserServiceOptionsInterface extends
    PasswordOptionsInterface,
    RegistrationOptionsInterface,
    AuthenticationOptionsInterface
{
    public function setUserEntityClass($userEntityClass);

    public function getUserEntityClass();
}
