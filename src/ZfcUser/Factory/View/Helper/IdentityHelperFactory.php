<?php
namespace ZfcUser\Factory\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\View\Helper\IdentityHelper;

/**
 * Class IdentityHelperFactory
 * @package ZfcUser\Factory\View\Helpe
 */
class IdentityHelperFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return IdentityHelper
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var \Zend\Authentication\AuthenticationServiceInterface $authenticationService
         * @var \Zend\View\HelperPluginManager                      $serviceLocator
         * @var \Zend\ServiceManager\ServiceManager                 $serviceManager
         */
        $serviceManager         = $serviceLocator->getServiceLocator();
        $authenticationService  = $serviceManager->get('zfcuser_auth_service');

        return new IdentityHelper($authenticationService);
    }
}
