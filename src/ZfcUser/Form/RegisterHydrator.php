<?php

namespace ZfcUser\Form;

use Zend\Stdlib\Hydrator\ClassMethods;

class RegisterHydrator extends ClassMethods
{
    public function __construct(PasswordStrategy $hashPasswordStrategy)
    {
        parent::__construct();

        $this->addStrategy('password', $hashPasswordStrategy);
    }
}