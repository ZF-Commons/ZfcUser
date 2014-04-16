<?php

namespace ZfcUserTest\Authentication\Adapter\TestAsset;

use ZfcUser\Authentication\Adapter\AbstractAdapter;
use ZfcUser\Authentication\Adapter\AdapterChainEvent;

class AbstractAdapterExtension extends AbstractAdapter
{
    public function authenticate(AdapterChainEvent $e)
    {
    }
}
