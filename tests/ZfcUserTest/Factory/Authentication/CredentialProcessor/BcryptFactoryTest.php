<?php

namespace ZfcUserTest\Factory\Authentication\CredentialProcessor;

use ZfcUser\Factory\Authentication\CredentialProcessor\BcryptFactory;
use Zend\ServiceManager\ServiceManager;

class BcryptFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $options = $this->getMock('ZfcUser\Options\ModuleOptions');
        $options->expects($this->once())->method('getPasswordCost')->will($this->returnValue(5));
        
        $serviceLocator = new ServiceManager();
        $serviceLocator->setService('zfcuser_module_options', $options);
        
        $factory = new BcryptFactory();
        $processor = $factory->createService($serviceLocator);
        $this->assertInstanceOf('Zend\Crypt\Password\Bcrypt', $processor);
        $this->assertEquals(5, $processor->getCost());
    }
}
