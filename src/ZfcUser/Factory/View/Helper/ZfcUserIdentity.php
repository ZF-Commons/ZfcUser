<?php

namespace ZfcUser\Factory\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use ZfcUser\View;

class ZfcUserIdentity implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $viewHelper = new View\Helper\ZfcUserIdentity;
        $viewHelper->setAuthService($container->get('zfcuser_auth_service'));

        return $viewHelper;
    }
}
