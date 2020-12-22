<?php
namespace ZfcUserTest\Factory\Form;

use Laminas\Form\FormElementManager;
use Laminas\ServiceManager\ServiceManager;
use ZfcUser\Factory\Form\ChangePassword as ChangePasswordFactory;
use ZfcUser\Options\ModuleOptions;
use ZfcUser\Mapper\User as UserMapper;

class ChangePasswordFormFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager;
        $serviceManager->setService('zfcuser_module_options', new ModuleOptions);
        $serviceManager->setService('zfcuser_user_mapper', new UserMapper);

        $formElementManager = new FormElementManager($serviceManager);
        $serviceManager->setService('FormElementManager', $formElementManager);

        $factory = new ChangePasswordFactory();

        $this->assertInstanceOf('ZfcUser\Form\ChangePassword', $factory->__invoke($serviceManager, 'ZfcUser\Form\ChangePassword'));
    }
}
