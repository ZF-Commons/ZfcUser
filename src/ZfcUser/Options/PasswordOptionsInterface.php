<?php

namespace ZfcUser\Options;

interface PasswordOptionsInterface
{
    public function setPasswordSalt($salt);

    public function getPasswordSalt();

    public function setPasswordCost($cost);

    public function getPasswordCost();
}
