<?php

namespace ZfcUser\Options;

interface PasswordOptionsInterface
{
    public function setPasswordCost($cost);

    public function getPasswordCost();
}
