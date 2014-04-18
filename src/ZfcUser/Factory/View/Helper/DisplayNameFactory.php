<?php
namespace ZfcUser\Factory\View\Helper;

use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\HelperPluginManager;
use ZfcUser\View\Helper\ZfcUserDisplayName;

class DisplayNameFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $helpers)
    {
        /* @var $helpers HelperPluginManager */
        $locator = $helpers->getServiceLocator();

        /* @var $authService AuthenticationService */
        $authService = $locator->get('zfcuser_auth_service');

        $viewHelper = new ZfcUserDisplayName;
        $viewHelper->setAuthService($authService);

        return $viewHelper;
    }
}
