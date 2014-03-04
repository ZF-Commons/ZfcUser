<?php
namespace ZfcUserTest\Controller\Plugin\Factory;

use Zend\ServiceManager\ServiceManager;
use ZfcUser\Controller\Plugin\Factory\ZfcUserAuthenticationFactory;

class ZfcUserAuthenticationFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager;
        $serviceManager->setService('zfcuser_auth_service', new \Zend\Authentication\AuthenticationService);
        $serviceManager->setService('ZfcUser\Authentication\Adapter\AdapterChain', new \ZfcUser\Authentication\Adapter\AdapterChain);
        $plugins = $this->getMock('Zend\ServiceManager\AbstractPluginManager');
        $plugins->expects($this->any())
            ->method('getServiceLocator')
            ->will($this->returnValue($serviceManager));      
        $factory = new ZfcUserAuthenticationFactory;
        $this->assertInstanceOf('ZfcUser\Controller\Plugin\ZfcUserAuthentication', $factory->createService($plugins));    
    }
}
