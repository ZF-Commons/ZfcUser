<?php

namespace ZfcUser\Form;

use Zend\Crypt\Password\Bcrypt;
use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;
use ZfcUser\Extension\Password;
use ZfcUser\Service\PasswordService;

class PasswordStrategy implements StrategyInterface
{
    /**
     * @var Password
     */
    protected $passwordExtension;

    public function __construct(Password $passwordExtension)
    {
        $this->passwordExtension = $passwordExtension;
    }

    /**
     * {@inheritDoc}
     */
    public function extract($value)
    {
        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function hydrate($value)
    {
        return $this->passwordExtension->crypt($value);
    }
}