<?php
namespace ZfcUser\Factory\Controller\Plugin;

use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\PluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Authentication\Adapter;
use ZfcUser\Controller\Plugin\ZfcUserAuthentication;

class ZfcUserAuthenticationFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $plugins)
    {
        /* @var $plugins PluginManager */
        $serviceLocator = $plugins->getServiceLocator();

        /* @var $authService AuthenticationService */
        $authService = $serviceLocator->get('zfcuser_auth_service');

        /* @var $authAdapter Adapter\AdapterChain */
        $authAdapter = $serviceLocator->get('ZfcUser\Authentication\Adapter\AdapterChain');

        $controllerPlugin = new ZfcUserAuthentication;
        $controllerPlugin
            ->setAuthService($authService)
            ->setAuthAdapter($authAdapter)
        ;

        return $controllerPlugin;
    }
}
