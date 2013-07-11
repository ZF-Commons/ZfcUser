<?php

namespace ZfcUser\Extension;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

interface ExtensionInterface extends ListenerAggregateInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options);

    /**
     * @return Manager
     */
    public function getManager();

    /**
     * @param Manager $manager
     * @return $this
     */
    public function setManager(Manager $manager);
}