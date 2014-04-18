<?php
namespace ZfcUserTest\Factory\Form;

use Zend\ServiceManager\ServiceManager;
use ZfcUser\Factory\Form\ChangePasswordFormFactory;
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
