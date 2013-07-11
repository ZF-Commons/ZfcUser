<?php

namespace ZfcUser;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * A service manager configuration to be registered with the extension manager.
     *
     * @var array
     */
    protected $extensions = array();

    /**
     * @param array $extensions
     * @return $this
     */
    public function setExtensions($extensions)
    {
        $this->extensions = $extensions;
        return $this;
    }

    /**
     * @return array
     */
    public function getExtensions()
    {
        return $this->extensions;
    }
}