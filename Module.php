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

    public function init(ModuleManager $moduleManager)
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
            'invokables' => array(
                'ZfcUser\Authentication\Adapter\Db' => 'ZfcUser\Authentication\Adapter\Db',
                'ZfcUser\Authentication\Storage\Db' => 'ZfcUser\Authentication\Storage\Db',
                'ZfcUser\Form\Login'                => 'ZfcUser\Form\Login',
                'zfcuser_user_service'              => 'ZfcUser\Service\User',
                'zfcUserAuthentication'             => 'ZfcUser\Controller\Plugin\ZfcUserAuthentication',
            ),
            'factories' => array(
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
                    $form = new \ZfcUser\Form\Login();
                    // TODO set hydrator and input filter?
                    return $form;
                },

                'zfcuser_register_form' => function ($sm) {
                    $form = new \ZfcUser\Form\Register();
                    //$form->setCaptchaElement($sm->get('zfcuser_captcha_element'));
                    $form->setInputFilter($sm->get('ZfcUser\Form\RegisterFilter'));
                    $form->setHydrator($sm->get('zfcuser_user_hydrator'));
                    return $form;
                },

                'zfcuser_user_hydrator' => function ($sm) {
                    $hydrator = new \Zend\Stdlib\Hydrator\ClassMethods();
                    return $hydrator;
                },

                'zfcuser_user_mapper' => function ($sm) {
                    $adapter = $sm->get('zfcuser_zend_db_adapter');
                    $tg = new \Zend\Db\TableGateway\TableGateway('user', $adapter);
                    $mapper = new Mapper\User();
                    $mapper->setTableGateway($tg);
                    return $mapper;
                },

                'zfcuser_user_repository' => function ($sm) {
                    $mapper = $sm->get('zfcuser_user_mapper');
                    return new Repository\User($mapper);
                },

                'zfcuser_usermeta_mapper' => function ($sm) {
                    $adapter = $sm->get('zfcuser_zend_db_adapter');
                    $tg = new \Zend\Db\TableGateway\TableGateway('user_meta', $adapter);
                    $mapper = new Mapper\UserMeta($tg);
                    $mapper->setTableGateway($tg);
                    return $mapper;
                },

                'zfcuser_uemail_validator' => function($sm) {
                    $repository = $sm->get('zfcuser_user_repository');
                    return new \ZfcUser\Validator\NoRecordExists(array(
                        'repository' => $repository,
                        'key'        => 'email'
                    ));
                },

                'zfcuser_uusername_validator' => function($sm) {
                    $repository = $sm->get('zfcuser_user_repository');
                    return new \ZfcUser\Validator\NoRecordExists(array(
                        'repository' => $repository,
                        'key'        => 'username'
                    ));
                },

                'ZfcUser\Form\RegisterFilter' => function($sm) {
                    return new \ZfcUser\Form\RegisterFilter(
                        $sm->get('zfcuser_uemail_validator'),
                        $sm->get('zfcuser_uusername_validator')
                    );
                },
            ),
        );
    }

    public function modulesLoaded($e)
    {
        $config = $e->getConfigListener()->getMergedConfig(false);
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
