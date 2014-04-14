<?php
namespace ZfcUser\Factory\InputFilter;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\InputFilter\LoginFilter;

/**
 * Class LoginFilterFactory
 * @package ZfcUser\Factory\InputFilter
 */
class LoginFilterFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return LoginFilter
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var $options        \ZfcUser\Options\AuthenticationOptionsInterface
         * @var $serviceLocator \Zend\InputFilter\InputFilterPluginManager
         * @var $serviceManager \Zend\ServiceManager\ServiceManager
         */
        $serviceManager = $serviceLocator->getServiceLocator();
        $options = $serviceManager->get('zfcuser_module_options');

        return new LoginFilter($options);
    }
}
