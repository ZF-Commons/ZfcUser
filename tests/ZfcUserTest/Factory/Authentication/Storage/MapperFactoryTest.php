<?php

namespace ZfcUserTest\Factory\Authentication\Storage;

use ZfcUser\Factory\Authentication\Storage\MapperFactory;
use Zend\ServiceManager\ServiceManager;

class MapperFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = new ServiceManager();
        
        $serviceLocator->setService(
            'zfcuser_user_mapper',
            $this->getMock('ZfcUser\Mapper\UserInterface')
        );
        
        $serviceLocator->setService(
            'zfcuser_authentication_storage_backend',
            $this->getMock('Zend\Authentication\Storage\StorageInterface')
        );
        
        $factory = new MapperFactory();        
        $mapper = $factory->createService($serviceLocator);
        $this->assertInstanceOf('ZfcUser\Authentication\Storage\Mapper', $mapper);
    }
}
