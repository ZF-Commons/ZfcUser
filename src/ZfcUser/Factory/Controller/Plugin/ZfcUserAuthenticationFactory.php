<?php
namespace ZfcUser\Factory\Controller\Plugin;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use ZfcUser\Controller\Plugin\ZfcUserAuthentication;

class ZfcUserAuthenticationFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $plugins)
    {
        $serviceLocator = $plugins->getServiceLocator();
        $controllerPlugin = new ZfcUserAuthentication;
        $controllerPlugin->setAuthService($serviceLocator->get('zfcuser_auth_service'));
        $controllerPlugin->setAuthAdapter($serviceLocator->get('ZfcUser\Authentication\Adapter\AdapterChain'));

        return $controllerPlugin;        
    }    
}
