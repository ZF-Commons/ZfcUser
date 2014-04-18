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
         * @var \Zend\Authentication\AuthenticationServiceInterface $authenticationService
         * @var \Zend\InputFilter\InputFilterPluginManager          $serviceLocator
         * @var \Zend\ServiceManager\ServiceManager                 $serviceManager
         */
        $serviceManager         = $serviceLocator->getServiceLocator();
        $authenticationService  = $serviceManager->get('zfcuser_auth_service');

        return new VerifyPasswordValidator($authenticationService);
    }
}
