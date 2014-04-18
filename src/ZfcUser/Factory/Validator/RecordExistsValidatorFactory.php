<?php
namespace ZfcUser\Factory\Validator;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Validator\RecordExistsValidator;

/**
 * Class RecordExistsValidatorFactory
 * @package ZfcUser\Factory\Validator
 */
class RecordExistsValidatorFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return RecordExistsValidator
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

        return new RecordExistsValidator($this->options, $userMapper);
    }

    /**
     * @param array $options
     */
    public function setCreationOptions(array $options)
    {
        $this->options = $options;
    }
}
