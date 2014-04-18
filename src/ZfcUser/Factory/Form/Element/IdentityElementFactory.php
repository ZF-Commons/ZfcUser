<?php
namespace ZfcUser\Factory\Form\Element;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Form\Element\IdentityElement;

/**
 * Class IdentityElementFactory
 * @package ZfcUser\Factory\Form\Element
 */
class IdentityElementFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return IdentityElement
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var $authenticationOptions  \ZfcUser\Options\AuthenticationOptionsInterface
         * @var $serviceLocator         \Zend\Form\FormElementManager
         * @var $serviceManager         \Zend\ServiceManager\ServiceManager
         */
        $serviceManager = $serviceLocator->getServiceLocator();
        $authenticationOptions = $serviceManager->get('zfcuser_module_options');

        return new IdentityElement(null, $authenticationOptions);
    }
}
