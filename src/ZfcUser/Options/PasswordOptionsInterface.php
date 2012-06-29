<?php

namespace ZfcUser\Options;

interface PasswordOptionsInterface
{
    /**
     * set password cost
     *
     * @param int $passwordCost
     * @return ModuleOptions
     */
    public function setPasswordCost($cost);

    /**
     * get password cost
     *
     * @return int
     */
    public function getPasswordCost();
}
