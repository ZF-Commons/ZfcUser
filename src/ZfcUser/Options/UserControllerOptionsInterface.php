<?php

namespace ZfcUser\Options;

use ZfcUser\Options\AuthenticationOptionsInterface;

interface UserControllerOptionsInterface
{
    /**
     * set use redirect param if present
     *
     * @param bool $useRedirectParameterIfPresent
     */
    public function setUseRedirectParameterIfPresent($useRedirectParameterIfPresent);

    /**
     * get use redirect param if present
     *
     * @return bool
     */
    public function getUseRedirectParameterIfPresent();
}
