<?php

namespace ZfcUser;

use Zend\Module\Manager,
    Zend\EventManager\StaticEventManager,
    Zend\Module\Feature\AutoloaderProviderInterface,
    Zend\Module\Feature\ConfigProviderInterface,
    Zend\Module\Feature\ServiceProviderInterface;

class Module implements 
    AutoloaderProviderInterface, 
    ConfigProviderInterface, 
    ServiceProviderInterface
{
    protected static $options;

    public function init(Manager $moduleManager)
    {
        $moduleManager->events()->attach('loadModules.post', array($this, 'modulesLoaded'));
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

    public function getServiceConfiguration()
    {
        return array(
            'factories' => array(
                'zfcUserAuthentication' => function ($sm) {
                    $plugin = new Controller\Plugin\ZfcUserAuthentication();
                    $authAdapter = $sm->get('ZfcUser\Authentication\Adapter\AdapterChain');
                    $authService = $sm->get('Zend\Authentication\AuthenticationService');
                    $plugin->setAuthAdapter($authAdapter);
                    $plugin->setAuthService($authService);
                    return $plugin;
                },
            ),
        );
    }

    public function modulesLoaded($e)
    {
        $config = $e->getConfigListener()->getMergedConfig();
        static::$options = $config['zfcuser'];
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
