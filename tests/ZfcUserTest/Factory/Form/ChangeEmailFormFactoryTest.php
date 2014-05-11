<?php
namespace ZfcUserTest\Factory\Form;

use Zend\ServiceManager\ServiceManager;
use ZfcUser\Factory\Form\ChangeEmailFormFactory;
use ZfcUser\Options\ModuleOptions;
use ZfcUser\Mapper\User as UserMapper;

class ChangeEmailFormFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager;
        $options = new ModuleOptions;

        $serviceManager->setService('zfcuser_module_options', $options);
        $serviceManager->setService('zfcuser_user_mapper', new UserMapper);

        $factory = new ChangeEmailFormFactory;

        $this->assertInstanceOf('ZfcUser\Form\ChangeEmail', $factory->createService($serviceManager));
    }
}
