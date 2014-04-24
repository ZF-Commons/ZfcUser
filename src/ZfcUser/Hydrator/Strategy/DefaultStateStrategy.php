<?php
namespace ZfcUser\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;
use ZfcUser\Options\ModuleOptions;

/**
 * Class DefaultStateStrategy
 * @package ZfcUser\Hydrator\Strategy
 */
class DefaultStateStrategy implements StrategyInterface
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
     * @param  int|null $value
     * @return int|null
     */
    public function extract($value)
    {
        return $value;
    }

    /**
     * @param  string $value
     * @return int|null
     */
    public function hydrate($value)
    {
        if ($this->options->getEnableUserState() && $this->options->getDefaultUserState()) {
            return $this->options->getDefaultUserState();
        }

        return null;
    }
}
