<?php

namespace ZfcUser\Factory\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use ZfcUser\View;

class ZfcUserLoginWidget implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $viewHelper = new View\Helper\ZfcUserLoginWidget;
        $viewHelper->setViewTemplate($container->get('zfcuser_module_options')->getUserLoginWidgetViewTemplate());
        $viewHelper->setLoginForm($container->get('zfcuser_login_form'));

        return $viewHelper;
    }
}
