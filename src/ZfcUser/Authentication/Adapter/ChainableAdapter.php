<?php

namespace ZfcUser\Authentication\Adapter;

use Zend\Authentication\Storage\StorageInterface;
use Zend\EventManager\EventInterface;

interface ChainableAdapter
{
    /**
     * @param AdapterChainEvent $e
     * @return bool
     */
    public function authenticate(AdapterChainEvent $e);

    /**
     * @return StorageInterface
     */
    public function getStorage();
}
