<?php

namespace ZfcUser\Extension;

use Zend\EventManager\EventManager;

class Manager
{
    /**
     * @var ExtensionInterface[]
     */
    protected $extensions = array();

    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @var bool
     */
    protected $loaded = false;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->eventManager = new EventManager();
    }

    /**
     * Loads extensions.
     */
    public function loadExtensions()
    {
        if (true === $this->loaded) {
            return $this;
        }

        $this->eventManager->trigger(Event::EXTENSION_LOAD, $this->getEvent());
        $this->loaded = true;
        return $this;
    }

    /**
     * @return \ZfcUser\Extension\Event
     */
    public function getEvent()
    {
        $event = new Event();
        $event->setManager($this);

        return $event;
    }

    /**
     * @return \Zend\EventManager\EventManager
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    /**
     * @param ExtensionInterface $extension
     * @return $this
     */
    public function add(ExtensionInterface $extension)
    {
        $extension->setManager($this);
        $extension->attach($this->getEventManager());

        $this->extensions[$extension->getName()] = $extension;
        return $this;
    }

    /**
     * @param string $name
     * @throws Exception\MissingExtensionException
     * @return ExtensionInterface
     */
    public function get($name)
    {
        if (!isset($this->extensions[$name])) {
            throw new Exception\MissingExtensionException(sprintf(
                'No extension with name "%s" could be found',
                $name
            ));
        }
        return $this->extensions[$name];
    }

    /**
     * @return \ZfcUser\Extension\ExtensionInterface[]
     */
    public function getExtensions()
    {
        return $this->extensions;
    }
}