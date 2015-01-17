<?php

namespace ZfcUserTest\Factory\Authentication\Adapter;

use ZfcUser\Factory\Authentication\Adapter\MapperUsernameFactory;
use Zend\ServiceManager\ServiceManager;

class MapperUsernameFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = new ServiceManager();
        
        $serviceLocator->setService(
            'zfcuser_user_mapper',
            $this->getMock('ZfcUser\Mapper\UserInterface')
        );
        
        $serviceLocator->setService(
            'zfcuser_authentication_credentialprocessor',
            $this->getMock('Zend\Crypt\Password\PasswordInterface')
        );
        
        $factory = new MapperUsernameFactory();        
        $mapper = $factory->createService($serviceLocator);
        $this->assertInstanceOf('ZfcUser\Authentication\Adapter\Mapper', $mapper);
    }
}
