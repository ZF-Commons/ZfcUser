<?php

namespace ZfcUserTest\Authentication\Adapter\TestAsset;

use Laminas\EventManager\EventInterface;
use ZfcUser\Authentication\Adapter\AbstractAdapter;

class AbstractAdapterExtension extends AbstractAdapter
{
    public function authenticate(EventInterface $e)
    {
    }
}
