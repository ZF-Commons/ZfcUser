<?php
namespace ZfcUser\Factory\InputFilter;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\InputFilter\RegistrationFilter;

/**
 * Class RegistrationFilterFactory
 * @package ZfcUser\Factory\InputFilter
 */
class RegistrationFilterFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return RegistrationFilter
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var \ZfcUser\Options\RegistrationOptionsInterface   $registrationOptions
         * @var \Zend\InputFilter\InputFilterPluginManager      $serviceLocator
         * @var \Zend\ServiceManager\ServiceManager             $serviceManager
         */
        $serviceManager         = $serviceLocator->getServiceLocator();
        $registrationOptions    = $serviceManager->get('zfcuser_module_options');

        return new RegistrationFilter($registrationOptions);
    }
}
