<?php
namespace ZfcUser\View\Helper\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use ZfcUser\View\Helper\ZfcUserLoginWidget;

class LoginWidgetFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $helpers)
    {
        $locator = $helpers->getServiceLocator();
        $viewHelper = new ZfcUserLoginWidget;
        $viewHelper->setViewTemplate($locator->get('zfcuser_module_options')->getUserLoginWidgetViewTemplate());
        $viewHelper->setLoginForm($locator->get('zfcuser_login_form'));

        return $viewHelper;        
    }
}
