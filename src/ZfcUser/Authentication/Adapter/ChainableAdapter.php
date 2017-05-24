<?php

namespace ZfcUser\Authentication\Adapter;

use Zend\Authentication\Storage\StorageInterface;
use Zend\EventManager\EventInterface;

interface ChainableAdapter
{
    /**
     * @param EventInterface $e
     * @return bool
     */
    public function authenticate(EventInterface $e);

    /**
     * @return StorageInterface
     */
    public function getStorage();
}
