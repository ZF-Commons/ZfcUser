<?php

namespace ZfcUser\Form;

use Zend\Crypt\Password\Bcrypt;
use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;
use ZfcUser\Options\ModuleOptions;

class PasswordStrategy implements StrategyInterface
{
    /**
     * @var ModuleOptions
     */
    protected $options;

    /**
     * @param ModuleOptions $options
     */
    public function __construct(ModuleOptions $options)
    {
        $this->options = $options;
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
        $bcrypt = new Bcrypt();
        $bcrypt->setCost($this->options->getPasswordCost());
        $bcrypt->setSalt($this->options->getPasswordSalt());

        return $bcrypt->create($value);
    }
}