<?php

namespace ZfcUser\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use ZfcUser\Extension\Manager;

class Extension extends AbstractPlugin
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @param Manager $manager
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param null|$name
     * @return Manager|\ZfcUser\Extension\ExtensionInterface
     */
    public function __invoke($name = null)
    {
        if ($name) {
            return $this->manager->get($name);
        }
        return $this->manager;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, array $arguments)
    {
        return call_user_func_array(array($this->manager, $name), $arguments);
    }
}