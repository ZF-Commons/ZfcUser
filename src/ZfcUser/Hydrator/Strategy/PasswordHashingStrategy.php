<?php
namespace ZfcUser\Hydrator\Strategy;

use Zend\Crypt\Password\Bcrypt;
use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;
use ZfcUser\Options\ModuleOptions;

/**
 * Class PasswordHashingStrategy
 * @package ZfcUser\Hydrator\Strategy
 */
class PasswordHashingStrategy implements StrategyInterface
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
     * @param  string $value
     * @return string
     */
    public function extract($value)
    {
        return '';
    }

    /**
     * @param  string $value
     * @return string
     */
    public function hydrate($value)
    {
        // TODO: Possibly introduce a way to switch crypt class in config
        $bcrypt = new Bcrypt(['cost' => $this->options->getPasswordCost()]);
        return $bcrypt->create($value);
    }
}
