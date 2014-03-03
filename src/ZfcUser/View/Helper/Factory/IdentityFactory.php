<?php
namespace ZfcUser\View\Helper\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use ZfcUser\View\Helper\ZfcUserIdentity;

class IdentityFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $helpers)
    {
        $locator = $helpers->getServiceLocator();
        $viewHelper = new ZfcUserIdentity;
        $viewHelper->setAuthService($locator->get('zfcuser_auth_service'));

        return $viewHelper;        
    }    
}
