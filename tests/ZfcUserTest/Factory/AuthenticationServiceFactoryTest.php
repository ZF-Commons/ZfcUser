<?php
namespace ZfcUserTest\Factory;

use Zend\ServiceManager\ServiceManager;
use ZfcUser\Factory\AuthenticationServiceFactory;

class AuthenticationServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager;
        $serviceManager->setService('ZfcUser\Authentication\Storage\Db', $this->getMock('ZfcUser\Authentication\Storage\Db'));
        $serviceManager->setService('ZfcUser\Authentication\Adapter\AdapterChain', $this->getMock('ZfcUser\Authentication\Adapter\AdapterChain'));

        $factory = new AuthenticationServiceFactory;

        $this->assertInstanceOf('Zend\Authentication\AuthenticationService', $factory->createService($serviceManager));
    }
}
