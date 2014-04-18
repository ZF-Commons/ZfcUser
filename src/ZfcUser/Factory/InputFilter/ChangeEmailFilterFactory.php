<?php
namespace ZfcUser\Factory\InputFilter;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\InputFilter\ChangeEmailFilter;

/**
 * Class ChangeEmailFilterFactory
 * @package ZfcUser\Factory\InputFilter
 */
class ChangeEmailFilterFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ChangeEmailFilter
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var $authenticationOptions  \ZfcUser\Options\AuthenticationOptionsInterface
         * @var $serviceLocator         \Zend\InputFilter\InputFilterPluginManager
         * @var $serviceManager         \Zend\ServiceManager\ServiceManager
         */
        $serviceManager         = $serviceLocator->getServiceLocator();
        $authenticationOptions  = $serviceManager->get('zfcuser_module_options');

        return new ChangeEmailFilter($authenticationOptions);
    }
}
