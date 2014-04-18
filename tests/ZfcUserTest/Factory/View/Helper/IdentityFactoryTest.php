<?php
namespace ZfcUserTest\Factory\View\Helper;

use ZfcUser\Factory\View\Helper\IdentityFactory;
use Zend\ServiceManager\ServiceManager;

class IdentityFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager;
        $serviceManager->setService('zfcuser_auth_service', new \Zend\Authentication\AuthenticationService);
        $factory = new IdentityFactory;
        $helpers = $this->getMock('Zend\ServiceManager\AbstractPluginManager');
        $helpers->expects($this->any())
            ->method('getServiceLocator')
            ->will($this->returnValue($serviceManager));
        $this->assertInstanceOf('ZfcUser\View\Helper\ZfcUserIdentity', $factory->createService($helpers));
    }
}
