<?php
namespace ZfcUser\Factory\Hydrator;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Hydrator\Strategy\DefaultStateStrategy;
use ZfcUser\Hydrator\Strategy\PasswordHashingStrategy;

/**
 * Class RegistrationHydratorFactory
 * @package ZfcUser\Factory\Hydrator
 */
class RegistrationHydratorFactory implements FactoryInterface
{
    /**
     * @param  ServiceLocatorInterface $serviceLocator
     * @return \Zend\Stdlib\Hydrator\ClassMethods
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var \Zend\ServiceManager\ServiceManager         $serviceManager
         * @var \Zend\Stdlib\Hydrator\ClassMethods          $hydrator
         * @var \Zend\Stdlib\Hydrator\HydratorPluginManager $serviceLocator
         * @var \ZfcUser\Options\ModuleOptions              $options
         */
        $serviceManager = $serviceLocator->getServiceLocator();
        $options        = $serviceManager->get('zfcuser_module_options');

        $hydrator = $serviceLocator->get('ClassMethods');
        $hydrator->addStrategy('password', new PasswordHashingStrategy($options));
        $hydrator->addStrategy('state', new DefaultStateStrategy($options));

        return $hydrator;
    }
}
