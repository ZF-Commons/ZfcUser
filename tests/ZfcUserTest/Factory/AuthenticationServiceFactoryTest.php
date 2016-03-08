<?php
namespace ZfcUserTest\Factory;

use Zend\ServiceManager\ServiceManager;
use ZfcUser\Factory\AuthenticationService;

class AuthenticationServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager;
        $storage = $this->getMockBuilder('ZfcUser\Authentication\Storage\Db')
            ->disableOriginalConstructor()
            ->getMock();
        $serviceManager->setService('ZfcUser\Authentication\Storage\Db', $storage);
        $serviceManager->setService('ZfcUser\Authentication\Adapter\AdapterChain', $this->getMock('ZfcUser\Authentication\Adapter\AdapterChain'));

        $factory = new AuthenticationService;

        $this->assertInstanceOf('Zend\Authentication\AuthenticationService', $factory->createService($serviceManager));
    }
}
