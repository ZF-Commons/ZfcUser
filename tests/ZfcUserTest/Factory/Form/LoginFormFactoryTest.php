<?php
namespace ZfcUserTest\Factory\Form;

use Zend\ServiceManager\ServiceManager;
use ZfcUser\Factory\Form\LoginFormFactory;
use ZfcUser\Options\ModuleOptions;

class LoginFormFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager;
        $serviceManager->setService('zfcuser_module_options', new ModuleOptions);
        $factory = new LoginFormFactory;
        $this->assertInstanceOf('ZfcUser\Form\Login', $factory->createService($serviceManager));
    }
}
