<?php
namespace ZfcUser\Factory\Validator;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Validator\VerifyPasswordValidator;

/**
 * Class VerifyPasswordValidatorFactory
 * @package ZfcUser\Factory\Validator
 */
class VerifyPasswordValidatorFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return VerifyPasswordValidator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var $authenticationService  \Zend\Authentication\AuthenticationServiceInterface
         * @var $serviceLocator         \Zend\InputFilter\InputFilterPluginManager
         * @var $serviceManager         \Zend\ServiceManager\ServiceManager
         */
        $serviceManager         = $serviceLocator->getServiceLocator();
        $authenticationService  = $serviceManager->get('zfcuser_auth_service');

        return new VerifyPasswordValidator($authenticationService);
    }
}
