<?php

namespace ZfcUserTest\Authentication\Adapter\TestAsset;

use Zend\EventManager\EventInterface;
use ZfcUser\Authentication\Adapter\AbstractAdapter;

class AbstractAdapterExtension extends AbstractAdapter
{
    public function authenticate(EventInterface $e)
    {
    }
}
