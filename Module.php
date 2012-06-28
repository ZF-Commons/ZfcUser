<?php

namespace ZfcUser;

use Zend\ModuleManager\ModuleManager,
    Zend\EventManager\StaticEventManager,
    Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    Zend\ModuleManager\Feature\ConfigProviderInterface,
    Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ServiceProviderInterface
{
    protected static $options;

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
            'invokables' => array(
                'ZfcUser\Authentication\Adapter\Db' => 'ZfcUser\Authentication\Adapter\Db',
                'ZfcUser\Authentication\Storage\Db' => 'ZfcUser\Authentication\Storage\Db',
                'ZfcUser\Form\Login'                => 'ZfcUser\Form\Login',
                'zfcuser_user_service'              => 'ZfcUser\Service\User',
                'zfcUserAuthentication'             => 'ZfcUser\Controller\Plugin\ZfcUserAuthentication',
            ),
            'aliases' => array(
                'zfcUserDisplayName'                => 'ZfcUser\View\Helper\ZfcUserDisplayName',
                'zfcUserIdentity'                   => 'ZfcUser\View\Helper\ZfcUserIdentity',
                'zfcUserLoginWidget'                => 'ZfcUser\View\Helper\ZfcUserLoginWidget',
            ),
            'factories' => array(

                'zfcuser_module_options' => function ($sm) {
                    $config = $sm->get('Configuration');
                    return new Options\ModuleOptions($config['zfcuser']);
                },

                'ZfcUser\View\Helper\ZfcUserDisplayName' => function ($sm) {
                    $viewHelper = new View\Helper\ZfcUserDisplayName;
                    $viewHelper->setAuthService($sm->get('zfcuser_auth_service'));
                    return $viewHelper;
                },
                'ZfcUser\View\Helper\ZfcUserIdentity' => function ($sm) {
                    $viewHelper = new View\Helper\ZfcUserIdentity;
                    $viewHelper->setAuthService($sm->get('zfcuser_auth_service'));
                    return $viewHelper;
                },
                'ZfcUser\View\Helper\ZfcUserLoginWidget' => function ($sm) {
                    $viewHelper = new View\Helper\ZfcUserLoginWidget;
                    $viewHelper->setLoginForm($sm->get('zfcuser_login_form'));
                    return $viewHelper;
                },

                // We alias this one because it's ZfcUser's instance of
                // Zend\Authentication\AuthenticationService. We don't want to
                // hog the FQCN service alias for a Zend\* class.
                'zfcuser_auth_service' => function ($sm) {
                    return new \Zend\Authentication\AuthenticationService(
                        $sm->get('ZfcUser\Authentication\Storage\Db'),
                        $sm->get('ZfcUser\Authentication\Adapter\AdapterChain')
                    );
                    return $authService;
                },

                'ZfcUser\Authentication\Adapter\AdapterChain' => function ($sm) {
                    $chain = new Authentication\Adapter\AdapterChain;
                    $adapter = $sm->get('ZfcUser\Authentication\Adapter\Db');
                    $chain->events()->attach('authenticate', array($adapter, 'authenticate'));
                    return $chain;
                },

                'zfcuser_login_form' => function($sm) {
                    $options = $sm->get('zfcuser_module_options');
                    $form = new Form\Login(null, $sm->get('zfcuser_module_options'));
                    $form->setInputFilter(new Form\LoginFilter($options));
                    return $form;
                },

                'zfcuser_register_form' => function ($sm) {
                    $options = $sm->get('zfcuser_module_options');
                    $form = new Form\Register(null, $options);
                    //$form->setCaptchaElement($sm->get('zfcuser_captcha_element'));
                    $form->setInputFilter(new Form\RegisterFilter(
                        $sm->get('zfcuser_uemail_validator'),
                        $sm->get('zfcuser_uusername_validator'),
                        $options
                    ));
                    return $form;
                },

                'zfcuser_user_hydrator' => function ($sm) {
                    $hydrator = new \Zend\Stdlib\Hydrator\ClassMethods();
                    return $hydrator;
                },

                'zfcuser_user_mapper' => function ($sm) {
                    $mapper = new Mapper\User();
                    $mapper->setDbAdapter($sm->get('zfcuser_zend_db_adapter'));
                    $mapper->setEntityPrototype(new Entity\User);
                    $mapper->setHydrator(new Mapper\UserHydrator(false));
                    return $mapper;
                },

                'zfcuser_uemail_validator' => function($sm) {
                    return new Validator\NoRecordExists(array(
                        'mapper' => $sm->get('zfcuser_user_mapper'),
                        'key'    => 'email'
                    ));
                },

                'zfcuser_uusername_validator' => function($sm) {
                    return new Validator\NoRecordExists(array(
                        'mapper' => $sm->get('zfcuser_user_mapper'),
                        'key'    => 'username'
                    ));
                },
            ),
        );
    }

    public function modulesLoaded($e)
    {
        $config = $e->getConfigListener()->getMergedConfig(false);
        static::$options = $config['zfcuser'];

        // Set default if not overridden previously.  This is necessary
        // due to the way config merging is implemented, as specifying
        // this default in module.config.php would mean it could never
        // be overridden (ie: array('username') would not be possible
        if (!isset(static::$options['auth_identity_fields'])) {
            static::$options['auth_identity_fields'] = array( 'email' );
        }

        static::$options['auth_identity_fields'] = array_unique(static::$options['auth_identity_fields']);
    }
}
