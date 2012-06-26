<?php

namespace ZfcUser\Options;

use ZfcUser\Options\AuthenticationOptionsInterface;

interface UserControllerOptionsInterface
{
    public function setUseRedirectParameterIfPresent($useRedirectParameterIfPresent);

    public function getUseRedirectParameterIfPresent();
}
