<?php
namespace ZfcUser\Factory\InputFilter;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\InputFilter\Registration;

/**
 * Class RegistrationFactory
 * @package ZfcUser\Factory\InputFilter
 */
class RegistrationFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return Registration
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var $options        \ZfcUser\Options\RegistrationOptionsInterface
         * @var $serviceLocator \Zend\InputFilter\InputFilterPluginManager
         * @var $serviceManager \Zend\ServiceManager\ServiceManager
         */
        $serviceManager = $serviceLocator->getServiceLocator();
        $options = $serviceManager->get('zfcuser_module_options');

        return new Registration($options);
    }
}
