<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 5/6/2015
 * Time: 6:53 PM
 */

namespace ZfcUser\Factory\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\View;

class ZfcUserIdentity implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceManager
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceManager)
    {
        $locator = $serviceManager->getServiceLocator();
        $viewHelper = new View\Helper\ZfcUserIdentity;
        $viewHelper->setAuthService($locator->get('zfcuser_auth_service'));
        return $viewHelper;
    }
}
