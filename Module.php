<?php

namespace ZfcUser;

use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

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
                'zfcUserAuthentication' => 'ZfcUser\Controller\Plugin\Factory\ZfcUserAuthenticationFactory'
            ),
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'zfcUserDisplayName'    => 'ZfcUser\View\Helper\Factory\DisplayNameFactory',
                'zfcUserIdentity'       => 'ZfcUser\View\Helper\Factory\IdentityFactory',
                'zfcUserLoginWidget'    => 'ZfcUser\View\Helper\Factory\LoginWidgetFactory',
            ),
        );

    }

    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'ZfcUser\Authentication\Adapter\Db' => 'ZfcUser\Authentication\Adapter\Db',
                'ZfcUser\Authentication\Storage\Db' => 'ZfcUser\Authentication\Storage\Db',
                'ZfcUser\Form\Login'                => 'ZfcUser\Form\Login',
                'zfcuser_user_service'              => 'ZfcUser\Service\User',
                'zfcuser_register_form_hydrator'    => 'Zend\Stdlib\Hydrator\ClassMethods',
                'zfcuser_user_hydrator'             => 'ZfcUser\Mapper\UserHydrator',
            ),
            'factories' => array(
                'zfcuser_module_options'                        => 'ZfcUser\Factory\ModuleOptionsFactory',
                'zfcuser_auth_service'                          => 'ZfcUser\Factory\AuthenticationServiceFactory',
                'ZfcUser\Authentication\Adapter\AdapterChain'   => 'ZfcUser\Authentication\Adapter\AdapterChainServiceFactory',
                'zfcuser_login_form'                            => 'ZfcUser\Factory\LoginFormFactory',
                'zfcuser_register_form'                         => 'ZfcUser\Factory\RegisterFormFactory',
                'zfcuser_change_password_form'                  => 'ZfcUser\Factory\ChangePasswordFormFactory',
                'zfcuser_change_email_form'                     => 'ZfcUser\Factory\ChangeEmailFormFactory',
                'zfcuser_user_mapper'                           => 'ZfcUser\Factory\UserMapperFactory',
            ),
        );
    }
}
