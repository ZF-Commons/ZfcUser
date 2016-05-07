<?php

namespace ZfcUser;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ServiceProviderInterface
{
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

    public function getControllerPluginConfig()
    {
        return array(
            'factories' => array(
                'zfcUserAuthentication' => 'ZfcUser\Factory\Controller\Plugin\ZfcUserAuthentication',
            ),
        );
    }

    public function getControllerConfig()
    {
        return array(
            'factories' => array(
                'zfcuser' => 'ZfcUser\Factory\Controller\UserControllerFactory',
            ),
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'zfcUserDisplayName' => 'ZfcUser\Factory\View\Helper\ZfcUserDisplayName',
                'zfcUserIdentity' => 'ZfcUser\Factory\View\Helper\ZfcUserIdentity',
                'zfcUserLoginWidget' => 'ZfcUser\Factory\View\Helper\ZfcUserLoginWidget',
            ),
        );

    }

    public function getServiceConfig()
    {
        return array(
            'aliases' => array(
                'zfcuser_zend_db_adapter' => 'Zend\Db\Adapter\Adapter',
            ),
            'invokables' => array(
                'zfcuser_register_form_hydrator'    => 'Zend\Stdlib\Hydrator\ClassMethods',
            ),
            'factories' => array(
                'zfcuser_redirect_callback' => 'ZfcUser\Factory\Controller\RedirectCallbackFactory',
                'zfcuser_module_options' => 'ZfcUser\Factory\Options\ModuleOptions',
                'ZfcUser\Authentication\Adapter\AdapterChain' => 'ZfcUser\Authentication\Adapter\AdapterChainServiceFactory',

                // We alias this one because it's ZfcUser's instance of
                // Zend\Authentication\AuthenticationService. We don't want to
                // hog the FQCN service alias for a Zend\* class.
                'zfcuser_auth_service' => 'ZfcUser\Factory\AuthenticationService',

                'zfcuser_user_hydrator' => 'ZfcUser\Factory\UserHydrator',
                'zfcuser_user_mapper' => 'ZfcUser\Factory\Mapper\User',

                'zfcuser_login_form'            => 'ZfcUser\Factory\Form\Login',
                'zfcuser_register_form'         => 'ZfcUser\Factory\Form\Register',
                'zfcuser_change_password_form'  => 'ZfcUser\Factory\Form\ChangePassword',
                'zfcuser_change_email_form'     => 'ZfcUser\Factory\Form\ChangeEmail',

                'ZfcUser\Authentication\Adapter\Db' => 'ZfcUser\Factory\Authentication\Adapter\DbFactory',
                'ZfcUser\Authentication\Storage\Db' => 'ZfcUser\Factory\Authentication\Storage\DbFactory',

                'zfcuser_user_service'              => 'ZfcUser\Factory\Service\UserFactory',
            ),
        );
    }
}
