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
         * @var $options        \ZfcUser\Options\RegistrationOptionsInterface
         * @var $serviceLocator \Zend\InputFilter\InputFilterPluginManager
         * @var $serviceManager \Zend\ServiceManager\ServiceManager
         */
        $serviceManager = $serviceLocator->getServiceLocator();
        $options = $serviceManager->get('zfcuser_module_options');

        return new RegistrationFilter($options);
    }
}
