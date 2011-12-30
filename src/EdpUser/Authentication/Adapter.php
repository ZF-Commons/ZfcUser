<?php

namespace EdpUser\Authentication;

//use Zend\Authentication\Adapter as ZendAuthAdapter;

interface Adapter //extends ZendAuthAdapter
{
    public function authenticate(AuthEvent $e);
}
