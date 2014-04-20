<?php
namespace ZfcUser\Factory\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\View\Helper\LoginFormHelper;

/**
 * Class LoginFormHelperFactory
 * @package ZfcUser\Factory\View\Helper
 */
class LoginFormHelperFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return LoginFormHelper
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var \Zend\Form\FormInterface            $loginForm
         * @var \Zend\View\HelperPluginManager      $serviceLocator
         * @var \Zend\ServiceManager\ServiceManager $serviceManager
         */
        $serviceManager     = $serviceLocator->getServiceLocator();
        $formElementManager = $serviceManager->get('FormElementManager');
        $loginForm          = $formElementManager->get('ZfcUser\Form\LoginForm');

        return new LoginFormHelper($loginForm);
    }
}
