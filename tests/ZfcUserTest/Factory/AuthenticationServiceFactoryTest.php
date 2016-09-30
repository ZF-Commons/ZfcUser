<?php
namespace ZfcUserTest\Factory;

use Zend\ServiceManager\ServiceManager;
use ZfcUser\Factory\AuthenticationServiceFactory;

class AuthenticationServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $mapper = $this->getMockBuilder('ZfcUser\Authentication\Storage\Mapper')
                       ->disableOriginalConstructor()
                       ->getMock();
        
        $adapter = $this->getMock('ZfcUser\Authentication\Adapter\AdapterChain');
        
        $serviceManager = new ServiceManager;
        $serviceManager->setService('zfcuser_authentication_storage', $mapper);
        $serviceManager->setService('ZfcUser\Authentication\Adapter\AdapterChain', $adapter);

        $factory = new AuthenticationServiceFactory;

        $this->assertInstanceOf('Zend\Authentication\AuthenticationService', $factory->createService($serviceManager));
    }
}
