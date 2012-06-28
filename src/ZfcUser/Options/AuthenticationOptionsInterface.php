<?php

namespace ZfcUser\Options;

interface AuthenticationOptionsInterface extends PasswordOptionsInterface
{
    public function setAuthIdentityFields($authIdentityFields);

    public function getAuthIdentityFields();
}
