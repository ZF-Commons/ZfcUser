<?php
namespace ZfcUserTest\Factory;

use Zend\ServiceManager\ServiceManager;
use ZfcUser\Factory\RegisterFormFactory;
use ZfcUser\Options\ModuleOptions;
use ZfcUser\Mapper\User as UserMapper;

class RegisterFormFactoryTest extends \PHPUnit_Framework_TestCase 
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager;
        $serviceManager->setService('zfcuser_module_options', new ModuleOptions);
        $serviceManager->setService('zfcuser_user_mapper', new UserMapper);
        $factory = new RegisterFormFactory;
        $this->assertInstanceOf('ZfcUser\Form\Register', $factory->createService($serviceManager)); 
    }    
}
