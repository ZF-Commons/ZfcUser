<?php
namespace ZfcUserTest\Factory\Form;

use Laminas\Form\FormElementManager;
use Laminas\ServiceManager\ServiceManager;
use ZfcUser\Factory\Form\ChangeEmail as ChangeEmailFactory;
use ZfcUser\Options\ModuleOptions;
use ZfcUser\Mapper\User as UserMapper;

class ChangeEmailFormFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager([
            'services' => [
                'zfcuser_module_options' => new ModuleOptions,
                'zfcuser_user_mapper' => new UserMapper
            ]
        ]);

        $formElementManager = new FormElementManager($serviceManager);
        $serviceManager->setService('FormElementManager', $formElementManager);

        $factory = new ChangeEmailFactory();

        $this->assertInstanceOf('ZfcUser\Form\ChangeEmail', $factory->__invoke($serviceManager, 'ZfcUser\Form\ChangeEmail'));
    }
}
