<?php
namespace ZfcUser;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\View\Helper\ZfcUserLoginWidget;

/**
 * Class Module
 * @package ZfcUser
 */
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
                'zfcUserIdentity' => function ($sm) {
                    $locator = $sm->getServiceLocator();
                    $viewHelper = new View\Helper\ZfcUserIdentity;
                    $viewHelper->setAuthService($locator->get('zfcuser_auth_service'));
                    return $viewHelper;
                },
                'zfcUserLoginWidget' => function (ServiceLocatorInterface $serviceLocator) {
                    /**
                     * @var \Zend\Form\FormInterface            $form
                     * @var \Zend\Form\FormElementManager       $formElementManager
                     * @var \Zend\View\HelperPluginManager      $serviceLocator
                     * @var \Zend\ServiceManager\ServiceManager $serviceManager
                     */
                    $serviceManager = $serviceLocator->getServiceLocator();
                    $formElementManager = $serviceManager->get('FormElementManager');
                    $form = $formElementManager->get('ZfcUser\Form\LoginForm');

                    $viewHelper = new ZfcUserLoginWidget();
                    $viewHelper->setViewTemplate($serviceManager->get('zfcuser_module_options')->getUserLoginWidgetViewTemplate());
                    $viewHelper->setLoginForm($form);

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
                'zfcuser_user_service'              => 'ZfcUser\Service\User',
            ),
            'factories' => array(
                'zfcuser_module_options' => function ($sm) {
                    $config = $sm->get('Config');
                    return new Options\ModuleOptions(isset($config['zfcuser']) ? $config['zfcuser'] : array());
                },
                // We alias this one because it's ZfcUser's instance of
                // Zend\Authentication\AuthenticationService. We don't want to
                // hog the FQCN service alias for a Zend\* class.
                'zfcuser_auth_service' => function ($sm) {
                    return new \Zend\Authentication\AuthenticationService(
                        $sm->get('ZfcUser\Authentication\Storage\Db'),
                        $sm->get('ZfcUser\Authentication\Adapter\AdapterChain')
                    );
                },

                'ZfcUser\Authentication\Adapter\AdapterChain' => 'ZfcUser\Authentication\Adapter\AdapterChainServiceFactory',

                'zfcuser_user_mapper' => function ($sm) {
                    $options = $sm->get('zfcuser_module_options');
                    $mapper = new Mapper\User();
                    $mapper->setDbAdapter($sm->get('zfcuser_zend_db_adapter'));
                    $entityClass = $options->getUserEntityClass();
                    $mapper->setEntityPrototype(new $entityClass);
                    $mapper->setHydrator(new Mapper\UserHydrator());
                    $mapper->setTableName($options->getTableName());
                    return $mapper;
                },
            ),
        );
    }
}
