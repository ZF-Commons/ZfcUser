<?php
namespace ZfcUser\Factory\Validator;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Validator\NoRecordExists;

/**
 * Class NoRecordExistsFactory
 * @package ZfcUser\Factory\Validator
 */
class NoRecordExistsFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return NoRecordExists
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

        return new NoRecordExists($this->options, $userMapper);
    }

    /**
     * @param array $options
     */
    public function setCreationOptions(array $options)
    {
        $this->options = $options;
    }
}
