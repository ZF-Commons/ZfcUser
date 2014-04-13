<?php
namespace ZfcUser\Factory\Validator;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Validator\RecordExists;

/**
 * Class NoRecordExistsFactory
 * @package ZfcUser\Factory\Validator
 */
class RecordExistsFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return RecordExists
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var $serviceLocator \Zend\InputFilter\InputFilterPluginManager
         * @var $serviceManager \Zend\ServiceManager\ServiceManager
         * @var $userMapper     \ZfcUser\Mapper\UserInterface
         */
        $serviceManager = $serviceLocator->getServiceLocator();
        $userMapper = $serviceManager->get('zfcuser_user_mapper');

        return new RecordExists($this->options, $userMapper);
    }

    /**
     * @param array $options
     */
    public function setCreationOptions(array $options)
    {
        $this->options = $options;
    }
}
