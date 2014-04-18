<?php
namespace ZfcUser\Factory\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
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
