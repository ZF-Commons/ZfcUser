<?php
namespace ZfcUser\Factory\Validator;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Validator\NoRecordExistsValidator;

/**
 * Class NoRecordExistsValidatorFactory
 * @package ZfcUser\Factory\Validator
 */
class NoRecordExistsValidatorFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return NoRecordExistsValidator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var \Zend\InputFilter\InputFilterPluginManager  $serviceLocator
         * @var \Zend\ServiceManager\ServiceManager         $serviceManager
         * @var \ZfcUser\Mapper\UserInterface               $userMapper
         */
        $serviceManager = $serviceLocator->getServiceLocator();
        $userMapper = $serviceManager->get('zfcuser_user_mapper');

        return new NoRecordExistsValidator($this->options, $userMapper);
    }

    /**
     * @param array $options
     */
    public function setCreationOptions(array $options)
    {
        $this->options = $options;
    }
}
