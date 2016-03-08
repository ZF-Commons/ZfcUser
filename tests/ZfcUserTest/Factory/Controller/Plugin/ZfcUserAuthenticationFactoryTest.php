<?php
namespace ZfcUserTest\Factory\Controller\Plugin;

use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\ServiceManager;
use ZfcUser\Authentication\Adapter\AdapterChain;
use ZfcUser\Factory\Controller\Plugin\ZfcUserAuthentication;

class ZfcUserAuthenticationFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager;

        $serviceManager->setService('zfcuser_auth_service', new AuthenticationService);
        $serviceManager->setService('ZfcUser\Authentication\Adapter\AdapterChain', new AdapterChain);

        $plugins = $this->getMock('Zend\ServiceManager\AbstractPluginManager');
        $plugins->expects($this->any())
            ->method('getServiceLocator')
            ->will($this->returnValue($serviceManager));

        $factory = new ZfcUserAuthentication();
        $this->assertInstanceOf('ZfcUser\Controller\Plugin\ZfcUserAuthentication', $factory->createService($plugins));
    }
}
