<?php

namespace ZfcUser\Authentication\Adapter;

use ZfcUser\Authentication\ChainEvent;
use Zend\EventManager\ListenerAggregateInterface;

interface ChainableAdapterInterface extends ListenerAggregateInterface
{
    /**
     * Authenticates in a chain.
     *
     * @param ChainEvent $e
     * @return void
     */
    public function authenticate(ChainEvent $e);
}