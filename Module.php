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
                'zfcUserAuthentication' => function ($sm) {
                    $serviceLocator = $sm->getServiceLocator();
                    $authService = $serviceLocator->get('zfcuser_auth_service');
                    $authAdapter = $serviceLocator->get('ZfcUser\Authentication\Adapter\AdapterChain');
                    $controllerPlugin = new Controller\Plugin\ZfcUserAuthentication;
                    $controllerPlugin->setAuthService($authService);
                    $controllerPlugin->setAuthAdapter($authAdapter);
                    return $controllerPlugin;
                },
            ),
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'zfcUserDisplayName' => function ($sm) {
                    $locator = $sm->getServiceLocator();
                    $viewHelper = new View\Helper\ZfcUserDisplayName;
                    $viewHelper->setAuthService($locator->get('zfcuser_auth_service'));
                    return $viewHelper;
                },
                'zfcUserIdentity' => function ($sm) {
                    $locator = $sm->getServiceLocator();
                    $viewHelper = new View\Helper\ZfcUserIdentity;
                    $viewHelper->setAuthService($locator->get('zfcuser_auth_service'));
                    return $viewHelper;
                },
                'zfcUserLoginWidget' => function ($sm) {
                    $locator = $sm->getServiceLocator();
                    $viewHelper = new View\Helper\ZfcUserLoginWidget;
                    $viewHelper->setViewTemplate($locator->get('zfcuser_module_options')->getUserLoginWidgetViewTemplate());
                    $viewHelper->setLoginForm($locator->get('zfcuser_login_form'));
                    return $viewHelper;
                },
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
                'zfcuser_user_hydrator'             => 'Zend\Stdlib\Hydrator\ClassMethods',
            ),
            'factories' => array(
                'zfcuser_module_options' => 'ZfcUser\Factory\ModuleOptionsFactory',
                // We alias this one because it's ZfcUser's instance of
                // Zend\Authentication\AuthenticationService. We don't want to
                // hog the FQCN service alias for a Zend\* class.
                'zfcuser_auth_service' => 'ZfcUser\Factory\AuthenticationServiceFactory',
                'ZfcUser\Authentication\Adapter\AdapterChain' => 'ZfcUser\Authentication\Adapter\AdapterChainServiceFactory',
                'zfcuser_login_form' => 'ZfcUser\Factory\LoginFormFactory',
                'zfcuser_register_form' => 'ZfcUser\Factory\RegisterFormFactory',
                'zfcuser_change_password_form' => 'ZfcUser\Factory\ChangePasswordFormFactory',
                'zfcuser_change_email_form' => 'ZfcUser\Factory\ChangeEmailFormFactory',
                'zfcuser_user_mapper' => 'ZfcUser\Factory\UserMapperFactory',
            ),
        );
    }
}
