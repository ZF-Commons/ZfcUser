<?php

namespace EdpUser;

use Zend\Module\Manager,
    Zend\Loader\AutoloaderFactory,
    EdpUser\Service\User as UserService;

class Module
{
    protected static $options;

    public function init(Manager $moduleManager)
    {
        $this->initAutoloader();
        $moduleManager->events()->attach('init.post', array($this, 'postInit'));
    }

    protected function initAutoloader()
    {
        AutoloaderFactory::factory(array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        ));
    }

    public function getConfig($env = null)
    {
        return include __DIR__ . '/configs/module.config.php';
    }

    public function postInit($e)
    {
        $config = $e->getTarget()->getMergedConfig();
        static::$options = $config['edpuser'];
    }

    public static function getOption($option)
    {
        if (!isset(static::$options[$option])) {
            return null;
        }
        return static::$options[$option];
    }
}
