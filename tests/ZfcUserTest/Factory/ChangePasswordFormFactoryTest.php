<?php
namespace ZfcUserTest\Factory;

use Zend\ServiceManager\ServiceManager;
use ZfcUser\Factory\ChangePasswordFormFactory;
use ZfcUser\Options\ModuleOptions;

class ChangePasswordFormFactoryTest extends \PHPUnit_Framework_TestCase 
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager;
        $serviceManager->setService('zfcuser_module_options', new ModuleOptions);
        $factory = new ChangePasswordFormFactory;
        $this->assertInstanceOf('ZfcUser\Form\ChangePassword', $factory->createService($serviceManager)); 
    }
}
