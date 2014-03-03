<?php
namespace ZfcUser\View\Helper\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use ZfcUser\View\Helper\ZfcUserDisplayName;

class DisplayNameFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $helpers)
    {
        $locator = $helpers->getServiceLocator();
        $viewHelper = new ZfcUserDisplayName;
        $viewHelper->setAuthService($locator->get('zfcuser_auth_service'));

        return $viewHelper;        
    }    
}
