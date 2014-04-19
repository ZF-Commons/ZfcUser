<?php
namespace ZfcUser\Factory\InputFilter;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\InputFilter\ChangePasswordFilter;

/**
 * Class RegistrationFilterFactory
 * @package ZfcUser\Factory\InputFilter
 */
class ChangePasswordFilterFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ChangePasswordFilter
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var \ZfcUser\Options\AuthenticationOptionsInterface $authenticationOptions
         * @var \Zend\InputFilter\InputFilterPluginManager      $serviceLocator
         * @var \Zend\ServiceManager\ServiceManager             $serviceManager
         */
        $serviceManager         = $serviceLocator->getServiceLocator();
        $authenticationOptions  = $serviceManager->get('zfcuser_module_options');

        return new ChangePasswordFilter($authenticationOptions);
    }
}
