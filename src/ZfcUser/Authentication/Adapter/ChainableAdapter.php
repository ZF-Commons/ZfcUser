<?php

namespace ZfcUser\Authentication\Adapter;

interface ChainableAdapter
{
    public function authenticate(\Zend\EventManager\Event $e);
}
