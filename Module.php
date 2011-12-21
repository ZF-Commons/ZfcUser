<?php

namespace EdpUser;

use Zend\Module\Manager,
    Zend\EventManager\StaticEventManager,
    EdpUser\Event\ResolveTargetEntityListener,
    Doctrine\ORM\Events,
    Zend\Module\Consumer\AutoloaderProvider;

class Module implements AutoloaderProvider
{
    protected static $options;

    public function init(Manager $moduleManager)
    {
        $moduleManager->events()->attach('loadModules.post', array($this, 'modulesLoaded'));
        $events = StaticEventManager::getInstance();
        $events->attach('bootstrap', 'bootstrap', array($this, 'attachDoctrineEvents'), 100);
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig($env = null)
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function modulesLoaded($e)
    {
        $config = $e->getConfigListener()->getMergedConfig();
        static::$options = $config['edpuser'];
    }

    public function attachDoctrineEvents($e)
    {
        if (('EdpUser\Model\User' === static::getOption('user_model_class'))
            || ('doctrine' !== static::getOption('db_abstraction'))
        ) {
            return;
        }
        $app      = $e->getParam('application');
        $locator  = $app->getLocator();
        $em       = $locator->get('edpuser_doctrine_em');
        $evm      = $em->getEventManager();
        $listener = new ResolveTargetEntityListener;
        $listener->addResolveTargetEntity(
            'EdpUser\Model\UserInterface',
            static::getOption('user_model_class'),
            array()
        );
        $evm->addEventListener(Events::loadClassMetadata, $listener);
    }

    /**
     * @TODO: Come up with a better way of handling module settings/options
     */
    public static function getOption($option)
    {
        if (!isset(static::$options[$option])) {
            return null;
        }
        return static::$options[$option];
    }

}
