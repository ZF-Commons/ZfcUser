<?php
namespace ZfcUser\Factory\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\HelperPluginManager;
use ZfcUser\Form;
use ZfcUser\Options;
use ZfcUser\View\Helper\ZfcUserLoginWidget;

class LoginWidgetFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $helpers)
    {
        /* @var $helpers HelperPluginManager */
        $locator = $helpers->getServiceLocator();

        /* @var $options Options\ModuleOptions */
        $options = $locator->get('zfcuser_module_options');
        $viewTemplate = $options->getUserLoginWidgetViewTemplate();

        /* @var $loginForm Form\Login */
        $loginForm = $locator->get('zfcuser_login_form');

        $viewHelper = new ZfcUserLoginWidget;
        $viewHelper
            ->setViewTemplate($viewTemplate)
            ->setLoginForm($loginForm)
        ;

        return $viewHelper;
    }
}
