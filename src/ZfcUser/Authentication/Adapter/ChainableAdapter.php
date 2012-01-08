<?php

namespace EdpUser\Authentication\Adapter;

interface ChainableAdapter
{
    public function authenticate(AdapterChainEvent $e);
}
